<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders'; // Specify the table name if different from the model name
    protected $fillable = [
        'table_id',
        'user_id',
        'customer_name',
        'status',
        'total_amount',
        'payment_status',
        'payment_method',
        'notes'
    ];
    
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
            'pending' => 'Pendente',
            'preparing' => 'Preparando',
            'ready' => 'Pronto',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
            'completed' => 'Concluído'
        ];
    }

    // Define os possíveis status de pagamento
    public static function paymentStatuses()
    {
        return [
            'pending' => 'Pendente',
            'paid' => 'Pago',
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