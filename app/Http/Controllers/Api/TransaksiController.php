<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $transaksi = Transaksi::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Data transaksi berhasil diambil',
            'data'    => $transaksi
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id'        => 'required|integer',
            'total_harga'       => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string',
        ]);

        $transaksi = Transaksi::create([
            'user_id'           => $request->user()->id,
            'booking_id'        => $request->booking_id,
            'total_harga'       => $request->total_harga,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status'            => 'pending',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Transaksi berhasil dibuat',
            'data'    => $transaksi
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['status' => false, 'message' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($transaksi->user_id !== $request->user()->id) {
            return response()->json(['status' => false, 'message' => 'Akses ditolak'], 403);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail transaksi',
            'data'    => $transaksi
        ]);
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['status' => false, 'message' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($transaksi->user_id !== $request->user()->id) {
            return response()->json(['status' => false, 'message' => 'Akses ditolak'], 403);
        }

        $transaksi->update($request->only(['metode_pembayaran', 'status']));

        return response()->json([
            'status'  => true,
            'message' => 'Transaksi berhasil diperbarui',
            'data'    => $transaksi
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['status' => false, 'message' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($transaksi->user_id !== $request->user()->id) {
            return response()->json(['status' => false, 'message' => 'Akses ditolak'], 403);
        }

        $transaksi->delete();

        return response()->json(['status' => true, 'message' => 'Transaksi berhasil dihapus']);
    }
}