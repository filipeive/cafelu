<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public static function createNotification($data)
    {
        return Notification::create(array_merge([
            'type' => 'info',
            'priority' => 'medium',
            'is_read' => false,
        ], $data));
    }

    // Notificações específicas do sistema
    public static function lowStockNotification($product, $currentStock)
    {
        return self::createNotification([
            'title' => 'Estoque Baixo',
            'message' => "O produto {$product->name} está com estoque baixo: {$currentStock} unidades",
            'type' => 'warning',
            'priority' => 'high',
            'related_model' => 'Product',
            'related_id' => $product->id,
            'metadata' => [
                'stock_level' => $currentStock,
                'product_name' => $product->name,
                'action_required' => true
            ]
        ]);
    }

    public static function orderCompletedNotification($order)
    {
        return self::createNotification([
            'title' => 'Pedido Finalizado',
            'message' => "Pedido #{$order->id} da Mesa {$order->table->number} - Total: " . number_format($order->total, 2) . " MZN",
            'type' => 'success',
            'priority' => 'medium',
            'related_model' => 'Order',
            'related_id' => $order->id,
            'metadata' => [
                'table_number' => $order->table->number,
                'total_amount' => $order->total_amount,
                'order_id' => $order->id
            ]
        ]);
    }

    public static function newReservationNotification($reservation)
    {
        return self::createNotification([
            'title' => 'Nova Reserva',
            'message' => "Nova reserva para {$reservation->people} pessoas às {$reservation->time}",
            'type' => 'info',
            'priority' => 'medium',
            'related_model' => 'Reservation',
            'related_id' => $reservation->id,
            'metadata' => [
                'people_count' => $reservation->people,
                'reservation_time' => $reservation->time,
                'customer_name' => $reservation->customer_name
            ]
        ]);
    }
    
    public static function paymentDueNotification($debt)
    {
        return self::createNotification([
            'title' => 'Pagamento em Atraso',
            'message' => "Cliente {$debt->client->name} com pagamento em atraso: " . number_format($debt->amount, 2) . " MZN",
            'type' => 'danger',
            'priority' => 'high',
            'related_model' => 'Debt',
            'related_id' => $debt->id,
            'metadata' => [
                'debt_amount' => $debt->amount,
                'client_name' => $debt->client->name,
                'due_date' => $debt->due_date
            ]
        ]);
    }

    public static function notifyWaiterOrderReady($order)
    {
        return self::createNotification([
            'title'   => 'Pedido Pronto',
            'message' => "O pedido #{$order->id} está pronto para entrega.",
            'type'    => 'success',
            'priority'=> 'high',
            'related_model' => 'Order',
            'related_id'    => $order->id,
            'metadata'      => [
                'table'     => $order->table ? $order->table->number : 'Balcão',
                'waiter'    => $order->user->name ?? 'Sistema',
                'total'     => $order->total_amount
            ]
        ]);
    }
    //novo pedido
    public static function newOrderNotification($order){
        return self::createNotification([
            'title' => 'Novo Pedido',
            'message' => "Novo pedido #{$order->id} para a Mesa {$order->table->number}, Total: " . number_format($order->total, 2) . " MZN",
            'type' => 'info',
            'priority' => 'medium',
            'related_model' => 'Order',
            'related_id' => $order->id,
            'metadata' => [
                'table_number' => $order->table->number,
                'total_amount' => $order->total,
                'order_id' => $order->id
            ]
        ]);
    }

    public static function systemMaintenanceNotification($message)
    {
        return self::createNotification([
            'title' => 'Manutenção do Sistema',
            'message' => $message,
            'type' => 'warning',
            'priority' => 'medium',
            'metadata' => [
                'system_notification' => true,
                'requires_acknowledgment' => true
            ]
        ]);
    }

    // Obter notificações para um usuário
    public static function getUserNotifications($userId, $limit = 10)
    {
        return Notification::forUser($userId)
            ->active()
            ->unread()
            ->byPriority()
            ->latest()
            ->limit($limit)
            ->get();
    }

    // Contar notificações não lidas
    public static function getUnreadCount($userId)
    {
        return Notification::forUser($userId)
            ->active()
            ->unread()
            ->count();
    }

    // Marcar todas como lidas
    public static function markAllAsRead($userId)
    {
        return Notification::forUser($userId)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    
}