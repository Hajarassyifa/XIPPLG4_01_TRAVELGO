<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * GET: Mengambil ulasan berdasarkan Travel Package ID
     */
    public function index($packageId)
    {
        try {
            // Memuat data review beserta user yang memberikan ulasan
            // Pastikan di Model Review sudah ada fungsi relasi: public function user()
            $reviews = Review::with(['user'])->where('travel_package_id', $packageId)->get();

            $averageRating = $reviews->avg('rating') ?? 0;

            return response()->json([
                'success' => true,
                'average_rating' => round($averageRating, 1),
                'total' => $reviews->count(),
                'data' => $reviews
            ], 200);

        } catch (\Exception $e) {
            // Mencegah error HTML keluar ke Postman / Android jika relasi user bermasalah
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST: Menyimpan ulasan baru
     */
    public function store(Request $request, $packageId)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $review = Review::create([
                'user_id' => $request->user()->id,
                'travel_package_id' => $packageId, // DIAMBIL LANGSUNG DARI URL ROUTE
                'transaksi_id' => $request->transaksi_id, // Opsional jika dikirim
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil ditambahkan!',
                'data' => $review
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan ulasan: ' . $e->getMessage()
            ], 500);
        }
    }
}