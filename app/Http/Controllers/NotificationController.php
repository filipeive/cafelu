<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', __('messages.all_notifications_marked_as_read'));
    }

    public function unread()
    {
        $user = Auth::user();
        $unreadNotifications = $user->unreadNotifications->take(5)->map(function ($n) {
            return [
                'id' => $n->id,
                'message' => $n->data['message'] ?? '',
                'icon' => $n->data['icon'] ?? 'mdi-bell',
                'color' => $n->data['color'] ?? 'text-primary',
                'link' => $n->data['link'] ?? '#',
                'time' => $n->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'unreadCount' => $user->unreadNotifications->count(),
            'notifications' => $unreadNotifications,
        ]);
    }

    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', __('messages.notification_deleted'));
    }
}
