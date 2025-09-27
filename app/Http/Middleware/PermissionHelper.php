<?php
namespace App\Http\Middleware;

use App\Services\PermissionCacheService;

class PermissionHelper
{
    /**
     * Verificar permissão na view com cache
     */
    public static function can($permission): bool
    {
        if (!auth()->check()) return false;
        return PermissionCacheService::hasPermission(auth()->user(), $permission);
    }

    /**
     * Verificar múltiplas permissões (OR)
     */
    public static function canAny(array $permissions): bool
    {
        if (!auth()->check()) return false;
        
        foreach ($permissions as $permission) {
            if (self::can($permission)) return true;
        }
        
        return false;
    }

    /**
     * Verificar se tem todas as permissões (AND)
     */
    public static function canAll(array $permissions): bool
    {
        if (!auth()->check()) return false;
        
        foreach ($permissions as $permission) {
            if (!self::can($permission)) return false;
        }
        
        return true;
    }

    /**
     * Verificar acesso a módulo
     */
    public static function canAccessModule(string $module): bool
    {
        if (!auth()->check()) return false;
        return PermissionMiddleware::canAccessModule(auth()->user(), $module);
    }

    /**
     * Obter todas as permissões do usuário atual
     */
    public static function getAllPermissions(): array
    {
        if (!auth()->check()) return [];
        
        return PermissionCacheService::getUserPermissions(
            auth()->user()->id, 
            auth()->user()->role
        );
    }

    /**
     * Verificar se é admin
     */
    public static function isAdmin(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Verificar se é manager ou admin
     */
    public static function isManagerOrAdmin(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'manager']);
    }

    /**
     * Obter nível de acesso (numérico para comparações)
     */
    public static function getAccessLevel(): int
    {
        if (!auth()->check()) return 0;

        $levels = [
            'cooker' => 1,
            'waiter' => 2,
            'cashier' => 3,
            'manager' => 4,
            'admin' => 5,
            'staff' => 0
        ];

        return $levels[auth()->user()->role] ?? 0;
    }

    /**
     * Verificar se tem nível de acesso mínimo
     */
    public static function hasMinimumLevel(int $minimumLevel): bool
    {
        return self::getAccessLevel() >= $minimumLevel;
    }
}
