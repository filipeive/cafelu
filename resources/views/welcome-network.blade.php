<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rede de Restaurantes — RestPOS | Cardápios Digitais & Pedidos Online</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased selection:bg-orange-500 selection:text-white min-h-screen flex flex-col">

    <!-- Top Navigation -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center text-white shadow-lg shadow-orange-500/25">
                    <i class="mdi mdi-silverware-fork-knife text-2xl"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-xl tracking-tight text-slate-900">RestPOS</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-orange-100 text-orange-700">Rede Digital</span>
                    </div>
                    <p class="text-xs text-slate-500">Diretório de Restaurantes & Cardápios Online</p>
                </div>
            </a>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ auth()->user()->isSuperAdmin() && !session('is_support_mode') ? route('admin.tenants.index') : route('dashboard') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold transition-all shadow-sm">
                        <i class="mdi mdi-view-dashboard-outline text-base"></i>
                        <span>Painel de Gestão</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-bold transition-all shadow-sm">
                        <i class="mdi mdi-shield-key-outline text-orange-500 text-base"></i>
                        <span>Área do Restaurante</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-orange-950 text-white py-14 sm:py-20">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#f97316_1px,transparent_1px)] [background-size:24px_24px]"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-orange-500/15 border border-orange-400/30 text-orange-300 text-xs font-bold uppercase tracking-wider mb-5">
                    <i class="mdi mdi-store-marker-outline text-sm"></i>
                    <span>{{ $restaurants->count() }} {{ $restaurants->count() === 1 ? 'Restaurante Registado' : 'Restaurantes Registados' }} na Rede</span>
                </div>

                <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                    Escolha o seu Restaurante e faça o seu <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-300">Pedido Online</span>
                </h1>

                <p class="mt-4 text-sm sm:text-base text-slate-300 leading-relaxed max-w-2xl mx-auto">
                    Explore os cardápios digitais dos restaurantes parceiros, faça pedidos diretamente para a sua mesa via QR Code ou encomende para levar com acompanhamento em tempo real.
                </p>

                <!-- Search & City Filter Bar -->
                <form action="{{ route('welcome') }}" method="GET" class="mt-8 bg-white/10 backdrop-blur-md p-2 sm:p-3 rounded-2xl border border-white/15 shadow-2xl max-w-2xl mx-auto">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <div class="relative flex-1">
                            <i class="mdi mdi-magnify absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl"></i>
                            <input type="text" name="q" value="{{ $search }}"
                                placeholder="Pesquisar restaurante por nome, cidade ou especialidade..."
                                class="w-full pl-11 pr-4 py-3 rounded-xl bg-white text-slate-900 placeholder-slate-400 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        @if($cities->count() > 1)
                            <select name="cidade"
                                class="px-4 py-3 rounded-xl bg-white text-slate-700 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-orange-500">
                                <option value="">Todas as Cidades</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c }}" {{ $city === $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        @endif
                        <button type="submit"
                            class="px-6 py-3 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm transition-all shadow-lg shadow-orange-500/30 flex items-center justify-center gap-2">
                            <i class="mdi mdi-Compass-outline"></i>
                            <span>Procurar</span>
                        </button>
                    </div>
                </form>

                @if($search !== '' || $city !== '')
                    <div class="mt-4 flex items-center justify-center gap-2 text-xs text-slate-300">
                        <span>A mostrar resultados filtrados</span>
                        <a href="{{ route('welcome') }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white font-bold">
                            <i class="mdi mdi-close"></i> Limpar filtros
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Restaurants Grid -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="mdi mdi-storefront text-orange-500"></i>
                    Restaurantes Disponíveis
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Selecione um estabelecimento para abrir o respetivo cardápio digital exclusivo
                </p>
            </div>

            <div class="flex items-center gap-3 text-xs text-slate-500 bg-white px-4 py-2.5 rounded-2xl border border-slate-200/80 shadow-sm">
                <i class="mdi mdi-qrcode-scan text-orange-500 text-lg"></i>
                <span>No restaurante? Escaneie o <strong>QR Code da sua mesa</strong> para atendimento imediato.</span>
            </div>
        </div>

        @if($restaurants->isEmpty())
            <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center mx-auto mb-4">
                    <i class="mdi mdi-store-search-outline text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Nenhum restaurante encontrado</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Não encontrámos estabelecimentos com os critérios pesquisados. Limpe a pesquisa para ver todos os restaurantes da rede.
                </p>
                <a href="{{ route('welcome') }}"
                    class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold transition-all">
                    <i class="mdi mdi-refresh"></i> Ver Todos os Restaurantes
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($restaurants as $rest)
                    <article class="group bg-white rounded-3xl border border-slate-200/80 hover:border-orange-500/50 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col">
                        <!-- Top Accent Banner -->
                        <div class="relative h-28 bg-gradient-to-r from-slate-900 via-slate-800 to-orange-950 p-5 flex items-start justify-between">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/15 backdrop-blur-md text-white">
                                    <i class="mdi mdi-map-marker text-orange-400"></i>
                                    {{ $rest['city'] }}
                                </span>
                                @if($rest['online_orders'])
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/20 border border-emerald-400/30 text-emerald-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Pedidos Online
                                    </span>
                                @endif
                            </div>

                            <span class="text-[11px] font-mono text-slate-300 bg-black/30 px-2.5 py-1 rounded-lg">
                                /{{ $rest['slug'] }}
                            </span>
                        </div>

                        <!-- Restaurant Identity -->
                        <div class="px-6 pt-0 pb-6 flex-1 flex flex-col">
                            <div class="relative -mt-10 mb-4 flex items-end justify-between">
                                <div class="w-20 h-20 rounded-2xl bg-white p-1.5 shadow-lg border border-slate-100 flex items-center justify-center overflow-hidden">
                                    @if($rest['logo'])
                                        <img src="{{ $rest['logo'] }}" alt="{{ $rest['name'] }}" class="w-full h-full object-contain rounded-xl">
                                    @else
                                        <div class="w-full h-full rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 text-white flex items-center justify-center font-black text-2xl">
                                            {{ strtoupper(substr($rest['name'], 0, 2)) }}
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                                    <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700" title="Produtos ativos no cardápio">
                                        <i class="mdi mdi-food-fork-drink text-orange-500 mr-1"></i>{{ $rest['products_count'] }} itens
                                    </span>
                                    @if($rest['tables_count'] > 0)
                                        <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700" title="Mesas com QR Code">
                                            <i class="mdi mdi-table-chair text-indigo-500 mr-1"></i>{{ $rest['tables_count'] }} mesas
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-orange-600 transition-colors">
                                <a href="{{ $rest['short_url'] }}">
                                    {{ $rest['name'] }}
                                </a>
                            </h3>

                            <p class="text-xs text-slate-500 mt-1.5 line-clamp-2 leading-relaxed">
                                {{ $rest['hero_subtitle'] }}
                            </p>

                            @if($rest['categories']->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5 mt-4">
                                    @foreach($rest['categories'] as $catName)
                                        <span class="px-2.5 py-0.5 rounded-lg bg-orange-50 text-orange-700 text-[11px] font-semibold">
                                            {{ $catName }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-5 pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-600">
                                <div class="flex items-start gap-2">
                                    <i class="mdi mdi-map-marker-outline text-slate-400 text-sm mt-0.5"></i>
                                    <span class="truncate">{{ $rest['address'] }}</span>
                                </div>
                                @if($rest['phone'])
                                    <div class="flex items-center gap-2">
                                        <i class="mdi mdi-phone-outline text-slate-400 text-sm"></i>
                                        <span>{{ $rest['phone'] }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6 pt-2 flex items-center gap-2 mt-auto">
                                <a href="{{ $rest['short_url'] }}"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 group-hover:bg-orange-500 text-white text-xs sm:text-sm font-bold transition-all shadow-sm">
                                    <i class="mdi mdi-book-open-page-variant-outline text-base"></i>
                                    <span>Abrir Cardápio Digital</span>
                                    <i class="mdi mdi-arrow-right text-base"></i>
                                </a>

                                @if(!empty($rest['whatsapp']))
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $rest['whatsapp']) }}" target="_blank" rel="noopener"
                                        class="w-11 h-11 rounded-2xl bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center transition-colors"
                                        title="Contactar via WhatsApp">
                                        <i class="mdi mdi-whatsapp text-xl"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div class="flex items-center gap-2">
                <i class="mdi mdi-silverware-fork-knife text-orange-500 text-base"></i>
                <span class="font-bold text-slate-800">RestPOS Cloud & Local</span>
                <span>— Plataforma Multi-Restaurante de Gestão, POS, QR Code & Faturação</span>
            </div>
            <div>
                &copy; {{ date('Y') }} RestPOS. Todos os direitos reservados.
            </div>
        </div>
    </footer>

</body>
</html>
