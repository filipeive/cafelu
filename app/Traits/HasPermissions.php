<?php
namespace App\Traits;

use App\Models\UserActivity;
use Illuminate\Support\Facades\Request;

trait HasPermissions
{
    /**
     * Verificar permissão no controller
     */
    protected function checkPermission(string $permission)
    {
        if (!auth()->check()) {
            abort(401, 'Não autenticado');
        }

        if (!PermissionMiddleware::hasPermission(auth()->user(), $permission)) {
            abort(403, 'Permissão insuficiente: ' . $permission);
        }
    }

    /**
     * Verificar múltiplas permissões (OR)
     */
    protected function checkAnyPermission(array $permissions)
    {
        if (!auth()->check()) {
            abort(401, 'Não autenticado');
        }

        $hasAnyPermission = false;
        foreach ($permissions as $permission) {
            if (PermissionMiddleware::hasPermission(auth()->user(), $permission)) {
                $hasAnyPermission = true;
                break;
            }
        }

        if (!$hasAnyPermission) {
            abort(403, 'Permissões insuficientes: ' . implode(', ', $permissions));
        }
    }

    /**
     * Middleware dinâmico baseado em ação
     */
    public function __construct()
    {
        // Mapear ações para permissões
        $actionPermissions = [
            'index' => $this->getViewPermission(),
            'show' => $this->getViewPermission(),
            'create' => $this->getCreatePermission(),
            'store' => $this->getCreatePermission(),
            'edit' => $this->getEditPermission(),
            'update' => $this->getEditPermission(),
            'destroy' => $this->getDeletePermission(),
        ];

        foreach ($actionPermissions as $action => $permission) {
            if ($permission) {
                $this->middleware("permission:$permission")->only($action);
            }
        }
    }

    // Métodos a serem implementados nos controllers filhos
    protected function getViewPermission(): ?string { return null; }
    protected function getCreatePermission(): ?string { return null; }
    protected function getEditPermission(): ?string { return null; }
    protected function getDeletePermission(): ?string { return null; }
}