<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SaleCompletedNotification extends Notification
{
    use Queueable;

    protected $sale;

    /**
     * Create a new notification instance.
     */
    public function __construct($sale)
    {
        $this->sale = $sale;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'sale_completed',
            'sale_id' => $this->sale->id,
            'total_amount' => $this->sale->total_amount,
            'message' => __('messages.sale_completed_notification', ['id' => $this->sale->id, 'amount' => number_format($this->sale->total_amount, 2)]),
            'icon' => 'mdi-check-circle',
            'color' => 'text-success',
            'link' => route('sales.show', $this->sale->id),
        ];
    }
}
