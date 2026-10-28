<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'key',
        'value',
        'group',
        'type',
    ];

    protected static function cacheKey(?int $tenantId = null): string
    {
        $resolvedTenantId = $tenantId ?? (function_exists('current_tenant_id') ? current_tenant_id() : null) ?? auth()->user()?->tenant_id;
        return 'app_settings_tenant_' . ($resolvedTenantId ?: 'global');
    }

    public static function clearCache(?int $tenantId = null): void
    {
        Cache::forget(self::cacheKey($tenantId));
        Cache::forget('app_settings_all');
    }

    protected static function booted()
    {
        static::saved(function ($setting) {
            self::clearCache($setting->tenant_id);
        });

        static::deleted(function ($setting) {
            self::clearCache($setting->tenant_id);
        });
    }

    public static function allCached(?int $tenantId = null): array
    {
        $resolvedTenantId = $tenantId ?? (function_exists('current_tenant_id') ? current_tenant_id() : null) ?? auth()->user()?->tenant_id;

        return Cache::remember(self::cacheKey($resolvedTenantId), 3600, function () use ($resolvedTenantId) {
            try {
                $query = self::withoutGlobalScopes();
                if ($resolvedTenantId) {
                    $query->where('tenant_id', $resolvedTenantId);
                } else {
                    $query->whereNull('tenant_id');
                }
                return $query->pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    public static function get($key, $default = null, ?int $tenantId = null)
    {
        $settings = self::allCached($tenantId);
        return array_key_exists($key, $settings) && $settings[$key] !== null
            ? $settings[$key]
            : $default;
    }

    public static function set($key, $value, $group = 'general', $type = 'text', ?int $tenantId = null)
    {
        $resolvedTenantId = $tenantId ?? (function_exists('current_tenant_id') ? current_tenant_id() : null) ?? auth()->user()?->tenant_id;

        self::clearCache($resolvedTenantId);

        return self::withoutGlobalScopes()->updateOrCreate(
            [
                'tenant_id' => $resolvedTenantId,
                'key'       => $key,
            ],
            [
                'value' => $value,
                'group' => $group,
                'type'  => $type,
            ]
        );
    }
}
