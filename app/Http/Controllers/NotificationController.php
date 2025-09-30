<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibir listagem de notificações (VIEW HTML)
     */
    public function index(Request $request)
    {
        $notifications = NotificationService::getUserNotifications(auth()->id(), 20);
        
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Exibir detalhes de uma notificação (VIEW HTML)
     */
    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        
        // Verificar permissão
        if ($notification->user_id && $notification->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para visualizar esta notificação.');
        }
        
        // Marcar como lida ao visualizar
        if (!$notification->is_read) {
            $notification->markAsRead();
        }
        
        return view('notifications.show', compact('notification'));
    }

    /**
     * Marcar notificação como lida (REDIRECT)
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id && $notification->user_id !== auth()->id()) {
            abort(403);
        }
        
        $notification->markAsRead();
        
        return redirect()->back()->with('success', 'Notificação marcada como lida');
    }

    /**
     * Marcar todas as notificações como lidas (REDIRECT)
     */
    public function markAllAsRead()
    {
        NotificationService::markAllAsRead(auth()->id());
        
        return redirect()->back()->with('success', 'Todas as notificações foram marcadas como lidas');
    }

    /**
     * API: Obter contagem de não lidas (JSON)
     */
    public function getUnreadCount()
    {
        $count = NotificationService::getUnreadCount(auth()->id());
        
        return response()->json(['count' => $count]);
    }

    /**
     * API: Obter lista de notificações (JSON) - Para o dropdown do navbar
     */
    public function getNotifications()
    {
        $notifications = NotificationService::getUserNotifications(auth()->id(), 10);
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => NotificationService::getUnreadCount(auth()->id())
        ]);
    }

    /**
     * Stream de notificações (Server-Sent Events)
     */
    public function stream()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Desabilitar buffer do Nginx

        // Enviar contagem inicial
        $count = NotificationService::getUnreadCount(auth()->id());
        echo "data: " . json_encode(['unread_count' => $count]) . "\n\n";
        ob_flush();
        flush();

        // Manter conexão aberta
        while (true) {
            $count = NotificationService::getUnreadCount(auth()->id());
            echo "data: " . json_encode(['unread_count' => $count]) . "\n\n";
            ob_flush();
            flush();
            
            sleep(5); // Verificar a cada 5 segundos
            
            // Verificar se conexão ainda está ativa
            if (connection_aborted()) {
                break;
            }
        }
    }

    /**
     * Verificar novas notificações (JSON)
     */
    public function checkNew(Request $request)
    {
        $lastCheck = $request->input('last_check');
        
        $newNotifications = Notification::where('user_id', auth()->id())
            ->where('created_at', '>', $lastCheck)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json([
            'has_new' => $newNotifications->count() > 0,
            'count' => $newNotifications->count(),
            'notifications' => $newNotifications,
            'unread_count' => NotificationService::getUnreadCount(auth()->id())
        ]);
    }
}