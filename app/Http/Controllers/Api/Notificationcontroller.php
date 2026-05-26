<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * GET /api/notifications
     * Ambil semua notifikasi milik user yang login
     */
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'unread_count' => $notifications->where('is_read', false)->count(),
            'data'    => $notifications,
        ]);
    }

    /**
     * PUT /api/notifications/{id}/read
     * Tandai satu notifikasi sebagai sudah dibaca
     */
    public function markRead(Request $request, $id)
    {
        $notif = Notification::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $notif->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca.',
        ]);
    }

    /**
     * PUT /api/notifications/read-all
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi telah ditandai dibaca.',
        ]);
    }

    /**
     * Helper: dipanggil dari controller lain (mis. BookingController)
     * untuk membuat notifikasi otomatis
     *
     * Contoh penggunaan:
     *   NotificationController::create($userId, 'Booking Berhasil', 'Paket wisata Anda sedang diproses.', 'booking', $bookingId);
     */
    public static function create(int $userId, string $title, string $message, string $type = 'booking', $referenceId = null): void
    {
        Notification::create([
            'user_id'      => $userId,
            'title'        => $title,
            'message'      => $message,
            'type'         => $type,
            'reference_id' => $referenceId,
        ]);
    }
}