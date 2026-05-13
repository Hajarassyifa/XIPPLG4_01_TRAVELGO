<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Exception\FirebaseException;

class ArtikelController extends Controller
{
    protected $firestore;

    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore->database();
    }

    // =========================================================
    // [R] READ - Ambil Semua Artikel
    // GET /api/artikel
    // =========================================================
    public function index()
    {
        try {
            $documents = $this->firestore->collection('artikel')->documents();

            $data = [];
            foreach ($documents as $doc) {
                if ($doc->exists()) {
                    $data[] = array_merge(['id' => $doc->id()], $doc->data());
                }
            }

            return response()->json([
                'status'  => true,
                'message' => 'Data artikel berhasil diambil.',
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
    // [R] READ - Ambil Satu Artikel by ID
    // GET /api/artikel/{id}
    // =========================================================
    public function show($id)
    {
        try {
            $doc = $this->firestore->collection('artikel')->document($id)->snapshot();

            if (!$doc->exists()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Artikel tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Artikel ditemukan.',
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
    // [C] CREATE - Buat Artikel Baru
    // POST /api/artikel
    // Body: judul, isi, penulis, tanggal
    // =========================================================
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul'   => 'required|string|max:255',
            'isi'     => 'required|string',
            'penulis' => 'required|string|max:100',
            'tanggal' => 'required|date_format:Y-m-d',
        ], [
            'judul.required'   => 'Judul artikel wajib diisi.',
            'isi.required'     => 'Isi artikel wajib diisi.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date_format' => 'Format tanggal harus YYYY-MM-DD.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $data = [
                'judul'      => $request->judul,
                'isi'        => $request->isi,
                'penulis'    => $request->penulis,
                'tanggal'    => $request->tanggal,
                'created_at' => now()->toIso8601String(),
                'updated_at' => now()->toIso8601String(),
            ];

            $newDoc = $this->firestore->collection('artikel')->add($data);

            return response()->json([
                'status'  => true,
                'message' => 'Artikel berhasil dibuat.',
                'id'      => $newDoc->id(),
                'data'    => $data,
            ], 201);

        } catch (FirebaseException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal membuat artikel: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================
    // [U] UPDATE - Edit Artikel
    // PUT /api/artikel/{id}
    // Body: judul, isi, penulis, tanggal (semua opsional)
    // =========================================================
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul'   => 'sometimes|string|max:255',
            'isi'     => 'sometimes|string',
            'penulis' => 'sometimes|string|max:100',
            'tanggal' => 'sometimes|date_format:Y-m-d',
        ], [
            'tanggal.date_format' => 'Format tanggal harus YYYY-MM-DD.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $artikelRef  = $this->firestore->collection('artikel')->document($id);
            $artikelSnap = $artikelRef->snapshot();

            if (!$artikelSnap->exists()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Artikel tidak ditemukan.',
                ], 404);
            }

            $updateData = ['updated_at' => now()->toIso8601String()];

            if ($request->has('judul'))   $updateData['judul']   = $request->judul;
            if ($request->has('isi'))     $updateData['isi']     = $request->isi;
            if ($request->has('penulis')) $updateData['penulis'] = $request->penulis;
            if ($request->has('tanggal')) $updateData['tanggal'] = $request->tanggal;

            $artikelRef->set($updateData, ['merge' => true]);

            return response()->json([
                'status'  => true,
                'message' => 'Artikel berhasil diupdate.',
                'data'    => $updateData,
            ], 200);

        } catch (FirebaseException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengupdate artikel: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================
    // [D] DELETE - Hapus Artikel
    // DELETE /api/artikel/{id}
    // =========================================================
    public function destroy($id)
    {
        try {
            $artikelRef  = $this->firestore->collection('artikel')->document($id);
            $artikelSnap = $artikelRef->snapshot();

            if (!$artikelSnap->exists()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Artikel tidak ditemukan.',
                ], 404);
            }

            $artikelRef->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Artikel berhasil dihapus.',
            ], 200);

        } catch (FirebaseException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menghapus artikel: ' . $e->getMessage(),
            ], 500);
        }
    }
}