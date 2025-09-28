<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'order_id',
        'user_id',
        'customer_name',
        'client_id',
        'sale_date',
        'total_amount',
        'payment_method',
        'status',
        'cash_amount',
        'card_amount',
        'mpesa_amount',
        'emola_amount',
        'notes'  // Adicionado o novo campo
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'cash_amount' => 'decimal:2',
        'card_amount' => 'decimal:2',
        'mpesa_amount' => 'decimal:2',
        'emola_amount' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function items(): HasMany
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
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

}