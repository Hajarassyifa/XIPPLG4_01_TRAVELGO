<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role === 'admin') {
            $transaksi = Transaksi::all();
        } else {
            $transaksi = Transaksi::where('user_id', $request->user()->id)->get();
        }

        return response()->json([
            'status' => true,
            'message' => 'Data transaksi berhasil diambil',
            'data' => $transaksi
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required',
            'total_harga' => 'required|numeric',
            'metode_pembayaran' => 'required',
        ]);

        $transaksi = Transaksi::create([
            'user_id' => $request->user()->id,
            'booking_id' => $validated['booking_id'],
            'total_harga' => $validated['total_harga'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status' => 'pending'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Transaksi berhasil ditambahkan',
            'data' => $transaksi
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        if ($request->user()->role !== 'admin' && $transaksi->user_id !== $request->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail transaksi berhasil diambil',
            'data' => $transaksi
        ]);
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        if ($request->user()->role !== 'admin' && $transaksi->user_id !== $request->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        if ($request->user()->role === 'admin') {
            $validated = $request->validate([
                'booking_id' => 'sometimes',
                'total_harga' => 'sometimes|numeric',
                'metode_pembayaran' => 'sometimes',
                'status' => 'sometimes|in:pending,paid,failed,cancelled'
            ]);
        } else {
            $validated = $request->validate([
                'metode_pembayaran' => 'sometimes',
            ]);
        }

        $transaksi->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Transaksi berhasil diupdate',
            'data' => $transaksi
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        if ($request->user()->role !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak. Hanya admin yang boleh menghapus transaksi.'
            ], 403);
        }

        $transaksi->delete();

        return response()->json([
            'status' => true,
            'message' => 'Transaksi berhasil dihapus'
        ]);
    }
}