<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToTenant;

class CashShift extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'opened_at',
        'closed_at',
        'opening_balance',
        'closing_balance_system',
        'closing_balance_actual',
        'difference',
        'cash_sales',
        'card_sales',
        'mpesa_sales',
        'emola_sales',
        'total_sales',
        'status',
        'notes',
    ];

    protected $casts = [
        'opened_at'              => 'datetime',
        'closed_at'              => 'datetime',
        'opening_balance'        => 'decimal:2',
        'closing_balance_system' => 'decimal:2',
        'closing_balance_actual' => 'decimal:2',
        'difference'             => 'decimal:2',
        'cash_sales'             => 'decimal:2',
        'card_sales'             => 'decimal:2',
        'mpesa_sales'            => 'decimal:2',
        'emola_sales'            => 'decimal:2',
        'total_sales'            => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'cash_shift_id');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function getExpectedCashAttribute(): float
    {
        return round((float) $this->opening_balance + (float) $this->cash_sales, 2);
    }

    /**
     * Recalcula os totais com base nas vendas associadas a este turno.
     */
    public function recalculateTotals(): void
    {
        $sales = $this->sales;

        $this->cash_sales = $sales->sum('cash_amount');
        $this->card_sales = $sales->sum('card_amount');
        $this->mpesa_sales = $sales->sum('mpesa_amount');
        $this->emola_sales = $sales->sum('emola_amount');
        $this->total_sales = $sales->sum('total_amount');

        $this->saveQuietly();
    }
}

