<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth h-full">

@php
    $companyName = \App\Models\Setting::get('company_name', 'Café & Restaurante');
    $companySubtitle = \App\Models\Setting::get('company_subtitle', 'Cardápio Digital & Atendimento');
    $primaryColor = \App\Models\Setting::get('primary_color', '#f97316');
    $currency = \App\Models\Setting::get('currency_symbol', 'MT');
    $logo = \App\Models\Setting::get('system_logo');
    $favicon = \App\Models\Setting::get('system_favicon');
    $loginBg = \App\Models\Setting::get('login_background');
    $heroBg = $loginBg && file_exists(public_path('storage/' . $loginBg))
        ? asset('storage/' . $loginBg)
        : (file_exists(public_path('assets/images/restaurant-bg.jpeg'))
            ? asset('assets/images/restaurant-bg.jpeg')
            : 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&q=80&w=2000');

    // Flatten all active products
    $allProducts = [];
    foreach ($categories as $cat) {
        foreach ($cat->products as $p) {
            $allProducts[] = [
                'id' => $p->id,
                'name' => $p->name,
                'price' => (float)($p->price ?? $p->selling_price),
                'price_formatted' => number_format((float)($p->price ?? $p->selling_price), 2, '.', ' ') . ' ' . $currency,
                'category_id' => $cat->id,
                'category_name' => $cat->name,
                'description' => $p->description ?? '',
                'image_url' => $p->image_url,
                'stock' => $p->stock_quantity ?? 99,
            ];
        }
    }
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $companyName }} - Cardápio Digital</title>

    <!-- Favicon -->
    @if($favicon && file_exists(public_path('storage/' . $favicon)))
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Material Design Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">

    <!-- Tailwind & Alpine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '{{ $primaryColor }}',
                            600: '{{ $primaryColor }}',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 font-sans min-h-screen flex flex-col selection:bg-orange-500 selection:text-white"
      x-data="restaurantMenuApp()"
      x-cloak>

    <!-- Header Principal -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-xs transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                
                <!-- Logo & Nome do Restaurante -->
                <div class="flex items-center gap-3.5">
                    @if($logo && file_exists(public_path('storage/' . $logo)))
                        <img src="{{ asset('storage/' . $logo) }}" alt="{{ $companyName }}" class="h-12 w-auto max-w-[130px] object-contain">
                    @else
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center text-white shadow-md shadow-orange-500/20">
                            <i class="mdi mdi-silverware-fork-knife text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-lg md:text-xl font-black text-gray-900 tracking-tight">{{ $companyName }}</h1>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                Aberto
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 font-medium">
                            {{ $companySubtitle }}
                        </p>
                    </div>
                </div>

                <!-- Barra de Pesquisa Rápida (Desktop) -->
                <div class="hidden md:flex flex-1 max-w-md mx-4">
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="mdi mdi-magnify text-xl"></i>
                        </span>
                        <input type="text"
                               x-model="searchQuery"
                               placeholder="O que você deseja saborear hoje?"
                               class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-gray-100/90 border-0 text-xs font-semibold text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-orange-500 transition-all">
                        <button x-show="searchQuery" @click="searchQuery = ''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i class="mdi mdi-close-circle text-base"></i>
                        </button>
                    </div>
                </div>

                <!-- Botões de Ação do Cliente -->
                <div class="flex items-center gap-2.5">
                    
                    <!-- Mesa Detectada se veio via QR Code -->
                    <template x-if="tableNumber">
                        <div class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-50 text-orange-700 font-bold text-xs border border-orange-200">
                            <i class="mdi mdi-map-marker-radius"></i>
                            <span x-text="'Mesa ' + tableNumber"></span>
                        </div>
                    </template>

                    <!-- Botão Chamar Garçom -->
                    <button type="button"
                            @click="openWaiterModal()"
                            class="px-3 sm:px-4 py-2.5 rounded-2xl bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs border border-amber-200/80 transition-all flex items-center gap-1.5 cursor-pointer"
                            title="Solicitar atendimento na mesa">
                        <i class="mdi mdi-bell-ring-outline text-base text-amber-600 animate-bounce"></i>
                        <span class="hidden sm:inline">Chamar Atendente</span>
                    </button>

                    <!-- Botão da Comanda / Carrinho -->
                    <button type="button"
                            @click="cartOpen = true"
                            style="background-color: {{ $primaryColor }};"
                            class="relative text-white font-extrabold px-4 py-2.5 rounded-2xl shadow-lg shadow-orange-500/20 hover:opacity-95 active:scale-95 transition-all flex items-center gap-2 cursor-pointer">
                        <i class="mdi mdi-silverware text-xl"></i>
                        <span class="hidden sm:inline text-xs">Comanda</span>
                        <span class="bg-white text-gray-900 text-[11px] font-black px-2 py-0.5 rounded-full shadow-2xs"
                              x-text="cartCount">
                        </span>
                        <span class="hidden lg:inline text-xs border-l border-white/30 pl-2"
                              x-text="cartTotalFormatted">
                        </span>
                    </button>

                    <!-- Acesso Staff / Login -->
                    @auth
                        <a href="{{ url('/home') }}" class="p-2.5 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition-all" title="Painel Administrativo">
                            <i class="mdi mdi-view-dashboard-outline text-xl"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="p-2.5 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition-all" title="Acesso dos Funcionários">
                            <i class="mdi mdi-account-circle-outline text-xl"></i>
                        </a>
                    @endauth
                </div>

            </div>

            <!-- Barra de Pesquisa (Mobile) -->
            <div class="md:hidden pb-3">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="mdi mdi-magnify text-lg"></i>
                    </span>
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="O que você deseja saborear hoje?"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-100 border-0 text-xs font-semibold text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-orange-500">
                </div>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal / Cardápio -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Banner de Boas-Vindas do Restaurante -->
        <div class="relative rounded-3xl overflow-hidden shadow-sm bg-gradient-to-r from-gray-950 via-gray-900 to-gray-950 text-white p-6 sm:p-8">
            <div class="absolute inset-0 opacity-25 bg-cover bg-center pointer-events-none" style="background-image: url('{{ $heroBg }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/75 to-transparent"></div>
            
            <div class="relative z-10 max-w-xl space-y-2.5">
                <template x-if="tableNumber">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-orange-500 text-white">
                        <i class="mdi mdi-table-chair"></i> Você está na Mesa <strong x-text="tableNumber"></strong>
                    </span>
                </template>
                <template x-if="!tableNumber">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/10 backdrop-blur-md text-orange-400 border border-white/15">
                        <i class="mdi mdi-silverware-clean"></i> Cardápio Oficial
                    </span>
                </template>

                <h2 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-white leading-tight">
                    Bem-vindo! Escolha e peça com facilidade.
                </h2>
                <p class="text-xs sm:text-sm text-gray-300 font-medium">
                    Explore os nossos pratos e bebidas preparados com ingredientes selecionados. Adicione os itens à sua comanda e envie diretamente para a nossa equipe.
                </p>
            </div>
        </div>

        <!-- Seletor Horizontal de Categorias (Pills Fixas) -->
        <div class="sticky top-20 z-30 bg-gray-50/95 backdrop-blur-sm py-2">
            <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar pb-1">
                <button type="button"
                        @click="selectedCategory = 'all'"
                        :class="selectedCategory === 'all' ? 'bg-gray-900 text-white shadow-md shadow-gray-900/20' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200/80'"
                        class="px-5 py-2.5 rounded-2xl text-xs font-black transition-all flex items-center gap-2 flex-shrink-0 cursor-pointer">
                    <i class="mdi mdi-view-grid-outline text-base"></i>
                    <span>Todos os Itens</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px]"
                          :class="selectedCategory === 'all' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500'"
                          x-text="products.length">
                    </span>
                </button>

                @foreach($categories as $category)
                    <button type="button"
                            @click="selectedCategory = {{ $category->id }}"
                            :class="selectedCategory === {{ $category->id }} ? 'bg-orange-500 text-white shadow-md shadow-orange-500/20' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200/80'"
                            class="px-5 py-2.5 rounded-2xl text-xs font-black transition-all flex items-center gap-2 flex-shrink-0 cursor-pointer">
                        <i class="mdi mdi-food-fork-drink text-base"></i>
                        <span>{{ $category->name }}</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px]"
                              :class="selectedCategory === {{ $category->id }} ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500'">
                            {{ $category->products->count() }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Vitrine de Produtos do Cardápio -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-black text-gray-900 tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-orange-500 rounded-full"></span>
                    <span x-text="categoryTitle"></span>
                </h3>
                <span class="text-xs text-gray-500 font-semibold" x-text="filteredProducts.length + ' opções disponíveis'"></span>
            </div>

            <!-- Grade de Pratos e Bebidas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                        
                        <!-- Foto do Prato -->
                        <div class="relative h-44 bg-gray-100 overflow-hidden cursor-pointer" @click="openItemModal(product)">
                            <template x-if="product.image_url">
                                <img :src="product.image_url"
                                     :alt="product.name"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </template>
                            <template x-if="!product.image_url">
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gradient-to-br from-gray-50 to-gray-100">
                                    <i class="mdi mdi-silverware-fork-knife text-5xl"></i>
                                    <span class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-wider">Foto em Breve</span>
                                </div>
                            </template>

                            <!-- Badge da Categoria -->
                            <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-gray-800 text-[10px] font-black px-2.5 py-1 rounded-xl shadow-2xs"
                                  x-text="product.category_name">
                            </span>

                            <!-- Quantidade no Carrinho -->
                            <template x-if="getCartQty(product.id) > 0">
                                <span class="absolute top-3 right-3 bg-orange-500 text-white text-[11px] font-black w-7 h-7 rounded-full flex items-center justify-center shadow-lg shadow-orange-500/40">
                                    <span x-text="getCartQty(product.id)"></span>
                                </span>
                            </template>
                        </div>

                        <!-- Detalhes do Produto -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 group-hover:text-orange-600 transition-colors line-clamp-1 cursor-pointer"
                                    @click="openItemModal(product)"
                                    x-text="product.name"></h4>
                                <p class="text-xs text-gray-400 mt-1 line-clamp-2 min-h-[32px]"
                                   x-text="product.description || 'Preparado na hora com ingredientes frescos e selecionados.'"></p>
                            </div>

                            <!-- Preço e Botão Adicionar -->
                            <div class="flex items-center justify-between pt-4 mt-2 border-t border-gray-100">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Preço</span>
                                    <span class="text-base font-black text-gray-900" x-text="product.price_formatted"></span>
                                </div>

                                <!-- Ações de Adicionar / Contador -->
                                <template x-if="getCartQty(product.id) === 0">
                                    <button type="button"
                                            @click="addToCart(product)"
                                            class="px-3.5 py-2 rounded-xl bg-orange-50 hover:bg-orange-500 text-orange-600 hover:text-white font-bold text-xs transition-all flex items-center gap-1.5 cursor-pointer">
                                        <i class="mdi mdi-plus text-base"></i>
                                        <span>Pedir</span>
                                    </button>
                                </template>

                                <template x-if="getCartQty(product.id) > 0">
                                    <div class="flex items-center gap-1.5 bg-orange-500 text-white rounded-xl p-1 shadow-xs">
                                        <button type="button"
                                                @click="decreaseQty(product.id)"
                                                class="w-6 h-6 rounded-lg bg-orange-600 hover:bg-orange-700 flex items-center justify-center text-xs font-black transition-colors">
                                            <i class="mdi mdi-minus"></i>
                                        </button>
                                        <span class="text-xs font-black px-1.5" x-text="getCartQty(product.id)"></span>
                                        <button type="button"
                                                @click="addToCart(product)"
                                                class="w-6 h-6 rounded-lg bg-orange-600 hover:bg-orange-700 flex items-center justify-center text-xs font-black transition-colors">
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </template>
            </div>

            <!-- Caso Nenhum Item Encontrado -->
            <div x-show="filteredProducts.length === 0" class="py-16 text-center space-y-3 bg-white rounded-3xl border border-gray-100">
                <div class="w-16 h-16 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center text-3xl">
                    <i class="mdi mdi-food-off"></i>
                </div>
                <h4 class="font-bold text-gray-800 text-sm">Nenhum item encontrado</h4>
                <p class="text-xs text-gray-400 max-w-sm mx-auto">
                    Tente buscar por outro termo ou selecione uma categoria diferente acima.
                </p>
                <button type="button" @click="searchQuery = ''; selectedCategory = 'all'"
                        class="px-4 py-2 rounded-xl bg-orange-500 text-white font-bold text-xs hover:bg-orange-600 transition-colors">
                    Ver Todos os Itens
                </button>
            </div>
        </div>

    </main>

    <!-- Botão Flutuante Inferior (Mobile) -->
    <div x-show="cartCount > 0"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="transform translate-y-full opacity-0"
         x-transition:enter-end="transform translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="transform translate-y-0 opacity-100"
         x-transition:leave-end="transform translate-y-full opacity-0"
         class="fixed bottom-4 inset-x-4 z-40 max-w-lg mx-auto sm:hidden">
        <button type="button"
                @click="cartOpen = true"
                style="background-color: {{ $primaryColor }};"
                class="w-full py-4 px-6 rounded-3xl text-white font-extrabold shadow-2xl flex items-center justify-between gap-4 cursor-pointer">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-white text-gray-900 font-black text-xs flex items-center justify-center"
                      x-text="cartCount">
                </span>
                <span class="text-xs font-bold">Ver Comanda</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm font-black" x-text="cartTotalFormatted"></span>
                <i class="mdi mdi-chevron-right text-xl"></i>
            </div>
        </button>
    </div>

    <!-- Gaveta Lateral da Comanda (Slide-Over) -->
    <div x-show="cartOpen"
         class="fixed inset-0 z-50 overflow-hidden"
         aria-labelledby="slide-over-title"
         role="dialog"
         aria-modal="true">
        
        <!-- Fundo Escuro -->
        <div x-show="cartOpen"
             x-transition:enter="ease-in-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in-out duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity"
             @click="cartOpen = false"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div x-show="cartOpen"
                 x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-md bg-white shadow-2xl flex flex-col justify-between">
                
                <!-- Topo da Comanda -->
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/70">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-orange-500/10 text-orange-500 flex items-center justify-center text-xl">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-gray-900">Sua Comanda</h3>
                            <p class="text-xs text-gray-400" x-text="cartCount + ' item(ns) selecionado(s)'"></p>
                        </div>
                    </div>
                    <button type="button" @click="cartOpen = false" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl transition-colors cursor-pointer">
                        <i class="mdi mdi-close text-2xl"></i>
                    </button>
                </div>

                <!-- Lista de Itens do Pedido -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    
                    <!-- Comanda Vazia -->
                    <template x-if="cart.length === 0">
                        <div class="py-20 text-center space-y-3">
                            <div class="w-20 h-20 rounded-full bg-orange-50 text-orange-400 mx-auto flex items-center justify-center text-4xl">
                                <i class="mdi mdi-silverware-fork-knife"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-sm">Sua comanda está vazia</h4>
                            <p class="text-xs text-gray-400 max-w-xs mx-auto">
                                Toque em "Pedir" em qualquer prato ou bebida do menu para adicionar à sua comanda.
                            </p>
                            <button type="button" @click="cartOpen = false"
                                    class="px-5 py-2.5 rounded-xl bg-gray-900 text-white font-bold text-xs hover:bg-black transition-colors cursor-pointer">
                                Explorar o Menu
                            </button>
                        </div>
                    </template>

                    <!-- Itens Selecionados -->
                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100 flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <template x-if="item.image_url">
                                    <img :src="item.image_url" :alt="item.name" class="w-12 h-12 rounded-xl object-cover border border-gray-200 flex-shrink-0">
                                </template>
                                <template x-if="!item.image_url">
                                    <div class="w-12 h-12 rounded-xl bg-gray-200 text-gray-400 flex items-center justify-center text-xl flex-shrink-0">
                                        <i class="mdi mdi-silverware"></i>
                                    </div>
                                </template>

                                <div class="flex-1 min-w-0">
                                    <h5 class="text-xs font-bold text-gray-900 truncate" x-text="item.name"></h5>
                                    <span class="text-xs font-black text-orange-600" x-text="formatMoney(item.price * item.quantity)"></span>

                                    <!-- Observações do Item -->
                                    <template x-if="item.notes">
                                        <p class="text-[10px] text-amber-600 font-medium italic mt-0.5 flex items-center gap-1">
                                            <i class="mdi mdi-note-text-outline"></i>
                                            <span x-text="item.notes"></span>
                                        </p>
                                    </template>
                                </div>
                            </div>

                            <!-- Controles de Quantidade & Excluir -->
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-xl p-0.5 shadow-2xs">
                                    <button type="button" @click="decreaseQty(item.id)" class="w-6 h-6 rounded-lg text-gray-600 hover:bg-gray-100 flex items-center justify-center text-xs">
                                        <i class="mdi mdi-minus"></i>
                                    </button>
                                    <span class="text-xs font-bold px-1" x-text="item.quantity"></span>
                                    <button type="button" @click="addToCart(item)" class="w-6 h-6 rounded-lg text-gray-600 hover:bg-gray-100 flex items-center justify-center text-xs">
                                        <i class="mdi mdi-plus"></i>
                                    </button>
                                </div>
                                <button type="button" @click="removeFromCart(index)" class="text-gray-300 hover:text-rose-500 transition-colors p-1" title="Remover item">
                                    <i class="mdi mdi-trash-can-outline text-base"></i>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Identificação do Pedido (Processo Lógico de Restaurante) -->
                    <template x-if="cart.length > 0">
                        <div class="space-y-4 pt-4 border-t border-gray-200">
                            
                            <!-- Tipo de Atendimento: Mesa ou Levar -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Como deseja o atendimento?</label>
                                <div class="grid grid-cols-2 gap-2 p-1 bg-gray-100 rounded-2xl">
                                    <button type="button"
                                            @click="serviceType = 'dine_in'"
                                            :class="serviceType === 'dine_in' ? 'bg-white text-gray-900 shadow-xs font-black' : 'text-gray-500 font-bold'"
                                            class="py-2.5 px-3 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                                        <i class="mdi mdi-silverware-fork-knife text-orange-500"></i>
                                        <span>Consumo na Mesa</span>
                                    </button>
                                    <button type="button"
                                            @click="serviceType = 'takeaway'; selectedTableId = ''; tableNumber = ''"
                                            :class="serviceType === 'takeaway' ? 'bg-white text-gray-900 shadow-xs font-black' : 'text-gray-500 font-bold'"
                                            class="py-2.5 px-3 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                                        <i class="mdi mdi-shopping-outline text-orange-500"></i>
                                        <span>Para Levar (Takeaway)</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Caso Consumo na Mesa -->
                            <template x-if="serviceType === 'dine_in'">
                                <div class="space-y-3">
                                    <!-- Localização: Mesa Real do Restaurante -->
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1 flex items-center justify-between">
                                            <span>Mesa de Atendimento</span>
                                            <template x-if="selectedTableId">
                                                <span class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                                    Mesa Selecionada
                                                </span>
                                            </template>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                                <i class="mdi mdi-table-chair text-lg"></i>
                                            </span>
                                            <select x-model="selectedTableId"
                                                    @change="onTableChange($event.target.value)"
                                                    class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold focus:bg-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                                <option value="">Balcão / Salão Geral</option>
                                                @foreach($tables as $table)
                                                    <option value="{{ $table->id }}">
                                                        Mesa {{ $table->number }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Alerta de Comanda Cumulativa se houver pedido ativo -->
                                    <template x-if="isAppendingToActiveComanda">
                                        <div class="p-3 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center gap-2.5">
                                            <i class="mdi mdi-clipboard-text-clock text-xl text-amber-600 flex-shrink-0"></i>
                                            <div class="text-[11px] leading-tight">
                                                <span class="font-extrabold text-amber-900">Comanda Aberta #<span x-text="trackedOrder ? trackedOrder.id : ''"></span></span>
                                                <p class="text-amber-700 mt-0.5">Estes novos itens serão somados diretamente à sua comanda em andamento.</p>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Nome do Cliente (Opcional) -->
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1">
                                            Seu Nome <span class="text-gray-400 font-normal">(opcional)</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                                <i class="mdi mdi-account-outline text-lg"></i>
                                            </span>
                                            <input type="text"
                                                   x-model="customerName"
                                                   placeholder="Como deseja ser chamado?"
                                                   class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold focus:bg-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                        </div>
                                    </div>

                                    <!-- Forma de Pagamento no Local -->
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1">Pagamento</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <button type="button"
                                                    @click="paymentMethod = 'counter'"
                                                    :class="paymentMethod === 'counter' ? 'border-orange-500 bg-orange-50/50 text-orange-950 font-black' : 'border-gray-200 bg-gray-50 text-gray-600 font-medium'"
                                                    class="p-2.5 rounded-xl border text-left text-xs transition-all flex items-center gap-2 cursor-pointer">
                                                <i class="mdi mdi-receipt text-orange-500 text-base"></i>
                                                <span class="text-[11px] leading-tight">Pagar no Final (Comanda)</span>
                                            </button>
                                            <button type="button"
                                                    @click="paymentMethod = 'mpesa'"
                                                    :class="paymentMethod === 'mpesa' ? 'border-orange-500 bg-orange-50/50 text-orange-950 font-black' : 'border-gray-200 bg-gray-50 text-gray-600 font-medium'"
                                                    class="p-2.5 rounded-xl border text-left text-xs transition-all flex items-center gap-2 cursor-pointer">
                                                <i class="mdi mdi-cellphone-wireless text-red-500 text-base"></i>
                                                <span class="text-[11px] leading-tight">M-Pesa / E-Mola</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Caso Para Levar / Takeaway -->
                            <template x-if="serviceType === 'takeaway'">
                                <div class="space-y-3">
                                    <!-- Nome do Cliente -->
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1">
                                            Seu Nome para Retirada <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                                <i class="mdi mdi-account-outline text-lg"></i>
                                            </span>
                                            <input type="text"
                                                   x-model="customerName"
                                                   placeholder="Nome de quem vai retirar"
                                                   class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold focus:bg-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                        </div>
                                    </div>

                                    <!-- Telefone para Aviso de Pronto -->
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1">
                                            Telefone (para aviso quando estiver pronto)
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                                <i class="mdi mdi-phone-outline text-lg"></i>
                                            </span>
                                            <input type="tel"
                                                   x-model="customerPhone"
                                                   placeholder="Ex: 84 123 4567"
                                                   class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold focus:bg-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                        </div>
                                    </div>

                                    <!-- Pagamento Takeaway -->
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1">Forma de Pagamento</label>
                                        <div class="grid grid-cols-3 gap-1.5">
                                            <button type="button"
                                                    @click="paymentMethod = 'counter'"
                                                    :class="paymentMethod === 'counter' ? 'border-orange-500 bg-orange-50 text-orange-900 font-bold' : 'border-gray-200 bg-gray-50 text-gray-600'"
                                                    class="py-2 px-1.5 rounded-xl border text-center text-[10px] leading-tight transition-all cursor-pointer">
                                                <i class="mdi mdi-cash text-sm block mb-0.5 text-emerald-600"></i>
                                                No Balcão
                                            </button>
                                            <button type="button"
                                                    @click="paymentMethod = 'mpesa'"
                                                    :class="paymentMethod === 'mpesa' ? 'border-red-500 bg-red-50 text-red-900 font-bold' : 'border-gray-200 bg-gray-50 text-gray-600'"
                                                    class="py-2 px-1.5 rounded-xl border text-center text-[10px] leading-tight transition-all cursor-pointer">
                                                <i class="mdi mdi-cellphone-wireless text-sm block mb-0.5 text-red-600"></i>
                                                M-Pesa
                                            </button>
                                            <button type="button"
                                                    @click="paymentMethod = 'emola'"
                                                    :class="paymentMethod === 'emola' ? 'border-orange-500 bg-orange-50 text-orange-900 font-bold' : 'border-gray-200 bg-gray-50 text-gray-600'"
                                                    class="py-2 px-1.5 rounded-xl border text-center text-[10px] leading-tight transition-all cursor-pointer">
                                                <i class="mdi mdi-cellphone-message text-sm block mb-0.5 text-orange-600"></i>
                                                E-Mola
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Observações para a Cozinha -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 mb-1">
                                    Observações do Pedido <span class="text-gray-400 font-normal">(opcional)</span>
                                </label>
                                <textarea x-model="orderNotes"
                                          rows="2"
                                          placeholder="Ex: Ponto da carne, sem cebola, gelo e limão..."
                                          class="w-full px-3.5 py-2 rounded-xl border border-gray-200 bg-gray-50 text-xs font-medium focus:bg-white focus:ring-2 focus:ring-orange-500"></textarea>
                            </div>
                        </div>
                    </template>

                </div>

                <!-- Rodapé da Comanda: Total e Envio -->
                <template x-if="cart.length > 0">
                    <div class="p-6 bg-gray-50 border-t border-gray-100 space-y-4">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>Subtotal dos Itens Selecionados</span>
                                <span class="font-bold" x-text="cartTotalFormatted"></span>
                            </div>
                            <div class="flex items-center justify-between text-base font-black text-gray-900 pt-1 border-t border-gray-200/60">
                                <span>Total</span>
                                <span class="text-xl text-orange-600" x-text="cartTotalFormatted"></span>
                            </div>
                        </div>

                        <!-- Botão Enviar Pedido -->
                        <button type="button"
                                @click="submitOrder()"
                                :disabled="submitting"
                                style="background-color: {{ $primaryColor }};"
                                class="w-full py-4 rounded-2xl text-white font-extrabold text-sm shadow-xl shadow-orange-500/20 hover:opacity-95 active:scale-95 transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                            <template x-if="!submitting">
                                <span class="flex items-center gap-2">
                                    <i class="mdi text-lg" :class="isAppendingToActiveComanda ? 'mdi-plus-circle-outline' : 'mdi-check-circle-outline'"></i>
                                    <span x-text="isAppendingToActiveComanda ? 'Adicionar Itens à Minha Comanda' : 'Enviar Meu Pedido para a Cozinha'"></span>
                                </span>
                            </template>
                            <template x-if="submitting">
                                <span class="flex items-center gap-2">
                                    <i class="mdi mdi-loading animate-spin text-lg"></i>
                                    <span>Transmitindo Pedido...</span>
                                </span>
                            </template>
                        </button>
                    </div>
                </template>

            </div>
        </div>
    </div>

    <!-- Modal de Observação do Prato ao Clicar -->
    <div x-show="itemModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="itemModalOpen = false"></div>
        <div class="relative bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 z-10">
            <template x-if="modalItem">
                <div>
                    <div class="flex items-start justify-between pb-3 border-b border-gray-100">
                        <div>
                            <h3 class="text-base font-black text-gray-900" x-text="modalItem.name"></h3>
                            <span class="text-sm font-black text-orange-600" x-text="modalItem.price_formatted"></span>
                        </div>
                        <button @click="itemModalOpen = false" class="text-gray-400 hover:text-gray-600"><i class="mdi mdi-close text-2xl"></i></button>
                    </div>

                    <div class="py-3 space-y-3">
                        <template x-if="modalItem.image_url">
                            <img :src="modalItem.image_url" class="w-full h-44 object-cover rounded-2xl">
                        </template>
                        <p class="text-xs text-gray-500" x-text="modalItem.description || 'Prato preparado na hora com os melhores ingredientes.'"></p>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">
                                Alguma preferência especial neste prato?
                            </label>
                            <input type="text"
                                   x-model="modalItemNotes"
                                   placeholder="Ex: Sem cebola, molho à parte, bem passado..."
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-xs font-semibold focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="button"
                                @click="confirmModalAdd()"
                                class="w-full py-3.5 rounded-2xl bg-orange-500 text-white font-extrabold text-xs shadow-md shadow-orange-500/20 hover:bg-orange-600 transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <i class="mdi mdi-plus"></i>
                            <span>Adicionar à Comanda</span>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Modal Chamar Garçom -->
    <div x-show="waiterModalOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="waiterModalOpen = false"></div>
        <div class="relative bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl text-center space-y-4 z-10">
            <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-500 mx-auto flex items-center justify-center text-3xl">
                <i class="mdi mdi-bell-ring-outline"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-gray-900">Chamar Atendente</h3>
                <p class="text-xs text-gray-400 mt-1">Um dos nossos garçons virá atendê-lo imediatamente.</p>
            </div>

            <div class="text-left space-y-2">
                <label class="block text-[11px] font-bold text-gray-700">Onde você está sentado?</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="mdi mdi-table-chair text-lg"></i>
                    </span>
                    <select x-model="selectedTableId"
                            @change="onTableChange($event.target.value)"
                            class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold focus:ring-2 focus:ring-orange-500">
                        <option value="">Balcão / Salão Geral</option>
                        @foreach($tables as $table)
                            <option value="{{ $table->id }}">Mesa {{ $table->number }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-2 flex gap-2">
                <button type="button" @click="waiterModalOpen = false" class="flex-1 py-3 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-200">
                    Cancelar
                </button>
                <button type="button" @click="confirmCallWaiter()" class="flex-1 py-3 rounded-xl bg-orange-500 text-white text-xs font-bold hover:bg-orange-600 shadow-md shadow-orange-500/20">
                    Chamar Agora
                </button>
            </div>
        </div>
    </div>

    <!-- Botão Flutuante de Acompanhamento do Pedido (Ativo) -->
    <template x-if="activeOrderId && trackedOrder && trackedOrder.status_step < 4">
        <div class="fixed bottom-20 sm:bottom-6 left-1/2 -translate-x-1/2 z-40 max-w-sm w-[92%] sm:w-auto">
            <button type="button"
                    @click="trackerModalOpen = true"
                    class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-gray-900/95 text-white shadow-2xl backdrop-blur-md border border-gray-700/80 flex items-center justify-between sm:justify-start gap-3 text-xs font-bold hover:bg-black active:scale-95 transition-all cursor-pointer">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                              :class="trackedOrder.status === 'ready' ? 'bg-emerald-400' : 'bg-orange-400'"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3"
                              :class="trackedOrder.status === 'ready' ? 'bg-emerald-500' : 'bg-orange-500'"></span>
                    </span>
                    <span class="font-black">Pedido #<span x-text="trackedOrder.id"></span></span>
                    <span class="text-gray-400">•</span>
                    <span class="text-orange-400" x-text="trackedOrder.status_label"></span>
                </div>
                <div class="flex items-center gap-1 text-[11px] text-gray-300 font-semibold bg-white/10 px-2 py-0.5 rounded-lg">
                    <span>Acompanhar</span>
                    <i class="mdi mdi-chevron-right text-base"></i>
                </div>
            </button>
        </div>
    </template>

    <!-- Modal Interativo de Acompanhamento do Pedido em Tempo Real -->
    <div x-show="trackerModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="fixed inset-0 bg-gray-950/80 backdrop-blur-sm" @click="trackerModalOpen = false"></div>

        <div class="relative bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl space-y-5 z-10 my-8 overflow-hidden">
            
            <!-- Topo do Modal -->
            <div class="flex items-start justify-between border-b border-gray-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-xs"
                         :class="{
                             'bg-amber-100 text-amber-600': !trackedOrder || trackedOrder.status_step === 1,
                             'bg-sky-100 text-sky-600': trackedOrder && trackedOrder.status_step === 2,
                             'bg-emerald-100 text-emerald-600 animate-bounce': trackedOrder && trackedOrder.status_step === 3,
                             'bg-purple-100 text-purple-600': trackedOrder && trackedOrder.status_step >= 4
                         }">
                        <i class="mdi"
                           :class="{
                               'mdi-clock-outline': !trackedOrder || trackedOrder.status_step === 1,
                               'mdi-fire': trackedOrder && trackedOrder.status_step === 2,
                               'mdi-bell-ring-outline': trackedOrder && trackedOrder.status_step === 3,
                               'mdi-check-decagram': trackedOrder && trackedOrder.status_step >= 4
                           }"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-black text-gray-900">
                                Pedido #<span x-text="trackedOrder ? trackedOrder.id : activeOrderId"></span>
                            </h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-orange-100 text-orange-800"
                                  x-text="trackedOrder ? trackedOrder.table_name : 'Mesa'">
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 font-medium mt-0.5" x-text="trackedOrder ? ('Cliente: ' + trackedOrder.customer_name) : 'Acompanhamento do Pedido'"></p>
                    </div>
                </div>

                <button type="button" @click="trackerModalOpen = false" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl transition-colors cursor-pointer">
                    <i class="mdi mdi-close text-2xl"></i>
                </button>
            </div>

            <template x-if="trackedOrder">
                <div class="space-y-5">
                    
                    <!-- Timeline Visual de 4 Etapas -->
                    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                        <div class="relative flex items-center justify-between">
                            <!-- Linha Conectora de Fundo -->
                            <div class="absolute left-4 right-4 top-4 -translate-y-1/2 h-1 bg-gray-200 -z-0"></div>
                            
                            <!-- Linha de Progresso Ativa -->
                            <div class="absolute left-4 top-4 -translate-y-1/2 h-1 bg-orange-500 -z-0 transition-all duration-500"
                                 :style="'width: ' + ((trackedOrder.status_step - 1) / 3 * 100) + '%; max-width: calc(100% - 32px);'"></div>

                            <!-- Etapa 1: Recebido -->
                            <div class="relative z-10 flex flex-col items-center text-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all"
                                     :class="trackedOrder.status_step >= 1 ? 'bg-orange-500 border-orange-500 text-white' : 'bg-white border-gray-300 text-gray-400'">
                                    <i class="mdi mdi-check"></i>
                                </div>
                                <span class="text-[10px] font-bold mt-1.5"
                                      :class="trackedOrder.status_step >= 1 ? 'text-gray-900' : 'text-gray-400'">Recebido</span>
                                <span class="text-[9px] text-gray-400" x-text="trackedOrder.created_at_fmt"></span>
                            </div>

                            <!-- Etapa 2: Preparando -->
                            <div class="relative z-10 flex flex-col items-center text-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all"
                                     :class="trackedOrder.status_step >= 2 ? (trackedOrder.status_step === 2 ? 'bg-sky-500 border-sky-500 text-white ring-4 ring-sky-100 animate-pulse' : 'bg-orange-500 border-orange-500 text-white') : 'bg-white border-gray-300 text-gray-400'">
                                    <i class="mdi mdi-fire"></i>
                                </div>
                                <span class="text-[10px] font-bold mt-1.5"
                                      :class="trackedOrder.status_step >= 2 ? 'text-gray-900' : 'text-gray-400'">No Fogo</span>
                                <span class="text-[9px] text-gray-400" x-text="trackedOrder.preparing_at_fmt || '--:--'"></span>
                            </div>

                            <!-- Etapa 3: Pronto -->
                            <div class="relative z-10 flex flex-col items-center text-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all"
                                     :class="trackedOrder.status_step >= 3 ? (trackedOrder.status_step === 3 ? 'bg-emerald-500 border-emerald-500 text-white ring-4 ring-emerald-100 animate-bounce' : 'bg-orange-500 border-orange-500 text-white') : 'bg-white border-gray-300 text-gray-400'">
                                    <i class="mdi mdi-bell-ring-outline"></i>
                                </div>
                                <span class="text-[10px] font-bold mt-1.5"
                                      :class="trackedOrder.status_step >= 3 ? 'text-emerald-700 font-black' : 'text-gray-400'">Pronto!</span>
                                <span class="text-[9px] text-gray-400" x-text="trackedOrder.ready_at_fmt || '--:--'"></span>
                            </div>

                            <!-- Etapa 4: Entregue -->
                            <div class="relative z-10 flex flex-col items-center text-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all"
                                     :class="trackedOrder.status_step >= 4 ? 'bg-purple-600 border-purple-600 text-white' : 'bg-white border-gray-300 text-gray-400'">
                                    <i class="mdi mdi-check-all"></i>
                                </div>
                                <span class="text-[10px] font-bold mt-1.5"
                                      :class="trackedOrder.status_step >= 4 ? 'text-purple-700' : 'text-gray-400'">Entregue</span>
                                <span class="text-[9px] text-gray-400" x-text="trackedOrder.delivered_at_fmt || '--:--'"></span>
                            </div>
                        </div>

                        <!-- Banner de Status com Mensagem Dinâmica -->
                        <div class="mt-4 p-3 rounded-xl text-center text-xs font-bold"
                             :class="{
                                 'bg-amber-100/70 text-amber-900 border border-amber-200': trackedOrder.status_step === 1,
                                 'bg-sky-100/70 text-sky-900 border border-sky-200': trackedOrder.status_step === 2,
                                 'bg-emerald-100 text-emerald-900 border border-emerald-300 shadow-sm shadow-emerald-500/10': trackedOrder.status_step === 3,
                                 'bg-purple-100/70 text-purple-900 border border-purple-200': trackedOrder.status_step >= 4
                             }">
                            <template x-if="trackedOrder.status_step === 1">
                                <span>📝 Pedido recebido! A cozinha está organizando os preparativos.</span>
                            </template>
                            <template x-if="trackedOrder.status_step === 2">
                                <span>🔥 Os cozinheiros estão preparando seus pratos com todo carinho.</span>
                            </template>
                            <template x-if="trackedOrder.status_step === 3">
                                <span class="text-emerald-800 text-sm font-black flex items-center justify-center gap-1.5">
                                    <i class="mdi mdi-bell-ring animate-bounce"></i>
                                    SEU PEDIDO ESTÁ PRONTO! Aguarde na mesa ou dirija-se ao balcão.
                                </span>
                            </template>
                            <template x-if="trackedOrder.status_step >= 4">
                                <span>✅ Pedido entregue! Esperamos que aprecie sua refeição.</span>
                            </template>
                        </div>
                    </div>

                    <!-- Resumo dos Itens da Comanda -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-extrabold text-gray-700 uppercase tracking-wider text-[11px]">Itens da Comanda (<span x-text="trackedOrder.items_count"></span>)</span>
                            <span class="text-gray-400 text-[11px]" x-text="'Tempo decorrido: ' + trackedOrder.elapsed_min + ' min'"></span>
                        </div>
                        <div class="max-h-48 overflow-y-auto space-y-2 pr-1">
                            <template x-for="item in trackedOrder.items" :key="item.id">
                                <div class="p-2.5 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-between gap-3 text-xs">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <span class="w-6 h-6 rounded-lg bg-orange-500/10 text-orange-600 font-black text-xs flex items-center justify-center flex-shrink-0"
                                              x-text="item.quantity + 'x'">
                                        </span>
                                        <div class="truncate">
                                            <div class="flex items-center gap-1.5 truncate">
                                                <span class="font-bold text-gray-900 truncate" x-text="item.name"></span>
                                                <template x-if="item.is_new">
                                                    <span class="px-1.5 py-0.2 rounded bg-amber-500 text-white text-[9px] font-black uppercase">Adicionado</span>
                                                </template>
                                            </div>
                                            <template x-if="item.notes">
                                                <p class="text-[10px] text-gray-400 truncate" x-text="item.notes"></p>
                                            </template>
                                        </div>
                                    </div>
                                    <span class="font-black text-gray-800 flex-shrink-0" x-text="item.total_formatted"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Total e Forma de Pagamento -->
                    <div class="p-3.5 rounded-2xl bg-gray-900 text-white flex items-center justify-between">
                        <div>
                            <span class="text-[11px] text-gray-400 block">Total da Comanda</span>
                            <span class="text-xs text-orange-400 font-semibold" x-text="'Pagamento: ' + (trackedOrder.payment_method_label || 'No Balcão')"></span>
                        </div>
                        <span class="text-xl font-black text-orange-400" x-text="trackedOrder.total_formatted"></span>
                    </div>

                    <!-- Ações Rápidas -->
                    <div class="grid grid-cols-2 gap-2.5 pt-1">
                        <!-- Botão Adicionar Mais Itens -->
                        <button type="button"
                                @click="trackerModalOpen = false"
                                class="py-3 px-3 rounded-2xl bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-xs shadow-md shadow-orange-500/20 transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="mdi mdi-plus-circle-outline text-base"></i>
                            <span>Pedir Mais Itens</span>
                        </button>

                        <!-- Botão Chamar Atendente -->
                        <button type="button"
                                @click="trackerModalOpen = false; openWaiterModal()"
                                class="py-3 px-3 rounded-2xl bg-amber-50 hover:bg-amber-100 text-amber-900 font-extrabold text-xs border border-amber-200 transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="mdi mdi-bell-ring-outline text-amber-600 text-base"></i>
                            <span>Chamar Garçom</span>
                        </button>
                    </div>

                    <!-- Botão Finalizar se Entregue -->
                    <template x-if="trackedOrder.status_step >= 4">
                        <button type="button"
                                @click="clearTracker()"
                                class="w-full py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold transition-colors">
                            Concluir Acompanhamento e Iniciar Novo Pedido
                        </button>
                    </template>
                </div>
            </template>

            <!-- Loading Tracker -->
            <template x-if="!trackedOrder">
                <div class="py-12 text-center space-y-3">
                    <i class="mdi mdi-loading animate-spin text-4xl text-orange-500"></i>
                    <p class="text-xs text-gray-500 font-medium">Carregando status do seu pedido...</p>
                </div>
            </template>

        </div>
    </div>

    <!-- Rodapé -->
    <footer class="bg-gray-900 text-gray-400 text-xs py-8 border-t border-gray-800 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center space-y-2">
            <p class="text-gray-300 font-bold">{{ $companyName }} - {{ $companySubtitle }}</p>
            <p class="text-gray-500 text-[11px]">&copy; {{ date('Y') }} Todos os direitos reservados. Cardápio Digital.</p>
        </div>
    </footer>

    <!-- Lógica Alpine.js -->
    <script>
    function restaurantMenuApp() {
        return {
            selectedCategory: 'all',
            searchQuery: '',
            selectedTableId: '{{ $detectedTableId ?? "" }}',
            tableNumber: '{{ $tableNumber ?? "" }}',
            customerName: '{{ Auth::check() ? Auth::user()->name : "" }}',
            customerPhone: '',
            serviceType: 'dine_in',
            paymentMethod: 'counter',
            orderNotes: '',
            cart: [],
            cartOpen: false,
            submitting: false,

            // Live Order Tracking
            activeOrderId: null,
            trackedOrder: null,
            trackerModalOpen: false,
            pollInterval: null,

            itemModalOpen: false,
            modalItem: null,
            modalItemNotes: '',

            waiterModalOpen: false,

            systemTables: @json($tables->map(fn($t) => ['id' => $t->id, 'number' => $t->number])),
            products: @json($allProducts),

            init() {
                const savedOrderId = localStorage.getItem('cafelu_active_order_id');
                if (savedOrderId) {
                    this.activeOrderId = parseInt(savedOrderId);
                    this.fetchOrderStatus(this.activeOrderId);
                    this.startTrackingPoll();
                }
            },

            onTableChange(tableId) {
                this.selectedTableId = tableId;
                const matched = this.systemTables.find(t => t.id == tableId);
                if (matched) {
                    this.tableNumber = matched.number;
                } else {
                    this.tableNumber = '';
                }
            },

            get isAppendingToActiveComanda() {
                if (!this.activeOrderId || !this.trackedOrder) return false;
                if (this.trackedOrder.status_step >= 4) return false;
                if (this.serviceType !== 'dine_in') return false;

                // If user selected a table, check if matches tracked order table
                if (this.selectedTableId && this.trackedOrder.table_id) {
                    return this.selectedTableId == this.trackedOrder.table_id;
                }
                return true;
            },

            get filteredProducts() {
                return this.products.filter(p => {
                    const matchCategory = (this.selectedCategory === 'all') || (p.category_id === this.selectedCategory);
                    const matchQuery = !this.searchQuery.trim() || 
                        p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                        p.description.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        p.category_name.toLowerCase().includes(this.searchQuery.toLowerCase());
                    return matchCategory && matchQuery;
                });
            },

            get categoryTitle() {
                if (this.selectedCategory === 'all') return 'Todos os Pratos e Bebidas';
                const p = this.products.find(i => i.category_id === this.selectedCategory);
                return p ? p.category_name : 'Cardápio';
            },

            get cartCount() {
                return this.cart.reduce((sum, item) => sum + item.quantity, 0);
            },

            get cartTotal() {
                return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            },

            get cartTotalFormatted() {
                return this.formatMoney(this.cartTotal);
            },

            formatMoney(value) {
                return parseFloat(value).toFixed(2) + ' {{ $currency }}';
            },

            getCartQty(productId) {
                const item = this.cart.find(i => i.id === productId);
                return item ? item.quantity : 0;
            },

            addToCart(product, notes = '') {
                const existing = this.cart.find(i => i.id === product.id);
                if (existing) {
                    existing.quantity++;
                    if (notes && !existing.notes) existing.notes = notes;
                } else {
                    this.cart.push({
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        image_url: product.image_url,
                        notes: notes,
                        quantity: 1
                    });
                }
            },

            decreaseQty(productId) {
                const index = this.cart.findIndex(i => i.id === productId);
                if (index !== -1) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity--;
                    } else {
                        this.cart.splice(index, 1);
                    }
                }
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
            },

            openItemModal(product) {
                this.modalItem = product;
                this.modalItemNotes = '';
                this.itemModalOpen = true;
            },

            confirmModalAdd() {
                if (this.modalItem) {
                    this.addToCart(this.modalItem, this.modalItemNotes);
                    this.itemModalOpen = false;
                }
            },

            openWaiterModal() {
                this.waiterModalOpen = true;
            },

            confirmCallWaiter() {
                fetch('{{ route("welcome.call-waiter") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        table: this.tableNumber ? 'Mesa ' + this.tableNumber : 'Balcão / Salão',
                        name: this.customerName || 'Cliente'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.waiterModalOpen = false;
                    alert(data.message || 'Atendente chamado com sucesso!');
                })
                .catch(() => {
                    this.waiterModalOpen = false;
                    alert('Atendente chamado! Um garçom virá até você em instantes.');
                });
            },

            startTrackingPoll() {
                if (this.pollInterval) clearInterval(this.pollInterval);
                this.pollInterval = setInterval(() => {
                    if (this.activeOrderId) {
                        this.fetchOrderStatus(this.activeOrderId);
                    }
                }, 8000);
            },

            fetchOrderStatus(orderId) {
                fetch('/order-status/' + orderId)
                    .then(res => {
                        if (!res.ok) throw new Error('Status query failed');
                        return res.json();
                    })
                    .then(data => {
                        if (data && data.success) {
                            const prevStatus = this.trackedOrder ? this.trackedOrder.status : null;
                            this.trackedOrder = data;

                            // Sync local table selection if empty
                            if (!this.selectedTableId && data.table_id) {
                                this.selectedTableId = String(data.table_id);
                                this.tableNumber = (data.table_name || '').replace(/[^0-9]/g, '');
                            }

                            // Chime alert when order is marked ready!
                            if (data.status === 'ready' && prevStatus && prevStatus !== 'ready') {
                                this.playChime();
                            }
                        }
                    })
                    .catch(() => {
                        // ignore network hitch
                    });
            },

            openTracker() {
                if (this.activeOrderId) {
                    this.fetchOrderStatus(this.activeOrderId);
                    this.trackerModalOpen = true;
                }
            },

            clearTracker() {
                localStorage.removeItem('cafelu_active_order_id');
                if (this.pollInterval) clearInterval(this.pollInterval);
                this.activeOrderId = null;
                this.trackedOrder = null;
                this.trackerModalOpen = false;
            },

            playChime() {
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const now = ctx.currentTime;
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.frequency.setValueAtTime(587.33, now); // D5
                    osc.frequency.setValueAtTime(880.00, now + 0.15); // A5
                    gain.gain.setValueAtTime(0.25, now);
                    gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.6);
                    osc.start(now);
                    osc.stop(now + 0.6);
                } catch(e) {}
            },

            submitOrder() {
                if (this.cart.length === 0) return;

                if (this.serviceType === 'takeaway' && !this.customerName.trim()) {
                    alert('Por favor, informe seu nome para a retirada do pedido no balcão.');
                    return;
                }

                this.submitting = true;

                const payload = {
                    customer_name: this.customerName || null,
                    phone: this.customerPhone || null,
                    table_id: this.serviceType === 'dine_in' ? (this.selectedTableId || null) : null,
                    table_number: this.serviceType === 'dine_in' && this.tableNumber ? String(this.tableNumber) : null,
                    service_type: this.serviceType,
                    payment_method: this.paymentMethod,
                    notes: this.orderNotes || null,
                    append_to_order_id: this.isAppendingToActiveComanda ? this.activeOrderId : null,
                    items: this.cart.map(i => ({
                        id: i.id,
                        quantity: i.quantity,
                        notes: i.notes || null
                    }))
                };

                fetch('{{ route("self-service.order") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(async res => {
                    const data = await res.json();
                    this.submitting = false;
                    if (res.ok && data.success) {
                        this.activeOrderId = data.order_id;
                        localStorage.setItem('cafelu_active_order_id', String(data.order_id));
                        this.cart = [];
                        this.cartOpen = false;
                        this.orderNotes = '';
                        this.fetchOrderStatus(data.order_id);
                        this.trackerModalOpen = true;
                        this.startTrackingPoll();
                    } else {
                        let errMsg = data.message || 'Erro ao processar o pedido.';
                        if (data.errors) {
                            errMsg = Object.values(data.errors).flat().join('\n');
                        }
                        alert(errMsg);
                    }
                })
                .catch(err => {
                    this.submitting = false;
                    console.error('Order submit error:', err);
                    alert('Erro de conexão ao enviar o pedido.');
                });
            }
        }
    }
    </script>
</body>
</html>
