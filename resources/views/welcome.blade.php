<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Models\Setting::get('company_name', 'Zalala Beach Bar') }} - Zalala Beach Bar</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#FFA500',
                        orange: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    },
                    animation: {
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
        }

        .hero-gradient {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.4) 100%);
        }
    </style>
</head>

<body class="antialiased bg-gray-50 text-gray-900 overflow-x-hidden" x-data="{ scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 20)">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 px-6 py-4"
        :class="scrolled ? 'glass shadow-lg py-3' : 'bg-transparent'">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div
                    class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/30">
                    <i class="mdi mdi-coffee text-white text-xl"></i>
                </div>
                <span class="text-xl font-extrabold tracking-tight" :class="scrolled ? 'text-gray-900' : 'text-white'">
                    {{ \App\Models\Setting::get('company_name', 'Zalala Beach Bar') }}
                </span>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/home') }}" class="px-5 py-2.5 rounded-xl font-bold transition-all"
                        :class="scrolled ? 'bg-orange-500 text-white hover:bg-orange-600' : 'bg-white text-gray-900 hover:bg-orange-50'">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="font-semibold transition-colors"
                        :class="scrolled ? 'text-gray-700 hover:text-orange-500' : 'text-white/90 hover:text-white'">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl font-bold transition-all shadow-lg"
                        :class="scrolled ? 'bg-orange-500 text-white hover:bg-orange-600 shadow-orange-500/20' : 'bg-white text-gray-900 hover:bg-orange-50 shadow-black/10'">
                        Criar Conta
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&q=80&w=2000"
                class="w-full h-full object-cover scale-105 animate-pulse-slow" alt="Café Background">
            <div class="absolute inset-0 hero-gradient"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <span
                class="inline-block px-4 py-1.5 mb-6 rounded-full bg-orange-500/20 border border-orange-500/30 text-orange-400 text-sm font-bold tracking-widest uppercase animate-fade-in-down">
                Zalala Beach Bar
            </span>
            <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-6 leading-tight animate-fade-in-up">
                Sabores que <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Inspiram</span>
                o seu Dia
            </h1>
            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed animate-fade-in-up delay-100">
                O lugar perfeito para saborear o melhor café, refeições deliciosas e momentos inesquecíveis. Gestão
                profissional para uma experiência gastronómica superior.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up delay-200">
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.order.create') }}"
                            class="w-full sm:w-auto px-8 py-4 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-2xl shadow-2xl shadow-orange-500/40 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            <i class="mdi mdi-plus-circle"></i> Fazer Novo Pedido
                        </a>
                        <a href="{{ route('customer.dashboard') }}"
                            class="w-full sm:w-auto px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold rounded-2xl backdrop-blur-md border border-white/20 transition-all flex items-center justify-center gap-2">
                            <i class="mdi mdi-view-dashboard"></i> Meu Painel
                        </a>
                    @else
                        <a href="{{ url('/home') }}"
                            class="w-full sm:w-auto px-8 py-4 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-2xl shadow-2xl shadow-orange-500/40 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            <i class="mdi mdi-view-dashboard"></i> Painel Administrativo
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto px-8 py-4 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-2xl shadow-2xl shadow-orange-500/40 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <i class="mdi mdi-rocket-launch"></i> Acessar o Sistema
                    </a>
                    <a href="#menu"
                        class="w-full sm:w-auto px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold rounded-2xl backdrop-blur-md border border-white/20 transition-all flex items-center justify-center gap-2">
                        Ver Nosso Menu
                    </a>
                @endauth
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-10 animate-bounce">
            <a href="#menu" class="text-white/50 hover:text-white transition-colors">
                <i class="mdi mdi-chevron-down text-4xl"></i>
            </a>
        </div>
    </header>

    <!-- Menu Section -->
    <section id="menu" class="py-24 px-6 bg-white" x-data="{ 
        cart: [],
        addToCart(product) {
            let item = this.cart.find(i => i.id === product.id);
            if (item) {
                item.quantity++;
            } else {
                this.cart.push({ ...product, quantity: 1 });
            }
        },
        get cartTotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },
        get cartCount() {
            return this.cart.reduce((sum, item) => sum + item.quantity, 0);
        },
        placeOrder() {
            if (this.cart.length === 0) return;
            
            fetch('{{ route('customer.order.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ items: this.cart })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Pedido realizado com sucesso!');
                    this.cart = [];
                    window.location.href = '{{ route('customer.dashboard') }}';
                } else {
                    alert('Erro ao realizar pedido: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao processar o pedido.');
            });
        }
    }" @place-order.window="placeOrder()">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-4">Nosso Menu Especial</h2>
                <div class="w-20 h-1.5 bg-orange-500 mx-auto rounded-full mb-6"></div>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto">
                    Cada prato é preparado com ingredientes frescos e selecionados para garantir o máximo de sabor.
                </p>
            </div>

            @foreach($categories as $category)
                @if($category->products->count() > 0)
                    <div class="mb-16">
                        <h3 class="text-2xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                            <span class="w-2 h-8 bg-orange-500 rounded-full"></span>
                            {{ $category->name }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            @foreach($category->products as $product)
                                <div
                                    class="group bg-gray-50 rounded-3xl overflow-hidden border border-gray-100 hover:border-orange-200 transition-all duration-500 hover:shadow-2xl hover:shadow-orange-500/10">
                                    <div class="relative h-64 overflow-hidden">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                                alt="{{ $product->name }}">
                                        @else
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                <i class="mdi mdi-food text-4xl text-gray-400"></i>
                                            </div>
                                        @endif
                                        @if($product->created_at > now()->subDays(7))
                                            <div
                                                class="absolute top-4 right-4 px-3 py-1 bg-white/90 backdrop-blur rounded-full text-xs font-bold text-orange-600 shadow-sm">
                                                Novo
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-6">
                                        <h3
                                            class="text-xl font-bold text-gray-900 mb-2 group-hover:text-orange-500 transition-colors">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-2">
                                            {{ $product->description ?? 'Sem descrição disponível.' }}
                                        </p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-orange-500 font-extrabold">{{ number_format($product->price, 2) }}
                                                MT</span>
                                            <button
                                                @click="addToCart({ id: {{ $product->id }}, name: '{{ $product->name }}', price: {{ $product->price }} })"
                                                class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 group-hover:bg-orange-500 group-hover:text-white transition-all">
                                                <i class="mdi mdi-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Floating Cart Button -->
        <div x-show="cartCount > 0" x-transition class="fixed bottom-8 right-8 z-50">
            <button @click="$dispatch('open-cart')"
                class="bg-orange-500 text-white p-4 rounded-2xl shadow-2xl shadow-orange-500/40 flex items-center gap-3 hover:bg-orange-600 transition-all transform hover:scale-105">
                <div class="relative">
                    <i class="mdi mdi-cart text-2xl"></i>
                    <span
                        class="absolute -top-2 -right-2 bg-white text-orange-500 text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-orange-500"
                        x-text="cartCount"></span>
                </div>
                <div class="text-left">
                    <p class="text-[10px] uppercase font-bold opacity-80 leading-none">Seu Pedido</p>
                    <p class="font-bold leading-none" x-text="cartTotal.toFixed(2) + ' MT'"></p>
                </div>
            </button>
        </div>

        <!-- Cart Modal -->
        <div x-data="{ open: false }" @open-cart.window="open = true" x-show="open"
            class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="open = false">
                </div>

                <div x-show="open" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                    <div
                        class="px-6 py-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="mdi mdi-cart-outline text-orange-500"></i>
                            Seu Pedido
                        </h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                            <i class="mdi mdi-close text-2xl"></i>
                        </button>
                    </div>

                    <div class="px-6 py-6 max-h-[60vh] overflow-y-auto">
                        <template x-if="cart.length === 0">
                            <div class="text-center py-12">
                                <div
                                    class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="mdi mdi-cart-off text-4xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">Seu carrinho está vazio.</p>
                            </div>
                        </template>

                        <div class="space-y-4">
                            <template x-for="(item, index) in cart" :key="item.id">
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900 dark:text-white" x-text="item.name"></h4>
                                        <p class="text-sm text-orange-500 font-bold"
                                            x-text="item.price.toFixed(2) + ' MT'"></p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button
                                            @click="if(item.quantity > 1) item.quantity--; else cart.splice(index, 1)"
                                            class="w-8 h-8 rounded-lg bg-white dark:bg-gray-600 border border-gray-200 dark:border-gray-500 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-gray-100">
                                            <i class="mdi mdi-minus"></i>
                                        </button>
                                        <span class="font-bold text-gray-900 dark:text-white w-4 text-center"
                                            x-text="item.quantity"></span>
                                        <button @click="item.quantity++"
                                            class="w-8 h-8 rounded-lg bg-white dark:bg-gray-600 border border-gray-200 dark:border-gray-500 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-gray-100">
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="px-6 py-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Total do Pedido</span>
                            <span class="text-2xl font-extrabold text-gray-900 dark:text-white"
                                x-text="cartTotal.toFixed(2) + ' MT'"></span>
                        </div>

                        @auth
                            <button @click="$dispatch('place-order')"
                                class="w-full py-4 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-2xl shadow-xl shadow-orange-500/20 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                <i class="mdi mdi-check-circle"></i>
                                Finalizar Pedido
                            </button>
                        @else
                            <div class="text-center">
                                <p class="text-sm text-gray-500 mb-4">Faça login para finalizar seu pedido.</p>
                                <a href="{{ route('login') }}"
                                    class="w-full py-4 bg-gray-900 hover:bg-black text-white font-bold rounded-2xl transition-all flex items-center justify-center gap-2">
                                    <i class="mdi mdi-login"></i>
                                    Fazer Login
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                    <i class="mdi mdi-coffee text-white"></i>
                </div>
                <span class="text-xl font-bold">{{ \App\Models\Setting::get('company_name', 'Café Lufamina') }}</span>
            </div>

            <div class="flex gap-6 text-gray-400">
                <a href="#" class="hover:text-orange-500 transition-colors"><i
                        class="mdi mdi-facebook text-2xl"></i></a>
                <a href="#" class="hover:text-orange-500 transition-colors"><i
                        class="mdi mdi-instagram text-2xl"></i></a>
                <a href="#" class="hover:text-orange-500 transition-colors"><i
                        class="mdi mdi-whatsapp text-2xl"></i></a>
            </div>

            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} {{ \App\Models\Setting::get('company_name', 'Café Lufamina') }}. Todos os
                direitos reservados.
            </p>
        </div>
    </footer>

</body>

</html>