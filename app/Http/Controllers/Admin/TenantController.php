<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\LicenseKey;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Table;
use App\Models\Tenant;
use App\Models\User;
use App\Services\LicenseService;
use App\Services\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
                abort(403, 'Acesso restrito ao Dono da Plataforma (SuperAdmin).');
            }
            return $next($request);
        });
    }

    public function index(Request $request, TenantContext $context): View
    {
        // Bypass tenant scope to see all tenants and platform-wide metrics
        return $context->withoutTenant(function () use ($request) {
            $tenants = Tenant::query()
                ->with(['latestLicenseKey'])
                ->withCount(['users', 'tables', 'products', 'sales'])
                ->when($request->filled('search'), function ($query) use ($request) {
                    $search = $request->string('search');
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('nuit', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
                })
                ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
                ->when($request->filled('installation_mode'), fn ($q) => $q->where('installation_mode', $request->installation_mode))
                ->latest()
                ->paginate(12)
                ->withQueryString();

            // Estimated Monthly Recurring Revenue based on active plans
            $planMonthlyValues = [
                'trial' => 0,
                'monthly' => 1500,
                'quarterly' => 1350, // 4050/3
                'annual' => 1000,    // 12000/12
                'lifetime' => 0,
            ];

            $activeTenants = Tenant::where('status', 'active')->get();
            $mrr = 0;
            foreach ($activeTenants as $t) {
                $mrr += $planMonthlyValues[$t->plan] ?? 1000;
            }

            $stats = [
                'total'           => Tenant::count(),
                'active'          => Tenant::where('status', 'active')->count(),
                'trial'           => Tenant::where('status', 'trial')->count(),
                'suspended'       => Tenant::where('status', 'suspended')->count(),
                'offline'         => Tenant::where('installation_mode', 'offline')->count(),
                'expiring'        => Tenant::whereNotNull('license_expires_at')
                    ->whereBetween('license_expires_at', [now(), now()->addDays(30)])
                    ->count(),
                'total_users'     => User::count(),
                'total_tables'    => Table::count(),
                'total_products'  => Product::count(),
                'total_sales'     => Sale::count(),
                'total_revenue'   => (float) Sale::sum('total_amount'),
                'mrr'             => (float) $mrr,
                'arr'             => (float) ($mrr * 12),
            ];

            return view('admin.tenants.index', compact('tenants', 'stats'));
        });
    }

    public function store(Request $request, LicenseService $licenseService, TenantContext $context): RedirectResponse
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:150',
            'slug'              => 'nullable|string|max:80|unique:tenants,slug',
            'nuit'              => 'nullable|string|max:30',
            'email'             => 'nullable|email|max:150',
            'phone'             => 'nullable|string|max:30',
            'address'           => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:100',
            'plan'              => 'required|in:trial,monthly,quarterly,annual,lifetime',
            'installation_mode' => 'required|in:cloud,local_online,offline',
            'max_tables'        => 'required|integer|min:1|max:500',
            'max_users'         => 'required|integer|min:1|max:100',
            'duration_months'   => 'nullable|integer|in:1,3,6,12,24,36',

            // Admin account for the restaurant
            'admin_name'        => 'required|string|max:150',
            'admin_username'    => 'required|string|max:60|unique:users,username',
            'admin_email'       => 'required|email|max:150|unique:users,email',
            'admin_password'    => 'required|string|min:6',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        if (Tenant::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::lower(Str::random(4));
        }

        $durationMonths = (int) ($validated['duration_months'] ?? 12);
        if ($validated['plan'] === 'trial') {
            $expiresAt = now()->addDays(15);
            $status = 'trial';
            $trialEndsAt = $expiresAt;
        } elseif ($validated['plan'] === 'lifetime') {
            $expiresAt = now()->addYears(30);
            $status = 'active';
            $trialEndsAt = null;
        } else {
            $expiresAt = now()->addMonths($durationMonths);
            $status = 'active';
            $trialEndsAt = null;
        }

        $tenant = DB::transaction(function () use ($validated, $slug, $status, $expiresAt, $trialEndsAt, $licenseService) {
            // 1. Create Tenant
            $tenant = Tenant::create([
                'name'               => $validated['name'],
                'slug'               => $slug,
                'subdomain'          => $slug,
                'nuit'               => $validated['nuit'] ?? null,
                'email'              => $validated['email'] ?? null,
                'phone'              => $validated['phone'] ?? null,
                'address'            => $validated['address'] ?? null,
                'city'               => $validated['city'] ?? 'Quelimane',
                'currency'           => 'MZN',
                'status'             => $status,
                'installation_mode'  => $validated['installation_mode'],
                'plan'               => $validated['plan'],
                'max_tables'         => $validated['max_tables'],
                'max_users'          => $validated['max_users'],
                'trial_ends_at'      => $trialEndsAt,
                'license_expires_at' => $expiresAt,
            ]);

            // 2. Create Restaurant Administrator User
            User::create([
                'tenant_id' => $tenant->id,
                'name'      => $validated['admin_name'],
                'username'  => $validated['admin_username'],
                'email'     => $validated['admin_email'],
                'password'  => Hash::make($validated['admin_password']),
                'role'      => 'admin',
                'status'    => 'active',
            ]);

            // 3. Issue License Key directly
            $licenseKey = LicenseKey::create([
                'tenant_id'      => $tenant->id,
                'business_name'  => $tenant->name,
                'customer_name'  => $validated['admin_name'],
                'customer_email' => $validated['admin_email'],
                'customer_phone' => $validated['phone'] ?? null,
                'license_type'   => $validated['plan'],
                'status'         => 'active',
                'starts_at'      => now(),
                'expires_at'     => $expiresAt,
                'max_tables'     => $validated['max_tables'],
                'max_users'      => $validated['max_users'],
                'is_current'     => true,
                'notes'          => "Chave inicial emitida na criação do restaurante via Painel do Dono ({$validated['installation_mode']})",
            ]);

            return $tenant;
        });

        AuditLog::create([
            'tenant_id'      => $tenant->id,
            'user_id'        => auth()->id(),
            'action'         => 'tenant_created',
            'auditable_type' => Tenant::class,
            'auditable_id'   => $tenant->id,
            'new_values'     => [
                'name'              => $tenant->name,
                'slug'              => $tenant->slug,
                'plan'              => $tenant->plan,
                'installation_mode' => $tenant->installation_mode,
                'city'              => $tenant->city,
            ],
            'ip_address'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
        ]);

        return redirect()->route('admin.tenants.show', $tenant)
            ->with('success', "Restaurante '{$tenant->name}' registado com sucesso com licença {$tenant->plan}!");
    }

    public function show(Tenant $tenant, TenantContext $context): View
    {
        return $context->withoutTenant(function () use ($tenant) {
            $this->resolveOrCreateTenantLicense($tenant);
            $tenant->load(['users', 'latestLicenseKey', 'licenseKeys' => fn ($q) => $q->latest()]);
            
            $stats = [
                'users_count'    => $tenant->users()->count(),
                'tables_count'   => Table::where('tenant_id', $tenant->id)->count(),
                'products_count' => Product::where('tenant_id', $tenant->id)->count(),
                'sales_count'    => Sale::where('tenant_id', $tenant->id)->count(),
                'sales_total'    => (float) Sale::where('tenant_id', $tenant->id)->sum('total_amount'),
            ];

            return view('admin.tenants.show', compact('tenant', 'stats'));
        });
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:150',
            'nuit'              => 'nullable|string|max:30',
            'email'             => 'nullable|email|max:150',
            'phone'             => 'nullable|string|max:30',
            'address'           => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:100',
            'plan'              => 'required|in:trial,monthly,quarterly,annual,lifetime',
            'installation_mode' => 'required|in:cloud,local_online,offline',
            'max_tables'        => 'required|integer|min:1|max:500',
            'max_users'         => 'required|integer|min:1|max:100',
            'license_expires_at'=> 'nullable|date',
            'status'            => 'required|in:active,trial,suspended,cancelled',
        ]);

        $tenant->update($validated);

        return redirect()->route('admin.tenants.show', $tenant)
            ->with('success', "Dados do restaurante '{$tenant->name}' atualizados com sucesso!");
    }

    public function impersonate(Request $request, Tenant $tenant): RedirectResponse
    {
        session([
            'is_support_mode'    => true,
            'support_owner_id'   => auth()->id(),
            'current_tenant_id'  => $tenant->id,
            'current_tenant_name'=> $tenant->name,
        ]);

        AuditLog::create([
            'tenant_id'      => $tenant->id,
            'user_id'        => auth()->id(),
            'action'         => 'impersonate_start',
            'auditable_type' => Tenant::class,
            'auditable_id'   => $tenant->id,
            'new_values'     => [
                'tenant_name' => $tenant->name,
                'tenant_slug' => $tenant->slug,
                'mode'        => 'Modo Suporte Técnico',
            ],
            'ip_address'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', "A aceder ao ambiente do restaurante: {$tenant->name} (Modo Suporte Técnico)");
    }

    public function leaveImpersonate(Request $request): RedirectResponse
    {
        $tenantId = session('current_tenant_id');
        $tenantName = session('current_tenant_name');

        session()->forget(['is_support_mode', 'support_owner_id', 'current_tenant_id', 'current_tenant_name']);

        AuditLog::create([
            'tenant_id'      => $tenantId,
            'user_id'        => auth()->id(),
            'action'         => 'impersonate_end',
            'auditable_type' => Tenant::class,
            'auditable_id'   => $tenantId,
            'new_values'     => [
                'tenant_name' => $tenantName,
                'mode'        => 'Saída do Modo Suporte',
            ],
            'ip_address'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
        ]);

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Saiu do modo de suporte. Retornou ao Painel do Dono da Plataforma.');
    }

    public function suspend(Request $request, Tenant $tenant): RedirectResponse
    {
        $oldStatus = $tenant->status;
        $tenant->update(['status' => 'suspended']);

        AuditLog::create([
            'tenant_id'      => $tenant->id,
            'user_id'        => auth()->id(),
            'action'         => 'tenant_suspended',
            'auditable_type' => Tenant::class,
            'auditable_id'   => $tenant->id,
            'old_values'     => ['status' => $oldStatus],
            'new_values'     => ['status' => 'suspended', 'name' => $tenant->name],
            'ip_address'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
        ]);

        return back()->with('warning', "Restaurante '{$tenant->name}' suspenso.");
    }

    public function reactivate(Request $request, Tenant $tenant): RedirectResponse
    {
        $oldStatus = $tenant->status;
        $tenant->update(['status' => 'active']);

        AuditLog::create([
            'tenant_id'      => $tenant->id,
            'user_id'        => auth()->id(),
            'action'         => 'tenant_reactivated',
            'auditable_type' => Tenant::class,
            'auditable_id'   => $tenant->id,
            'old_values'     => ['status' => $oldStatus],
            'new_values'     => ['status' => 'active', 'name' => $tenant->name],
            'ip_address'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
        ]);

        return back()->with('success', "Restaurante '{$tenant->name}' reativado com sucesso.");
    }

    public function certificate(Tenant $tenant, TenantContext $context): View
    {
        return $context->withoutTenant(function () use ($tenant) {
            $license = $this->resolveOrCreateTenantLicense($tenant);

            return view('admin.tenants.certificate', compact('tenant', 'license'));
        });
    }

    public function certificatePdf(Tenant $tenant, TenantContext $context)
    {
        return $context->withoutTenant(function () use ($tenant) {
            $license = $this->resolveOrCreateTenantLicense($tenant);

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.tenants.certificate_pdf', compact('tenant', 'license'))
                ->setPaper('a4', 'portrait');

            $cleanTenant = Str::slug($tenant->name);
            $cleanKey = $license->key_code ?? ('LIC-' . $license->id);

            return $pdf->download("Certificado_Licenca_{$cleanTenant}_{$cleanKey}.pdf");
        });
    }

    /**
     * Garante que qualquer restaurante (incluindo o tenant inicial #1) tenha uma chave de licença associada.
     */
    protected function resolveOrCreateTenantLicense(Tenant $tenant): LicenseKey
    {
        $license = $tenant->latestLicenseKey ?? $tenant->licenseKeys()->latest()->first();

        if ($license) {
            return $license;
        }

        // Se existir uma licença órfã (sem tenant_id) do restaurante principal, vincula-a
        $orphanLicense = LicenseKey::whereNull('tenant_id')->latest()->first();
        if ($orphanLicense && $tenant->id === 1) {
            $orphanLicense->update([
                'tenant_id'     => $tenant->id,
                'business_name' => $tenant->name,
            ]);
            return $orphanLicense->fresh();
        }

        $adminUser = $tenant->users()->where('role', 'admin')->first();

        return LicenseKey::create([
            'tenant_id'      => $tenant->id,
            'business_name'  => $tenant->name,
            'customer_name'  => $adminUser?->name ?? $tenant->name,
            'customer_email' => $adminUser?->email ?? $tenant->email,
            'customer_phone' => $tenant->phone,
            'license_type'   => $tenant->plan ?? 'annual',
            'status'         => $tenant->status === 'suspended' ? 'suspended' : 'active',
            'starts_at'      => $tenant->created_at ?? now(),
            'expires_at'     => $tenant->license_expires_at ?? now()->addYear(),
            'max_tables'     => $tenant->max_tables ?? 30,
            'max_users'      => $tenant->max_users ?? 15,
            'is_current'     => true,
            'notes'          => 'Chave oficial associada automaticamente ao restaurante.',
        ]);
    }
}
