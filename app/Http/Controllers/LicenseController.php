<?php

namespace App\Http\Controllers;

use App\Models\LicenseKey;
use App\Services\LicenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LicenseController extends Controller
{
    protected LicenseService $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Painel do Dono do Sistema / Super Admin para gerir todas as licenças.
     */
    public function adminIndex(Request $request): View
    {
        $query = LicenseKey::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('key_code', 'like', "%{$search}%");
            });
        }

        $licenses = $query->latest()->paginate(15)->withQueryString();

        // KPIs
        $totalLicenses = LicenseKey::count();
        $activeLicenses = LicenseKey::where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })->count();

        $expiringSoon = LicenseKey::where('status', 'active')
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays(30)])
            ->count();

        $expiredLicenses = LicenseKey::where(function ($q) {
            $q->where('status', 'expired')
              ->orWhere(function ($sub) {
                  $sub->whereNotNull('expires_at')
                      ->where('expires_at', '<=', now());
              });
        })->count();

        $fingerprint = $this->licenseService->getSystemFingerprint();
        $currentLicense = $this->licenseService->getCurrentLicense();

        return view('admin.licenses.index', compact(
            'licenses',
            'totalLicenses',
            'activeLicenses',
            'expiringSoon',
            'expiredLicenses',
            'fingerprint',
            'currentLicense'
        ));
    }

    /**
     * Emissão / Geração de uma nova licença.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name'  => 'required|string|max:255',
            'license_type'   => 'required|in:trial,monthly,quarterly,annual,lifetime',
            'customer_name'  => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'max_tables'     => 'required|integer|min:1|max:500',
            'max_users'      => 'required|integer|min:1|max:100',
            'notes'          => 'nullable|string|max:500',
        ]);

        $license = $this->licenseService->generateLicense(
            $validated['business_name'],
            $validated['license_type'],
            $validated['customer_name'] ?? null,
            $validated['customer_email'] ?? null,
            $validated['customer_phone'] ?? null,
            (int) $validated['max_tables'],
            (int) $validated['max_users'],
            $validated['notes'] ?? null
        );

        return redirect()->route('admin.licenses.index')
            ->with('success', 'Nova chave de licença gerada com sucesso: ' . $license->key_code);
    }

    /**
     * Estender validade de uma licença.
     */
    public function extend(Request $request, LicenseKey $license)
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:1|max:3650',
        ]);

        $this->licenseService->extendLicense($license, (int) $validated['days']);

        return back()->with('success', 'Validade da licença estendida em ' . $validated['days'] . ' dias.');
    }

    /**
     * Revogar ou suspender uma licença.
     */
    public function updateStatus(Request $request, LicenseKey $license)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,suspended,revoked',
        ]);

        $license->update(['status' => $validated['status']]);

        return back()->with('success', 'Estado da licença alterado para ' . $validated['status'] . '.');
    }

    /**
     * Página do Restaurante/Cliente para visualizar a licença ativa da instalação.
     */
    public function clientStatus(): View
    {
        $currentLicense = $this->licenseService->getCurrentLicense();
        $fingerprint = $this->licenseService->getSystemFingerprint();

        return view('license.status', compact('currentLicense', 'fingerprint'));
    }

    /**
     * Ativação de serial key pela interface do restaurante.
     */
    public function activate(Request $request)
    {
        $validated = $request->validate([
            'key_code' => 'required|string|max:64',
        ]);

        $result = $this->licenseService->activateKey($validated['key_code']);

        if (!$result['success']) {
            if ($request->wantsJson()) {
                return response()->json($result, 422);
            }
            return back()->with('error', $result['message']);
        }

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        return back()->with('success', $result['message']);
    }

    /**
     * Disparo manual de sincronização com a nuvem pelo cliente.
     */
    public function syncNow(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('restpos:sync-push');
            return back()->with('success', 'Sincronização com o servidor central na nuvem concluída com sucesso!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Falha na comunicação com a nuvem: ' . $e->getMessage());
        }
    }
}

