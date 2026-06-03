<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destinasi;
use Illuminate\Http\Request;

class TravelController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with('destinasi')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($b) => $this->format($b));

        return response()->json([
            'status'  => true,
            'message' => 'Data booking berhasil diambil',
            'data'    => $bookings
        ]);
    }

    public function show(Request $request, $id)
    {
        $booking = Booking::with('destinasi')->find($id);

        if (!$booking) {
            return response()->json(['status' => false, 'message' => 'Booking tidak ditemukan'], 404);
        }

        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['status' => false, 'message' => 'Akses ditolak'], 403);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail booking',
            'data'    => $this->format($booking)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'destinasi_id'      => 'required|integer|exists:destinasi,id',
            'tanggal_berangkat' => 'required|date',
            'jumlah_tiket'      => 'required|integer|min:1',
            'customer_name'     => 'nullable|string',
            'customer_email'    => 'nullable|email',
            'customer_phone'    => 'nullable|string',
            'special_requests'  => 'nullable|string',
        ]);

        // Hitung total_harga otomatis dari harga destinasi
        $destinasi  = Destinasi::findOrFail($request->destinasi_id);
        $totalHarga = $destinasi->harga_tiket * $request->jumlah_tiket;

        $booking = Booking::create([
            'user_id'           => $request->user()->id,
            'destinasi_id'      => $request->destinasi_id,
            'booking_code'      => 'BOOK-' . strtoupper(uniqid()),
            'tanggal_berangkat' => $request->tanggal_berangkat,
            'jumlah_tiket'      => $request->jumlah_tiket,
            'total_harga'       => $totalHarga,
            'status'            => 'pending',
            'payment_status'    => 'unpaid',
            'customer_name'     => $request->customer_name,
            'customer_email'    => $request->customer_email,
            'customer_phone'    => $request->customer_phone,
            'special_requests'  => $request->special_requests,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Booking berhasil dibuat',
            'data'    => [
                'id'           => $booking->id,
                'booking_code' => $booking->booking_code,
                'total_harga'  => $booking->total_harga,
                'status'       => $booking->status,
            ]
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['status' => false, 'message' => 'Booking tidak ditemukan'], 404);
        }

        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['status' => false, 'message' => 'Akses ditolak'], 403);
        }

        $booking->update($request->only([
            'tanggal_berangkat', 'jumlah_tiket', 'status',
            'payment_status', 'payment_method',
            'customer_name', 'customer_email', 'customer_phone',
            'special_requests', 'qr_code'
        ]));

        return response()->json([
            'status'  => true,
            'message' => 'Booking berhasil diperbarui',
            'data'    => $this->format($booking->fresh('destinasi'))
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['status' => false, 'message' => 'Booking tidak ditemukan'], 404);
        }

        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['status' => false, 'message' => 'Akses ditolak'], 403);
        }

        $booking->delete();

        return response()->json(['status' => true, 'message' => 'Booking berhasil dihapus']);
    }

    private function format($booking)
    {
        return [
            'id'               => $booking->id,
            'booking_code'     => $booking->booking_code,
            'tanggal_berangkat'=> $booking->tanggal_berangkat,
            'jumlah_tiket'     => $booking->jumlah_tiket,
            'total_harga'      => (float) $booking->total_harga,
            'status'           => $booking->status,
            'payment_status'   => $booking->payment_status,
            'payment_method'   => $booking->payment_method,
            'customer_name'    => $booking->customer_name,
            'customer_email'   => $booking->customer_email,
            'customer_phone'   => $booking->customer_phone,
            'special_requests' => $booking->special_requests,
            'qr_code'          => $booking->qr_code,
            'created_at'       => $booking->created_at,
            'destinasi'        => $booking->destinasi ? [
                'id'       => $booking->destinasi->id,
                'name'     => $booking->destinasi->nama_destinasi,
                'location' => $booking->destinasi->lokasi,
                'image'    => $booking->destinasi->image ?? null,
                'price'    => (float) $booking->destinasi->harga_tiket,
            ] : null,
        ];
    }
}