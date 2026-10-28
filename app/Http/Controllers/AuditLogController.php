<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $isOwnerConsole = function_exists('is_owner_console') && is_owner_console();
        $tenantId = function_exists('current_tenant_id') ? current_tenant_id() : auth()->user()?->tenant_id;

        $query = AuditLog::with(['user', 'tenant'])->latest();

        if ($isOwnerConsole) {
            if ($request->filled('tenant_id')) {
                if ($request->tenant_id === 'platform') {
                    $query->whereNull('tenant_id');
                } else {
                    $query->where('tenant_id', $request->tenant_id);
                }
            }

            if ($request->filled('scope')) {
                if ($request->scope === 'saas') {
                    $query->whereIn('action', [
                        'impersonate_start',
                        'impersonate_end',
                        'tenant_created',
                        'tenant_suspended',
                        'tenant_reactivated',
                        'login',
                        'logout',
                    ]);
                } elseif ($request->scope === 'operations') {
                    $query->whereIn('action', ['created', 'updated', 'deleted']);
                }
            }

            $users = User::orderBy('name')->get();
            $tenants = Tenant::orderBy('name')->get();

            $stats = [
                'total_events'       => AuditLog::count(),
                'today_events'       => AuditLog::whereDate('created_at', today())->count(),
                'support_sessions'   => AuditLog::where('action', 'impersonate_start')->count(),
                'logins_24h'         => AuditLog::where('action', 'login')->where('created_at', '>=', now()->subDay())->count(),
                'critical_events'    => AuditLog::whereIn('action', ['deleted', 'tenant_suspended'])->count(),
                'monitored_tenants'  => Tenant::count(),
            ];
        } else {
            $query->where('tenant_id', $tenantId);

            $users = User::where('tenant_id', $tenantId)->orderBy('name')->get();
            $tenants = collect();

            $baseTenantQuery = AuditLog::where('tenant_id', $tenantId);
            $stats = [
                'total_events'    => (clone $baseTenantQuery)->count(),
                'today_events'    => (clone $baseTenantQuery)->whereDate('created_at', today())->count(),
                'creations'       => (clone $baseTenantQuery)->where('action', 'created')->count(),
                'updates'         => (clone $baseTenantQuery)->where('action', 'updated')->count(),
                'deletions'       => (clone $baseTenantQuery)->where('action', 'deleted')->count(),
                'logins_24h'      => (clone $baseTenantQuery)->where('action', 'login')->where('created_at', '>=', now()->subDay())->count(),
            ];
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('auditable_type', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('new_values', 'like', "%{$search}%")
                    ->orWhere('old_values', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('audit_logs.index', compact('logs', 'users', 'tenants', 'stats', 'isOwnerConsole'));
    }

    public function show(AuditLog $auditLog)
    {
        $isOwnerConsole = function_exists('is_owner_console') && is_owner_console();
        $tenantId = function_exists('current_tenant_id') ? current_tenant_id() : auth()->user()?->tenant_id;

        if (!$isOwnerConsole && $auditLog->tenant_id !== $tenantId) {
            abort(403, 'Não tem permissão para visualizar registos de auditoria de outro restaurante.');
        }

        $auditLog->load(['user', 'tenant']);

        return view('audit_logs.show', compact('auditLog', 'isOwnerConsole'));
    }
}
