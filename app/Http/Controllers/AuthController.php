<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
{
    \Log::info('register called');
    try {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

       $user = User::create([
    'nama' => $request->nama,
    'email' => $request->email,
    'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
    'id_user' => $user->id,
    'nama' => $user->nama,
    'email' => $user->email
]
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

}