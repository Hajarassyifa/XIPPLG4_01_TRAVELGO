<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;

class DestinasiController extends Controller
{
    public function index()
    {
        $data = Destinasi::all();

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
            'status' => false,
            'message' => 'Destinasi tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'Detail destinasi',
        'data' => $destinasi
    ]);
}
}