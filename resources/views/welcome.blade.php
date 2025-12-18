<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Models\Setting::get('company_name', 'Café Lufamina') }} - Lu & Yosh Catering</title>

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
                    {{ \App\Models\Setting::get('company_name', 'Café Lufamina') }}
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
                        Começar Agora
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
                Lu & Yosh Catering
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
                <a href="{{ route('login') }}"
                    class="w-full sm:w-auto px-8 py-4 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-2xl shadow-2xl shadow-orange-500/40 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                    <i class="mdi mdi-rocket-launch"></i> Acessar o Sistema
                </a>
                <a href="#menu"
                    class="w-full sm:w-auto px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold rounded-2xl backdrop-blur-md border border-white/20 transition-all flex items-center justify-center gap-2">
                    Ver Nosso Menu
                </a>
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
    <section id="menu" class="py-24 px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-4">Nosso Menu Especial</h2>
                <div class="w-20 h-1.5 bg-orange-500 mx-auto rounded-full mb-6"></div>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto">
                    Cada prato é preparado com ingredientes frescos e selecionados para garantir o máximo de sabor.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Item 1 -->
                <div
                    class="group bg-gray-50 rounded-3xl overflow-hidden border border-gray-100 hover:border-orange-200 transition-all duration-500 hover:shadow-2xl hover:shadow-orange-500/10">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&q=80&w=800"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            alt="Café Especial">
                        <div
                            class="absolute top-4 right-4 px-3 py-1 bg-white/90 backdrop-blur rounded-full text-xs font-bold text-orange-600 shadow-sm">
                            Popular</div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-orange-500 transition-colors">
                            Café Especial</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-4">Desfrute do nosso café especial, feito com
                            grãos selecionados e torrados na perfeição.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-orange-500 font-extrabold">A partir de 50 MT</span>
                            <div
                                class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 group-hover:bg-orange-500 group-hover:text-white transition-all">
                                <i class="mdi mdi-plus"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item 2 -->
                <div
                    class="group bg-gray-50 rounded-3xl overflow-hidden border border-gray-100 hover:border-orange-200 transition-all duration-500 hover:shadow-2xl hover:shadow-orange-500/10">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&q=80&w=800"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            alt="Hamburguer Gourmet">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-orange-500 transition-colors">
                            Hamburguer Gourmet</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-4">Saboreie nossos hamburgueres artesanais,
                            preparados com carne suculenta e ingredientes frescos.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-orange-500 font-extrabold">A partir de 250 MT</span>
                            <div
                                class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 group-hover:bg-orange-500 group-hover:text-white transition-all">
                                <i class="mdi mdi-plus"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item 3 -->
                <div
                    class="group bg-gray-50 rounded-3xl overflow-hidden border border-gray-100 hover:border-orange-200 transition-all duration-500 hover:shadow-2xl hover:shadow-orange-500/10">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?auto=format&fit=crop&q=80&w=800"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            alt="Pequeno-almoço">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-orange-500 transition-colors">
                            Pequeno-almoço</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-4">Comece o dia com um pequeno-almoço
                            nutritivo e saboroso, com opções para todos os gostos.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-orange-500 font-extrabold">A partir de 150 MT</span>
                            <div
                                class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 group-hover:bg-orange-500 group-hover:text-white transition-all">
                                <i class="mdi mdi-plus"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item 4 -->
                <div
                    class="group bg-gray-50 rounded-3xl overflow-hidden border border-gray-100 hover:border-orange-200 transition-all duration-500 hover:shadow-2xl hover:shadow-orange-500/10">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&q=80&w=800"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            alt="Pizzas Artesanais">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-orange-500 transition-colors">
                            Pizzas Artesanais</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-4">Experimente nossas pizzas de massa fina e
                            crocante, com coberturas deliciosas e queijo derretido.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-orange-500 font-extrabold">A partir de 400 MT</span>
                            <div
                                class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 group-hover:bg-orange-500 group-hover:text-white transition-all">
                                <i class="mdi mdi-plus"></i>
                            </div>
                        </div>
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