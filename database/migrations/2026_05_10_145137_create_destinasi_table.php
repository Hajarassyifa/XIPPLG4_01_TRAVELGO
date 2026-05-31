<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;

class DestinasiController extends Controller
{
    public function index()
    {
        $data = Destinasi::all()->map(function ($item) {
            return $this->format($item);
        });

        return response()->json([
            'status'  => true,
            'message' => 'List Destinasi',
            'data'    => $data,
        ]);
    }

    public function show($id)
    {
        $destinasi = Destinasi::find($id);

        if (!$destinasi) {
            return response()->json([
                'status'  => false,
                'message' => 'Destinasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail destinasi',
            'data'    => $this->format($destinasi)
        ]);
    }

    private function format($item)
    {
        return [
            'id'          => $item->id,
            'name'        => $item->nama_destinasi,
            'location'    => $item->lokasi,
            'description' => $item->deskripsi,
            'price'       => (float) $item->harga_tiket,
            'image'       => $item->image ?? null,
            'open_time'   => $item->open_time ?? null,
            'close_time'  => $item->close_time ?? null,
        ];
    }
}