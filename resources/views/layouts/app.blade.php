<!DOCTYPE html>
<html lang="pt" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistema de Gestão ZALALA BEACH BAR - Restaurante e Bar">
    <meta name="author" content="ZALALA BEACH BAR">

    <title>ZALALA BEACH BAR - @yield('title', 'Sistema de Gestão')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Dancing+Script:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Costom -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/orders.css') }}"> --}}

    <style>
        :root {
            /* Cores do tema praia/beach */
            --primary-color: #0891b2;
            --primary-dark: #0e7490;
            --secondary-color: #f59e0b;
            --secondary-dark: #d97706;
            --accent-color: #06b6d4;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --dark-color: #1f2937;
            --light-color: #f8fafc;

            /* Gradientes */
            --primary-gradient: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            --secondary-gradient: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            --beach-gradient: linear-gradient(135deg, #0891b2 0%, #06b6d4 50%, #fbbf24 100%);
            --sunset-gradient: linear-gradient(135deg, #f59e0b 0%, #fb923c 50%, #f97316 100%);

            /* Sombras */
            --shadow-soft: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);

            /* Outros */
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 320px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #c5f1fc 0%, #06b6d4 50%, #fbbf24 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(8, 145, 178, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(6, 182, 212, 0.03) 0%, transparent 50%);
            z-index: -1;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--beach-gradient);
            box-shadow: var(--shadow-xl);
            z-index: 1040;
            transition: transform 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        /* Sidebar Brand */
        .sidebar-brand {
            background: rgba(0, 0, 0, 0.15);
            padding: 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            position: relative;
        }

        .sidebar-brand::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: 20px 20px;
            opacity: 0.3;
        }

        .brand-logo {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .brand-logo .logo-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 3px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .brand-title {
            font-family: 'montserrate', cursive;
            font-size: 1.6rem;
            font-weight: 700;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            margin: 0;
            line-height: 1.2;
        }

        .brand-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.85rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .business-info {
            background: rgba(0, 0, 0, 0.1);
            padding: 1rem;
            margin-top: 1rem;
            border-radius: var(--border-radius);
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            text-align: left;
        }

        .business-info .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .business-info .info-item:last-child {
            margin-bottom: 0;
        }

        .business-info i {
            width: 16px;
            margin-right: 0.5rem;
            color: var(--secondary-color);
        }

        /* Navigation */
        .sidebar-nav {
            padding: 1.5rem 1rem;
        }

        .top-navbar{
            background: linear-gradient(135deg, #c5f1fc 0%, #61fff7 50%, #fdd97c 100%) !important;
        }
        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            padding: 0 0.75rem;
            display: flex;
            align-items: center;
        }

        .nav-section-title i {
            margin-right: 0.5rem;
            font-size: 0.9rem;
        }

        .nav-pills .nav-link {
            color: rgba(255, 255, 255, 0.85);
            border-radius: var(--border-radius);
            margin: 0.25rem 0;
            padding: 0.875rem 1rem;
            transition: var(--transition);
            font-weight: 500;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .nav-pills .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: left 0.3s ease;
            z-index: 0;
        }

        .nav-pills .nav-link:hover::before {
            left: 0;
        }

        .nav-pills .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .nav-pills .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            box-shadow: var(--shadow-md);
            border-left: 4px solid var(--secondary-color);
        }

        .nav-pills .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 0.75rem;
            position: relative;
            z-index: 1;
            font-size: 1.1rem;
        }

        .nav-pills .nav-link span {
            position: relative;
            z-index: 1;
            flex-grow: 1;
        }

        .nav-pills .nav-link .badge {
            position: relative;
            z-index: 1;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        /* User Area */
        .user-area {
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(15px);
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding: 1.5rem 1rem;
            margin-top: auto;
        }

        .user-profile {
            display: flex;
            align-items: center;
            color: white;
            margin-bottom: 1rem;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }

        .user-avatar .status-dot {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            background: var(--success-color);
            border: 2px solid white;
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        .user-info .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-info .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* ===== TOP NAVBAR ===== */
        .top-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: var(--shadow-soft);
            position: sticky;
            top: 0;
            z-index: 1030;
            padding: 1rem 0;
        }

        .navbar-brand-mobile {
            font-family: 'montserrate', cursive;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin: 0;
        }

        .search-container {
            position: relative;
            max-width: 400px;
        }

        .search-input {
            border-radius: 25px;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.9);
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(8, 145, 178, 0.15);
            background: white;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }

        /* ===== CARDS ===== */
        .card {
            border: none;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-soft);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            transition: var(--transition);
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--beach-gradient);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .card:hover::before {
            transform: scaleX(1);
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        /* ===== BUTTONS ===== */
        .btn {
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: var(--transition);
            padding: 0.75rem 1.5rem;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--secondary-gradient);
            border: none;
            color: white;
        }

        .btn-secondary:hover {
            background: var(--secondary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ===== STATS CARDS ===== */
        .stats-card {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-soft);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--beach-gradient);
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stats-card .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stats-card.primary .stats-icon {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
        }

        .stats-card.secondary .stats-icon {
            background: var(--secondary-gradient);
            color: white;
        }

        .stats-card.success .stats-icon {
            background: linear-gradient(135deg, var(--success-color), #34d399);
            color: white;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040 !important;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }
        }

        /* ===== BREADCRUMB ===== */
        .breadcrumb {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius-lg);
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-soft);
        }

        /* ===== FOOTER ===== */
        .main-footer {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            margin-top: 3rem;
            box-shadow: var(--shadow-soft);
        }

        /* ===== ALERTS ===== */
        .alert {
            border: none;
            border-radius: var(--border-radius-lg);
            padding: 1rem 1.5rem;
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(10px);
        }

        /* ===== BADGES ===== */
        .badge {
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius);
            font-size: 0.75rem;
        }

        .badge.bg-success {
            background: var(--success-color) !important;
        }

        .badge.bg-warning {
            background: var(--warning-color) !important;
        }

        .badge.bg-danger {
            background: var(--danger-color) !important;
        }

        .badge.bg-info {
            background: var(--info-color) !important;
        }

        /* ===== CUSTOM SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <div class="brand-logo text-center">
                <div class="logo-icon mb-2">
                    <img src="{{ asset('assets/images/logo-zalala.png') }}" alt="ZALALA Logo"
                        style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                </div>
                <h1 class="brand-title">ZALALA BEACH BAR</h1>
                <div class="brand-subtitle">Restaurante • Bar • Gestão</div>
            </div>
            <div class="business-info">
                <div class="info-item">
                    <i class="mdi mdi-map-marker"></i>
                    <span>ER470, Bairro Zalala, Quelimane</span>
                </div>
                <div class="info-item">
                    <i class="mdi mdi-phone"></i>
                    <span>846885214</span>
                </div>
                <div class="info-item">
                    <i class="mdi mdi-card-account-details"></i>
                    <span>NUIT: 110735901</span>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="sidebar-nav">
            <!-- Dashboard - Todos os usuários -->
            <div class="nav-section">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="mdi mdi-view-dashboard text-warning"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- OPERACIONAL - Baseado em permissões operacionais -->
            @if (\App\Http\Middleware\PermissionHelper::canAny(['view_products', 'create_sales', 'view_orders', 'manage_tables']))
                <div class="nav-section">
                    <div class="nav-section-title">
                        <i class="mdi mdi-store"></i>
                        <span>Operacional</span>
                    </div>
                    <ul class="nav nav-pills flex-column">
                        <!-- PDV - Cashiers, Managers, Admin -->
                        @if (\App\Http\Middleware\PermissionHelper::canAll(['view_products', 'create_sales']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}"
                                    href="{{ route('pos.index') }}">
                                    <i class="mdi mdi-point-of-sale"></i>
                                    <span>PDV (Ponto de Venda)</span>
                                    <span class="badge bg-success ms-auto">Live</span>
                                </a>
                            </li>
                        @endif

                        <!-- Pedidos - Waiters, Managers, Admin -->
                        @if (\App\Http\Middleware\PermissionHelper::can('view_orders'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"
                                    href="{{ route('orders.index') }}">
                                    <i class="mdi mdi-receipt"></i>
                                    <span>Pedidos</span>
                                    @php
                                        $pendingOrdersCount = \App\Models\Order::whereIn('status', [
                                            'active',
                                            'pending',
                                        ])->count();
                                    @endphp
                                    @if ($pendingOrdersCount > 0)
                                        <span class="badge ms-auto" style="background: var(--warning-color);">
                                            {{ $pendingOrdersCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endif

                        <!-- Cozinha - Cooks, Managers, Admin -->
                        @if (\App\Http\Middleware\PermissionHelper::can('manage_kitchen'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('kitchen.*') ? 'active' : '' }}"
                                    href="{{ route('kitchen.dashboard') }}">
                                    <i class="mdi mdi-chef-hat"></i>
                                    <span>Cozinha</span>
                                    @php
                                        $kitchenOrders = \App\Models\Order::where('status', 'active')->count();
                                        $pendingItems = \App\Models\OrderItem::whereHas('order', function ($q) {
                                            $q->where('status', 'active');
                                        })
                                            ->where('status', 'pending')
                                            ->count();
                                    @endphp
                                    @if ($kitchenOrders > 0)
                                        <span class="badge ms-auto"
                                            style="background: {{ $pendingItems > 0 ? 'var(--warning-color)' : 'var(--success-color)' }};"
                                            title="Pedidos ativos: {{ $kitchenOrders }} | Itens pendentes: {{ $pendingItems }}">
                                            {{ $kitchenOrders }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endif

                        <!-- Mesas - Waiters, Managers, Admin -->
                        @if (\App\Http\Middleware\PermissionHelper::canAny(['view_orders', 'manage_tables']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('tables.*') ? 'active' : '' }}"
                                    href="{{ route('tables.index') }}">
                                    <i class="mdi mdi-table-chair"></i>
                                    <span>Mesas</span>
                                    @php
                                        $occupiedTables = \App\Models\Table::whereHas('orders', function ($query) {
                                            $query->whereIn('status', ['active', 'completed']);
                                        })->count();
                                        $totalTables = \App\Models\Table::count();
                                        $availableTables = $totalTables - $occupiedTables;
                                    @endphp
                                    <span class="badge ms-auto"
                                        title="Livre: {{ $availableTables }} | Ocupada: {{ $occupiedTables }}"
                                        style="background: {{ $availableTables > 0 ? 'var(--success-color)' : 'var(--danger-color)' }};">
                                        {{ $availableTables }}
                                    </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            <!-- MENU & PRODUTOS - Baseado em permissões de produtos -->
            @if (\App\Http\Middleware\PermissionHelper::canAny(['view_products', 'manage_categories', 'view_stock_movements']))
                <div class="nav-section">
                    <div class="nav-section-title">
                        <i class="mdi mdi-food-variant"></i>
                        <span>Menu & Produtos</span>
                    </div>
                    <ul class="nav nav-pills flex-column">
                        <!-- Produtos - Todos exceto cook básico -->
                        @if (\App\Http\Middleware\PermissionHelper::can('view_products'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                                    href="{{ route('products.index') }}">
                                    <i class="mdi mdi-food"></i>
                                    <span>Produtos</span>
                                    @php
                                        $lowStockCount = \App\Models\Product::whereColumn(
                                            'stock_quantity',
                                            '<=',
                                            'min_stock_level',
                                        )->count();
                                    @endphp
                                    @if ($lowStockCount > 0)
                                        <span class="badge bg-warning ms-auto" title="Produtos com estoque baixo">
                                            {{ $lowStockCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endif

                        <!-- Categorias - Managers e Admin -->
                        @if (\App\Http\Middleware\PermissionHelper::can('manage_categories'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                                    href="{{ route('categories.index') }}">
                                    <i class="mdi mdi-format-list-bulleted"></i>
                                    <span>Categorias</span>
                                </a>
                            </li>
                        @endif

                        <!-- Cardápio Digital - Todos com view_products -->
                        @if (\App\Http\Middleware\PermissionHelper::can('view_products'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}"
                                    href="{{ route('menu.index') }}">
                                    <i class="mdi mdi-book-open-variant"></i>
                                    <span>Cardápio Digital</span>
                                </a>
                            </li>
                        @endif

                        <!-- Movimentos de Stock - Managers e Admin -->
                        @if (\App\Http\Middleware\PermissionHelper::can('view_stock_movements'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}"
                                    href="{{ route('stock-movements.index') }}">
                                    <i class="mdi mdi-swap-vertical"></i>
                                    <span>Movimentos de Stock</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            <!-- VENDAS & FINANCEIRO - Baseado em permissões financeiras -->
            @if (\App\Http\Middleware\PermissionHelper::canAny(['view_sales', 'view_reports', 'view_expenses']))
                <div class="nav-section">
                    <div class="nav-section-title">
                        <i class="mdi mdi-cash-multiple"></i>
                        <span>Vendas & Financeiro</span>
                    </div>
                    <ul class="nav nav-pills flex-column">
                        <!-- Vendas - Cashiers, Managers, Admin -->
                        @if (\App\Http\Middleware\PermissionHelper::can('view_sales'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}"
                                    href="{{ route('sales.index') }}">
                                    <i class="mdi mdi-currency-usd"></i>
                                    <span>Vendas</span>
                                    @php
                                        $todaySalesCount = \App\Models\Sale::whereDate('created_at', today())->count();
                                    @endphp
                                    @if ($todaySalesCount > 0)
                                        <span class="badge bg-success ms-auto" title="Vendas hoje">
                                            {{ $todaySalesCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endif

                        <!-- Despesas - Managers e Admin -->
                        @if (\App\Http\Middleware\PermissionHelper::can('view_expenses'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}"
                                    href="{{ route('expenses.index') }}">
                                    <i class="mdi mdi-cash-remove"></i>
                                    <span>Despesas</span>
                                </a>
                            </li>
                        @endif

                        <!-- Relatórios - Baseado em permissões específicas -->
                        @if (\App\Http\Middleware\PermissionHelper::can('view_reports'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                                    href="{{ route('reports.index') }}">
                                    <i class="mdi mdi-chart-line"></i>
                                    <span>Relatórios</span>
                                    @if (\App\Http\Middleware\PermissionHelper::can('financial_reports'))
                                        <span class="badge bg-info ms-auto" title="Relatórios Financeiros">Pro</span>
                                    @endif
                                </a>
                            </li>
                        @endif

                        <!-- Fluxo de Caixa - Apenas com relatórios financeiros -->
                        @if (\App\Http\Middleware\PermissionHelper::can('financial_reports'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reports.cash-flow') ? 'active' : '' }}"
                                    href="{{ route('reports.cash-flow') }}">
                                    <i class="mdi mdi-bank"></i>
                                    <span>Fluxo de Caixa</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            <!-- RELACIONAMENTO - Clientes -->
            @if (\App\Http\Middleware\PermissionHelper::can('view_clients'))
                <div class="nav-section">
                    <div class="nav-section-title">
                        <i class="mdi mdi-account-group"></i>
                        <span>Relacionamento</span>
                    </div>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}"
                                href="{{ route('clients.index') }}">
                                <i class="mdi mdi-account-heart"></i>
                                <span>Clientes</span>
                                @php
                                    $totalClients = \App\Models\Client::count();
                                @endphp
                                @if ($totalClients > 0)
                                    <span class="badge bg-primary ms-auto">
                                        {{ $totalClients }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            @endif

            <!-- GESTÃO DE PESSOAS - Funcionários e Usuários -->
            @if (\App\Http\Middleware\PermissionHelper::canAny(['view_employees', 'view_users']))
                <div class="nav-section">
                    <div class="nav-section-title">
                        <i class="mdi mdi-shield-account"></i>
                        <span>Gestão de Pessoas</span>
                    </div>
                    <ul class="nav nav-pills flex-column">
                        <!-- Funcionários - Managers e Admin -->
                        @if (\App\Http\Middleware\PermissionHelper::can('view_employees'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}"
                                    href="{{ route('employees.index') }}">
                                    <i class="mdi mdi-account-tie"></i>
                                    <span>Funcionários</span>
                                    @php
                                        $totalEmployees = \App\Models\Employee::count();
                                    @endphp
                                    <span class="badge bg-secondary ms-auto">
                                        {{ $totalEmployees }}
                                    </span>
                                </a>
                            </li>
                        @endif

                        <!-- Usuários do Sistema - Apenas Admin -->
                        @if (auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                                    href="{{ route('users.index') }}">
                                    <i class="mdi mdi-account-key"></i>
                                    <span>Usuários do Sistema</span>
                                    @php
                                        $totalUsers = \App\Models\User::count();
                                    @endphp
                                    <span class="badge bg-danger ms-auto">
                                        {{ $totalUsers }}
                                    </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            <!-- SISTEMA - Configurações e Logs (Apenas Admin) -->
            @if (auth()->user()->role === 'admin')
                <div class="nav-section">
                    <div class="nav-section-title">
                        <i class="mdi mdi-cog-outline"></i>
                        <span>Sistema</span>
                    </div>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#"
                                onclick="showToast('Funcionalidade em desenvolvimento', 'info')">
                                <i class="mdi mdi-cog"></i>
                                <span>Configurações</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"
                                onclick="showToast('Backup automático ativo', 'success')">
                                <i class="mdi mdi-backup-restore"></i>
                                <span>Backup & Restore</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"
                                onclick="showToast('Logs de auditoria disponíveis', 'info')">
                                <i class="mdi mdi-file-document-outline"></i>
                                <span>Logs de Auditoria</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif

            <!-- ATALHOS RÁPIDOS - Baseado no role do usuário -->
            <div class="nav-section">
                <div class="nav-section-title">
                    <i class="mdi mdi-lightning-bolt"></i>
                    <span>Ações Rápidas</span>
                </div>
                <ul class="nav nav-pills flex-column">
                    @if (\App\Http\Middleware\PermissionHelper::can('create_orders'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tables.index') }}"
                                style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                                <i class="mdi mdi-plus-circle"></i>
                                <span>Novo Pedido</span>
                            </a>
                        </li>
                    @endif

                    @if (\App\Http\Middleware\PermissionHelper::can('create_products'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.create') }}"
                                style="background: rgba(245, 158, 11, 0.1); color: var(--warning-color);">
                                <i class="mdi mdi-food-variant"></i>
                                <span>Novo Produto</span>
                            </a>
                        </li>
                    @endif

                    @if (\App\Http\Middleware\PermissionHelper::can('create_clients'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('clients.create') }}"
                                style="background: rgba(59, 130, 246, 0.1); color: var(--info-color);">
                                <i class="mdi mdi-account-plus"></i>
                                <span>Novo Cliente</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- INDICADOR DE ROLE -->
            <div class="nav-section">
                <div class="user-role-indicator p-3 text-center">
                    @php
                        $roleInfo = [
                            'admin' => [
                                'icon' => 'mdi-shield-crown',
                                'color' => 'var(--danger-color)',
                                'name' => 'Administrador',
                            ],
                            'manager' => [
                                'icon' => 'mdi-account-star',
                                'color' => 'var(--warning-color)',
                                'name' => 'Gerente',
                            ],
                            'cashier' => [
                                'icon' => 'mdi-cash-register',
                                'color' => 'var(--success-color)',
                                'name' => 'Caixa',
                            ],
                            'waiter' => [
                                'icon' => 'mdi-account-tie',
                                'color' => 'var(--info-color)',
                                'name' => 'Garçom',
                            ],
                            'cook' => [
                                'icon' => 'mdi-chef-hat',
                                'color' => 'var(--secondary-color)',
                                'name' => 'Cozinheiro',
                            ],
                        ];
                        $currentRole = $roleInfo[auth()->user()->role] ?? $roleInfo['admin'];
                    @endphp
                    <div class="role-badge"
                        style="background: {{ $currentRole['color'] }}20; border: 2px solid {{ $currentRole['color'] }}30; border-radius: var(--border-radius); padding: 0.75rem;">
                        <i class="{{ $currentRole['icon'] }}"
                            style="color: {{ $currentRole['color'] }}; font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                        <div style="color: rgba(255,255,255,0.9); font-size: 0.8rem; font-weight: 600;">
                            {{ $currentRole['name'] }}
                        </div>
                        <div style="color: rgba(255,255,255,0.7); font-size: 0.7rem; margin-top: 0.25rem;">
                            Nível: {{ ucfirst(auth()->user()->role) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Left Section -->
                    <div class="d-flex align-items-center">
                        <!-- Mobile Sidebar Toggle -->
                        <button class="btn btn-outline-primary d-lg-none me-3" type="button" id="sidebar-toggle">
                            <i class="mdi mdi-menu"></i>
                        </button>

                        <!-- Page Title -->
                        <div class="d-flex align-items-center">
                            <i class="mdi @yield('title-icon', 'mdi-view-dashboard') text-primary me-2 fs-4"></i>
                            <div>
                                <h1 class="navbar-brand-mobile mb-0">@yield('page-title', 'Dashboard')</h1>
                                {{--  <small class="text-muted d-none d-md-block">@yield('page-subtitle', 'Painel de Controle')</small> --}}
                            </div>
                        </div>
                    </div>

                    <!-- Center Section - Search -->
                    <div class="search-container d-none d-md-block flex-grow-1 mx-4">
                        <div class="position-relative">
                            <i class="mdi mdi-magnify search-icon"></i>
                            <input type="text" class="form-control search-input"
                                placeholder="Buscar produtos, pedidos, clientes..." id="global-search">
                            <!-- Search Results Dropdown -->
                            <div class="dropdown-menu w-100 mt-1" id="search-results"
                                style="display: none; max-height: 300px; overflow-y: auto;">
                                <div class="p-3 text-center text-muted">
                                    <i class="mdi mdi-magnify"></i>
                                    Digite para buscar...
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Quick Actions -->
                        <div class="dropdown d-none d-md-block">
                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-lightning-bolt me-1"></i>
                                Ações Rápidas
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <h6 class="dropdown-header">Ações Rápidas</h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('pos.index') ?? '#' }}">
                                        <i class="mdi mdi-point-of-sale text-success me-2"></i>
                                        Abrir PDV
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('tables.index') ?? '#' }}">
                                        <i class="mdi mdi-plus-circle text-primary me-2"></i>
                                        Novo Pedido
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('products.create') ?? '#' }}">
                                        <i class="mdi mdi-food-variant text-warning me-2"></i>
                                        Adicionar Produto
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('reports.index') ?? '#' }}">
                                        <i class="mdi mdi-chart-line text-info me-2"></i>
                                        Relatórios
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Notifications -->
                        <div class="dropdown">
                            <button class="btn btn-outline-primary position-relative" type="button"
                                data-bs-toggle="dropdown" id="notifications-dropdown">
                                <i class="mdi mdi-bell-outline"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    id="notifications-badge">
                                    0
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end"
                                style="min-width: 380px; max-height: 500px; overflow-y: auto;">
                                <div class="dropdown-header d-flex justify-content-between align-items-center">
                                    <span>Notificações</span>
                                    <button class="btn btn-link btn-sm p-0" id="mark-all-read">
                                        <small>Marcar todas como lidas</small>
                                    </button>
                                </div>
                                <div class="dropdown-divider"></div>

                                <div id="notifications-list">
                                    <div class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Carregando...</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>
                                <div class="text-center p-2">
                                    <a href="{{ route('notifications.index') }}"
                                        class="btn btn-sm btn-primary w-100">
                                        Ver Todas as Notificações
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- User Profile -->
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center"
                                type="button" data-bs-toggle="dropdown">
                                <div class="user-avatar me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                                </div>
                                <span class="d-none d-lg-inline">{{ auth()->user()->name ?? 'Administrador' }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <div class="dropdown-header text-center">
                                        <div class="user-avatar mx-auto mb-2" style="width: 48px; height: 48px;">
                                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                                        </div>
                                        <div class="fw-semibold">{{ auth()->user()->name ?? 'Administrador' }}</div>
                                        <div class="text-muted small">
                                            {{ auth()->user()->email ?? 'admin@zalalabeachbar.com' }}</div>
                                    </div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') ?? '#' }}">
                                        <i class="mdi mdi-account-edit text-primary me-2"></i>
                                        Editar Perfil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="toggleTheme()">
                                        <i class="mdi mdi-theme-light-dark text-secondary me-2"></i>
                                        Alternar Tema
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="mdi mdi-cog text-secondary me-2"></i>
                                        Configurações
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') ?? '#' }}" class="mb-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="mdi mdi-logout me-2"></i>
                                            Sair do Sistema
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Search -->
        <div class="container-fluid d-md-none py-3">
            <div class="search-container">
                <div class="position-relative">
                    <i class="mdi mdi-magnify search-icon"></i>
                    <input type="text" class="form-control search-input" placeholder="Buscar...">
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="container-fluid p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="fade-in">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') ?? '#' }}" class="text-decoration-none">
                            <i class="mdi mdi-home"></i> Início
                        </a>
                    </li>
                    @yield('breadcrumbs')
                </ol>
            </nav>

            <!-- Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
                    <i class="mdi mdi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                    <i class="mdi mdi-alert-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show fade-in" role="alert">
                    <i class="mdi mdi-alert-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Content Area -->
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted">
                            © {{ date('Y') }} <strong>ZALALA BEACH BAR</strong> - Sistema de Gestão
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <small class="text-muted">
                            Versão 2.0 |
                            <a href="#" class="text-primary text-decoration-none">Suporte</a> |
                            <a href="#" class="text-primary text-decoration-none">Documentação</a>
                        </small>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/pos/printRecibo.js') }}"></script>
    <script>
        // ===== SIDEBAR MANAGEMENT =====
        class SidebarManager {
            constructor() {
                this.sidebar = document.getElementById('sidebar');
                this.mainContent = document.getElementById('main-content');
                this.overlay = document.getElementById('sidebar-overlay');
                this.toggle = document.getElementById('sidebar-toggle');
                this.isDesktop = window.innerWidth >= 992;
                this.isOpen = false;

                this.init();
            }

            init() {
                this.setupEventListeners();
                this.handleResize();
                window.addEventListener('resize', () => this.handleResize());
            }

            setupEventListeners() {
                this.toggle?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleSidebar();
                });

                this.overlay?.addEventListener('click', () => {
                    this.closeSidebar();
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.isOpen && !this.isDesktop) {
                        this.closeSidebar();
                    }
                });
            }

            toggleSidebar() {
                if (this.isOpen) {
                    this.closeSidebar();
                } else {
                    this.openSidebar();
                }
            }

            openSidebar() {
                if (!this.isDesktop) {
                    this.sidebar.classList.add('show');
                    this.overlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                } else {
                    this.sidebar.classList.remove('collapsed');
                    this.mainContent.classList.remove('expanded');
                }
                this.isOpen = true;
            }

            closeSidebar() {
                if (!this.isDesktop) {
                    this.sidebar.classList.remove('show');
                    this.overlay.classList.remove('show');
                    document.body.style.overflow = '';
                } else {
                    this.sidebar.classList.add('collapsed');
                    this.mainContent.classList.add('expanded');
                }
                this.isOpen = false;
            }

            handleResize() {
                const wasDesktop = this.isDesktop;
                this.isDesktop = window.innerWidth >= 992;

                if (wasDesktop !== this.isDesktop) {
                    this.sidebar.classList.remove('show', 'collapsed');
                    this.overlay.classList.remove('show');
                    this.mainContent.classList.remove('expanded');
                    document.body.style.overflow = '';
                    this.isOpen = false;
                }
            }
        }

        // ===== THEME TOGGLE =====
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            showToast(`Tema ${newTheme === 'dark' ? 'escuro' : 'claro'} ativado`, 'info');
        }

        // ===== TOAST NOTIFICATIONS =====
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container') || createToastContainer();

            const iconMap = {
                success: 'mdi-check-circle',
                error: 'mdi-alert-circle',
                warning: 'mdi-alert-triangle',
                info: 'mdi-information'
            };

            const colorMap = {
                success: 'text-bg-success',
                error: 'text-bg-danger',
                warning: 'text-bg-warning',
                info: 'text-bg-info'
            };

            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div class="toast ${colorMap[type] || 'text-bg-primary'}" role="alert" id="${toastId}">
                    <div class="toast-body d-flex align-items-center">
                        <i class="${iconMap[type] || 'mdi-information'} me-2"></i>
                        <span class="flex-grow-1">${message}</span>
                        <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHtml);

            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 4000
            });

            toast.show();

            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        }

        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
            return container;
        }

        // ===== PROFESSIONAL SEARCH MANAGER =====
        class ProfessionalSearch {
            constructor() {
                this.searchInput = document.querySelector('.search-input');
                this.searchTimeout = null;
                this.init();
            }

            init() {
                if (!this.searchInput) return;

                this.searchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.performSearch(e.target.value);
                    }
                });

                this.searchInput.addEventListener('input', (e) => {
                    if (e.target.value.length > 2) {
                        this.debounceSearch(e.target.value);
                    }
                });
            }

            debounceSearch(query) {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.performSearch(query);
                }, 500);
            }

            performSearch(query) {
                if (query.trim().length === 0) return;

                // Para Laravel, adapte a rota conforme necessário
                const searchUrl = window.location.origin + '/search?q=' + encodeURIComponent(query);
                window.location.href = searchUrl;
            }
        }

        // ===== INITIALIZATION =====
        document.addEventListener('DOMContentLoaded', function() {
            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);

            // Initialize components
            try {
                new SidebarManager();
                new ProfessionalSearch();
            } catch (error) {
                console.error('Error initializing components:', error);
            }

            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('.alert.show').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    setTimeout(() => bsAlert?.close(), 5000);
                });
            }, 100);

            // Show welcome message
            setTimeout(() => {
                console.log('Bem-vindo ao ZALALA BEACH BAR!', 'info');
            }, 1000);
        });
        // ===== NOTIFICATION MANAGER =====
        class NotificationManager {
            constructor() {
                this.badge = document.getElementById('notifications-badge');
                this.dropdown = document.getElementById('notifications-dropdown');
                this.list = document.getElementById('notifications-list');
                this.markAllBtn = document.getElementById('mark-all-read');
                this.unreadCount = 0;

                this.init();
            }

            init() {
                this.loadNotifications();
                this.setupEventListeners();
                this.startPolling();
            }

            setupEventListeners() {
                this.markAllBtn?.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.markAllAsRead();
                });

                // Atualizar notificações quando o dropdown for aberto
                this.dropdown?.addEventListener('show.bs.dropdown', () => {
                    this.loadNotifications();
                });
            }

            async loadNotifications() {
                try {
                    const response = await fetch('{{ route('notifications.list') }}');
                    const data = await response.json();

                    this.updateNotificationsList(data.notifications);
                    this.updateBadge(data.unread_count);
                } catch (error) {
                    console.error('Erro ao carregar notificações:', error);
                }
            }

            updateNotificationsList(notifications) {
                if (!this.list) return;

                if (notifications.length === 0) {
                    this.list.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="mdi mdi-bell-off-outline fs-1"></i>
                    <p class="mt-2 mb-0">Nenhuma notificação</p>
                </div>
            `;
                    return;
                }

                this.list.innerHTML = notifications.map(notification => `
            <div class="px-3 py-2 border-bottom notification-item ${notification.is_read ? '' : 'bg-light'}" 
                 data-notification-id="${notification.id}">
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle p-2 ${this.getNotificationColor(notification.type)}">
                            <i class="mdi ${this.getNotificationIcon(notification.type)} text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 small">${this.escapeHtml(notification.title)}</h6>
                        <p class="mb-1 small text-muted">${this.escapeHtml(notification.message)}</p>
                        <small class="text-muted">${this.formatTime(notification.created_at)}</small>
                    </div>
                    ${!notification.is_read ? `
                                    <button class="btn btn-sm btn-link p-0 mark-as-read" title="Marcar como lida">
                                        <i class="mdi mdi-check-circle text-muted"></i>
                                    </button>
                                ` : ''}
                </div>
            </div>
        `).join('');

                // Adicionar event listeners para os botões de marcar como lida
                this.list.querySelectorAll('.mark-as-read').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const item = btn.closest('.notification-item');
                        this.markAsRead(item.dataset.notificationId);
                    });
                });

                // Adicionar event listeners para clicar nas notificações
                this.list.querySelectorAll('.notification-item').forEach(item => {
                    item.addEventListener('click', () => {
                        this.markAsRead(item.dataset.notificationId);
                        // Redirecionar para a página relevante se houver related_model
                        // Implementar conforme necessário
                    });
                });
            }

            updateBadge(count) {
                this.unreadCount = count;
                if (this.badge) {
                    if (count > 0) {
                        this.badge.textContent = count > 99 ? '99+' : count;
                        this.badge.style.display = 'block';
                    } else {
                        this.badge.style.display = 'none';
                    }
                }
            }

            async markAsRead(notificationId) {
                try {
                    const response = await fetch(`/notifications/${notificationId}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });

                    if (response.ok) {
                        this.loadNotifications(); // Recarregar a lista
                    }
                } catch (error) {
                    console.error('Erro ao marcar notificação como lida:', error);
                }
            }

            async markAllAsRead() {
                try {
                    const response = await fetch('{{ route('notifications.markAllAsRead') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });

                    if (response.ok) {
                        this.loadNotifications();
                    }
                } catch (error) {
                    console.error('Erro ao marcar todas como lidas:', error);
                }
            }

            getNotificationColor(type) {
                const colors = {
                    'success': 'bg-success',
                    'warning': 'bg-warning',
                    'danger': 'bg-danger',
                    'info': 'bg-info'
                };
                return colors[type] || 'bg-primary';
            }

            getNotificationIcon(type) {
                const icons = {
                    'success': 'mdi-check-circle',
                    'warning': 'mdi-alert-circle',
                    'danger': 'mdi-alert-octagon',
                    'info': 'mdi-information'
                };
                return icons[type] || 'mdi-bell';
            }

            formatTime(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffMs = now - date;
                const diffMins = Math.floor(diffMs / 60000);
                const diffHours = Math.floor(diffMs / 3600000);
                const diffDays = Math.floor(diffMs / 86400000);

                if (diffMins < 1) return 'Agora';
                if (diffMins < 60) return `${diffMins} min atrás`;
                if (diffHours < 24) return `${diffHours} h atrás`;
                if (diffDays < 7) return `${diffDays} dias atrás`;

                return date.toLocaleDateString('pt-PT');
            }

            escapeHtml(unsafe) {
                return unsafe
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            startPolling() {
                // Atualizar a contagem a cada 30 segundos
                setInterval(() => {
                    this.loadNotifications();
                }, 30000);
            }
        }

        // Inicializar o gerenciador de notificações
        document.addEventListener('DOMContentLoaded', function() {
            new NotificationManager();
        });
        // Expose functions globally
        window.toggleTheme = toggleTheme;
        window.showToast = showToast;
    </script>

    @stack('scripts')
</body>

</html>
