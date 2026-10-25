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
                        <div class="space-y-3.5 pt-4 border-t border-gray-200">
                            <h4 class="text-xs font-black uppercase tracking-wider text-gray-400">Onde você está?</h4>

                            <!-- Localização: Mesa Real do Restaurante ou Balcão -->
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
                                        <option value="">Balcão / Pedido para Viagem</option>
                                        @foreach($tables as $table)
                                            <option value="{{ $table->id }}">
                                                Mesa {{ $table->number }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

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
                                <span>Subtotal</span>
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
                                    <i class="mdi mdi-check-circle-outline text-lg"></i>
                                    <span>Enviar Meu Pedido para a Cozinha</span>
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

    <!-- Modal de Sucesso do Pedido -->
    <div x-show="orderSuccessModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-950/80 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl text-center space-y-5 z-10 animate-fade-in-up">
            <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-500 mx-auto flex items-center justify-center text-4xl shadow-md shadow-emerald-500/20">
                <i class="mdi mdi-check-circle-outline"></i>
            </div>

            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-black uppercase mb-2">
                    Pedido #<span x-text="confirmedOrder ? confirmedOrder.order_id : ''"></span> Confirmado!
                </span>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">Enviado para a Cozinha!</h3>
                <p class="text-xs text-gray-500 mt-2">
                    O seu pedido já está no visor dos cozinheiros e está sendo preparado com todo carinho.
                </p>
            </div>

            <template x-if="confirmedOrder">
                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 text-left text-xs space-y-1.5">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Localização:</span>
                        <strong class="text-gray-800" x-text="confirmedOrder.table_name"></strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total:</span>
                        <strong class="text-orange-600 font-black text-sm" x-text="confirmedOrder.total"></strong>
                    </div>
                </div>
            </template>

            <div class="space-y-2 pt-2">
                <button type="button"
                        @click="orderSuccessModal = false"
                        class="w-full py-3.5 rounded-2xl bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-xs shadow-md transition-all cursor-pointer">
                    Fazer Outro Pedido
                </button>
                @auth
                    <a href="{{ route('customer.orders') }}" class="block w-full py-2.5 text-xs font-bold text-gray-500 hover:text-gray-900 transition-colors">
                        Ver Meus Pedidos
                    </a>
                @endauth
            </div>
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
            orderNotes: '',
            cart: [],
            cartOpen: false,
            submitting: false,
            orderSuccessModal: false,
            confirmedOrder: null,

            itemModalOpen: false,
            modalItem: null,
            modalItemNotes: '',

            waiterModalOpen: false,

            systemTables: @json($tables->map(fn($t) => ['id' => $t->id, 'number' => $t->number])),
            products: @json($allProducts),

            onTableChange(tableId) {
                this.selectedTableId = tableId;
                const matched = this.systemTables.find(t => t.id == tableId);
                if (matched) {
                    this.tableNumber = matched.number;
                } else {
                    this.tableNumber = '';
                }
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

            submitOrder() {
                if (this.cart.length === 0) return;

                this.submitting = true;

                fetch('{{ route("self-service.order") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        customer_name: this.customerName || null,
                        table_id: this.selectedTableId || null,
                        table_number: this.tableNumber ? String(this.tableNumber) : null,
                        notes: this.orderNotes || null,
                        items: this.cart.map(i => ({
                            id: i.id,
                            quantity: i.quantity,
                            notes: i.notes || null
                        }))
                    })
                })
                .then(async res => {
                    const data = await res.json();
                    this.submitting = false;
                    if (res.ok && data.success) {
                        this.confirmedOrder = data;
                        this.cart = [];
                        this.cartOpen = false;
                        this.orderSuccessModal = true;
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
