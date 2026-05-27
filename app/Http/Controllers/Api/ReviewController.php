<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\TravelPackage;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * GET /api/packages/{packageId}/reviews
     * Semua review milik satu paket wisata (publik, tanpa auth)
     */
    public function index($packageId)
    {
        $reviews = Review::with('user:id,nama,photo')
            ->where('travel_package_id', $packageId)
            ->latest()
            ->get()
            ->map(function ($r) {
                return [
                    'id'         => $r->id,
                    'rating'     => $r->rating,
                    'comment'    => $r->comment,
                    'created_at' => $r->created_at,
                    'user' => [
                        'id'        => $r->user->id,
                        'nama'      => $r->user->nama,
                        'photo_url' => $r->user->photo
                            ? \Storage::url($r->user->photo)
                            : null,
                    ],
                ];
            });

        $avg = $reviews->avg('rating');

        return response()->json([
            'success'        => true,
            'average_rating' => $avg ? round($avg, 1) : null,
            'total'          => $reviews->count(),
            'data'           => $reviews,
        ]);
    }

    /**
     * POST /api/packages/{packageId}/reviews
     * Buat review baru (harus login)
     */
    public function store(Request $request, $packageId)
    {
        $request->validate([
            'rating'       => 'required|integer|min:1|max:5',
            'comment'      => 'nullable|string|max:1000',
            'transaksi_id' => 'nullable|exists:transaksis,id',
        ]);

        // Pastikan paket ada
        TravelPackage::findOrFail($packageId);

        // Cek sudah pernah review
        $existing = Review::where('user_id', $request->user()->id)
            ->where('travel_package_id', $packageId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah memberikan review untuk paket ini.',
            ], 422);
        }

        $review = Review::create([
            'user_id'           => $request->user()->id,
            'travel_package_id' => $packageId,
            'transaksi_id'      => $request->transaksi_id,
            'rating'            => $request->rating,
            'comment'           => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil ditambahkan!',
            'data'    => $review,
        ], 201);
    }

    /**
     * PUT /api/reviews/{id}
     * Edit review milik sendiri
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = Review::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $review->update([
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil diperbarui.',
            'data'    => $review,
        ]);
    }

    /**
     * DELETE /api/reviews/{id}
     * Hapus review milik sendiri
     */
    public function destroy(Request $request, $id)
    {
        $review = Review::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil dihapus.',
        ]);
    }

    /**
     * GET /api/reviews/my
     * Semua review yang pernah dibuat oleh user yang login
     */
    public function myReviews(Request $request)
    {
        $reviews = Review::with('travelPackage:id,name')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $reviews,
        ]);
    }
}