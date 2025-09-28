<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\UserActivity;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): ResponseInterface
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Acesso negado. Faça login primeiro.');
        }

        $user = Auth::user();
        $userRole = $user->role;

        // Log da tentativa de acesso
        $this->logAccessAttempt($request, $user, $roles);

        // Verificar se o usuário tem uma das roles necessárias
        if (!in_array($userRole, $roles)) {
            abort(403, 'Acesso negado. Permissões insuficientes.');
        }

        return $next($request);
    }

    private function logAccessAttempt($request, $user, $roles)
    {
        UserActivity::create([
            'user_id' => $user->id,
            'action' => 'access_attempt',
            'model_type' => 'route',
            'model_id' => null,
            'description' => "Tentativa de acesso à rota: {$request->route()->getName()}. Roles necessárias: " . implode(', ', $roles),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
