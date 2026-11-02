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
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        border: 'hsl(var(--border))',
                        input: 'hsl(var(--input))',
                        ring: 'hsl(var(--ring))',
                        background: 'hsl(var(--background))',
                        foreground: 'hsl(var(--foreground))',
                        primary: {
                            DEFAULT: 'hsl(var(--primary))',
                            foreground: 'hsl(var(--primary-foreground))',
                        },
                        secondary: {
                            DEFAULT: 'hsl(var(--secondary))',
                            foreground: 'hsl(var(--secondary-foreground))',
                        },
                        destructive: {
                            DEFAULT: 'hsl(var(--destructive))',
                            foreground: 'hsl(var(--destructive-foreground))',
                        },
                        muted: {
                            DEFAULT: 'hsl(var(--muted))',
                            foreground: 'hsl(var(--muted-foreground))',
                        },
                        accent: {
                            DEFAULT: 'hsl(var(--accent))',
                            foreground: 'hsl(var(--accent-foreground))',
                        },
                        card: {
                            DEFAULT: 'hsl(var(--card))',
                            foreground: 'hsl(var(--card-foreground))',
                        },
                    },
                    borderRadius: {
                        lg: 'var(--radius)',
                        md: 'calc(var(--radius) - 2px)',
                        sm: 'calc(var(--radius) - 4px)',
                    },
                }
            }
        }
    </script>

    <style>
        :root {
            --background: 210 40% 98%;
            --foreground: 222.2 84% 4.9%;
            --card: 0 0% 100%;
            --card-foreground: 222.2 84% 4.9%;
            --popover: 0 0% 100%;
            --popover-foreground: 222.2 84% 4.9%;
            --primary: 24.6 95% 53.1%;
            --primary-foreground: 0 0% 100%;
            --secondary: 210 40% 96.1%;
            --secondary-foreground: 222.2 47.4% 11.2%;
            --muted: 210 40% 96.1%;
            --muted-foreground: 215.4 16.3% 46.9%;
            --accent: 210 40% 96.1%;
            --accent-foreground: 222.2 47.4% 11.2%;
            --destructive: 0 84.2% 60.2%;
            --destructive-foreground: 210 40% 98%;
            --border: 214.3 31.8% 91.4%;
            --input: 214.3 31.8% 91.4%;
            --ring: 24.6 95% 53.1%;
            --radius: 0.75rem;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-background text-foreground antialiased selection:bg-primary/20 selection:text-foreground min-h-screen flex flex-col">

    <!-- Top Utility Bar -->
    <div class="bg-slate-900 text-slate-300 text-xs border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/20 text-orange-400 border border-primary/30">
                    <span class="size-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Rede Online
                </span>
                <span class="hidden sm:inline text-slate-400">Cardápios Digitais Interativos & Pedidos em Tempo Real</span>
            </div>
            <div class="flex items-center gap-4 text-slate-400">
                <a href="#como-funciona" class="hover:text-white transition-colors hidden md:inline">Como Funciona</a>
                <span class="hidden md:inline text-slate-700">|</span>
                <a href="{{ route('app.download') }}" class="hover:text-orange-400 text-white font-medium inline-flex items-center gap-1 transition-colors">
                    <i class="mdi mdi-cellphone-check text-emerald-400"></i>
                    <span>Baixe a Nossa App</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Top Navigation -->
    <header class="sticky top-0 z-40 bg-card/90 backdrop-blur-md border-b border-border/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 py-3.5 flex items-center justify-between gap-4">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <div class="size-11 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center text-white shadow-md shadow-orange-500/20 group-hover:scale-105 transition-transform">
                    <i class="mdi mdi-silverware-fork-knife text-2xl"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-xl tracking-tight text-foreground">RestPOS</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-orange-100 dark:bg-orange-950 text-orange-700 dark:text-orange-300">Rede Digital</span>
                    </div>
                    <p class="text-xs text-muted-foreground">Diretório de Restaurantes & Cardápios Online</p>
                </div>
            </a>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('app.download') }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl bg-secondary hover:bg-secondary/80 text-secondary-foreground border border-border text-xs sm:text-sm font-semibold transition-all shadow-xs"
                    title="Conheça e baixe a nossa aplicação móvel">
                    <i class="mdi mdi-cellphone-arrow-down text-base text-emerald-600"></i>
                    <span class="hidden sm:inline">Baixe a Nossa App</span>
                    <span class="sm:hidden">App Móvel</span>
                </a>

                @auth
                    <a href="{{ auth()->user()->isSuperAdmin() && !session('is_support_mode') ? route('admin.tenants.index') : route('dashboard') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold transition-all shadow-sm">
                        <i class="mdi mdi-view-dashboard-outline text-base"></i>
                        <span>Painel de Gestão</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-border bg-card hover:bg-accent text-foreground text-xs sm:text-sm font-semibold transition-all shadow-xs">
                        <i class="mdi mdi-shield-key-outline text-primary text-base"></i>
                        <span>Área do Restaurante</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    @php
        $featuredRestaurant = $restaurants->firstWhere('slug', 'lu-yoshi') ?? $restaurants->first();
    @endphp

    <!-- Hero Section with Modern Visual Hierarchy -->
    <section class="relative overflow-hidden bg-slate-950 text-white py-16 sm:py-24">
        <!-- Ambient Background Glows -->
        <div class="absolute -top-24 -right-24 size-96 rounded-full bg-orange-500/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 size-96 rounded-full bg-amber-600/15 blur-3xl pointer-events-none"></div>
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#f97316_1px,transparent_1px)] [background-size:24px_24px]"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-orange-500/15 border border-orange-400/30 text-orange-300 text-xs font-bold uppercase tracking-wider mb-6 shadow-xs">
                    <i class="mdi mdi-store-marker-outline text-sm"></i>
                    <span>{{ $restaurants->count() }} {{ $restaurants->count() === 1 ? 'Restaurante Registado' : 'Restaurantes Registados' }} na Rede</span>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight sm:leading-none">
                    Descubra Sabores e Faça o seu <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-amber-300 to-yellow-300">Pedido Online</span>
                </h1>

                <p class="mt-5 text-sm sm:text-base text-slate-300 leading-relaxed max-w-2xl mx-auto">
                    Aceda aos cardápios digitais interativos, faça pedidos diretamente na mesa via QR Code ou encomende para takeaway com confirmação imediata.
                </p>

                <!-- Modern Search Bar -->
                <form action="{{ route('welcome') }}" method="GET" class="mt-8 bg-white/10 backdrop-blur-md p-2 rounded-2xl sm:rounded-full border border-white/20 shadow-2xl max-w-2xl mx-auto flex flex-col sm:flex-row gap-2">
                    <div class="relative flex-1">
                        <i class="mdi mdi-magnify absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl"></i>
                        <input type="text" name="q" value="{{ $search }}"
                            placeholder="Pesquisar por nome, cidade ou prato..."
                            class="w-full pl-11 pr-4 py-3 rounded-xl sm:rounded-full bg-white text-slate-900 placeholder-slate-400 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    @if($cities->count() > 1)
                        <select name="cidade"
                            class="px-4 py-3 rounded-xl sm:rounded-full bg-white text-slate-700 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary border-none">
                            <option value="">Todas as Cidades</option>
                            @foreach($cities as $c)
                                <option value="{{ $c }}" {{ $city === $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    @endif
                    <button type="submit"
                        class="px-6 py-3 rounded-xl sm:rounded-full bg-primary hover:bg-orange-600 text-white font-bold text-sm transition-all shadow-md shadow-orange-500/30 flex items-center justify-center gap-2 shrink-0">
                        <i class="mdi mdi-compass-outline text-base"></i>
                        <span>Procurar</span>
                    </button>
                </form>

                <!-- Filter Feedback and Quick City Badges -->
                <div class="mt-5 flex flex-wrap items-center justify-center gap-2 text-xs">
                    @if($search !== '' || $city !== '')
                        <div class="flex items-center gap-2 text-slate-300 mr-2">
                            <span>Filtro ativo:</span>
                            @if($search !== '') <span class="px-2 py-0.5 rounded-full bg-white/15 text-white font-mono">"{{ $search }}"</span> @endif
                            @if($city !== '') <span class="px-2 py-0.5 rounded-full bg-white/15 text-white font-mono">{{ $city }}</span> @endif
                            <a href="{{ route('welcome') }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-500/20 text-rose-300 hover:bg-rose-500/30 font-semibold transition-colors">
                                <i class="mdi mdi-close"></i> Limpar
                            </a>
                        </div>
                    @else
                        <span class="text-slate-400">Cidades na rede:</span>
                        <a href="{{ route('welcome') }}" class="px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-white font-medium transition-colors">Todas</a>
                        @foreach($cities as $c)
                            <a href="{{ route('welcome', ['cidade' => $c]) }}" class="px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-slate-200 transition-colors">{{ $c }}</a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Key Benefits Strip (shadcn style cards) -->
    <section class="border-b border-border bg-card py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-start gap-4 p-3 rounded-xl">
                    <div class="size-11 rounded-xl bg-orange-100 dark:bg-orange-950/60 text-orange-600 dark:text-orange-400 flex items-center justify-center shrink-0">
                        <i class="mdi mdi-qrcode-scan text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-foreground">Cardápio na Mesa via QR Code</h4>
                        <p class="text-xs text-muted-foreground mt-0.5 leading-relaxed">
                            Aceda instantaneamente ao menu sem esperar pelo garçom. Peça diretamente do seu smartphone.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-3 rounded-xl">
                    <div class="size-11 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                        <i class="mdi mdi-clock-fast text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-foreground">Pedidos & Takeaway em Tempo Real</h4>
                        <p class="text-xs text-muted-foreground mt-0.5 leading-relaxed">
                            Envie o pedido para a cozinha em segundos e acompanhe a preparação do prato passo a passo.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-3 rounded-xl">
                    <div class="size-11 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i class="mdi mdi-shield-check-outline text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-foreground">POS & Faturação Integrados</h4>
                        <p class="text-xs text-muted-foreground mt-0.5 leading-relaxed">
                            Tecnologia fiável que conecta mesas, cozinha e caixa com suporte offline e sincronização em nuvem.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 flex flex-col gap-10">

        @if($featuredRestaurant && $search === '' && $city === '')
            <!-- Spotlight / Featured Restaurant Card -->
            <div class="relative overflow-hidden rounded-3xl border border-orange-200/80 dark:border-orange-900/60 bg-gradient-to-br from-orange-50/80 via-white to-amber-50/50 dark:from-slate-900 dark:via-slate-900 dark:to-orange-950/30 p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-5 flex-1">
                        <div class="size-24 rounded-2xl bg-white p-2 shadow-md border border-border flex items-center justify-center shrink-0 overflow-hidden">
                            @if($featuredRestaurant['logo'])
                                <img src="{{ $featuredRestaurant['logo'] }}" alt="{{ $featuredRestaurant['name'] }}" class="size-full object-contain rounded-xl">
                            @else
                                <div class="size-full rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 text-white flex items-center justify-center font-black text-3xl">
                                    {{ strtoupper(substr($featuredRestaurant['name'], 0, 2)) }}
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-2">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-orange-500 text-white">
                                    <i class="mdi mdi-star"></i> Em Destaque
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                    <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    {{ $featuredRestaurant['opening_hours'] }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-secondary text-secondary-foreground">
                                    <i class="mdi mdi-map-marker text-orange-500"></i>
                                    {{ $featuredRestaurant['city'] }}
                                </span>
                            </div>

                            <h2 class="text-2xl sm:text-3xl font-extrabold text-foreground tracking-tight">
                                {{ $featuredRestaurant['name'] }}
                            </h2>
                            <p class="text-xs sm:text-sm text-muted-foreground mt-1 line-clamp-2 max-w-2xl leading-relaxed">
                                {{ $featuredRestaurant['hero_subtitle'] }}
                            </p>

                            @if($featuredRestaurant['categories']->isNotEmpty())
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-1.5 mt-3">
                                    @foreach($featuredRestaurant['categories'] as $catName)
                                        <span class="px-2.5 py-0.5 rounded-lg bg-orange-100/70 dark:bg-orange-950/60 text-orange-800 dark:text-orange-300 text-[11px] font-semibold">
                                            {{ $catName }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto shrink-0">
                        <a href="{{ $featuredRestaurant['short_url'] }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-primary hover:bg-orange-600 text-white text-sm font-bold transition-all shadow-md shadow-orange-500/25">
                            <i class="mdi mdi-book-open-page-variant-outline text-lg"></i>
                            <span>Abrir Cardápio Digital</span>
                            <i class="mdi mdi-arrow-right text-base"></i>
                        </a>

                        @if(!empty($featuredRestaurant['whatsapp_url']))
                            <a href="{{ $featuredRestaurant['whatsapp_url'] }}" target="_blank" rel="noopener"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold transition-all shadow-md shadow-emerald-500/20"
                                title="Falar com {{ $featuredRestaurant['name'] }} no WhatsApp">
                                <i class="mdi mdi-whatsapp text-xl"></i>
                                <span>WhatsApp</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Directory Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-foreground flex items-center gap-2">
                    <i class="mdi mdi-storefront text-primary"></i>
                    Restaurantes Disponíveis
                </h2>
                <p class="text-xs sm:text-sm text-muted-foreground mt-0.5">
                    Selecione um estabelecimento para abrir o respetivo cardápio digital e fazer pedidos
                </p>
            </div>

            <div class="flex items-center gap-3 text-xs text-muted-foreground bg-card px-4 py-2.5 rounded-2xl border border-border/80 shadow-xs">
                <i class="mdi mdi-qrcode-scan text-primary text-lg"></i>
                <span>No restaurante? Escaneie o <strong>QR Code da sua mesa</strong> para atendimento imediato.</span>
            </div>
        </div>

        <!-- Restaurants Cards Grid -->
        @if($restaurants->isEmpty())
            <div class="bg-card rounded-3xl border border-border p-12 text-center shadow-xs">
                <div class="size-16 rounded-2xl bg-orange-50 dark:bg-orange-950 text-primary flex items-center justify-center mx-auto mb-4">
                    <i class="mdi mdi-store-search-outline text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-foreground">Nenhum restaurante encontrado</h3>
                <p class="text-sm text-muted-foreground mt-1 max-w-md mx-auto">
                    Não encontrámos estabelecimentos com os critérios pesquisados. Limpe a pesquisa para ver todos os restaurantes da rede.
                </p>
                <a href="{{ route('welcome') }}"
                    class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary hover:bg-orange-600 text-white text-sm font-bold transition-all shadow-xs">
                    <i class="mdi mdi-refresh"></i> Ver Todos os Restaurantes
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($restaurants as $rest)
                    <article class="group bg-card rounded-3xl border border-border/80 hover:border-primary/50 shadow-xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col">
                        <!-- Top Accent Banner -->
                        <div class="relative h-24 bg-gradient-to-r from-slate-900 via-slate-800 to-stone-900 p-4 flex items-start justify-end overflow-hidden">
                            <!-- Subtle Ambient Glow -->
                            <div class="absolute -right-8 -top-8 size-24 rounded-full bg-orange-500/15 blur-xl pointer-events-none"></div>

                            <span class="relative z-10 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 backdrop-blur-md text-white border border-white/15 shadow-xs whitespace-nowrap">
                                <i class="mdi mdi-map-marker text-orange-400 text-xs"></i>
                                {{ $rest['city'] }}
                            </span>
                        </div>

                        <!-- Restaurant Identity & Content -->
                        <div class="px-6 pt-0 pb-6 flex-1 flex flex-col">
                            <div class="relative -mt-10 mb-4 flex items-end justify-between">
                                <div class="size-20 rounded-2xl bg-card p-1.5 shadow-lg border border-border flex items-center justify-center overflow-hidden shrink-0">
                                    @if($rest['logo'])
                                        <img src="{{ $rest['logo'] }}" alt="{{ $rest['name'] }}" class="size-full object-contain rounded-xl">
                                    @else
                                        <div class="size-full rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 text-white flex items-center justify-center font-black text-2xl">
                                            {{ strtoupper(substr($rest['name'], 0, 2)) }}
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 text-xs font-bold text-muted-foreground whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-xl bg-secondary text-secondary-foreground whitespace-nowrap" title="Produtos ativos no cardápio">
                                        <i class="mdi mdi-food-fork-drink text-primary mr-1"></i>{{ $rest['products_count'] }} itens
                                    </span>
                                    @if($rest['tables_count'] > 0)
                                        <span class="px-2.5 py-1 rounded-xl bg-secondary text-secondary-foreground whitespace-nowrap" title="Mesas com QR Code">
                                            <i class="mdi mdi-table-chair text-indigo-500 mr-1"></i>{{ $rest['tables_count'] }} mesas
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <h3 class="text-xl font-extrabold text-foreground group-hover:text-primary transition-colors">
                                <a href="{{ $rest['short_url'] }}">
                                    {{ $rest['name'] }}
                                </a>
                            </h3>

                            <p class="text-xs text-muted-foreground mt-1.5 line-clamp-2 leading-relaxed">
                                {{ $rest['hero_subtitle'] }}
                            </p>

                            @if($rest['categories']->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5 mt-4">
                                    @foreach($rest['categories'] as $catName)
                                        <span class="px-2.5 py-0.5 rounded-lg bg-orange-50 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300 text-[11px] font-semibold">
                                            {{ $catName }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-5 pt-4 border-t border-border flex flex-col gap-2 text-xs text-muted-foreground">
                                <div class="flex items-start gap-2">
                                    <i class="mdi mdi-map-marker-outline text-slate-400 text-sm mt-0.5 shrink-0"></i>
                                    <span class="truncate">{{ $rest['address'] }}</span>
                                </div>
                                @if(!empty($rest['whatsapp_url']))
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 truncate">
                                            <i class="mdi mdi-phone-outline text-slate-400 text-sm shrink-0"></i>
                                            <span class="truncate">{{ $rest['phone'] ?: 'Contacto disponível' }}</span>
                                        </div>
                                        <a href="{{ $rest['whatsapp_url'] }}" target="_blank" rel="noopener"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 font-bold text-[11px] transition-colors shrink-0"
                                            title="Conversar no WhatsApp">
                                            <i class="mdi mdi-whatsapp text-sm text-emerald-600 dark:text-emerald-400"></i>
                                            <span>WhatsApp</span>
                                        </a>
                                    </div>
                                @elseif($rest['phone'])
                                    <div class="flex items-center gap-2">
                                        <i class="mdi mdi-phone-outline text-slate-400 text-sm shrink-0"></i>
                                        <span>{{ $rest['phone'] }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6 pt-2 flex items-center gap-2 mt-auto">
                                <a href="{{ $rest['short_url'] }}"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 group-hover:bg-primary text-white text-xs sm:text-sm font-bold transition-all shadow-xs">
                                    <i class="mdi mdi-book-open-page-variant-outline text-base"></i>
                                    <span>Abrir Cardápio</span>
                                    <i class="mdi mdi-arrow-right text-base"></i>
                                </a>

                                @if(!empty($rest['whatsapp_url']))
                                    <a href="{{ $rest['whatsapp_url'] }}" target="_blank" rel="noopener"
                                        class="inline-flex items-center justify-center gap-1.5 px-3.5 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs sm:text-sm font-bold transition-all shadow-sm shadow-emerald-500/20 shrink-0"
                                        title="Falar no WhatsApp com {{ $rest['name'] }}">
                                        <i class="mdi mdi-whatsapp text-lg"></i>
                                        <span class="hidden sm:inline">WhatsApp</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        <!-- How It Works Section (Anchor #como-funciona) -->
        <section id="como-funciona" class="rounded-3xl border border-border bg-card p-8 sm:p-12 shadow-xs">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-orange-100 dark:bg-orange-950 text-primary">
                    Simples & Intuitivo
                </span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-foreground mt-3">
                    Como Fazer o seu Pedido em 3 Passos
                </h3>
                <p class="text-xs sm:text-sm text-muted-foreground mt-1.5">
                    Sem filas, sem aplicativos pesados obrigatórios e com total transparência.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <div class="flex flex-col items-center text-center p-4">
                    <div class="size-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-2xl font-black mb-4">
                        1
                    </div>
                    <h4 class="font-bold text-base text-foreground">Escolha ou Escaneie</h4>
                    <p class="text-xs text-muted-foreground mt-1.5 leading-relaxed">
                        Selecione o restaurante na lista ou aponte a câmara do telemóvel para o QR Code da mesa no local.
                    </p>
                </div>

                <div class="flex flex-col items-center text-center p-4">
                    <div class="size-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-2xl font-black mb-4">
                        2
                    </div>
                    <h4 class="font-bold text-base text-foreground">Monte o seu Pedido</h4>
                    <p class="text-xs text-muted-foreground mt-1.5 leading-relaxed">
                        Navegue pelas categorias, consulte preços, ingredientes e adicione observações personalizadas ao carrinho.
                    </p>
                </div>

                <div class="flex flex-col items-center text-center p-4">
                    <div class="size-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-2xl font-black mb-4">
                        3
                    </div>
                    <h4 class="font-bold text-base text-foreground">Acompanhe e Desfrute</h4>
                    <p class="text-xs text-muted-foreground mt-1.5 leading-relaxed">
                        Envie para a cozinha com 1 clique e receba o prato na sua mesa ou retire no balcão na hora combinada.
                    </p>
                </div>
            </div>
        </section>

        <!-- App Mobile Banner (shadcn card) -->
        <section class="rounded-3xl bg-gradient-to-br from-slate-900 to-slate-950 text-white p-8 sm:p-12 border border-slate-800 shadow-xl overflow-hidden relative">
            <div class="absolute -right-16 -bottom-16 size-80 rounded-full bg-primary/20 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="max-w-2xl text-center lg:text-left">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-orange-500/20 text-orange-300 border border-orange-400/30 mb-3">
                        <i class="mdi mdi-cellphone-check"></i>
                        App Android & PWA
                    </span>
                    <h3 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                        Leve o RestPOS no seu Smartphone
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-300 mt-2 leading-relaxed">
                        Tenha acesso direto aos cardápios, pedidos instantâneos e notificações em tempo real. Instale o APK oficial ou adicione diretamente ao ecrã principal.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0 w-full sm:w-auto">
                    <a href="{{ route('app.download') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm transition-all shadow-lg shadow-emerald-500/25">
                        <i class="mdi mdi-download text-lg"></i>
                        <span>Baixe a Nossa App</span>
                    </a>
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm transition-colors border border-white/15">
                        <i class="mdi mdi-login text-lg"></i>
                        <span>Acesso Operacional</span>
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-card border-t border-border py-10 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-6 text-xs text-muted-foreground">
            <div class="flex items-center gap-3">
                <div class="size-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <i class="mdi mdi-silverware-fork-knife text-base"></i>
                </div>
                <div>
                    <span class="font-bold text-foreground">RestPOS Suite</span>
                    <span class="text-muted-foreground"> — Plataforma de Gestão, POS, Cardápios Digitais & Faturação</span>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6">
                <a href="{{ route('welcome') }}" class="hover:text-primary transition-colors">Início</a>
                <a href="#como-funciona" class="hover:text-primary transition-colors">Como Funciona</a>
                <a href="{{ route('app.download') }}" class="text-primary hover:text-orange-600 font-semibold inline-flex items-center gap-1 transition-colors">
                    <i class="mdi mdi-cellphone-check text-emerald-500"></i>
                    <span>Baixe a Nossa App</span>
                </a>
                <span>&copy; {{ date('Y') }} RestPOS. Todos os direitos reservados.</span>
            </div>
        </div>
    </footer>

</body>
</html>
