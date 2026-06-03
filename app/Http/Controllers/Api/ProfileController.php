<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * GET /api/profile
     * Ambil data profil user yang sedang login
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'photo_url'  => $user->photo
                    ? Storage::url($user->photo)
                    : null,
                'created_at' => $user->created_at,
            ]
        ]);
    }

    /**
     * PUT /api/profile
     * Update nama saja
     */
    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = $request->user();
        $user->update(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'message' => 'Nama berhasil diperbarui.',
            'data'    => ['name' => $user->name],
        ]);
    }

    /**
     * POST /api/profile/photo
     * Upload / ganti foto profil
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        // Hapus foto lama kalau ada
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        // Simpan foto baru
        $path = $request->file('photo')->store('profile_photos', 'public');
        $user->update(['photo' => $path]);

        return response()->json([
            'success'   => true,
            'message'   => 'Foto profil berhasil diperbarui.',
            'photo_url' => Storage::url($path),
        ]);
    }

    /**
     * PUT /api/change-password
     * Ganti password user
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
            // Android harus kirim 'new_password_confirmation' juga
        ]);

        $user = $request->user();

        // Cek apakah password lama cocok
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak sesuai'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah'
        ]);
    }
}