<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $context = app(TenantContext::class);
        $tenant = null;

        // 1. Support Mode / Impersonation by SuperAdmin
        if (session('is_support_mode') && session()->has('current_tenant_id')) {
            $tenant = Tenant::find(session('current_tenant_id'));
        }

        // 2. Resolve from Authenticated User
        if (!$tenant && auth()->check()) {
            $user = auth()->user();
            if ($user->tenant_id) {
                $tenant = Tenant::find($user->tenant_id);
            }
        }

        // 3. Resolve from Request Headers (API / POS Terminal Sync)
        if (!$tenant && $request->hasHeader('X-Tenant-ID')) {
            $tenant = Tenant::find($request->header('X-Tenant-ID'));
        } elseif (!$tenant && $request->hasHeader('X-Tenant-Slug')) {
            $tenant = Tenant::where('slug', $request->header('X-Tenant-Slug'))->first();
        }

        // 4. Resolve from Subdomain (e.g. cafe.restpos.co.mz)
        if (!$tenant) {
            $host = $request->getHost();
            $parts = explode('.', $host);
            if (count($parts) >= 3 && !in_array($parts[0], ['www', 'app', 'admin', 'pos'])) {
                $tenant = Tenant::where('subdomain', $parts[0])
                    ->orWhere('slug', $parts[0])
                    ->first();
            }
        }

        // 5. Default Fallback: First active tenant ONLY for public menu routes or non-SuperAdmin users
        // SuperAdmin in Normal Mode (Owner Console) must NEVER fall back to a client restaurant tenant!
        $isSuperAdminNormalMode = auth()->check() && auth()->user()->isSuperAdmin() && !session('is_support_mode');
        $isPublicMenuRoute = $request->routeIs('welcome*', 'self-service.*', 'lang.*') || $request->is('/', 'm/*', 'mesa/*', 'self-service/*', 'call-waiter', 'order-status/*');

        if (!$tenant && (!$isSuperAdminNormalMode || $isPublicMenuRoute)) {
            $tenant = Tenant::where('status', 'active')->first() ?? Tenant::first();
        }

        if ($tenant) {
            $context->setTenant($tenant);

            // Share current tenant to all Blade views automatically
            view()->share('currentTenant', $tenant);
        }

        return $next($request);
    }
}

