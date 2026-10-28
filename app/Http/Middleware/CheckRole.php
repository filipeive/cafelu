<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $userRole = strtolower(trim(Auth::user()->role));

        // If no roles specified, just check if authenticated
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user has any of the required roles
        foreach ($roles as $role) {
            $expected = strtolower(trim($role));
            if ($userRole === $expected) {
                return $next($request);
            }
            // Superadmin access to operational restaurant routes (ZBIZ Pattern):
            // In Normal Mode (Owner Console), SuperAdmin only accesses SaaS & Global Admin routes (users, audit_logs).
            // Operational restaurant routes (POS, Products, Tables, Orders, Sales, etc.) require Support Mode!
            if ($userRole === 'superadmin' && in_array($expected, ['admin', 'manager', 'waiter', 'cashier'])) {
                $isGlobalAdminRoute = $request->routeIs('users.*', 'audit_logs.*', 'admin.*', 'profile.*', 'notifications.*');
                if (!session('is_support_mode') && !$isGlobalAdminRoute) {
                    return redirect()->route('admin.tenants.index')
                        ->with('warning', 'Isolamento SaaS Ativo: Para aceder ou gerir os módulos operacionais de um restaurante (Produtos, POS, Mesas, Pedidos, Faturação), clique em "Entrar (Modo Suporte)" no restaurante desejado.');
                }
                return $next($request);
            }
        }

        if ($userRole === 'customer') {
            return redirect()->route('customer.dashboard')->with('error', 'Você não tem permissão para acessar esta área administrativa.');
        }

        return redirect('/')->with('error', 'Você não tem permissão para acessar esta área.');
    }
}
