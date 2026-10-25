@extends('layouts.app')

@section('content')
@php
    $primaryColor = \App\Models\Setting::get('primary_color', '#F97316');
    $secondaryColor = \App\Models\Setting::get('secondary_color', '#10B981');
    $companyName = \App\Models\Setting::get('company_name', 'Zalala Beach Bar');

    $groupIcons = [
        'general' => 'mdi-storefront-outline',
        'branding' => 'mdi-palette-outline',
        'finance' => 'mdi-currency-usd',
        'stock' => 'mdi-package-variant-closed',
        'system' => 'mdi-cog-outline',
    ];

    $settingIcons = [
        'company_name' => 'mdi-store-outline',
        'company_subtitle' => 'mdi-format-title',
        'company_address' => 'mdi-map-marker-outline',
        'company_city' => 'mdi-city-variant-outline',
        'company_phone' => 'mdi-phone-outline',
        'company_email' => 'mdi-email-outline',
        'company_nuit' => 'mdi-card-account-details-outline',
        'system_logo' => 'mdi-image-outline',
        'system_favicon' => 'mdi-image-filter-vintage',
        'login_background' => 'mdi-image-filter-hdr',
        'login_backdrop_filter' => 'mdi-blur',
        'primary_color' => 'mdi-format-color-fill',
        'secondary_color' => 'mdi-palette',
        'currency_symbol' => 'mdi-cash',
        'tax_rate' => 'mdi-percent-outline',
        'low_stock_threshold' => 'mdi-alert-outline',
        'system_language' => 'mdi-translate',
        'system_timezone' => 'mdi-clock-time-four-outline',
        'date_format' => 'mdi-calendar-range',
        'time_format' => 'mdi-clock-outline',
    ];
@endphp

