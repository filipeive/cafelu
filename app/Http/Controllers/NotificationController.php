<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $query = Auth::user()->notifications();

        // Extra safety: if customer, ensure they only see relevant types if any leaked
        if (Auth::user()->role === 'customer') {
            $query->whereIn('data->type', ['order_ready', 'sale_completed', 'new_order']);
            // Note: sale_completed and new_order are only sent to them if it's THEIR order
        }

        $notifications = $query->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', __('messages.notification_marked_as_read'));
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', __('messages.all_notifications_marked_as_read'));
    }

    public function unread()
    {
        $user = Auth::user();
        $query = $user->unreadNotifications();

        if ($user->role === 'customer') {
            $query->whereIn('data->type', ['order_ready', 'sale_completed', 'new_order']);
        }

        $unreadNotifications = $query->take(5)->get()->map(function ($n) {
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
            'unreadCount' => $user->role === 'customer'
                ? $user->unreadNotifications()->whereIn('data->type', ['order_ready', 'sale_completed', 'new_order'])->count()
                : $user->unreadNotifications->count(),
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
