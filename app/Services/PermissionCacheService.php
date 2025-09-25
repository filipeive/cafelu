<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Http\Middleware\PermissionMiddleware;

class PermissionCacheService
{
    private const CACHE_PREFIX = 'permissions:';
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Obter permissões do usuário com cache
     */
    public static function getUserPermissions($userId, $role): array
    {
        $cacheKey = self::CACHE_PREFIX . "user:{$userId}:role:{$role}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($role) {
            return PermissionMiddleware::getRolePermissions($role);
        });
    }

    /**
     * Verificar permissão com cache
     */
    public static function hasPermission($user, $permission): bool
    {
        if (!$user) return false;

        $permissions = self::getUserPermissions($user->id, $user->role);
        return in_array($permission, $permissions);
    }

    /**
     * Limpar cache de permissões do usuário
     */
    public static function clearUserCache($userId, $role = null)
    {
        if ($role) {
            $cacheKey = self::CACHE_PREFIX . "user:{$userId}:role:{$role}";
            Cache::forget($cacheKey);
        } else {
            // Limpar todas as variações de role
            $roles = ['admin', 'manager', 'cashier', 'waiter', 'cook'];
            foreach ($roles as $roleItem) {
                $cacheKey = self::CACHE_PREFIX . "user:{$userId}:role:{$roleItem}";
                Cache::forget($cacheKey);
            }
        }
    }

    /**
     * Limpar todo cache de permissões
     */
    public static function clearAllCache()
    {
        Cache::flush(); // Em produção, usar tags de cache para maior precisão
    }
}
