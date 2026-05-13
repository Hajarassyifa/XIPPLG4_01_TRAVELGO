<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Exception\FirebaseException;

class TransaksiController extends Controller
{
    protected $firestore;

    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore->database();
    }

    // =========================================================
    // [R] READ - Ambil Semua Transaksi
    // GET /api/transaksi?id_booking=xxx (filter opsional)
    // =========================================================
    public function index(Request $request)
    {
        try {
            $collection = $this->firestore->collection('transaksi');

            // Filter by id_booking kalau ada query param
            if ($request->has('id_booking')) {
                $documents = $collection
                    ->where('id_booking', '=', $request->id_booking)
                    ->documents();
            } else {
                $documents = $collection->documents();
            }

            $data = [];
            foreach ($documents as $doc) {
                if ($doc->exists()) {
                    $data[] = array_merge(['id' => $doc->id()], $doc->data());
                }
            }

            return response()->json([
                'status'  => true,
                'message' => 'Data transaksi berhasil diambil.',
                'total'   => count($data),
                'data'    => $data,
            ], 200);

        } catch (FirebaseException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================
    // [R] READ - Ambil Satu Transaksi by ID
    // GET /api/transaksi/{id}
    // =========================================================
    public function show($id)
    {
        try {
            $doc = $this->firestore->collection('transaksi')->document($id)->snapshot();

            if (!$doc->exists()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Transaksi tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Transaksi ditemukan.',
                'data'    => array_merge(['id' => $doc->id()], $doc->data()),
            ], 200);

        } catch (FirebaseException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================
    // [C] CREATE - Buat Transaksi Baru
    // POST /api/transaksi
    // Body: id_booking, metode_pembayaran, tanggal_bayar
    // =========================================================
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_booking'         => 'required|string',
            'metode_pembayaran'  => 'required|string|in:Transfer,QRIS,COD,Kartu Kredit',
            'tanggal_bayar'      => 'required|date_format:Y-m-d',
        ], [
            'id_booking.required'        => 'ID booking wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib diisi.',
            'metode_pembayaran.in'       => 'Metode pembayaran hanya boleh: Transfer, QRIS, COD, atau Kartu Kredit.',
            'tanggal_bayar.required'     => 'Tanggal bayar wajib diisi.',
            'tanggal_bayar.date_format'  => 'Format tanggal harus YYYY-MM-DD.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            // Cek apakah id_booking ada di koleksi booking
            $bookingDoc = $this->firestore->collection('booking')
                ->document($request->id_booking)
                ->snapshot();

            if (!$bookingDoc->exists()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Booking tidak ditemukan.',
                ], 404);
            }

            // Simpan transaksi
            $data = [
                'id_booking'        => $request->id_booking,
                'metode_pembayaran' => $request->metode_pembayaran,
                'tanggal_bayar'     => $request->tanggal_bayar,
                'status'            => 'Menunggu Konfirmasi', // default
                'created_at'        => now()->toIso8601String(),
                'updated_at'        => now()->toIso8601String(),
            ];

            $newDoc = $this->firestore->collection('transaksi')->add($data);

            // Update status_pembayaran di booking jadi 'Proses'
            $this->firestore->collection('booking')
                ->document($request->id_booking)
                ->set(['status_pembayaran' => 'Proses', 'updated_at' => now()->toIso8601String()],
                      ['merge' => true]);

            return response()->json([
                'status'  => true,
                'message' => 'Transaksi berhasil dibuat.',
                'id'      => $newDoc->id(),
                'data'    => $data,
            ], 201);

        } catch (FirebaseException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================
    // [U] UPDATE - Update Status Transaksi
    // PUT /api/transaksi/{id}
    // Body: status, metode_pembayaran, tanggal_bayar (semua opsional)
    // =========================================================
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status'            => 'sometimes|string|in:Menunggu Konfirmasi,Berhasil,Gagal',
            'metode_pembayaran' => 'sometimes|string|in:Transfer,QRIS,COD,Kartu Kredit',
            'tanggal_bayar'     => 'sometimes|date_format:Y-m-d',
        ], [
            'status.in'            => 'Status hanya boleh: Menunggu Konfirmasi, Berhasil, atau Gagal.',
            'metode_pembayaran.in' => 'Metode pembayaran hanya boleh: Transfer, QRIS, COD, atau Kartu Kredit.',
            'tanggal_bayar.date_format' => 'Format tanggal harus YYYY-MM-DD.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $transaksiRef  = $this->firestore->collection('transaksi')->document($id);
            $transaksiSnap = $transaksiRef->snapshot();

            if (!$transaksiSnap->exists()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Transaksi tidak ditemukan.',
                ], 404);
            }

            $updateData = ['updated_at' => now()->toIso8601String()];

            if ($request->has('status'))            $updateData['status']            = $request->status;
            if ($request->has('metode_pembayaran')) $updateData['metode_pembayaran'] = $request->metode_pembayaran;
            if ($request->has('tanggal_bayar'))     $updateData['tanggal_bayar']     = $request->tanggal_bayar;

            $transaksiRef->set($updateData, ['merge' => true]);

            // Sinkronisasi: jika status transaksi Berhasil → update booking jadi Paid
            //               jika status transaksi Gagal    → update booking jadi Cancelled
            if ($request->has('status')) {
                $transaksiData   = $transaksiSnap->data();
                $bookingStatus   = match ($request->status) {
                    'Berhasil' => 'Paid',
                    'Gagal'    => 'Cancelled',
                    default    => null,
                };

                if ($bookingStatus && isset($transaksiData['id_booking'])) {
                    $this->firestore->collection('booking')
                        ->document($transaksiData['id_booking'])
                        ->set([
                            'status_pembayaran' => $bookingStatus,
                            'updated_at'        => now()->toIso8601String(),
                        ], ['merge' => true]);
                }
            }

            return response()->json([
                'status'  => true,
                'message' => 'Transaksi berhasil diupdate.',
                'data'    => $updateData,
            ], 200);

        } catch (FirebaseException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengupdate transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================
    // [D] DELETE - Hapus Transaksi
    // DELETE /api/transaksi/{id}
    // =========================================================
    public function destroy($id)
    {
        try {
            $transaksiRef  = $this->firestore->collection('transaksi')->document($id);
            $transaksiSnap = $transaksiRef->snapshot();

            if (!$transaksiSnap->exists()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Transaksi tidak ditemukan.',
                ], 404);
            }

            $transaksiRef->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Transaksi berhasil dihapus.',
            ], 200);

        } catch (FirebaseException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }
}