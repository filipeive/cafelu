<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LicenseKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'key_code',
        'key_hash',
        'business_name',
        'customer_name',
        'customer_email',
        'customer_phone',
        'license_type',
        'status',
        'starts_at',
        'expires_at',
        'activated_at',
        'max_tables',
        'max_users',
        'device_fingerprint',
        'is_current',
        'notes',
    ];

    protected $casts = [
        'starts_at'    => 'datetime',
        'expires_at'   => 'datetime',
        'activated_at' => 'datetime',
        'is_current'   => 'boolean',
        'max_tables'   => 'integer',
        'max_users'    => 'integer',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Gera uma chave serial única no formato CAFE-XXXX-XXXX-XXXX-XXXX
     */
    public static function generateUniqueKeyCode(): string
    {
        do {
            $parts = [];
            for ($i = 0; $i < 4; $i++) {
                $parts[] = strtoupper(Str::random(4));
            }
            $code = 'CAFE-' . implode('-', $parts);
        } while (static::where('key_code', $code)->exists());

        return $code;
    }

    protected static function booted(): void
    {
        static::creating(function ($license) {
            if (empty($license->key_code)) {
                $license->key_code = static::generateUniqueKeyCode();
            }
            if (empty($license->key_hash)) {
                $license->key_hash = hash('sha256', $license->key_code);
            }
        });
    }

    /**
     * Verifica se a licença está válida
     */
    public function isValid(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->license_type === 'lifetime') {
            return true;
        }

        if (!$this->expires_at) {
            return true;
        }

        return $this->expires_at->isFuture();
    }

    /**
     * Verifica se a licença expirou
     */
    public function isExpired(): bool
    {
        if ($this->license_type === 'lifetime') {
            return false;
        }

        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    /**
     * Retorna a contagem de dias restantes
     */
    public function daysRemaining(): int
    {
        if ($this->license_type === 'lifetime' || !$this->expires_at) {
            return 99999;
        }

        if ($this->expires_at->isPast()) {
            return 0;
        }

        return (int) now()->diffInDays($this->expires_at, false);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }
}

