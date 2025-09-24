<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'table_id',
        'user_id',
        'customer_name',
        'status',
        'total_amount',
        'payment_status',
        'payment_method',
        'notes',
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    // Relacionamentos
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

    // Status do pedido (alinhado com controller e view)
    public static function statuses()
    {
        return [
            'active' => 'Ativo',
            'completed' => 'Finalizado',
            'canceled' => 'Cancelado',
            'paid' => 'Pago'
        ];
    }

    // Status de pagamento
    public static function paymentStatuses()
    {
        return [
            'pending' => 'Pendente',
            'paid' => 'Pago',
            'canceled' => 'Cancelado'
        ];
    }

    // Métodos de pagamento (alinhado com view)
    public static function paymentMethods()
    {
        return [
            'cash' => 'Dinheiro',
            'card' => 'Cartão',
            'mpesa' => 'M-Pesa',
            'emola' => 'E-Mola',
            'mkesh' => 'M-Kesh',
            'outros' => 'Outros'
        ];
    }

    // Método para classes CSS do badge de status
    public function getStatusBadgeClass()
    {
        $classes = [
            'active' => 'bg-warning text-white',
            'completed' => 'bg-success text-white',
            'canceled' => 'bg-danger text-white',
            'paid' => 'bg-info text-white',
        ];
        return $classes[$this->status] ?? 'bg-secondary text-white';
    }

    // Método para texto legível do status
    public function getStatusText()
    {
        $texts = [
            'active' => 'Em Andamento',
            'completed' => 'Finalizado',
            'canceled' => 'Cancelado',
            'paid' => 'Pago'
        ];
        return $texts[$this->status] ?? ucfirst($this->status);
    }

    // Scopes úteis
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeSearch($query, $search)
    {
        if (empty($search)) return $query;

        return $query->where(function($q) use ($search) {
            $q->where('customer_name', 'like', "%{$search}%")
              ->orWhere('id', 'like', "%{$search}%")
              ->orWhere('notes', 'like', "%{$search}%")
              ->orWhereHas('table', function($tableQuery) use ($search) {
                  $tableQuery->where('number', 'like', "%{$search}%");
              });
        });
    }
}