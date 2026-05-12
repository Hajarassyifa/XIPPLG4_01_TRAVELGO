<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // validasi
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        // simpan user
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // response
        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'id_user' => $user->id_user,
                'nama' => $user->n=`ama,
                'email' => $user->email
            ]
        ], 201);
    }
}