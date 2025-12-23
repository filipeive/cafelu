<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Order extends Model
{
    use HasFactory, Auditable;
    protected $table = 'orders'; // Specify the table name if different from the model name
    protected $fillable = [
        'table_id',
        'user_id',
        'customer_name',
        'status',
        'total_amount',
        'payment_status',
        'payment_method',
        'notes',
        'preparing_at',
        'ready_at',
        'delivered_at',
        'cancel_requested_at',
        'cancellation_reason',
        'cancellation_status'
    ];

    public function canBeCanceled()
    {
        // Only active orders can be canceled
        if ($this->status !== 'active') {
            return false;
        }

        // Check if cancellation was already requested
        if ($this->cancellation_status !== 'none') {
            return false;
        }

        // Check time limit (e.g., 5 minutes)
        $timeLimit = 5;
        return $this->created_at->diffInMinutes(now()) <= $timeLimit;
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Define os possíveis status do pedido
    public static function statuses()
    {
        return [
            'active' => 'Ativo',
            'preparing' => 'Preparando',
            'ready' => 'Pronto',
            'delivered' => 'Entregue',
            'canceled' => 'Cancelado',
            'completed' => 'Concluído',
            'paid' => 'Pago',
            'pending_cancel' => 'Cancelamento Pendente'
        ];
    }

    // Define os possíveis status de pagamento
    public static function paymentStatuses()
    {
        return [
            'pending' => 'Pendente',
            'awaiting_confirmation' => 'Aguardando Confirmação',
            'paid' => 'Pago',
            'partial' => 'Parcial',
            'cancelled' => 'Cancelado',
            'refunded' => 'Reembolsado'
        ];
    }

    // Define os métodos de pagamento disponíveis
    public static function paymentMethods()
    {
        return [
            'cash' => 'Dinheiro',
            'credit_card' => 'Cartão de Crédito',
            'debit_card' => 'Cartão de Débito',
            'pix' => 'PIX',
            'transfer' => 'Transferência',
            'app' => 'Aplicativo'
        ];
    }
}