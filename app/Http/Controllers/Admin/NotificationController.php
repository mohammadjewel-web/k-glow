<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications (Admin).
     */
    public function index(Request $request)
    {
        $query = Notification::with('user');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
        }

        // Type filter
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            if ($request->status === 'read') {
                $query->where('is_read', true);
            } elseif ($request->status === 'unread') {
                $query->where('is_read', false);
            }
        }

        // Important filter
        if ($request->has('important') && $request->important) {
            if ($request->important === 'important') {
                $query->where('is_important', true);
            } elseif ($request->important === 'normal') {
                $query->where('is_important', false);
            }
        }

        $notifications = $query->latest()->paginate(20)->withQueryString();
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new notification (Admin).
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('admin.notifications.create', compact('users'));
    }

    /**
     * Store a newly created notification (Admin).
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:order,payment,system,promotion',
            'user_id' => 'nullable|exists:users,id',
            'is_important' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'is_important' => $request->has('is_important'),
            'expires_at' => $request->expires_at,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully!',
                'notification' => $notification
            ]);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification sent successfully!');
    }

    /**
     * Display the specified notification (Admin).
     */
    public function show(Notification $notification)
    {
        $notification->load('user');
        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified notification (Admin).
     */
    public function edit(Notification $notification)
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('admin.notifications.edit', compact('notification', 'users'));
    }

    /**
     * Update the specified notification (Admin).
     */
    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:order,payment,system,promotion',
            'user_id' => 'nullable|exists:users,id',
            'is_important' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $notification->update([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'is_important' => $request->has('is_important'),
            'expires_at' => $request->expires_at,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification updated successfully!',
                'notification' => $notification
            ]);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification updated successfully!');
    }

    /**
     * Remove the specified notification (Admin).
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully!'
            ]);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification deleted successfully!');
    }

    /**
     * Mark notification as read (Admin).
     */
    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read!'
            ]);
        }

        return redirect()->back()->with('success', 'Notification marked as read!');
    }

    /**
     * Mark all notifications as read (Admin).
     */
    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read!'
            ]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read!');
    }
}
