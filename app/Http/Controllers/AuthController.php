<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Exception\FirebaseException;

class AuthController extends Controller
{
    protected $firestore;

    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore->database();
    }

    // =========================================================
    // REGISTER - Daftar User Baru
    // POST /api/register
    // Body: nama, email, password, role (opsional)
    // =========================================================
    public function register(Request $request)
    {
        \Log::info('register called'); // ← dari kode teman, buat debug di Postman

        $validator = Validator::make($request->all(), [
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
            'role'     => 'sometimes|string|in:user,admin',
        ], [
            'nama.required'     => 'Nama wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'role.in'           => 'Role hanya boleh: user atau admin.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(), // ← format pesan mirip kode teman
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            // Cek email duplikat di Firestore (pengganti unique:users,email dari kode teman)
            $existingUsers = $this->firestore->collection('users')
                ->where('email', '=', $request->email)
                ->documents();

            foreach ($existingUsers as $doc) {
                if ($doc->exists()) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'The email has already been taken.', // ← pesan sama kayak Laravel default
                    ], 409);
                }
            }

            // Simpan user baru ke Firestore
            $data = [
                'nama'       => $request->nama,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'role'       => $request->role ?? 'user',
                'created_at' => now()->toIso8601String(),
            ];

            $newDoc = $this->firestore->collection('users')->add($data);

            return response()->json([
                'status'  => true,
                'message' => 'Registrasi berhasil',   // ← pesan sama kayak kode teman
                'data'    => [
                    'id_user' => $newDoc->id(),        // ← key id_user sama kayak kode teman
                    'nama'    => $data['nama'],
                    'email'   => $data['email'],
                    // password tidak dikembalikan
                ],
            ], 201);

        } catch (FirebaseException $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),         // ← format sama kayak kode teman
            ], 500);
        }
    }

    // =========================================================
    // LOGIN - Masuk dengan Email & Password
    // POST /api/login
    // Body: email, password
    // =========================================================
    public function login(Request $request)
    {
        \Log::info('login called');

        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            // Cari user berdasarkan email di Firestore
            $documents = $this->firestore->collection('users')
                ->where('email', '=', $request->email)
                ->documents();

            $user   = null;
            $userId = null;

            foreach ($documents as $doc) {
                if ($doc->exists()) {
                    $user   = $doc->data();
                    $userId = $doc->id();
                    break;
                }
            }

            if (!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Email atau password salah.',
                ], 401);
            }

            if (!Hash::check($request->password, $user['password'])) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Email atau password salah.',
                ], 401);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Login berhasil',
                'data'    => [
                    'id_user' => $userId,          // ← key id_user konsisten
                    'nama'    => $user['nama'],
                    'email'   => $user['email'],
                    'role'    => $user['role'] ?? 'user',
                ],
            ], 200);

        } catch (FirebaseException $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}