<div class="w-full space-y-6" x-data="settingsManager({
    primary: '{{ $primaryColor }}',
    secondary: '{{ $secondaryColor }}',
    activeTab: 'general'
})">

    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 border border-gray-100 dark:border-gray-700/80 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300 text-xs font-bold uppercase tracking-wider mb-2">
                <i class="mdi mdi-cog-outline text-sm"></i>
                Painel Administrativo
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                {{ __('messages.system_settings_title') }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ __('messages.system_settings_desc') }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-2xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-semibold">
                <i class="mdi mdi-shield-check-outline text-emerald-500 text-sm"></i>
                Modo Administrador
            </span>
        </div>
    </div>

    <!-- Quick Highlights Grid (Nome do Estabelecimento + Cores do Sistema) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Card 1: Nome do Estabelecimento -->
        <div class="bg-gradient-to-br from-orange-500/10 via-amber-500/5 to-transparent dark:from-orange-500/20 dark:via-gray-800 dark:to-gray-800 rounded-3xl p-6 md:p-7 border border-orange-200/80 dark:border-orange-500/30 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-3 mb-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300 text-xs font-bold uppercase tracking-wider">
                        <i class="mdi mdi-storefront-outline text-sm"></i>
                        {{ __('messages.establishment_name') }}
                    </span>
                    <span class="text-xs text-gray-400 font-medium">Identidade Principal</span>
                </div>

                <h2 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white">
                    {{ $companyName }}
                </h2>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1.5 leading-relaxed">
                    {{ __('messages.establishment_name_desc') }}
                </p>

                <!-- Pré-visualização onde o nome aparece (100% ícones MDI, zero emojis) -->
                <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white dark:bg-gray-700/80 rounded-xl border border-gray-200/80 dark:border-gray-600 text-gray-700 dark:text-gray-200 shadow-2xs font-medium">
                        <i class="mdi mdi-page-layout-header text-orange-500"></i>
                        <span>{{ __('messages.preview_navbar') }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white dark:bg-gray-700/80 rounded-xl border border-gray-200/80 dark:border-gray-600 text-gray-700 dark:text-gray-200 shadow-2xs font-medium">
                        <i class="mdi mdi-lock-outline text-blue-500"></i>
                        <span>{{ __('messages.preview_login') }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white dark:bg-gray-700/80 rounded-xl border border-gray-200/80 dark:border-gray-600 text-gray-700 dark:text-gray-200 shadow-2xs font-medium">
                        <i class="mdi mdi-receipt-text-outline text-emerald-500"></i>
                        <span>{{ __('messages.preview_receipts') }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white dark:bg-gray-700/80 rounded-xl border border-gray-200/80 dark:border-gray-600 text-gray-700 dark:text-gray-200 shadow-2xs font-medium">
                        <i class="mdi mdi-silverware-fork-knife text-amber-500"></i>
                        <span>{{ __('messages.preview_menu') }}</span>
                    </span>
                </div>
            </div>

            <!-- Formulário Rápido de Alteração de Nome -->
            <form action="{{ route('settings.update-name') }}" method="POST" class="mt-5 pt-4 border-t border-orange-200/60 dark:border-gray-700/60">
                @csrf
                <label for="quick_company_name" class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                    {{ __('messages.quick_establishment_name_update') }}
                </label>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <i class="mdi mdi-pencil-outline absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="text" id="quick_company_name" name="company_name" value="{{ $companyName }}" required
                            class="w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-xs font-bold focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                    </div>
                    <button type="submit" 
                        class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl transition-all shadow-md shadow-orange-500/20 flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                        <i class="mdi mdi-check"></i>
                        <span>Salvar Nome</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Card 2: Cores do Sistema & Tema Visual (Interativo com Live Preview e Paletas) -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-7 border border-gray-100 dark:border-gray-700/80 shadow-xs flex flex-col justify-between">
            <form action="{{ route('settings.update-colors') }}" method="POST" class="flex flex-col justify-between h-full space-y-4">
                @csrf
                <div>
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 text-xs font-bold uppercase tracking-wider">
                            <i class="mdi mdi-palette-outline text-sm"></i>
                            Cores do Sistema & Tema
                        </span>
                        <span class="text-xs text-gray-400 font-medium">Personalização Visual</span>
                    </div>

                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        Escolha a paleta de cores primária e secundária. O tema reflete nos botões, abas, badges e acentos do sistema.
                    </p>

                    <!-- Seletores de Cores -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <!-- Cor Primária -->
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5 flex items-center gap-1.5">
                                <i class="mdi mdi-format-color-fill text-orange-500"></i>
                                <span>Cor Primária</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="primary_color" x-model="primary"
                                    class="w-10 h-10 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer bg-transparent p-0.5">
                                <input type="text" x-model="primary" maxlength="7"
                                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-mono text-xs font-bold uppercase focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            </div>
                        </div>

                        <!-- Cor Secundária -->
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5 flex items-center gap-1.5">
                                <i class="mdi mdi-palette text-blue-500"></i>
                                <span>Cor Secundária</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="secondary_color" x-model="secondary"
                                    class="w-10 h-10 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer bg-transparent p-0.5">
                                <input type="text" x-model="secondary" maxlength="7"
                                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-mono text-xs font-bold uppercase focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Paletas Rápidas (Presets gastronômicos e corporativos) -->
                    <div class="mb-4">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Paletas Rápidas Recomendadas:</span>
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" @click="setPreset('#F97316', '#10B981')" class="px-2.5 py-1 rounded-lg text-[10px] font-bold border transition-all flex items-center gap-1.5 cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100" :class="primary.toUpperCase() === '#F97316' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-200 dark:border-gray-700'">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#F97316]"></span>
                                Laranja Café
                            </button>
                            <button type="button" @click="setPreset('#F59E0B', '#3B82F6')" class="px-2.5 py-1 rounded-lg text-[10px] font-bold border transition-all flex items-center gap-1.5 cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100" :class="primary.toUpperCase() === '#F59E0B' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-200 dark:border-gray-700'">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#F59E0B]"></span>
                                Âmbar Dourado
                            </button>
                            <button type="button" @click="setPreset('#10B981', '#3B82F6')" class="px-2.5 py-1 rounded-lg text-[10px] font-bold border transition-all flex items-center gap-1.5 cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100" :class="primary.toUpperCase() === '#10B981' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-200 dark:border-gray-700'">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#10B981]"></span>
                                Esmeralda Fresh
                            </button>
                            <button type="button" @click="setPreset('#3B82F6', '#10B981')" class="px-2.5 py-1 rounded-lg text-[10px] font-bold border transition-all flex items-center gap-1.5 cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100" :class="primary.toUpperCase() === '#3B82F6' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-200 dark:border-gray-700'">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#3B82F6]"></span>
                                Azul Real
                            </button>
                            <button type="button" @click="setPreset('#8B5CF6', '#F59E0B')" class="px-2.5 py-1 rounded-lg text-[10px] font-bold border transition-all flex items-center gap-1.5 cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100" :class="primary.toUpperCase() === '#8B5CF6' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-200 dark:border-gray-700'">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#8B5CF6]"></span>
                                Roxo Bistrô
                            </button>
                            <button type="button" @click="setPreset('#EF4444', '#F97316')" class="px-2.5 py-1 rounded-lg text-[10px] font-bold border transition-all flex items-center gap-1.5 cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100" :class="primary.toUpperCase() === '#EF4444' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-200 dark:border-gray-700'">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#EF4444]"></span>
                                Vermelho Gourmet
                            </button>
                            <button type="button" @click="setPreset('#1F2937', '#F97316')" class="px-2.5 py-1 rounded-lg text-[10px] font-bold border transition-all flex items-center gap-1.5 cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100" :class="primary.toUpperCase() === '#1F2937' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-200 dark:border-gray-700'">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#1F2937]"></span>
                                Preto Carvão
                            </button>
                        </div>
                    </div>

                    <!-- Live Mini Preview -->
                    <div class="p-3 bg-gray-50 dark:bg-gray-900/60 rounded-2xl border border-gray-150 dark:border-gray-700/60 flex items-center justify-between text-xs">
                        <span class="text-gray-500 font-medium text-[11px]">Pré-visualização:</span>
                        <div class="flex items-center gap-2">
                            <span :style="'background-color: ' + primary + '15; color: ' + primary + '; border-color: ' + primary + '40;'" class="border px-2.5 py-0.5 rounded-full font-bold text-[10px]">
                                Badge Ativo
                            </span>
                            <div :style="'background-color: ' + primary" class="px-3 py-1 rounded-xl text-white font-bold text-[11px] shadow-xs flex items-center gap-1">
                                <i class="mdi mdi-check"></i>
                                <span>Botão</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botão Salvar Cores -->
                <div class="pt-2 flex justify-end">
                    <button type="submit" 
                        class="w-full sm:w-auto px-5 py-2.5 bg-gray-900 dark:bg-gray-100 hover:bg-black dark:hover:bg-white text-white dark:text-gray-900 font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="mdi mdi-palette-swatch-outline text-base"></i>
                        <span>Aplicar Cores do Sistema</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Navigation Tabs por Categorias de Configuração -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-3 border border-gray-100 dark:border-gray-700/80 shadow-xs">
        <div class="flex flex-wrap gap-2">
            @foreach($settings as $group => $groupSettings)
                <button type="button" @click="activeTab = '{{ $group }}'"
                    class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer"
                    :class="activeTab === '{{ $group }}' 
                        ? 'bg-orange-500 text-white shadow-md shadow-orange-500/20' 
                        : 'bg-gray-100 dark:bg-gray-700/70 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-650'">
                    <i class="mdi {{ $groupIcons[$group] ?? 'mdi-cog-outline' }} text-base"></i>
                    <span>{{ __('messages.' . $group) }}</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px]"
                        :class="activeTab === '{{ $group }}' ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400'">
                        {{ count($groupSettings) }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Formulário Principal das Configurações em Abas -->
    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            @foreach($settings as $group => $groupSettings)
                <div x-show="activeTab === '{{ $group }}'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white dark:bg-gray-800 rounded-3xl shadow-xs border border-gray-100 dark:border-gray-700/80 overflow-hidden">
                    
                    <!-- Cabeçalho do Grupo -->
                    <div class="px-8 py-5 bg-gray-50/80 dark:bg-gray-900/40 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-orange-50 dark:bg-orange-950/40 text-orange-500 flex items-center justify-center text-xl">
                                <i class="mdi {{ $groupIcons[$group] ?? 'mdi-cog-outline' }}"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                    {{ __('messages.' . $group) }}
                                </h2>
                                <p class="text-xs text-gray-400">Gerencie todos os parâmetros relacionados a este módulo</p>
                            </div>
                        </div>

                        <button type="submit"
                            class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl shadow-md shadow-orange-500/20 transition-all cursor-pointer">
                            <i class="mdi mdi-content-save"></i>
                            <span>{{ __('messages.save_settings') }}</span>
                        </button>
                    </div>

                    <!-- Campos do Grupo -->
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($groupSettings as $setting)
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-1.5">
                                    @if(isset($settingIcons[$setting->key]))
                                        <i class="mdi {{ $settingIcons[$setting->key] }} text-orange-500 text-sm"></i>
                                    @endif
                                    <span>{{ __('messages.' . $setting->key) }}</span>
                                </label>

                                @if($setting->type === 'text')
                                    <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-medium text-xs focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">

                                @elseif($setting->type === 'number')
                                    <input type="number" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-medium text-xs focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">

                                @elseif($setting->type === 'textarea')
                                    <textarea name="{{ $setting->key }}" rows="3"
                                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-medium text-xs focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">{{ $setting->value }}</textarea>

                                @elseif($setting->type === 'file')
                                    <div class="space-y-3 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center gap-4">
                                            @php
                                                $previewImg = null;
                                                if ($setting->value && file_exists(public_path('storage/' . $setting->value))) {
                                                    $previewImg = asset('storage/' . $setting->value);
                                                } elseif ($setting->key === 'login_background' && file_exists(public_path('assets/images/restaurant-bg.jpeg'))) {
                                                    $previewImg = asset('assets/images/restaurant-bg.jpeg');
                                                } elseif ($setting->key === 'system_logo' && file_exists(public_path('assets/images/logo.png'))) {
                                                    $previewImg = asset('assets/images/logo.png');
                                                }
                                            @endphp
                                            @if($previewImg)
                                                <div class="relative group">
                                                    <img src="{{ $previewImg }}"
                                                        class="w-16 h-16 rounded-xl object-cover bg-white dark:bg-gray-800 p-1 border border-gray-200 dark:border-gray-700 shadow-xs">
                                                    @if($setting->value)
                                                        <span class="absolute -top-1 -right-1 bg-emerald-500 text-white rounded-full p-0.5 text-[10px]" title="Imagem personalizada ativa">
                                                            <i class="mdi mdi-check"></i>
                                                        </span>
                                                    @else
                                                        <span class="absolute -top-1 -right-1 bg-amber-500 text-white rounded-full px-1 text-[9px] font-bold" title="Padrão do sistema">
                                                            Padrão
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="w-16 h-16 rounded-xl bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                                                    <i class="mdi mdi-image-outline text-2xl"></i>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <input type="file" name="{{ $setting->key }}" accept="image/*"
                                                    class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer">
                                                @if($setting->key === 'login_background')
                                                    <p class="text-[11px] text-gray-400 mt-1">Recomendado: 1920x1080px (JPG/PNG). Imagem de fundo da tela de login.</p>
                                                @elseif($setting->key === 'system_logo')
                                                    <p class="text-[11px] text-gray-400 mt-1">Logotipo oficial exibido no cabeçalho e login.</p>
                                                @endif

                                                @if($setting->value)
                                                    <label class="inline-flex items-center gap-1.5 mt-2 text-rose-500 hover:text-rose-600 cursor-pointer text-[11px] font-semibold">
                                                        <input type="checkbox" name="{{ $setting->key }}_remove" value="1" class="rounded text-rose-500 focus:ring-rose-400">
                                                        <span>Remover imagem personalizada (restaurar padrão)</span>
                                                    </label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                @elseif($setting->type === 'color')
                                    <div class="flex items-center gap-3">
                                        <input type="color" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                            class="w-12 h-12 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer bg-transparent p-0.5">
                                        <input type="text" name="{{ $setting->key }}_hex" value="{{ $setting->value }}"
                                            oninput="this.previousElementSibling.value = this.value"
                                            class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-mono text-xs font-bold uppercase focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                    </div>

                                @elseif($setting->type === 'select')
                                    <select name="{{ $setting->key }}"
                                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-bold text-xs focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                        @if($setting->key === 'system_language')
                                            <option value="en" {{ $setting->value === 'en' ? 'selected' : '' }}>
                                                {{ __('messages.english') }} (English)
                                            </option>
                                            <option value="pt" {{ $setting->value === 'pt' ? 'selected' : '' }}>
                                                {{ __('messages.portuguese') }} (Português)
                                            </option>
                                        @elseif($setting->key === 'system_timezone')
                                            @foreach(\DateTimeZone::listIdentifiers() as $timezone)
                                                <option value="{{ $timezone }}" {{ $setting->value === $timezone ? 'selected' : '' }}>
                                                    {{ $timezone }}
                                                </option>
                                            @endforeach
                                        @elseif($setting->key === 'login_backdrop_filter')
                                            <option value="blur(6px) brightness(0.65)" {{ $setting->value === 'blur(6px) brightness(0.65)' ? 'selected' : '' }}>
                                                Desfoque Médio + Escurecido (Recomendado)
                                            </option>
                                            <option value="blur(12px) brightness(0.50)" {{ $setting->value === 'blur(12px) brightness(0.50)' ? 'selected' : '' }}>
                                                Desfoque Intenso + Fundo Escuro (Modo Foco)
                                            </option>
                                            <option value="blur(3px) brightness(0.75)" {{ $setting->value === 'blur(3px) brightness(0.75)' ? 'selected' : '' }}>
                                                Desfoque Suave + Leve Escurecido
                                            </option>
                                            <option value="blur(8px) contrast(1.1) brightness(0.6)" {{ $setting->value === 'blur(8px) contrast(1.1) brightness(0.6)' ? 'selected' : '' }}>
                                                Efeito Vidro / Glassmorphism Elegante
                                            </option>
                                            <option value="brightness(0.55)" {{ $setting->value === 'brightness(0.55)' ? 'selected' : '' }}>
                                                Apenas Escurecido (Sem Desfoque / Nítido)
                                            </option>
                                            <option value="none" {{ $setting->value === 'none' ? 'selected' : '' }}>
                                                Sem Filtro (Imagem Original Totalmente Nítida)
                                            </option>
                                        @endif
                                    </select>
                                    @if($setting->key === 'login_backdrop_filter')
                                        <p class="text-[11px] text-gray-400 mt-1">Efeito visual aplicado sobre a imagem de fundo na tela de login para garantir legibilidade dos formulários.</p>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Rodapé do Card da Aba com Botão Salvar -->
                    <div class="px-8 py-4 bg-gray-50/80 dark:bg-gray-900/40 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <span class="text-xs text-gray-400">
                            Certifique-se de salvar as alterações antes de navegar para outra página.
                        </span>
                        <button type="submit"
                            class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl transition-all shadow-md shadow-orange-500/20 flex items-center gap-2 cursor-pointer">
                            <i class="mdi mdi-content-save"></i>
                            <span>{{ __('messages.save_settings') }}</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </form>
</div>

@push('scripts')
<script>
    function settingsManager(config) {
        return {
            primary: config.primary || '#F97316',
            secondary: config.secondary || '#10B981',
            activeTab: config.activeTab || 'general',

            setPreset(pri, sec) {
                this.primary = pri;
                this.secondary = sec;
            }
        }
    }
</script>
@endpush
@endsection
