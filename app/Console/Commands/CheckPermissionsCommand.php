<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Http\Middleware\PermissionMiddleware;

class CheckPermissionsCommand extends Command
{
    protected $signature = 'permissions:check {--user=} {--role=}';
    protected $description = 'Verificar permissões de usuários e roles';

    public function handle()
    {
        if ($userId = $this->option('user')) {
            $this->checkUserPermissions($userId);
        } elseif ($role = $this->option('role')) {
            $this->checkRolePermissions($role);
        } else {
            $this->showAllRolesPermissions();
        }

        return 0;
    }

    private function checkUserPermissions($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            $this->error("Usuário {$userId} não encontrado.");
            return;
        }

        $this->info("Permissões do usuário: {$user->name} (Role: {$user->role})");
        $permissions = PermissionMiddleware::getRolePermissions($user->role);

        foreach ($permissions as $permission) {
            $this->line("✓ {$permission}");
        }
    }

    private function checkRolePermissions($role)
    {
        $permissions = PermissionMiddleware::getRolePermissions($role);

        if (empty($permissions)) {
            $this->error("Role '{$role}' não existe ou não tem permissões.");
            return;
        }

        $this->info("Permissões da role: {$role}");
        foreach ($permissions as $permission) {
            $this->line("✓ {$permission}");
        }
    }

    private function showAllRolesPermissions()
    {
        $roles = ['admin', 'manager', 'cashier', 'waiter', 'cook'];

        foreach ($roles as $role) {
            $this->info("\n=== ROLE: {$role} ===");
            $permissions = PermissionMiddleware::getRolePermissions($role);
            
            foreach ($permissions as $permission) {
                $this->line("  ✓ {$permission}");
            }
        }
    }
}
