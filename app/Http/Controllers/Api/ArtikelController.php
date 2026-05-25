<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikel = Artikel::all();

        return response()->json([
            'status' => true,
            'message' => 'Data artikel berhasil diambil',
            'data' => $artikel
        ], 200);
    }

    public function show($id)
    {
        $artikel = Artikel::find($id);

        if (!$artikel) {
            return response()->json([
                'status' => false,
                'message' => 'Artikel tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail artikel berhasil diambil',
            'data' => $artikel
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'kategori' => 'required|string|max:100',
        ]);

        $artikel = Artikel::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Artikel berhasil ditambahkan',
            'data' => $artikel
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $artikel = Artikel::find($id);

        if (!$artikel) {
            return response()->json([
                'status' => false,
                'message' => 'Artikel tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'kategori' => 'required|string|max:100',
        ]);

        $artikel->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Artikel berhasil diperbarui',
            'data' => $artikel
        ], 200);
    }

    public function destroy($id)
    {
        $artikel = Artikel::find($id);

        if (!$artikel) {
            return response()->json([
                'status' => false,
                'message' => 'Artikel tidak ditemukan'
            ], 404);
        }

        $artikel->delete();

        return response()->json([
            'status' => true,
            'message' => 'Artikel berhasil dihapus'
        ], 200);
    }
}