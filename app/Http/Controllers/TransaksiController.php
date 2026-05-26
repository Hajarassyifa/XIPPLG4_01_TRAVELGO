<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::all();
        return response()->json($transaksi);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'booking_id' => 'required',
            'total_harga' => 'required|numeric',
            'metode_pembayaran' => 'required',
            'status' => 'required'
        ]);

        $transaksi = Transaksi::create($validated);

        return response()->json([
            'message' => 'Transaksi berhasil ditambahkan',
            'data' => $transaksi
        ],201);
    }

    public function show($id)
    {
        $transaksi = Transaksi::find($id);

        if(!$transaksi){
            return response()->json([
                'message'=>'Transaksi tidak ditemukan'
            ],404);
        }

        return response()->json($transaksi);
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);

        if(!$transaksi){
            return response()->json([
                'message'=>'Transaksi tidak ditemukan'
            ],404);
        }

        $transaksi->update($request->all());

        return response()->json([
            'message'=>'Transaksi berhasil diupdate',
            'data'=>$transaksi
        ]);
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::find($id);

        if(!$transaksi){
            return response()->json([
                'message'=>'Transaksi tidak ditemukan'
            ],404);
        }

        $transaksi->delete();

        return response()->json([
            'message'=>'Transaksi berhasil dihapus'
        ]);
    }
}