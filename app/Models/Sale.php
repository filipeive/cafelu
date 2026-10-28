<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;

use App\Traits\Auditable;
use App\Traits\BelongsToTenant;

class Sale extends Model
{
    use HasFactory, Auditable, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'offline_id',
        'order_id',
        'quotation_id',
        'user_id',
        'cash_shift_id',
        'sale_date',
        'due_date',
        'customer_name',
        'customer_nuit',
        'customer_phone',
        'customer_address',
        'subtotal',
        'discount_amount',
        'total_amount',
        'tax_regime',
        'tax_rate',
        'tax_amount',
        'prices_include_tax',
        'payment_method',
        'invoice_type',
        'invoice_number',
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
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'prices_include_tax' => 'boolean',
        'cash_amount' => 'decimal:2',
        'card_amount' => 'decimal:2',
        'mpesa_amount' => 'decimal:2',
        'emola_amount' => 'decimal:2'
    ];

    protected static function booted(): void
    {
        static::creating(function (Sale $sale) {
            if (Schema::hasColumn('sales', 'invoice_type')) {
                if (empty($sale->invoice_type)) {
                    $sale->invoice_type = !empty($sale->customer_nuit) ? 'cash_invoice' : 'receipt';
                }
                if (empty($sale->invoice_number)) {
                    $tenantId = $sale->tenant_id ?? (function_exists('current_tenant_id') ? current_tenant_id() : null);
                    $sale->invoice_number = self::generateNextInvoiceNumber($tenantId, $sale->invoice_type);
                }
                if (empty($sale->subtotal) || (float) $sale->subtotal <= 0) {
                    $sale->subtotal = $sale->total_amount ?? 0;
                }
                if (empty($sale->tax_regime)) {
                    $sale->tax_regime = 'normal';
                }
                if ($sale->tax_rate === null) {
                    $sale->tax_rate = 16.00;
                }
                if (($sale->tax_amount === null || (float) $sale->tax_amount == 0) && $sale->tax_regime === 'normal' && (float) $sale->total_amount > 0) {
                    $rate = (float) ($sale->tax_rate ?: 16.0);
                    $base = ((float) $sale->total_amount) / (1 + ($rate / 100));
                    $sale->tax_amount = round(((float) $sale->total_amount) - $base, 2);
                }
            }
        });
    }

    public function cashShift()
    {
        return $this->belongsTo(CashShift::class, 'cash_shift_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getTotalPayments()
    {
        return $this->cash_amount +
            $this->card_amount +
            $this->mpesa_amount +
            $this->emola_amount;
    }

    public function getOfficialInvoiceTitleAttribute(): string
    {
        return match ($this->invoice_type) {
            'invoice', 'credit_invoice' => 'Factura Comercial',
            'cash_invoice'              => 'Factura-Recibo',
            'proforma'                  => 'Factura Proforma',
            default                     => 'Recibo de Pagamento',
        };
    }

    public function getFormattedPaymentMethodAttribute(): string
    {
        return match ($this->payment_method) {
            'cash'     => 'Dinheiro',
            'mpesa'    => 'M-Pesa',
            'emola'    => 'e-Mola',
            'mkesh'    => 'M-Kesh',
            'card'     => 'Cartão / POS',
            'credit'   => 'Crédito / Conta Corrente',
            'transfer' => 'Transferência Bancária',
            'mixed'    => 'Pagamento Misto',
            default    => ucfirst($this->payment_method ?? 'Dinheiro'),
        };
    }

    /**
     * Gera próximo número sequencial anual de documento para o tenant.
     * RC-YYYY/0001 (Recibo), FR-YYYY/0001 (Factura-Recibo), FT-YYYY/0001 (Factura Comercial), FP-YYYY/0001 (Proforma)
     */
    public static function generateNextInvoiceNumber(?int $tenantId = null, string $type = 'receipt'): string
    {
        $year = date('Y');
        $seriesCode = match ($type) {
            'invoice', 'credit_invoice' => 'FT',
            'cash_invoice'              => 'FR',
            'proforma'                  => 'FP',
            default                     => 'RC',
        };

        $prefix = "{$seriesCode}-{$year}/";

        if (!Schema::hasColumn('sales', 'invoice_number')) {
            return $prefix . '0001';
        }

        $query = self::withoutGlobalScopes()
            ->where('invoice_number', 'like', "{$prefix}%");

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        $lastSale = $query->orderBy('id', 'desc')->first();

        $nextSeq = 1;
        if ($lastSale && preg_match("/{$seriesCode}-{$year}\/(\d+)/", (string) $lastSale->invoice_number, $matches)) {
            $nextSeq = ((int) $matches[1]) + 1;
        }

        return $prefix . str_pad((string) $nextSeq, 4, '0', STR_PAD_LEFT);
    }
}
