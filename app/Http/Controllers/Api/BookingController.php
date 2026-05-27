<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role === 'admin') {
            $bookings = Booking::all();
        } else {
            $bookings = Booking::where('user_id', $request->user()->id)->get();
        }

        return response()->json([
            'status' => true,
            'message' => 'Data booking berhasil diambil',
            'data' => $bookings
        ]);
    }

    public function show(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
        }

        if ($request->user()->role !== 'admin' && $booking->user_id !== $request->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail booking berhasil diambil',
            'data' => $booking
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'destinasi_id' => 'required|integer',
            'tanggal_berangkat' => 'required|date',
            'jumlah_tiket' => 'required|integer|min:1',
            'total_harga' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'nullable|string',
            'special_requests' => 'nullable|string',
        ]);

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'destinasi_id' => $request->destinasi_id,
            'booking_code' => 'BOOK-' . time(),
            'tanggal_berangkat' => $request->tanggal_berangkat,
            'jumlah_tiket' => $request->jumlah_tiket,
            'total_harga' => $request->total_harga,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => $request->payment_method,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'special_requests' => $request->special_requests,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Booking berhasil dibuat',
            'data' => $booking
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
        }

        if ($request->user()->role !== 'admin' && $booking->user_id !== $request->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        $booking->update($request->only([
            'tanggal_berangkat',
            'jumlah_tiket',
            'total_harga',
            'status',
            'payment_status',
            'payment_method',
            'customer_name',
            'customer_email',
            'customer_phone',
            'special_requests',
            'qr_code'
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Booking berhasil diperbarui',
            'data' => $booking
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
        }

        if ($request->user()->role !== 'admin' && $booking->user_id !== $request->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        $booking->delete();

        return response()->json([
            'status' => true,
            'message' => 'Booking berhasil dihapus'
        ]);
    }
}