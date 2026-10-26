<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\Auditable;
use App\Traits\BelongsToTenant;

class Sale extends Model
{
    use HasFactory, Auditable, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'offline_id',
        'order_id',
        'user_id',
        'cash_shift_id',
        'customer_name',
        'total_amount',
        'payment_method',
        'cash_amount',
        'card_amount',
        'mpesa_amount',
        'emola_amount',
        'notes',
        'status',
        'synced_at',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'cash_amount' => 'decimal:2',
        'card_amount' => 'decimal:2',
        'mpesa_amount' => 'decimal:2',
        'emola_amount' => 'decimal:2'
    ];

    public function cashShift()
    {
        return $this->belongsTo(CashShift::class, 'cash_shift_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Método helper para obter o total dos diferentes métodos de pagamento
    public function getTotalPayments()
    {
        return $this->cash_amount +
            $this->card_amount +
            $this->mpesa_amount +
            $this->emola_amount;
    }
}