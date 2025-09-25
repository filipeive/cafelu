<?php
// ===== HELPER PARA VIEWS =====

class PermissionHelper
{
    /**
     * Verificar permissão na view
     */
    public static function can($permission): bool
    {
        if (!auth()->check()) return false;
        return PermissionMiddleware::hasPermission(auth()->user(), $permission);
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
}
