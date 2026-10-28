<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $tenantId = current_tenant_id() ?? auth()->user()?->tenant_id;

        // Ensure standard defaults exist if missing for this specific tenant
        foreach ($this->getDefaults() as $default) {
            Setting::withoutGlobalScopes()->firstOrCreate(
                [
                    'tenant_id' => $tenantId,
                    'key'       => $default['key'],
                ],
                array_merge($default, ['tenant_id' => $tenantId])
            );
        }

        $settings = Setting::where('tenant_id', $tenantId)->get()->groupBy('group');

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $tenantId = current_tenant_id() ?? auth()->user()?->tenant_id;
        $data = $request->except(['_token', '_method']);

        // Check if any file was requested to be removed
        foreach ($request->all() as $paramKey => $paramVal) {
            if (str_ends_with($paramKey, '_remove') && $paramVal == '1') {
                $actualKey = substr($paramKey, 0, -7);
                $setting = Setting::where('tenant_id', $tenantId)->where('key', $actualKey)->first();
                if ($setting && $setting->value) {
                    Storage::disk('public')->delete($setting->value);
                    $setting->update(['value' => null]);
                }
            }
        }

        // Handle file uploads
        foreach ($request->allFiles() as $key => $file) {
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('settings', 'public');
                Setting::set($key, $path, 'branding', 'file', $tenantId);
            }
        }

        // Handle regular fields
        foreach ($data as $key => $value) {
            if (str_ends_with($key, '_hex') || str_ends_with($key, '_remove') || $request->hasFile($key)) {
                continue;
            }

            $existing = Setting::where('tenant_id', $tenantId)->where('key', $key)->first();
            if ($existing && $existing->type === 'file') {
                continue;
            }

            Setting::set(
                $key,
                $value,
                $existing->group ?? 'general',
                $existing->type ?? 'text',
                $tenantId
            );
        }

        // Synchronize with tenant if present
        if ($tenant = current_tenant()) {
            $tenantUpdates = [];
            if (!empty($data['company_name'])) $tenantUpdates['name'] = trim($data['company_name']);
            if (!empty($data['company_phone'])) $tenantUpdates['phone'] = trim($data['company_phone']);
            if (!empty($data['company_email'])) $tenantUpdates['email'] = trim($data['company_email']);
            if (!empty($data['company_address'])) $tenantUpdates['address'] = trim($data['company_address']);
            if (!empty($data['company_city'])) $tenantUpdates['city'] = trim($data['company_city']);
            if (!empty($data['company_nuit'])) $tenantUpdates['nuit'] = trim($data['company_nuit']);
            if (!empty($data['currency_symbol'])) $tenantUpdates['currency'] = trim($data['currency_symbol']);
            if (!empty($tenantUpdates)) {
                $tenant->update($tenantUpdates);
            }
        }

        Setting::clearCache($tenantId);

        return redirect()->route('settings.index')->with('success', __('messages.settings_updated'));
    }

    public function updateName(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:100',
        ]);

        $tenantId = current_tenant_id() ?? auth()->user()?->tenant_id;
        $name = trim($request->input('company_name'));

        Setting::set('company_name', $name, 'general', 'text', $tenantId);

        if ($tenant = current_tenant()) {
            $tenant->update(['name' => $name]);
        }

        Setting::clearCache($tenantId);

        return redirect()->back()->with('success', __('messages.establishment_name_updated'));
    }

    public function updateColors(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|string|max:30',
            'secondary_color' => 'nullable|string|max:30',
        ]);

        $tenantId = current_tenant_id() ?? auth()->user()?->tenant_id;

        Setting::set('primary_color', $request->input('primary_color'), 'branding', 'color', $tenantId);

        if ($request->filled('secondary_color')) {
            Setting::set('secondary_color', $request->input('secondary_color'), 'branding', 'color', $tenantId);
        }

        Setting::clearCache($tenantId);

        return redirect()->back()->with('success', 'Cores do sistema atualizadas com sucesso!');
    }

    private function getDefaults(): array
    {
        $tenant = current_tenant();

        return [
            // General / Estabelecimento (preenchido a partir do Tenant atual ou Plataforma Global)
            ['key' => 'company_name', 'value' => $tenant?->name ?? 'RestPOS Cloud', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_subtitle', 'value' => $tenant ? 'Café, Bar & Restaurante' : 'Plataforma Multi-Restaurante SaaS', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_address', 'value' => $tenant?->address ?? '', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_city', 'value' => $tenant?->city ?? 'Maputo', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_phone', 'value' => $tenant?->phone ?? '', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_email', 'value' => $tenant?->email ?? '', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_nuit', 'value' => $tenant?->nuit ?? '', 'group' => 'general', 'type' => 'text'],

            // Branding
            ['key' => 'system_logo', 'value' => null, 'group' => 'branding', 'type' => 'file'],
            ['key' => 'system_favicon', 'value' => null, 'group' => 'branding', 'type' => 'file'],
            ['key' => 'login_background', 'value' => null, 'group' => 'branding', 'type' => 'file'],
            ['key' => 'login_backdrop_filter', 'value' => 'blur(6px) brightness(0.65)', 'group' => 'branding', 'type' => 'select'],
            ['key' => 'primary_color', 'value' => '#FFA500', 'group' => 'branding', 'type' => 'color'],
            ['key' => 'secondary_color', 'value' => '#000000', 'group' => 'branding', 'type' => 'color'],

            // Finance
            ['key' => 'currency_symbol', 'value' => $tenant?->currency ?? 'MZN', 'group' => 'finance', 'type' => 'text'],
            ['key' => 'tax_rate', 'value' => '16', 'group' => 'finance', 'type' => 'number'],

            // System
            ['key' => 'system_language', 'value' => 'pt', 'group' => 'system', 'type' => 'select'],
            ['key' => 'system_timezone', 'value' => 'Africa/Maputo', 'group' => 'system', 'type' => 'select'],
            ['key' => 'date_format', 'value' => 'd/m/Y', 'group' => 'system', 'type' => 'text'],
            ['key' => 'time_format', 'value' => 'H:i', 'group' => 'system', 'type' => 'text'],

            // Stock
            ['key' => 'low_stock_threshold', 'value' => '10', 'group' => 'stock', 'type' => 'number'],
        ];
    }
}
