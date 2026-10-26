<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Ensure standard defaults exist if missing
        foreach ($this->getDefaults() as $default) {
            Setting::firstOrCreate(['key' => $default['key']], $default);
        }

        $settings = Setting::all()->groupBy('group');

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        // Check if any file was requested to be removed
        foreach ($request->all() as $paramKey => $paramVal) {
            if (str_ends_with($paramKey, '_remove') && $paramVal == '1') {
                $actualKey = substr($paramKey, 0, -7);
                $setting = Setting::where('key', $actualKey)->first();
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
                Setting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $path,
                        'group' => 'branding',
                        'type' => 'file'
                    ]
                );
            }
        }

        // Handle regular fields
        foreach ($data as $key => $value) {
            // Ignore helper fields and files
            if (str_ends_with($key, '_hex') || str_ends_with($key, '_remove') || $request->hasFile($key)) {
                continue;
            }

            // If it's a known file setting and not uploaded, do not overwrite with null
            $existing = Setting::where('key', $key)->first();
            if ($existing && $existing->type === 'file') {
                continue;
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
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

        Cache::forget('app_settings_all');

        return redirect()->route('settings.index')->with('success', __('messages.settings_updated'));
    }

    public function updateName(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:100',
        ]);

        $name = trim($request->input('company_name'));

        Setting::updateOrCreate(
            ['key' => 'company_name'],
            [
                'value' => $name,
                'group' => 'general',
                'type' => 'text',
            ]
        );

        if ($tenant = current_tenant()) {
            $tenant->update(['name' => $name]);
        }

        Cache::forget('app_settings_all');

        return redirect()->back()->with('success', __('messages.establishment_name_updated'));
    }

    public function updateColors(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|string|max:30',
            'secondary_color' => 'nullable|string|max:30',
        ]);

        Setting::updateOrCreate(
            ['key' => 'primary_color'],
            [
                'value' => $request->input('primary_color'),
                'group' => 'branding',
                'type' => 'color',
            ]
        );

        if ($request->filled('secondary_color')) {
            Setting::updateOrCreate(
                ['key' => 'secondary_color'],
                [
                    'value' => $request->input('secondary_color'),
                    'group' => 'branding',
                    'type' => 'color',
                ]
            );
        }

        Cache::forget('app_settings_all');

        return redirect()->back()->with('success', 'Cores do sistema atualizadas com sucesso!');
    }

    private function getDefaults(): array
    {
        return [
            // General / Estabelecimento
            ['key' => 'company_name', 'value' => 'Zalala Beach Bar', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_subtitle', 'value' => 'Bar & Restaurante', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_address', 'value' => 'Bairro de Zalala, ER470', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_city', 'value' => 'Quelimane, Zambézia', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_phone', 'value' => '(+258) 846 885 214', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_email', 'value' => 'zalalabeachbar@gmail.com', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_nuit', 'value' => '110735901', 'group' => 'general', 'type' => 'text'],

            // Branding
            ['key' => 'system_logo', 'value' => null, 'group' => 'branding', 'type' => 'file'],
            ['key' => 'system_favicon', 'value' => null, 'group' => 'branding', 'type' => 'file'],
            ['key' => 'login_background', 'value' => null, 'group' => 'branding', 'type' => 'file'],
            ['key' => 'login_backdrop_filter', 'value' => 'blur(6px) brightness(0.65)', 'group' => 'branding', 'type' => 'select'],
            ['key' => 'primary_color', 'value' => '#FFA500', 'group' => 'branding', 'type' => 'color'],
            ['key' => 'secondary_color', 'value' => '#000000', 'group' => 'branding', 'type' => 'color'],

            // Finance
            ['key' => 'currency_symbol', 'value' => 'MZN', 'group' => 'finance', 'type' => 'text'],
            ['key' => 'tax_rate', 'value' => '17', 'group' => 'finance', 'type' => 'number'],

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
