<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    public static function allCached()
    {
        return \Illuminate\Support\Facades\Cache::remember('app_settings_all', 3600, function () {
            try {
                return self::pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    public static function get($key, $default = null)
    {
        $settings = self::allCached();
        return $settings[$key] ?? $default;
    }

    public static function set($key, $value, $group = 'general', $type = 'text')
    {
        \Illuminate\Support\Facades\Cache::forget('app_settings_all');
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );
    }
}
