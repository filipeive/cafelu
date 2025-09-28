<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = NotificationService::getUserNotifications(auth()->id(), 20);
        
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        // Verificar se o usuário tem permissão para ver esta notificação
        if ($notification->user_id && $notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        NotificationService::markAllAsRead(auth()->id());

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = NotificationService::getUnreadCount(auth()->id());

        return response()->json(['count' => $count]);
    }

    public function getNotifications()
    {
        $notifications = NotificationService::getUserNotifications(auth()->id(), 10);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => NotificationService::getUnreadCount(auth()->id())
        ]);
    }

    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        
        // Verificar permissão
        if ($notification->user_id && $notification->user_id !== auth()->id()) {
            abort(403);
        }

        // Marcar como lida ao visualizar
        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        return view('notifications.show', compact('notification'));
    }
}