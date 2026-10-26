<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'subdomain',
        'nuit',
        'email',
        'phone',
        'address',
        'city',
        'currency',
        'status',
        'installation_mode',
        'plan',
        'max_tables',
        'max_users',
        'trial_ends_at',
        'license_expires_at',
        'settings',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'license_expires_at' => 'datetime',
        'settings' => 'array',
        'max_tables' => 'integer',
        'max_users' => 'integer',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function cashShifts(): HasMany
    {
        return $this->hasMany(CashShift::class);
    }

    public function licenseKeys(): HasMany
    {
        return $this->hasMany(LicenseKey::class);
    }

    public function latestLicenseKey(): HasOne
    {
        return $this->hasOne(LicenseKey::class)->latestOfMany();
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    public function isTrial(): bool
    {
        return $this->status === 'trial';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isExpired(): bool
    {
        if ($this->plan === 'lifetime') {
            return false;
        }

        if ($this->license_expires_at && $this->license_expires_at->isPast()) {
            return true;
        }

        if ($this->status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isPast()) {
            return true;
        }

        return false;
    }

    public function daysRemaining(): int
    {
        if ($this->plan === 'lifetime') {
            return 9999;
        }

        $expiry = $this->license_expires_at ?? $this->trial_ends_at;
        if (!$expiry) {
            return 0;
        }

        return max(0, (int) now()->diffInDays($expiry, false));
    }
}

