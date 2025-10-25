<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Get user notifications
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $perPage = $request->get('per_page', 15);
        $type = $request->get('type');
        $important = $request->get('important');
        $unread = $request->get('unread');

        $query = $user->notifications()->notExpired()->latest();

        if ($type) {
            $query->byType($type);
        }

        if ($important) {
            $query->important();
        }

        if ($unread) {
            $query->unread();
        }

        $notifications = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'notifications' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ],
            'unread_count' => $user->unreadNotifications()->count(),
            'important_count' => $user->importantNotifications()->unread()->count(),
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, Notification $notification): JsonResponse
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = Auth::user();
        $user->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }

    /**
     * Get notification count
     */
    public function count(): JsonResponse
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'unread_count' => $user->unreadNotifications()->count(),
            'important_count' => $user->importantNotifications()->unread()->count(),
            'total_count' => $user->notifications()->notExpired()->count(),
        ]);
    }

    /**
     * Get recent notifications (for header dropdown)
     */
    public function recent(): JsonResponse
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->notExpired()
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Create notification (Admin only)
     */
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'channel' => 'required|in:email,sms,push,in_app',
            'is_important' => 'boolean',
            'data' => 'array',
        ]);

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'channel' => $request->channel,
            'is_important' => $request->is_important ?? false,
            'data' => $request->data ?? [],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification created successfully',
            'notification' => $notification
        ]);
    }

    /**
     * Send notification (trigger sending)
     */
    public function send(Notification $notification): JsonResponse
    {
        // This would integrate with email/SMS/push services
        // For now, just mark as sent
        $notification->markAsSent();

        return response()->json([
            'success' => true,
            'message' => 'Notification sent successfully'
        ]);
    }
}
