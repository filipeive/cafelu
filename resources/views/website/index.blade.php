@extends('layouts.app')

@section('title', 'Página Web / Pedidos Online')

@section('content')
<div class="w-full" x-data="{ tab: '{{ request('tab', 'slides') }}', editing: null }">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="mdi mdi-web text-orange-500"></i>
                Página Web &amp; Pedidos Online
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                Gerencie o banner, textos, horários e contactos da página pública. O menu é carregado automaticamente dos produtos ativos.
            </p>
        </div>
        <a href="{{ route('welcome') }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 hover:bg-black dark:bg-gray-700 dark:hover:bg-gray-600 text-white text-sm font-bold rounded-xl transition-all">
            <i class="mdi mdi-open-in-new"></i>
            Ver Página Pública
        </a>
    </div>

    @if (isset($errors) && $errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabs -->
    <div class="inline-flex p-1 bg-gray-100 dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-6">
        <button type="button" @click="tab = 'slides'"
            :class="tab === 'slides' ? 'bg-white dark:bg-gray-700 text-orange-500 shadow-sm' : 'text-gray-500 dark:text-gray-400'"
            class="px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
            <i class="mdi mdi-image-multiple-outline"></i> Banner / Slider
            <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300">{{ $slides->count() }}</span>
        </button>
        <button type="button" @click="tab = 'content'"
            :class="tab === 'content' ? 'bg-white dark:bg-gray-700 text-orange-500 shadow-sm' : 'text-gray-500 dark:text-gray-400'"
            class="px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
            <i class="mdi mdi-text-box-edit-outline"></i> Conteúdo &amp; Contactos
        </button>
    </div>

    <!-- TAB: SLIDES -->
    <div x-show="tab === 'slides'" class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Add slide -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm p-6 h-fit">
            <h3 class="font-bold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
                <i class="mdi mdi-plus-circle-outline text-orange-500"></i> Novo Slide
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-5">Recomendado: imagem horizontal 1920x900px (JPG/WEBP).</p>

            <form action="{{ route('website.slides.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">Imagem *</label>
                    <input type="file" name="image" accept="image/*" required
                        class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer">
                    <span class="block text-[11px] text-gray-400 mt-1">Formatos: JPG, PNG, WEBP. Imagens grandes são otimizadas automaticamente (máx. 4 MB).</span>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">Título</label>
                    <input type="text" name="title" maxlength="150" placeholder="Ex: Pequeno-almoço completo"
                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">Subtítulo</label>
                    <textarea name="subtitle" rows="2" maxlength="255" placeholder="Breve descrição da promoção ou destaque"
                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">Texto do botão</label>
                        <input type="text" name="button_text" maxlength="50" placeholder="Ver Menu"
                            class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">Link</label>
                        <input type="text" name="button_link" maxlength="255" placeholder="#menu"
                            class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                </div>
                <button type="submit"
                    class="w-full py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all text-sm flex items-center justify-center gap-2">
                    <i class="mdi mdi-upload"></i> Adicionar Slide
                </button>
            </form>
        </div>

        <!-- Slides list -->
        <div class="xl:col-span-2 space-y-4">
            @forelse ($slides as $slide)
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="flex flex-col sm:flex-row">
                        <div class="relative sm:w-64 h-40 sm:h-auto flex-shrink-0 bg-gray-100 dark:bg-gray-900">
                            <img src="{{ $slide->image_url }}" class="w-full h-full object-cover" alt="">
                            <span class="absolute top-2 left-2 px-2 py-0.5 rounded-lg text-[11px] font-bold bg-black/60 text-white">#{{ $loop->iteration }}</span>
                            @if (!$slide->is_active)
                                <span class="absolute top-2 right-2 px-2 py-0.5 rounded-lg text-[11px] font-bold bg-rose-600 text-white">Inativo</span>
                            @endif
                        </div>
                        <div class="flex-1 p-5 flex flex-col justify-between gap-4">
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">{{ $slide->title ?: 'Sem título' }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $slide->subtitle ?: '—' }}</p>
                                @if ($slide->button_text)
                                    <span class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-orange-600 dark:text-orange-400">
                                        <i class="mdi mdi-gesture-tap-button"></i> {{ $slide->button_text }} &rarr; {{ $slide->button_link ?: '#menu' }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <form action="{{ route('website.slides.move', [$slide, 'up']) }}" method="POST">
                                    @csrf
                                    <button class="p-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200" title="Mover para cima" @disabled($loop->first)>
                                        <i class="mdi mdi-arrow-up"></i>
                                    </button>
                                </form>
                                <form action="{{ route('website.slides.move', [$slide, 'down']) }}" method="POST">
                                    @csrf
                                    <button class="p-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200" title="Mover para baixo" @disabled($loop->last)>
                                        <i class="mdi mdi-arrow-down"></i>
                                    </button>
                                </form>
                                <button type="button" @click="editing = editing === {{ $slide->id }} ? null : {{ $slide->id }}"
                                    class="px-3 py-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-bold flex items-center gap-1.5">
                                    <i class="mdi mdi-pencil"></i> Editar
                                </button>
                                <form action="{{ route('website.slides.destroy', $slide) }}" method="POST" onsubmit="return confirm('Remover este slide?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-400 text-xs font-bold flex items-center gap-1.5">
                                        <i class="mdi mdi-delete"></i> Remover
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Inline edit -->
                    <div x-show="editing === {{ $slide->id }}" x-cloak class="border-t border-gray-100 dark:border-gray-700 p-5 bg-gray-50/70 dark:bg-gray-900/40">
                        <form action="{{ route('website.slides.update', $slide) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Título</label>
                                <input type="text" name="title" value="{{ $slide->title }}" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Subtítulo</label>
                                <input type="text" name="subtitle" value="{{ $slide->subtitle }}" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Texto do botão</label>
                                <input type="text" name="button_text" value="{{ $slide->button_text }}" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Link</label>
                                <input type="text" name="button_link" value="{{ $slide->button_link }}" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Substituir imagem</label>
                                <input type="file" name="image" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-700">
                            </div>
                            <div class="flex items-end justify-between gap-3">
                                <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" @checked($slide->is_active) class="rounded text-orange-500 focus:ring-orange-400">
                                    Slide ativo
                                </label>
                                <button class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl text-sm flex items-center gap-1.5">
                                    <i class="mdi mdi-content-save"></i> Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-300 dark:border-gray-700">
                    <div class="mx-auto w-16 h-16 rounded-2xl bg-orange-50 dark:bg-orange-950/40 text-orange-500 flex items-center justify-center mb-3">
                        <i class="mdi mdi-image-plus text-3xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 dark:text-white">Nenhum slide adicionado</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-md mx-auto">
                        Enquanto não houver slides, a página usa a imagem de fundo do login e o título configurado em "Conteúdo".
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- TAB: CONTENT -->
    <div x-show="tab === 'content'" x-cloak>
        <form action="{{ route('website.content') }}" method="POST" enctype="multipart/form-data"
              class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach ($fields as $key => [$label, $type, $default])
                    <div class="{{ $type === 'textarea' ? 'md:col-span-2' : '' }}">
                        @if ($type === 'boolean')
                            <label class="flex items-center justify-between gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 cursor-pointer">
                                <span class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $label }}</span>
                                <input type="checkbox" name="{{ $key }}" value="1" @checked($values[$key] == '1') class="w-5 h-5 rounded text-orange-500 focus:ring-orange-400">
                            </label>
                        @else
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">{{ $label }}</label>
                            @if ($type === 'textarea')
                                <textarea name="{{ $key }}" rows="3"
                                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent">{{ $values[$key] }}</textarea>
                            @else
                                <input type="text" name="{{ $key }}" value="{{ $values[$key] }}"
                                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            @endif
                        @endif
                    </div>
                @endforeach

                <!-- Geolocalização Inteligente (Separação Automática Presencial vs Online) -->
                <div class="md:col-span-2 p-4 rounded-2xl bg-orange-50/60 dark:bg-orange-950/20 border border-orange-200/70 dark:border-orange-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="mdi mdi-map-marker-radius text-orange-500 text-lg"></i>
                            Detecção Automática Presencial vs Pedido Online (Geofence GPS)
                        </h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            Quando as coordenadas estão definidas, o cardápio detecta automaticamente se o cliente está dentro do raio do restaurante (Atendimento na Mesa) ou à distância (Pedido Online / Takeaway).
                        </p>
                    </div>
                    <button type="button"
                            onclick="captureRestaurantGps()"
                            class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-black dark:bg-orange-500 dark:hover:bg-orange-600 text-white text-xs font-bold flex items-center gap-2 flex-shrink-0 cursor-pointer transition-all">
                        <i class="mdi mdi-crosshairs-gps text-base"></i>
                        <span>Capturar Meu GPS Atual</span>
                    </button>
                </div>

                <!-- About image -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">Imagem da secção "Sobre Nós"</label>
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700">
                        @if ($aboutImage)
                            <img src="{{ asset('storage/' . $aboutImage) }}" class="w-24 h-16 object-cover rounded-xl" alt="">
                        @else
                            <div class="w-24 h-16 rounded-xl bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400"><i class="mdi mdi-image-outline text-2xl"></i></div>
                        @endif
                        <div class="flex-1">
                            <input type="file" name="about_image" accept="image/*"
                                class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-700 cursor-pointer">
                            @if ($aboutImage)
                                <label class="inline-flex items-center gap-1.5 mt-2 text-rose-500 text-xs font-semibold cursor-pointer">
                                    <input type="checkbox" name="about_image_remove" value="1" class="rounded text-rose-500"> Remover imagem
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <p class="mt-5 text-xs text-gray-500 dark:text-gray-400 flex items-start gap-1.5">
                <i class="mdi mdi-information-outline text-base"></i>
                Nome, endereço, telefone, e-mail e logotipo são editados em <a href="{{ route('settings.index') }}" class="text-orange-600 font-semibold underline">Definições do Sistema</a>.
            </p>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="px-7 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all text-sm flex items-center gap-2">
                    <i class="mdi mdi-content-save"></i> Guardar Conteúdo
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function captureRestaurantGps() {
    if (!navigator.geolocation) {
        window.showToast('O seu navegador não suporta geolocalização GPS.', 'error');
        return;
    }
    window.showToast('A obter coordenadas GPS atuais...', 'info');
    navigator.geolocation.getCurrentPosition(
        function(pos) {
            const latInput = document.querySelector('input[name="site_latitude"]');
            const lngInput = document.querySelector('input[name="site_longitude"]');
            const radInput = document.querySelector('input[name="site_geofence_radius"]');
            if (latInput) latInput.value = pos.coords.latitude.toFixed(6);
            if (lngInput) lngInput.value = pos.coords.longitude.toFixed(6);
            if (radInput && !radInput.value) radInput.value = '150';
            window.showToast('Coordenadas GPS capturadas! Clique em "Guardar Conteúdo" para salvar.', 'success');
        },
        function(err) {
            window.showToast('Não foi possível obter a localização GPS. Verifique as permissões do navegador ou insira manualmente.', 'warning');
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
}
</script>
@endpush
@endsection

