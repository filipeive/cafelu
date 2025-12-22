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
            if ($userRole === strtolower(trim($role))) {
                return $next($request);
            }
        }

        if ($userRole === 'customer') {
            return redirect()->route('customer.dashboard')->with('error', 'Você não tem permissão para acessar esta área administrativa.');
        }

        return redirect('/')->with('error', 'Você não tem permissão para acessar esta área.');
    }
}
