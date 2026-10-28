<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\WebsiteSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * CMS for the public website / online ordering page (welcome).
 * Texts are stored as Settings (group "website"); banner slides in website_slides.
 */
class WebsiteController extends Controller
{
    /** Editable text fields: key => [label, type, default] */
    public static function contentFields(): array
    {
        return [
            'site_hero_badge'        => ['Etiqueta do banner', 'text', 'Cardápio Digital & Pedidos Online'],
            'site_hero_title'        => ['Título principal (sem slides)', 'text', 'Sabores que contam histórias'],
            'site_hero_subtitle'     => ['Subtítulo principal (sem slides)', 'textarea', 'Explore o nosso menu, faça o seu pedido online e receba na mesa ou leve consigo.'],
            'site_announcement'      => ['Aviso no topo (opcional)', 'text', ''],
            'site_about_title'       => ['Título "Sobre Nós"', 'text', 'Bem-vindo à nossa casa'],
            'site_about_text'        => ['Texto "Sobre Nós"', 'textarea', 'Somos um espaço acolhedor onde a boa comida e o bom atendimento se encontram. Cada prato é preparado na hora, com ingredientes frescos e selecionados.'],
            'site_opening_hours'     => ['Horário de funcionamento (uma linha por dia)', 'textarea', "Segunda a Sexta: 07:00 - 22:00\nSábado: 08:00 - 23:00\nDomingo: 08:00 - 21:00"],
            'site_whatsapp'          => ['WhatsApp (ex: 258841234567)', 'text', ''],
            'site_instagram'         => ['Instagram (URL)', 'text', ''],
            'site_facebook'          => ['Facebook (URL)', 'text', ''],
            'site_map_embed'         => ['Google Maps (URL de incorporação)', 'text', ''],
            'site_latitude'          => ['Latitude do Restaurante (GPS)', 'text', ''],
            'site_longitude'         => ['Longitude do Restaurante (GPS)', 'text', ''],
            'site_geofence_radius'   => ['Raio Presencial em Metros (ex: 150)', 'text', '150'],
            'site_online_orders'     => ['Permitir pedidos online', 'boolean', '1'],
        ];
    }

    public function index()
    {
        $slides = WebsiteSlide::orderBy('sort_order')->orderBy('id')->get();
        $fields = self::contentFields();
        $values = [];
        foreach ($fields as $key => [$label, $type, $default]) {
            $values[$key] = Setting::get($key, $default);
        }
        $aboutImage = Setting::get('site_about_image');

        return view('website.index', compact('slides', 'fields', 'values', 'aboutImage'));
    }

    public function updateContent(Request $request)
    {
        $request->validate([
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        foreach (self::contentFields() as $key => [$label, $type, $default]) {
            $value = $type === 'boolean'
                ? ($request->boolean($key) ? '1' : '0')
                : (string) $request->input($key, '');
            Setting::set($key, $value, 'website', $type === 'textarea' ? 'textarea' : 'text');
        }

        if ($request->boolean('about_image_remove')) {
            $old = Setting::get('site_about_image');
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            Setting::set('site_about_image', null, 'website', 'file');
        }

        if ($request->hasFile('about_image')) {
            $old = Setting::get('site_about_image');
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('about_image')->store('website', 'public');
            Setting::set('site_about_image', $path, 'website', 'file');
        }

        Setting::clearCache();

        return redirect()->route('website.index', ['tab' => 'content'])->with('success', 'Conteúdo da página atualizado com sucesso.');
    }

    public function storeSlide(Request $request)
    {
        $data = $request->validate([
            'title'       => 'nullable|string|max:150',
            'subtitle'    => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data['image_path'] = $request->file('image')->store('website/slides', 'public');
        $data['sort_order'] = (int) WebsiteSlide::max('sort_order') + 1;
        $data['is_active'] = true;
        unset($data['image']);

        WebsiteSlide::create($data);

        return redirect()->route('website.index')->with('success', 'Slide adicionado com sucesso.');
    }

    public function updateSlide(Request $request, WebsiteSlide $slide)
    {
        $data = $request->validate([
            'title'       => 'nullable|string|max:150',
            'subtitle'    => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($slide->image_path);
            $data['image_path'] = $request->file('image')->store('website/slides', 'public');
        }
        unset($data['image']);
        $data['is_active'] = $request->boolean('is_active');

        $slide->update($data);

        return redirect()->route('website.index')->with('success', 'Slide atualizado com sucesso.');
    }

    public function moveSlide(WebsiteSlide $slide, string $direction)
    {
        $slides = WebsiteSlide::orderBy('sort_order')->orderBy('id')->get()->values();
        $index = $slides->search(fn($s) => $s->id === $slide->id);
        $swap = $direction === 'up' ? $index - 1 : $index + 1;

        if ($index !== false && isset($slides[$swap])) {
            $ordered = $slides->all();
            [$ordered[$index], $ordered[$swap]] = [$ordered[$swap], $ordered[$index]];
            foreach ($ordered as $i => $s) {
                $s->update(['sort_order' => $i]);
            }
        }

        return redirect()->route('website.index');
    }

    public function destroySlide(WebsiteSlide $slide)
    {
        Storage::disk('public')->delete($slide->image_path);
        $slide->delete();

        return redirect()->route('website.index')->with('success', 'Slide removido.');
    }
}

