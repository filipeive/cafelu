<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');

        // Ensure some default groups exist if empty
        if ($settings->isEmpty()) {
            $this->seedDefaults();
            $settings = Setting::all()->groupBy('group');
        }

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token', '_method');

        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('settings', 'public');
                Setting::where('key', $key)->update(['value' => $path]);
            } else {
                Setting::where('key', $key)->update(['value' => $value]);
            }
        }

        return redirect()->route('settings.index')->with('success', 'Configurações atualizadas com sucesso.');
    }

    private function seedDefaults()
    {
        $defaults = [
            // General
            ['key' => 'company_name', 'value' => 'Café Lufamina', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_address', 'value' => 'Zalala Beach, Quelimane', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_phone', 'value' => '+258 84 000 0000', 'group' => 'general', 'type' => 'text'],
            ['key' => 'company_email', 'value' => 'contato@lufamina.com', 'group' => 'general', 'type' => 'text'],

            // Branding
            ['key' => 'system_logo', 'value' => null, 'group' => 'branding', 'type' => 'file'],
            ['key' => 'system_favicon', 'value' => null, 'group' => 'branding', 'type' => 'file'],
            ['key' => 'primary_color', 'value' => '#FFA500', 'group' => 'branding', 'type' => 'color'],
            ['key' => 'secondary_color', 'value' => '#000000', 'group' => 'branding', 'type' => 'color'],

            // Finance
            ['key' => 'currency_symbol', 'value' => 'MZN', 'group' => 'finance', 'type' => 'text'],
            ['key' => 'tax_rate', 'value' => '17', 'group' => 'finance', 'type' => 'number'],

            // Stock
            ['key' => 'low_stock_threshold', 'value' => '10', 'group' => 'stock', 'type' => 'number'],
        ];

        foreach ($defaults as $default) {
            Setting::updateOrCreate(['key' => $default['key']], $default);
        }
    }
}
