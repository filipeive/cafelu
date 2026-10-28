<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class WaiterCalledNotification extends Notification
{
    protected string $tableName;
    protected string $customerName;
    protected string $reason;

    public function __construct(string $tableName, string $customerName = 'Cliente', string $reason = 'Solicitou atendimento na mesa')
    {
        $this->tableName = $tableName;
        $this->customerName = $customerName;
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'waiter_called',
            'table_name' => $this->tableName,
            'customer_name' => $this->customerName,
            'reason' => $this->reason,
            'message' => "Chamado na {$this->tableName}: {$this->customerName} ({$this->reason})",
            'icon' => 'mdi-bell-ring',
            'color' => 'text-amber-500',
            'link' => route('tables.index'),
        ];
    }
}

