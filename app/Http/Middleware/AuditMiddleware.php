<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserActivity;
use Symfony\Component\HttpFoundation\Response;

class AuditMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log apenas para ações importantes
        if ($this->shouldLog($request)) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    private function shouldLog($request): bool
    {
        // Não logar GETs simples, apenas ações que modificam dados
        if ($request->isMethod('GET')) {
            return false;
        }

        // Não logar rotas de API internas
        if ($request->is('api/*')) {
            return false;
        }

        return auth()->check();
    }

    private function logActivity($request, $response)
    {
        UserActivity::create([
            'user_id' => auth()->id(),
            'action' => $request->method(),
            'model_type' => 'HTTP_REQUEST',
            'model_id' => null,
            'description' => "{$request->method()} {$request->path()}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'extra_data' => json_encode([
                'route_name' => $request->route()?->getName(),
                'status_code' => $response->getStatusCode(),
                'request_data' => $request->except(['password', 'password_confirmation', '_token']),
            ]),
        ]);
    }
}