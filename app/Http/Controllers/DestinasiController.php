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
}