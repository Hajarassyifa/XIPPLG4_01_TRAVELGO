<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Exception\FirebaseException;

class TravelController extends Controller
{
    protected $firestore;

    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore->database();
    }

    // =========================================================
    // [C] CREATE - Simpan Booking Baru
    // POST /api/booking
    // =========================================================
    public function storeBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user'           => 'required|string',
            'id_destinasi'      => 'required|string',
            'jumlah_tiket'      => 'required|integer|min:1',
            'tanggal_kunjungan' => 'required|date_format:Y-m-d',
            'total_harga'       => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $destinasiDoc = $this->firestore->collection('destinasi')
                ->document($request->id_destinasi)
                ->snapshot();

            if (!$destinasiDoc->exists()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Destinasi tidak ditemukan.',
                ], 404);
            }

            $data = [
                'id_user'           => $request->id_user,
                'id_destinasi'      => $request->id_destinasi,
                'jumlah_tiket'      => (int) $request->jumlah_tiket,
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'total_harga'       => (int) $request->total_harga,
                'status_pembayaran' => 'Pending',
                'created_at'        => now()->toIso8601String(),
                'updated_at'        => now()->toIso8601String(),
            ];

            $newDoc = $this->firestore->collection('booking')->add($data);

            return response()->json([
                'status'  => true,
                'message' => 'Booking berhasil dibuat.',
                'id'      => $newDoc->id(),
                'data'    => $data,
            ], 201);

        } catch (FirebaseException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal membuat booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================
    // [R] READ - Ambil Semua Booking
    // GET /api/booking?id_user=xxx
    // =========================================================
    public function indexBooking(Request $request)
    {
        try {
            $collection = $this->firestore->collection('booking');

            if ($request->has('id_user')) {
                $documents = $collection
                    ->where('id_user', '=', $request->id_user)
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
                'message' => 'Data booking berhasil diambil.',
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
    // [U] UPDATE - Ganti Status atau Data Booking
    // PUT /api/booking/{id}
    // =========================================================
    public function updateBooking(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status_pembayaran' => 'sometimes|string|in:Pending,Paid,Cancelled',
            'jumlah_tiket'      => 'sometimes|integer|min:1',
            'tanggal_kunjungan' => 'sometimes|date_format:Y-m-d',
            'total_harga'       => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $bookingRef  = $this->firestore->collection('booking')->document($id);
            $bookingSnap = $bookingRef->snapshot();

            if (!$bookingSnap->exists()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data booking tidak ditemukan.',
                ], 404);
            }

            $updateData = ['updated_at' => now()->toIso8601String()];

            if ($request->has('status_pembayaran')) $updateData['status_pembayaran'] = $request->status_pembayaran;
            if ($request->has('jumlah_tiket'))      $updateData['jumlah_tiket']      = (int) $request->jumlah_tiket;
            if ($request->has('tanggal_kunjungan')) $updateData['tanggal_kunjungan'] = $request->tanggal_kunjungan;
            if ($request->has('total_harga'))       $updateData['total_harga']       = (int) $request->total_harga;

            $bookingRef->set($updateData, ['merge' => true]);

            return response()->json([
                'status'  => true,
                'message' => 'Data booking berhasil diupdate.',
                'data'    => $updateData,
            ], 200);

        } catch (FirebaseException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengupdate data: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================
    // [D] DELETE - Hapus Data Booking
    // DELETE /api/booking/{id}
    // =========================================================
    public function destroyBooking($id)
    {
        try {
            $bookingRef  = $this->firestore->collection('booking')->document($id);
            $bookingSnap = $bookingRef->snapshot();

            if (!$bookingSnap->exists()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data booking tidak ditemukan.',
                ], 404);
            }

            $bookingRef->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Data booking berhasil dihapus.',
            ], 200);

        } catch (FirebaseException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }
}