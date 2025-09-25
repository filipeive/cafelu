<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\UserActivity;
use App\Models\User;

class PermissionMiddleware
{
    /**
     * Mapeamento de permissões por role
     */
    private const ROLE_PERMISSIONS = [
        'admin' => [
            // Produtos
            'view_products', 'create_products', 'edit_products', 'delete_products', 'manage_stock',
            // Vendas
            'view_sales', 'create_sales', 'edit_sales', 'delete_sales', 'manage_payments',
            // Usuários
            'view_users', 'create_users', 'edit_users', 'delete_users', 'manage_roles',
            // Relatórios
            'view_reports', 'export_reports', 'financial_reports', 'advanced_analytics',
            // Sistema
            'system_settings', 'backup_restore', 'audit_logs', 'manage_categories',
            // Despesas
            'view_expenses', 'create_expenses', 'edit_expenses', 'delete_expenses',
            // Estoque
            'view_stock_movements', 'create_stock_movements', 'edit_stock_movements',
            // Pedidos
            'view_orders', 'create_orders', 'edit_orders', 'cancel_orders', 'manage_kitchen',
            // Clientes
            'view_clients', 'create_clients', 'edit_clients', 'delete_clients',
            // Funcionários
            'view_employees', 'create_employees', 'edit_employees', 'delete_employees',
        ],
        'manager' => [
            // Produtos (sem deletar)
            'view_products', 'create_products', 'edit_products', 'manage_stock',
            // Vendas
            'view_sales', 'create_sales', 'edit_sales', 'manage_payments',
            // Relatórios (limitados)
            'view_reports', 'export_reports', 'financial_reports',
            // Despesas
            'view_expenses', 'create_expenses', 'edit_expenses',
            // Estoque
            'view_stock_movements', 'create_stock_movements',
            // Pedidos
            'view_orders', 'create_orders', 'edit_orders', 'cancel_orders', 'manage_kitchen',
            // Clientes
            'view_clients', 'create_clients', 'edit_clients',
            // Funcionários (apenas visualizar)
            'view_employees',
            // Categorias
            'manage_categories',
        ],
        'cashier' => [
            // Produtos (apenas visualizar)
            'view_products',
            // Vendas (operações básicas)
            'view_sales', 'create_sales',
            // Pedidos (básico)
            'view_orders', 'create_orders', 'edit_orders',
            // Clientes (básico)
            'view_clients', 'create_clients',
            // Relatórios (próprias vendas)
            'view_reports',
        ],
        'waiter' => [
            // Produtos (apenas visualizar)
            'view_products',
            // Pedidos (operações principais)
            'view_orders', 'create_orders', 'edit_orders',
            // Clientes (básico)
            'view_clients', 'create_clients',
            // Mesas
            'manage_tables',
        ],
        'cook' => [
            // Produtos (visualizar para cozinha)
            'view_products',
            // Pedidos (cozinha)
            'view_orders', 'manage_kitchen',
            // Estoque (visualizar)
            'view_stock_movements',
        ]
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$permissions): ResponseInterface
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Acesso negado. Faça login primeiro.');
        }

        $user = Auth::user();
        $userRole = $user->role;

        // Log da verificação de permissão
        $this->logPermissionCheck($request, $user, $permissions);

        // Obter permissões da role do usuário
        $userPermissions = self::ROLE_PERMISSIONS[$userRole] ?? [];

        // Verificar se tem pelo menos uma das permissões necessárias
        $hasPermission = !empty(array_intersect($permissions, $userPermissions));

        if (!$hasPermission) {
            // Log da negação de acesso
            $this->logAccessDenied($request, $user, $permissions);
            
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Permissões insuficientes.',
                    'required_permissions' => $permissions,
                    'user_permissions' => $userPermissions
                ], 403);
            }

            abort(403, 'Acesso negado. Você não tem permissão para realizar esta ação.');
        }

        return $next($request);
    }

    private function logPermissionCheck($request, $user, $permissions)
    {
        UserActivity::create([
            'user_id' => $user->id,
            'action' => 'permission_check',
            'model_type' => 'permission',
            'model_id' => null,
            'description' => "Verificação de permissões: " . implode(', ', $permissions) . " para rota: {$request->route()->getName()}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    private function logAccessDenied($request, $user, $permissions)
    {
        UserActivity::create([
            'user_id' => $user->id,
            'action' => 'access_denied',
            'model_type' => 'permission',
            'model_id' => null,
            'description' => "Acesso negado. Permissões necessárias: " . implode(', ', $permissions) . " para rota: {$request->route()->getName()}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * Verificar se usuário tem permissão específica
     */
    public static function hasPermission($user, string $permission): bool
    {
        if (!$user) return false;
        
        $userPermissions = self::ROLE_PERMISSIONS[$user->role] ?? [];
        return in_array($permission, $userPermissions);
    }

    /**
     * Obter todas as permissões de uma role
     */
    public static function getRolePermissions(string $role): array
    {
        return self::ROLE_PERMISSIONS[$role] ?? [];
    }

    /**
     * Verificar se usuário tem acesso a um módulo
     */
    public static function canAccessModule($user, string $module): bool
    {
        $modulePermissions = [
            'products' => ['view_products'],
            'sales' => ['view_sales'],
            'orders' => ['view_orders'],
            'reports' => ['view_reports'],
            'users' => ['view_users'],
            'expenses' => ['view_expenses'],
            'stock' => ['view_stock_movements'],
            'clients' => ['view_clients'],
            'employees' => ['view_employees'],
        ];

        if (!isset($modulePermissions[$module])) return false;

        $requiredPermissions = $modulePermissions[$module];
        $userPermissions = self::getRolePermissions($user->role);

        return !empty(array_intersect($requiredPermissions, $userPermissions));
    }
}
