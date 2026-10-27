<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth h-full">

@php
    $companyName = \App\Models\Setting::get('company_name', 'Café & Restaurante');
    $companySubtitle = \App\Models\Setting::get('company_subtitle', 'Cardápio Digital & Pedidos Online');
    $companyPhone = \App\Models\Setting::get('company_phone', '');
    $companyEmail = \App\Models\Setting::get('company_email', '');
    $companyAddress = \App\Models\Setting::get('company_address', '');
    $primaryColor = \App\Models\Setting::get('primary_color', '#f97316');
    $currency = \App\Models\Setting::get('currency_symbol', 'MT');
    $logo = \App\Models\Setting::get('system_logo');
    $favicon = \App\Models\Setting::get('system_favicon');
    $loginBg = \App\Models\Setting::get('login_background');

    // Website CMS Settings
    $heroBadge = \App\Models\Setting::get('site_hero_badge', 'Cardápio Digital & Pedidos Online');
    $heroTitle = \App\Models\Setting::get('site_hero_title', 'Sabores Autênticos Preparados na Hora');
    $heroSubtitle = \App\Models\Setting::get('site_hero_subtitle', 'Explore o nosso menu completo, faça o seu pedido diretamente para a sua mesa ou encomende online para levar.');
    $siteAnnouncement = \App\Models\Setting::get('site_announcement', '');
    $aboutTitle = \App\Models\Setting::get('site_about_title', 'Sobre o ' . $companyName);
    $aboutText = \App\Models\Setting::get('site_about_text', 'Servimos refeições preparadas com ingredientes frescos e selecionados, combinando tradição culinária, ambiente acolhedor e atendimento de excelência todos os dias.');
    $aboutImage = \App\Models\Setting::get('site_about_image', '');
    $openingHours = \App\Models\Setting::get('site_opening_hours', 'Segunda a Domingo: 07:00 - 22:00');
    $whatsappNumber = \App\Models\Setting::get('site_whatsapp', $companyPhone);
    $whatsappClean = preg_replace('/[^0-9]/', '', (string) $whatsappNumber);
    $instagramUrl = \App\Models\Setting::get('site_instagram', '');
    $facebookUrl = \App\Models\Setting::get('site_facebook', '');
    $onlineOrdersEnabled = \App\Models\Setting::get('site_online_orders', '1') !== '0';

    $defaultHeroBg = $loginBg && file_exists(public_path('storage/' . $loginBg))
        ? asset('storage/' . $loginBg)
        : (file_exists(public_path('assets/images/restaurant-bg.jpeg'))
            ? asset('assets/images/restaurant-bg.jpeg')
            : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&q=80&w=2000');

    $aboutImageUrl = ($aboutImage && file_exists(public_path('storage/' . $aboutImage)))
        ? asset('storage/' . $aboutImage)
        : 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&q=80&w=1200';

    // Prepare Hero Slider Data (CMS slides or curated default slides)
    $sliderItems = [];
    if (isset($slides) && $slides->count() > 0) {
        foreach ($slides as $s) {
            $sliderItems[] = [
                'id' => $s->id,
                'badge' => $s->badge ?: $heroBadge,
                'title' => $s->title ?: $heroTitle,
                'subtitle' => $s->subtitle ?: $heroSubtitle,
                'cta_text' => $s->button_text ?: ($s->cta_text ?: 'Explorar Cardápio'),
                'cta_link' => $s->button_link ?: ($s->cta_link ?: '#menu'),
                'image_url' => $s->image_url ?: $defaultHeroBg,
            ];
        }
    } else {
        $sliderItems = [
            [
                'id' => 1,
                'badge' => $heroBadge,
                'title' => $heroTitle,
                'subtitle' => $heroSubtitle,
                'cta_text' => 'Ver Cardápio Completo',
                'cta_link' => '#menu',
                'image_url' => $defaultHeroBg,
            ],
            [
                'id' => 2,
                'badge' => 'Especialidades da Casa',
                'title' => 'Gastronomia, Cafetaria & Refeições Selecionadas',
                'subtitle' => 'Pratos grelhados, mariscos frescos, snacks, pastelaria artesanal e bebidas refrescantes para todos os momentos do seu dia.',
                'cta_text' => 'Ver Sugestões do Chef',
                'cta_link' => '#destaques',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&q=80&w=2000',
            ],
            [
                'id' => 3,
                'badge' => 'Pedidos na Mesa & Takeaway',
                'title' => 'Peça pelo Telemóvel sem Filas de Espera',
                'subtitle' => 'Adicione os seus pratos favoritos à comanda digital, escolha consumo na mesa ou takeaway e acompanhe o preparo em tempo real.',
                'cta_text' => 'Fazer Pedido Agora',
                'cta_link' => '#menu',
                'image_url' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80&w=2000',
            ],
        ];
    }

    // Smart Culinary Fallback Image Resolver for products without uploaded photo
    $resolveCulinaryImage = function (string $name, string $categoryName, ?string $existingUrl, int $id): string {
        if (!empty($existingUrl)) {
            return $existingUrl;
        }
        $haystack = mb_strtolower($name . ' ' . $categoryName, 'UTF-8');

        $catalog = [
            'cafe' => [
                'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&q=80&w=700',
            ],
            'cha' => [
                'https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1597481499750-3e6b22637e12?auto=format&fit=crop&q=80&w=700',
            ],
            'sumo' => [
                'https://images.unsplash.com/photo-1613478223719-2ab802602423?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1546173159-315724a31696?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1553530666-ba11a7da3888?auto=format&fit=crop&q=80&w=700',
            ],
            'bebida' => [
                'https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1581006852262-e4307cf6283a?auto=format&fit=crop&q=80&w=700',
            ],
            'cerveja' => [
                'https://images.unsplash.com/photo-1608270196042-a8690097e27f?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1535958636474-b021ee887b13?auto=format&fit=crop&q=80&w=700',
            ],
            'burger' => [
                'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?auto=format&fit=crop&q=80&w=700',
            ],
            'sandes' => [
                'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1554433607-66b5efe9d304?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1509722747041-616f39b57569?auto=format&fit=crop&q=80&w=700',
            ],
            'pizza' => [
                'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&q=80&w=700',
            ],
            'massa' => [
                'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1621996346565-e3d5d6281298?auto=format&fit=crop&q=80&w=700',
            ],
            'frango' => [
                'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1532550907401-a500c9a57435?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&q=80&w=700',
            ],
            'carne' => [
                'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1600891964092-4316c288032e?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1558030006-450675393462?auto=format&fit=crop&q=80&w=700',
            ],
            'peixe' => [
                'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?auto=format&fit=crop&q=80&w=700',
            ],
            'salgado' => [
                'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?auto=format&fit=crop&q=80&w=700',
            ],
            'doce' => [
                'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&q=80&w=700',
            ],
            'pequeno_almoco' => [
                'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&q=80&w=700',
            ],
            'sopa' => [
                'https://images.unsplash.com/photo-1547592166-23ac45744acd?auto=format&fit=crop&q=80&w=700',
            ],
            'salada' => [
                'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&q=80&w=700',
            ],
            'default' => [
                'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&q=80&w=700',
                'https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?auto=format&fit=crop&q=80&w=700',
            ],
        ];

        $bucket = 'default';
        if (preg_match('/(caf[eé]|espresso|cappuccino|gal[aã]o|latte|macchiato|mocha)/u', $haystack)) {
            $bucket = 'cafe';
        } elseif (preg_match('/(\bch[aá]\b|infus[aã]o|camomila)/u', $haystack)) {
            $bucket = 'cha';
        } elseif (preg_match('/(sumo|batido|smoothie|laranja|anan[aá]s|manga|maracuj[aá])/u', $haystack)) {
            $bucket = 'sumo';
        } elseif (preg_match('/(cerveja|vinho|2m|laurentina|manica|heineken|savanna|whisky|gin)/u', $haystack)) {
            $bucket = 'cerveja';
        } elseif (preg_match('/(refresco|refrigerante|coca|fanta|sprite|\b[aá]gua\b|redbull|energ|bebida)/u', $haystack)) {
            $bucket = 'bebida';
        } elseif (preg_match('/(hamb[uú]rguer|burger)/u', $haystack)) {
            $bucket = 'burger';
        } elseif (preg_match('/(sandes|tosta|prego|cachorro|baguete|p[aã]o|wrap|shawarma)/u', $haystack)) {
            $bucket = 'sandes';
        } elseif (preg_match('/(pizza)/u', $haystack)) {
            $bucket = 'pizza';
        } elseif (preg_match('/(massa|esparguete|lasanha|penne|tagliatelle)/u', $haystack)) {
            $bucket = 'massa';
        } elseif (preg_match('/(frango|galinha|asas|nuggets)/u', $haystack)) {
            $bucket = 'frango';
        } elseif (preg_match('/(bife|carne|vaca|porco|costeleta|chouri[cç]o|picanha| espetada)/u', $haystack)) {
            $bucket = 'carne';
        } elseif (preg_match('/(peixe|marisco|camar[aã]o|lula|garoupa|bacalhau|atum)/u', $haystack)) {
            $bucket = 'peixe';
        } elseif (preg_match('/(chamussa|rissol|coxinha|empada|pastel|salgado|batata|frita)/u', $haystack)) {
            $bucket = 'salgado';
        } elseif (preg_match('/(bolo|doce|sobremesa|pudim|gelado|croissant|waffle|panqueca|muffin|donut)/u', $haystack)) {
            $bucket = 'doce';
        } elseif (preg_match('/(ovo|omelete|pequeno|matabicho)/u', $haystack)) {
            $bucket = 'pequeno_almoco';
        } elseif (preg_match('/(sopa|caldo)/u', $haystack)) {
            $bucket = 'sopa';
        } elseif (preg_match('/(salada)/u', $haystack)) {
            $bucket = 'salada';
        }

        $pool = $catalog[$bucket] ?? $catalog['default'];
        return $pool[$id % count($pool)];
    };

    // Flatten all active products with resolved culinary imagery
    $allProducts = [];
    foreach ($categories as $cat) {
        foreach ($cat->products as $p) {
            $unitPrice = (float) ($p->price ?? $p->selling_price ?? 0);
            $resolvedPhoto = $resolveCulinaryImage($p->name, $cat->name, $p->image_url, (int) $p->id);
            $allProducts[] = [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $unitPrice,
                'price_formatted' => number_format($unitPrice, 2, '.', ' ') . ' ' . $currency,
                'category_id' => $cat->id,
                'category_name' => $cat->name,
                'description' => $p->description ?? '',
                'image_url' => $resolvedPhoto,
                'has_custom_image' => !empty($p->image_url),
                'stock' => $p->stock_quantity ?? 99,
            ];
        }
    }

    // Featured products (prefer items with custom uploaded image first, then varied items up to 8)
    $featuredProducts = collect($allProducts)
        ->sortByDesc('has_custom_image')
        ->take(8)
        ->values()
        ->all();
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $companyName }} - {{ $companySubtitle }}</title>

    <!-- Favicon -->
    @if($favicon && file_exists(public_path('storage/' . $favicon)))
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    @endif

    <!-- PWA Manifest & Mobile Meta -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="{{ $primaryColor }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ $companyName }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
                        serif: ['Playfair Display', 'Georgia', 'serif'],
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
        .menu-leader {
            background-image: radial-gradient(circle, #cbd5e1 1px, transparent 1px);
            background-position: bottom;
            background-size: 8px 2px;
            background-repeat: repeat-x;
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-900 font-sans min-h-screen flex flex-col selection:bg-orange-500 selection:text-white"
      x-data="restaurantMenuApp()"
      x-cloak>

    <!-- Barra Superior de Anúncio / Horário & Contactos -->
    <div class="bg-gray-950 text-gray-300 text-[11px] border-b border-white/10">
        @if(!empty($siteAnnouncement))
            <div class="bg-gradient-to-r from-orange-600 via-amber-500 to-orange-600 text-white font-extrabold py-1.5 px-4 text-center text-xs tracking-wide">
                <i class="mdi mdi-bullhorn-outline mr-1"></i> {{ $siteAnnouncement }}
            </div>
        @endif
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-wrap items-center justify-between gap-2">
            <div class="flex flex-wrap items-center gap-4">
                @if(!empty($openingHours))
                    <span class="inline-flex items-center gap-1.5 font-medium text-gray-300">
                        <i class="mdi mdi-clock-outline text-orange-400 text-sm"></i>
                        <span>{{ $openingHours }}</span>
                    </span>
                @endif
                @if(!empty($companyAddress))
                    <span class="hidden sm:inline-flex items-center gap-1.5 font-medium text-gray-400">
                        <i class="mdi mdi-map-marker-outline text-orange-400 text-sm"></i>
                        <span>{{ $companyAddress }}</span>
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-4 ml-auto">
                @if(!empty($companyPhone))
                    <a href="tel:{{ $companyPhone }}" class="inline-flex items-center gap-1 hover:text-white transition-colors font-semibold">
                        <i class="mdi mdi-phone-outline text-orange-400"></i>
                        <span>{{ $companyPhone }}</span>
                    </a>
                @endif
                @if(!empty($whatsappClean))
                    <a href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-emerald-400 hover:text-emerald-300 transition-colors font-bold">
                        <i class="mdi mdi-whatsapp text-sm"></i>
                        <span>WhatsApp</span>
                    </a>
                @endif
                @if(!empty($instagramUrl))
                    <a href="{{ $instagramUrl }}" target="_blank" rel="noopener" class="hover:text-pink-400 transition-colors" title="Instagram">
                        <i class="mdi mdi-instagram text-sm"></i>
                    </a>
                @endif
                @if(!empty($facebookUrl))
                    <a href="{{ $facebookUrl }}" target="_blank" rel="noopener" class="hover:text-blue-400 transition-colors" title="Facebook">
                        <i class="mdi mdi-facebook text-sm"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Header Principal Sticky -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-200/70 shadow-xs transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-3">
                
                <!-- Logo & Nome do Restaurante -->
                <a href="#" class="flex items-center gap-3.5 group">
                    @if($logo && file_exists(public_path('storage/' . $logo)))
                        <img src="{{ asset('storage/' . $logo) }}" alt="{{ $companyName }}" class="h-12 w-auto max-w-[130px] object-contain">
                    @else
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center text-white shadow-md shadow-orange-500/20 group-hover:scale-105 transition-transform">
                            <i class="mdi mdi-silverware-fork-knife text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-lg md:text-xl font-black text-gray-900 tracking-tight">{{ $companyName }}</h1>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                Aberto
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-500 font-medium line-clamp-1">
                            {{ $companySubtitle }}
                        </p>
                    </div>
                </a>

                <!-- Links de Navegação Rápida (Desktop) -->
                <nav class="hidden xl:flex items-center gap-6 text-xs font-extrabold text-gray-600">
                    <a href="#menu" class="hover:text-orange-600 transition-colors flex items-center gap-1">
                        <i class="mdi mdi-book-open-page-variant-outline text-base text-orange-500"></i>
                        <span>Cardápio</span>
                    </a>
                    <a href="#destaques" class="hover:text-orange-600 transition-colors flex items-center gap-1">
                        <i class="mdi mdi-star-four-points-outline text-base text-amber-500"></i>
                        <span>Destaques</span>
                    </a>
                    <a href="#sobre" class="hover:text-orange-600 transition-colors flex items-center gap-1">
                        <i class="mdi mdi-storefront-outline text-base text-orange-500"></i>
                        <span>Sobre Nós</span>
                    </a>
                    <a href="#contactos" class="hover:text-orange-600 transition-colors flex items-center gap-1">
                        <i class="mdi mdi-map-marker-radius-outline text-base text-orange-500"></i>
                        <span>Contactos</span>
                    </a>
                </nav>

                <!-- Barra de Pesquisa Rápida (Desktop) -->
                <div class="hidden md:flex flex-1 max-w-xs lg:max-w-sm mx-2">
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="mdi mdi-magnify text-lg"></i>
                        </span>
                        <input type="text"
                               x-model="searchQuery"
                               @input="scrollToMenuIfSearching()"
                               placeholder="Pesquisar prato, bebida ou ingrediente..."
                               class="w-full pl-10 pr-8 py-2.5 rounded-2xl bg-stone-100 border border-stone-200/80 text-xs font-semibold text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-orange-500 transition-all">
                        <button x-show="searchQuery" @click="searchQuery = ''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i class="mdi mdi-close-circle text-base"></i>
                        </button>
                    </div>
                </div>

                <!-- Botões de Ação do Cliente -->
                <div class="flex items-center gap-2">
                    
                    <!-- Mesa Detectada se veio via QR Code -->
                    <template x-if="tableNumber">
                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-50 text-orange-800 font-black text-xs border border-orange-200 shadow-2xs">
                            <i class="mdi mdi-table-chair text-orange-600 text-sm"></i>
                            <span x-text="'Mesa ' + tableNumber"></span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse ml-0.5"></span>
                        </div>
                    </template>

                    <!-- Botão Chamar Garçom -->
                    <button type="button"
                            @click="openWaiterModal()"
                            class="px-3 sm:px-3.5 py-2.5 rounded-2xl bg-amber-50 hover:bg-amber-100 text-amber-900 font-bold text-xs border border-amber-200 transition-all flex items-center gap-1.5 cursor-pointer"
                            title="Solicitar atendimento na mesa">
                        <i class="mdi mdi-bell-ring-outline text-base text-amber-600"></i>
                        <span class="hidden sm:inline">Chamar Atendente</span>
                    </button>

                    <!-- Botão da Comanda / Pedido Online -->
                    <button type="button"
                            @click="cartOpen = true"
                            style="background-color: {{ $primaryColor }};"
                            class="relative text-white font-extrabold px-4 py-2.5 rounded-2xl shadow-lg shadow-orange-500/25 hover:opacity-95 active:scale-95 transition-all flex items-center gap-2 cursor-pointer">
                        <i class="mdi mdi-shopping-outline text-lg"></i>
                        <span class="hidden sm:inline text-xs">Pedido Online</span>
                        <span class="bg-white text-gray-900 text-[11px] font-black px-2 py-0.5 rounded-full shadow-2xs"
                              x-text="cartCount">
                        </span>
                        <span class="hidden lg:inline text-xs border-l border-white/30 pl-2"
                              x-text="cartTotalFormatted">
                        </span>
                    </button>

                    <!-- Acesso Staff / Admin -->
                    @auth
                        <a href="{{ url('/website') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2.5 rounded-2xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-extrabold border border-indigo-200 transition-all" title="Gerir Página Web (CMS)">
                            <i class="mdi mdi-web text-base"></i>
                            <span class="hidden lg:inline">CMS</span>
                        </a>
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
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-stone-100 border border-stone-200 text-xs font-semibold text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-orange-500">
                </div>
            </div>
        </div>
    </header>

    <!-- HERO BANNER SLIDER / CARROSSEL -->
    <section class="relative bg-gray-950 overflow-hidden">
        <div class="relative h-[380px] sm:h-[440px] lg:h-[500px] w-full">
            <template x-for="(slide, idx) in slides" :key="idx">
                <div x-show="activeSlide === idx"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0">
                    
                    <!-- Imagem de Fundo do Slide -->
                    <img :src="slide.image_url"
                         :alt="slide.title"
                         class="w-full h-full object-cover object-center">
                    
                    <!-- Gradiente Sofisticado Estilo Restaurante -->
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-950/95 via-gray-950/75 to-gray-950/30"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950/90 via-transparent to-black/30"></div>

                    <!-- Conteúdo do Slide -->
                    <div class="relative z-10 h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
                        <div class="max-w-2xl space-y-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <template x-if="tableNumber">
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-extrabold bg-orange-500 text-white shadow-lg shadow-orange-500/30">
                                        <i class="mdi mdi-table-chair"></i> Atendimento na Mesa <strong x-text="tableNumber"></strong>
                                    </span>
                                </template>
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-extrabold bg-white/15 backdrop-blur-md text-amber-300 border border-white/20">
                                    <i class="mdi mdi-silverware-clean"></i>
                                    <span x-text="slide.badge"></span>
                                </span>
                            </div>

                            <h2 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight tracking-tight drop-shadow-sm"
                                x-text="slide.title">
                            </h2>

                            <p class="text-xs sm:text-sm lg:text-base text-gray-200 font-medium leading-relaxed max-w-xl"
                               x-text="slide.subtitle">
                            </p>

                            <div class="pt-2 flex flex-wrap items-center gap-3">
                                <a :href="slide.cta_link || '#menu'"
                                   style="background-color: {{ $primaryColor }};"
                                   class="px-6 py-3.5 rounded-2xl text-white font-extrabold text-xs sm:text-sm shadow-xl shadow-orange-500/30 hover:opacity-95 active:scale-95 transition-all inline-flex items-center gap-2">
                                    <i class="mdi mdi-silverware-fork-knife text-base"></i>
                                    <span x-text="slide.cta_text || 'Explorar Cardápio'"></span>
                                </a>

                                <button type="button"
                                        @click="serviceType = 'takeaway'; cartOpen = true"
                                        class="px-5 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-md text-white font-bold text-xs sm:text-sm border border-white/25 transition-all inline-flex items-center gap-2 cursor-pointer">
                                    <i class="mdi mdi-shopping-outline text-amber-400 text-base"></i>
                                    <span>Encomendar Para Levar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Setas de Navegação do Slider -->
            <template x-if="slides.length > 1">
                <div>
                    <button type="button"
                            @click="prevSlide()"
                            class="hidden sm:flex absolute left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/40 hover:bg-black/70 text-white border border-white/20 backdrop-blur-md items-center justify-center transition-all cursor-pointer"
                            title="Slide Anterior">
                        <i class="mdi mdi-chevron-left text-2xl"></i>
                    </button>
                    <button type="button"
                            @click="nextSlide()"
                            class="hidden sm:flex absolute right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/40 hover:bg-black/70 text-white border border-white/20 backdrop-blur-md items-center justify-center transition-all cursor-pointer"
                            title="Próximo Slide">
                        <i class="mdi mdi-chevron-right text-2xl"></i>
                    </button>

                    <!-- Indicadores (Dots) -->
                    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2 bg-black/40 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/15">
                        <template x-for="(slide, idx) in slides" :key="'dot-' + idx">
                            <button type="button"
                                    @click="goToSlide(idx)"
                                    class="h-2 rounded-full transition-all cursor-pointer"
                                    :class="activeSlide === idx ? 'w-7 bg-orange-500' : 'w-2 bg-white/50 hover:bg-white'"></button>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </section>

    <!-- Faixa de Diferenciais / Experiência Gastronómica -->
    <section class="bg-white border-b border-stone-200/80 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center flex-shrink-0">
                        <i class="mdi mdi-chef-hat text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold text-gray-900">Preparado na Hora</h4>
                        <p class="text-[11px] text-gray-500">Ingredientes frescos todos os dias</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <i class="mdi mdi-cellphone-check text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold text-gray-900">Pedidos Online & Mesa</h4>
                        <p class="text-[11px] text-gray-500">Sem filas, direto para a cozinha</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                        <i class="mdi mdi-clock-fast text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold text-gray-900">Status ao Vivo</h4>
                        <p class="text-[11px] text-gray-500">Acompanhe cada etapa do preparo</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <i class="mdi mdi-credit-card-check-outline text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold text-gray-900">Pagamento Flexível</h4>
                        <p class="text-[11px] text-gray-500">Numerário, POS, M-Pesa ou E-Mola</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Conteúdo Principal -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">

        <!-- SECÇÃO: SUGESTÕES & DESTAQUES DA CASA -->
        @if(count($featuredProducts) > 0)
        <section id="destaques" class="scroll-mt-28 space-y-4">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-orange-600 block">Seleccionados para Si</span>
                    <h3 class="font-serif text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Sugestões & Destaques da Casa</h3>
                </div>
                <a href="#menu" class="text-xs font-extrabold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                    <span>Ver Menu Completo</span>
                    <i class="mdi mdi-arrow-right"></i>
                </a>
            </div>

            <div class="flex items-stretch gap-4 overflow-x-auto hide-scrollbar pb-2">
                <template x-for="item in featuredProducts" :key="'feat-' + item.id">
                    <div class="w-64 sm:w-72 flex-shrink-0 bg-white rounded-3xl border border-stone-200/80 shadow-xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col justify-between group">
                        <div class="relative h-44 overflow-hidden cursor-pointer" @click="openItemModal(item)">
                            <img :src="item.image_url"
                                 :alt="item.name"
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-transparent to-transparent"></div>
                            <span class="absolute top-3 left-3 bg-amber-500 text-white text-[10px] font-black px-2.5 py-1 rounded-xl shadow-sm flex items-center gap-1">
                                <i class="mdi mdi-star"></i> Destaque
                            </span>
                            <span class="absolute bottom-3 left-3 right-3 text-white font-extrabold text-sm line-clamp-1 drop-shadow-xs" x-text="item.name"></span>
                        </div>
                        <div class="p-4 flex items-center justify-between gap-2 bg-white">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase block" x-text="item.category_name"></span>
                                <span class="text-sm font-black text-orange-600" x-text="item.price_formatted"></span>
                            </div>
                            <button type="button"
                                    @click="addToCart(item)"
                                    class="px-3.5 py-2 rounded-xl bg-gray-900 hover:bg-orange-600 text-white font-bold text-xs transition-all flex items-center gap-1 cursor-pointer">
                                <i class="mdi mdi-plus text-sm"></i>
                                <span>Pedir</span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </section>
        @endif

        <!-- SECÇÃO PRINCIPAL DO MENU (ESTILO SOUTH BEACH: GALERIA DE FOTOS + LISTA CLÁSSICA DE RESTAURANTE) -->
        <section id="menu" class="scroll-mt-24 space-y-6">

            <!-- Cabeçalho do Cardápio & Alternador de Vista -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-stone-200 pb-4">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-orange-600 block">Gastronomia & Bebidas</span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Nosso Cardápio Completo</h2>
                </div>

                <!-- Alternador de Vista: Galeria de Fotos vs Lista Clássica -->
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <span class="text-xs font-bold text-gray-500 mr-1 hidden sm:inline">Modo de Exibição:</span>
                    <div class="inline-flex p-1 rounded-2xl bg-stone-200/80 border border-stone-300/60">
                        <button type="button"
                                @click="setMenuView('grid')"
                                :class="menuView === 'grid' ? 'bg-white text-gray-900 shadow-xs font-black' : 'text-gray-600 hover:text-gray-900 font-bold'"
                                class="px-3.5 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition-all cursor-pointer">
                            <i class="mdi mdi-view-grid-outline text-sm text-orange-500"></i>
                            <span>Galeria com Fotos</span>
                        </button>
                        <button type="button"
                                @click="setMenuView('classic')"
                                :class="menuView === 'classic' ? 'bg-white text-gray-900 shadow-xs font-black' : 'text-gray-600 hover:text-gray-900 font-bold'"
                                class="px-3.5 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition-all cursor-pointer">
                            <i class="mdi mdi-format-list-text text-sm text-orange-500"></i>
                            <span>Menu Clássico</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Barra Sticky de Categorias -->
            <div class="sticky top-20 z-30 bg-stone-50/95 backdrop-blur-md py-2.5 border-b border-stone-200/60">
                <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar pb-1">
                    <button type="button"
                            @click="selectedCategory = 'all'"
                            :class="selectedCategory === 'all' ? 'bg-gray-900 text-white shadow-md shadow-gray-900/20' : 'bg-white text-gray-700 hover:bg-stone-100 border border-stone-200'"
                            class="px-4 py-2.5 rounded-2xl text-xs font-black transition-all flex items-center gap-2 flex-shrink-0 cursor-pointer">
                        <i class="mdi mdi-silverware-variant text-base"></i>
                        <span>Todo o Cardápio</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px]"
                              :class="selectedCategory === 'all' ? 'bg-white/20 text-white' : 'bg-stone-100 text-gray-500'"
                              x-text="products.length">
                        </span>
                    </button>

                    @foreach($categories as $category)
                        <button type="button"
                                @click="selectedCategory = {{ $category->id }}"
                                :class="selectedCategory === {{ $category->id }} ? 'bg-orange-500 text-white shadow-md shadow-orange-500/25' : 'bg-white text-gray-700 hover:bg-stone-100 border border-stone-200'"
                                class="px-4 py-2.5 rounded-2xl text-xs font-black transition-all flex items-center gap-2 flex-shrink-0 cursor-pointer">
                            <i class="mdi mdi-food-fork-drink text-base"></i>
                            <span>{{ $category->name }}</span>
                            <span class="px-1.5 py-0.5 rounded-full text-[10px]"
                                  :class="selectedCategory === {{ $category->id }} ? 'bg-white/20 text-white' : 'bg-stone-100 text-gray-500'">
                                {{ $category->products->count() }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Subtítulo da Categoria Selecionada -->
            <div class="flex items-center justify-between">
                <h3 class="text-base font-black text-gray-900 tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-orange-500 rounded-full"></span>
                    <span x-text="categoryTitle"></span>
                </h3>
                <span class="text-xs text-gray-500 font-semibold" x-text="filteredProducts.length + ' pratos e bebidas disponíveis'"></span>
            </div>

            <!-- MODO 1: GALERIA DE CARTÕES COM FOTOS -->
            <div x-show="menuView === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div class="bg-white rounded-3xl border border-stone-200/80 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                        
                        <!-- Foto do Prato -->
                        <div class="relative h-48 bg-stone-100 overflow-hidden cursor-pointer" @click="openItemModal(product)">
                            <img :src="product.image_url"
                                 :alt="product.name"
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-60"></div>

                            <!-- Badge da Categoria -->
                            <span class="absolute top-3 left-3 bg-white/95 backdrop-blur-md text-gray-800 text-[10px] font-black px-2.5 py-1 rounded-xl shadow-2xs"
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
                                <h4 class="font-extrabold text-sm text-gray-900 group-hover:text-orange-600 transition-colors line-clamp-1 cursor-pointer"
                                    @click="openItemModal(product)"
                                    x-text="product.name"></h4>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2 min-h-[32px]"
                                   x-text="product.description || 'Preparado na hora com ingredientes frescos e selecionados.'"></p>
                            </div>

                            <!-- Preço e Botão Adicionar -->
                            <div class="flex items-center justify-between pt-4 mt-2 border-t border-stone-100">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Preço</span>
                                    <span class="text-base font-black text-gray-900" x-text="product.price_formatted"></span>
                                </div>

                                <!-- Ações de Adicionar / Contador -->
                                <template x-if="getCartQty(product.id) === 0">
                                    <button type="button"
                                            @click="addToCart(product)"
                                            class="px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-xs shadow-sm shadow-orange-500/25 transition-all flex items-center gap-1.5 cursor-pointer">
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

            <!-- MODO 2: MENU CLÁSSICO DE RESTAURANTE (ESTILO SOUTH BEACH COM LINHA PONTILHADA & MINIATURAS) -->
            <div x-show="menuView === 'classic'" class="bg-white rounded-3xl border border-stone-200/90 shadow-xs p-5 sm:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-10 gap-y-5">
                    <template x-for="product in filteredProducts" :key="'classic-' + product.id">
                        <div class="flex items-center gap-4 py-3 border-b border-stone-100 group">
                            <!-- Miniatura Redonda/Quadrada Elegante -->
                            <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden flex-shrink-0 border border-stone-200 cursor-pointer"
                                 @click="openItemModal(product)">
                                <img :src="product.image_url"
                                     :alt="product.name"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                <template x-if="getCartQty(product.id) > 0">
                                    <span class="absolute top-1 right-1 bg-orange-500 text-white text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center shadow-sm"
                                          x-text="getCartQty(product.id)"></span>
                                </template>
                            </div>

                            <!-- Nome + Linha Pontilhada + Preço -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-baseline gap-2">
                                    <h4 class="font-extrabold text-sm text-gray-900 group-hover:text-orange-600 transition-colors cursor-pointer truncate"
                                        @click="openItemModal(product)"
                                        x-text="product.name"></h4>
                                    <div class="flex-1 menu-leader h-3 hidden sm:block"></div>
                                    <span class="font-black text-sm text-orange-600 flex-shrink-0" x-text="product.price_formatted"></span>
                                </div>
                                <p class="text-[11px] text-gray-500 line-clamp-1 mt-0.5"
                                   x-text="product.description || product.category_name"></p>
                            </div>

                            <!-- Botão Rápido Adicionar -->
                            <div class="flex-shrink-0">
                                <template x-if="getCartQty(product.id) === 0">
                                    <button type="button"
                                            @click="addToCart(product)"
                                            class="px-3 py-2 rounded-xl bg-stone-100 hover:bg-orange-500 text-gray-800 hover:text-white font-bold text-xs transition-all flex items-center gap-1 cursor-pointer">
                                        <i class="mdi mdi-plus text-sm"></i>
                                        <span class="hidden sm:inline">Pedir</span>
                                    </button>
                                </template>
                                <template x-if="getCartQty(product.id) > 0">
                                    <div class="flex items-center gap-1 bg-orange-500 text-white rounded-xl p-1">
                                        <button type="button" @click="decreaseQty(product.id)" class="w-6 h-6 rounded-lg bg-orange-600 flex items-center justify-center text-xs font-black">
                                            <i class="mdi mdi-minus"></i>
                                        </button>
                                        <span class="text-xs font-black px-1" x-text="getCartQty(product.id)"></span>
                                        <button type="button" @click="addToCart(product)" class="w-6 h-6 rounded-lg bg-orange-600 flex items-center justify-center text-xs font-black">
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Caso Nenhum Item Encontrado -->
            <div x-show="filteredProducts.length === 0" class="py-16 text-center space-y-3 bg-white rounded-3xl border border-stone-200">
                <div class="w-16 h-16 rounded-full bg-stone-100 text-gray-400 mx-auto flex items-center justify-center text-3xl">
                    <i class="mdi mdi-food-off"></i>
                </div>
                <h4 class="font-bold text-gray-800 text-sm">Nenhum prato ou bebida encontrado</h4>
                <p class="text-xs text-gray-400 max-w-sm mx-auto">
                    Tente buscar por outro termo ou selecione uma categoria diferente acima.
                </p>
                <button type="button" @click="searchQuery = ''; selectedCategory = 'all'"
                        class="px-4 py-2 rounded-xl bg-orange-500 text-white font-bold text-xs hover:bg-orange-600 transition-colors cursor-pointer">
                    Ver Todos os Itens
                </button>
            </div>
        </section>

        <!-- SECÇÃO: SOBRE NÓS & HORÁRIO / CONTACTOS (GERIDO PELO CMS) -->
        <section id="sobre" class="scroll-mt-24 pt-6">
            <div class="bg-white rounded-3xl border border-stone-200/80 shadow-xs overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12">
                    <!-- Imagem Sobre Nós -->
                    <div class="lg:col-span-5 relative min-h-[260px] lg:min-h-full bg-stone-900">
                        <img src="{{ $aboutImageUrl }}" alt="{{ $aboutTitle }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6 text-white">
                            <span class="px-3 py-1 rounded-full bg-orange-500 text-white text-[10px] font-black uppercase tracking-wider">
                                {{ $companyName }}
                            </span>
                            <p class="font-serif text-xl font-bold mt-2">Qualidade & Sabor em Cada Detalhe</p>
                        </div>
                    </div>

                    <!-- Texto Sobre Nós & Contactos -->
                    <div id="contactos" class="lg:col-span-7 p-6 sm:p-10 flex flex-col justify-between space-y-6">
                        <div class="space-y-3">
                            <span class="text-[11px] font-extrabold uppercase tracking-widest text-orange-600 block">Nossa História & Espaço</span>
                            <h3 class="font-serif text-2xl sm:text-3xl font-bold text-gray-900">{{ $aboutTitle }}</h3>
                            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $aboutText }}</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-stone-100">
                            <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200/60 flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0">
                                    <i class="mdi mdi-clock-outline text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-extrabold text-gray-900">Horário de Atendimento</h4>
                                    <p class="text-xs text-gray-600 mt-0.5">{{ $openingHours }}</p>
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200/60 flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                    <i class="mdi mdi-phone-in-talk-outline text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-extrabold text-gray-900">Reservas & Encomendas</h4>
                                    @if(!empty($companyPhone))
                                        <p class="text-xs text-gray-600 mt-0.5">{{ $companyPhone }}</p>
                                    @endif
                                    @if(!empty($companyAddress))
                                        <p class="text-[11px] text-gray-500 mt-0.5">{{ $companyAddress }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <button type="button"
                                    @click="cartOpen = true"
                                    style="background-color: {{ $primaryColor }};"
                                    class="px-5 py-3 rounded-2xl text-white font-extrabold text-xs shadow-md shadow-orange-500/20 hover:opacity-95 transition-all flex items-center gap-2 cursor-pointer">
                                <i class="mdi mdi-silverware-fork-knife text-base"></i>
                                <span>Fazer Pedido Online</span>
                            </button>

                            @if(!empty($whatsappClean))
                                <a href="https://wa.me/{{ $whatsappClean }}"
                                   target="_blank"
                                   rel="noopener"
                                   class="px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md shadow-emerald-600/20 transition-all flex items-center gap-2">
                                    <i class="mdi mdi-whatsapp text-base"></i>
                                    <span>Falar no WhatsApp</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
                <span class="text-xs font-bold">Finalizar Pedido / Comanda</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm font-black" x-text="cartTotalFormatted"></span>
                <i class="mdi mdi-chevron-right text-xl"></i>
            </div>
        </button>
    </div>

    <!-- Gaveta Lateral da Comanda / Pedido Online (Slide-Over) -->
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
                            <h3 class="text-base font-black text-gray-900">Seu Pedido / Comanda</h3>
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
                                Toque em "Pedir" em qualquer prato ou bebida do menu para adicionar ao seu pedido.
                            </p>
                            <button type="button" @click="cartOpen = false"
                                    class="px-5 py-2.5 rounded-xl bg-gray-900 text-white font-bold text-xs hover:bg-black transition-colors cursor-pointer">
                                Explorar o Cardápio
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
                                <button type="button" @click="removeFromCart(index)" class="text-gray-400 hover:text-rose-600 transition-colors p-1" title="Remover item">
                                    <i class="mdi mdi-trash-can-outline text-base"></i>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Identificação do Pedido -->
                    <template x-if="cart.length > 0">
                        <div class="space-y-4 pt-4 border-t border-gray-200">
                            
                            <!-- Tipo de Atendimento: Mesa ou Levar -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Como deseja o seu pedido?</label>
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
                                        <span>Pedido Online / Levar</span>
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
                                        <template x-if="tableNumber">
                                            <div class="mt-1.5 flex items-center gap-1.5 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                                                <i class="mdi mdi-qrcode-scan text-emerald-600"></i>
                                                <span>Identificada via QR Code da Mesa <span x-text="tableNumber"></span></span>
                                            </div>
                                        </template>
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

                            <!-- Caso Para Levar / Pedido Online -->
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
                                                   placeholder="Nome de quem vai levantar o pedido"
                                                   class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold focus:bg-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                        </div>
                                    </div>

                                    <!-- Telefone para Aviso de Pronto -->
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1">
                                            Telefone / WhatsApp (para aviso quando estiver pronto)
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
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-orange-600 block" x-text="modalItem.category_name"></span>
                            <h3 class="text-base font-black text-gray-900" x-text="modalItem.name"></h3>
                            <span class="text-sm font-black text-orange-600" x-text="modalItem.price_formatted"></span>
                        </div>
                        <button @click="itemModalOpen = false" class="text-gray-400 hover:text-gray-600 cursor-pointer"><i class="mdi mdi-close text-2xl"></i></button>
                    </div>

                    <div class="py-3 space-y-3">
                        <template x-if="modalItem.image_url">
                            <img :src="modalItem.image_url" :alt="modalItem.name" class="w-full h-48 object-cover rounded-2xl">
                        </template>
                        <p class="text-xs text-gray-500 leading-relaxed" x-text="modalItem.description || 'Prato preparado na hora com os melhores ingredientes.'"></p>

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
                            <span>Adicionar ao Pedido</span>
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
                <button type="button" @click="waiterModalOpen = false" class="flex-1 py-3 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-200 cursor-pointer">
                    Cancelar
                </button>
                <button type="button" @click="confirmCallWaiter()" class="flex-1 py-3 rounded-xl bg-orange-500 text-white text-xs font-bold hover:bg-orange-600 shadow-md shadow-orange-500/20 cursor-pointer">
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
                                <span class="flex items-center justify-center gap-1.5">
                                    <i class="mdi mdi-clipboard-text-clock-outline text-amber-700 text-base"></i>
                                    <span>Pedido recebido! A cozinha está organizando os preparativos.</span>
                                </span>
                            </template>
                            <template x-if="trackedOrder.status_step === 2">
                                <span class="flex items-center justify-center gap-1.5">
                                    <i class="mdi mdi-fire text-sky-600 text-base"></i>
                                    <span>Os cozinheiros estão preparando seus pratos com todo cuidado.</span>
                                </span>
                            </template>
                            <template x-if="trackedOrder.status_step === 3">
                                <span class="text-emerald-800 text-sm font-black flex items-center justify-center gap-1.5">
                                    <i class="mdi mdi-bell-ring animate-bounce text-base"></i>
                                    <span>SEU PEDIDO ESTÁ PRONTO! Aguarde na mesa ou dirija-se ao balcão.</span>
                                </span>
                            </template>
                            <template x-if="trackedOrder.status_step >= 4">
                                <span class="flex items-center justify-center gap-1.5">
                                    <i class="mdi mdi-check-decagram text-purple-600 text-base"></i>
                                    <span>Pedido entregue! Esperamos que aprecie sua refeição.</span>
                                </span>
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
                                class="w-full py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold transition-colors cursor-pointer">
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

    <!-- Rodapé Elegante -->
    <footer class="bg-gray-950 text-gray-400 text-xs pt-12 pb-8 border-t border-gray-800 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-8 border-b border-gray-800/80">
                <div class="space-y-2">
                    <h4 class="font-serif text-lg font-bold text-white">{{ $companyName }}</h4>
                    <p class="text-xs text-gray-400 leading-relaxed">{{ $companySubtitle }}</p>
                    @if(!empty($openingHours))
                        <p class="text-[11px] text-orange-400 font-semibold pt-1">
                            <i class="mdi mdi-clock-outline mr-1"></i>{{ $openingHours }}
                        </p>
                    @endif
                </div>

                <div class="space-y-2">
                    <h5 class="text-xs font-extrabold uppercase tracking-wider text-gray-300">Acesso Rápido</h5>
                    <ul class="space-y-1.5 text-xs">
                        <li><a href="#menu" class="hover:text-white transition-colors">Cardápio Digital</a></li>
                        <li><a href="#destaques" class="hover:text-white transition-colors">Sugestões do Chef</a></li>
                        <li><a href="#sobre" class="hover:text-white transition-colors">Sobre Nós</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Área de Funcionários</a></li>
                    </ul>
                </div>

                <div class="space-y-2">
                    <h5 class="text-xs font-extrabold uppercase tracking-wider text-gray-300">Contactos & Localização</h5>
                    @if(!empty($companyAddress))
                        <p class="text-xs text-gray-400"><i class="mdi mdi-map-marker-outline text-orange-400 mr-1"></i>{{ $companyAddress }}</p>
                    @endif
                    @if(!empty($companyPhone))
                        <p class="text-xs text-gray-400"><i class="mdi mdi-phone-outline text-orange-400 mr-1"></i>{{ $companyPhone }}</p>
                    @endif
                    @if(!empty($companyEmail))
                        <p class="text-xs text-gray-400"><i class="mdi mdi-email-outline text-orange-400 mr-1"></i>{{ $companyEmail }}</p>
                    @endif
                </div>
            </div>

            <div class="pt-6 text-center text-[11px] text-gray-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p>&copy; {{ date('Y') }} {{ $companyName }}. Todos os direitos reservados.</p>
                <p>Cardápio Digital & Pedidos Online powered by RestPOS</p>
            </div>
        </div>
    </footer>

    <!-- Lógica Alpine.js -->
    <script>
    function restaurantMenuApp() {
        return {
            // Hero Slider
            slides: @json($sliderItems, JSON_UNESCAPED_UNICODE),
            activeSlide: 0,
            slideTimer: null,

            // View & Filter State
            menuView: localStorage.getItem('restpos_welcome_view') || 'grid',
            selectedCategory: 'all',
            searchQuery: '',

            // Order & Table State
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

            systemTables: @json($tables->map(fn($t) => ['id' => $t->id, 'number' => $t->number]), JSON_UNESCAPED_UNICODE),
            products: @json($allProducts, JSON_UNESCAPED_UNICODE),
            featuredProducts: @json($featuredProducts, JSON_UNESCAPED_UNICODE),

            init() {
                // Start Hero Slider Auto-play
                if (this.slides.length > 1) {
                    this.startSlideTimer();
                }

                const savedOrderId = localStorage.getItem('cafelu_active_order_id');
                if (savedOrderId) {
                    this.activeOrderId = parseInt(savedOrderId);
                    this.fetchOrderStatus(this.activeOrderId);
                    this.startTrackingPoll();
                }
            },

            startSlideTimer() {
                if (this.slideTimer) clearInterval(this.slideTimer);
                this.slideTimer = setInterval(() => {
                    this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                }, 6000);
            },

            nextSlide() {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                this.startSlideTimer();
            },

            prevSlide() {
                this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                this.startSlideTimer();
            },

            goToSlide(idx) {
                this.activeSlide = idx;
                this.startSlideTimer();
            },

            setMenuView(mode) {
                this.menuView = mode;
                localStorage.setItem('restpos_welcome_view', mode);
            },

            scrollToMenuIfSearching() {
                if (this.searchQuery.trim().length === 1) {
                    const el = document.getElementById('menu');
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
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
                fetch('{{ url("/order-status") }}/' + orderId)
                    .then(res => {
                        if (!res.ok) throw new Error('Status query failed');
                        return res.json();
                    })
                    .then(data => {
                        if (data && data.success) {
                            const prevStatus = this.trackedOrder ? this.trackedOrder.status : null;
                            this.trackedOrder = data;

                            if (!this.selectedTableId && data.table_id) {
                                this.selectedTableId = String(data.table_id);
                                this.tableNumber = (data.table_name || '').replace(/[^0-9]/g, '');
                            }

                            if (data.status === 'ready' && prevStatus && prevStatus !== 'ready') {
                                this.playChime();
                            }
                        }
                    })
                    .catch(() => {});
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
                    osc.frequency.setValueAtTime(587.33, now);
                    osc.frequency.setValueAtTime(880.00, now + 0.15);
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
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('{{ asset("sw.js") }}').catch(err => {
                    console.warn('SW registration skipped:', err);
                });
            });
        }
    </script>
</body>
</html>
