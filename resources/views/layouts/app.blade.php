<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistema de Gestão de Restaurantes">
    <meta name="author" content="Filipe dos Santos">

    <title>{{ config('app.name', 'ZALALA BEACH BARPOS') }} - @yield('title', 'Sistema de Gestão')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #2C3E50;
            --success-color: #27AE60;
            --warning-color: #F39C12;
            --danger-color: #E74C3C;
            --info-color: #3498DB;
            --dark-color: #1A1A1A;
            --light-color: #F8F9FA;
            --primary-gradient: linear-gradient(135deg, #FF6B35 0%, #FF8A56 100%);
            --secondary-gradient: linear-gradient(135deg, #2C3E50 0%, #34495E 100%);
            --success-gradient: linear-gradient(135deg, #27AE60 0%, #2ECC71 100%);
            --shadow-soft: 0 8px 25px rgba(0, 0, 0, 0.08);
            --shadow-strong: 0 15px 35px rgba(0, 0, 0, 0.12);
            --shadow-card: 0 4px 15px rgba(0, 0, 0, 0.06);
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
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
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="30" fill="none" stroke="rgba(255,107,53,0.02)" stroke-width="2"/><path d="M30,30 L70,30 L70,70 L30,70 Z" fill="rgba(255,107,53,0.01)"/></svg>');
            background-repeat: repeat;
            background-size: 150px 150px;
            z-index: -2;
            animation: subtleMove 80s ease-in-out infinite;
        }

        @keyframes subtleMove {

            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(-10px, -10px);
            }
        }

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
            }

            50% {
                transform: scale(1.05);
            }
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--primary-gradient);
            box-shadow: var(--shadow-strong);
            z-index: 1040;
            transition: transform 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        /* OVERLAY */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1035;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* MOBILE RESPONSIVE */
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

            .mobile-search {
                display: block !important;
            }

            .desktop-search {
                display: none !important;
            }
        }

        @media (min-width: 992px) {
            .mobile-search {
                display: none !important;
            }

            .desktop-search {
                display: flex !important;
            }
        }

        /* Sidebar Brand */
        .sidebar-brand {
            background: rgba(0, 0, 0, 0.15);
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand .logo-wrapper {
            position: relative;
            display: inline-block;
            animation: pulse 3s ease-in-out infinite;
        }

        .sidebar-brand h4 {
            color: white;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .sidebar-brand small {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 400;
        }

        /* Navigation Items */
        .nav-pills .nav-link {
            color: rgba(255, 255, 255, 0.85);
            border-radius: 12px;
            margin: 4px 0;
            padding: 12px 16px;
            transition: var(--transition);
            font-weight: 500;
            position: relative;
            overflow: hidden;
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
            transform: translateX(5px);
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-pills .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .nav-pills .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 12px;
            position: relative;
            z-index: 1;
        }

        .nav-pills .nav-link span {
            position: relative;
            z-index: 1;
        }

        /* Dropdown Menu */
        .nav-dropdown {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            margin: 8px 0;
            overflow: hidden;
        }

        .nav-dropdown-toggle {
            color: rgba(255, 255, 255, 0.9);
            padding: 12px 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-dropdown-item {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 16px 10px 40px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .nav-dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-dropdown-item.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left: 3px solid rgba(255, 255, 255, 0.5);
        }

        /* User Area */
        .user-area {
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem;
            margin-top: auto;
        }

        /* Top Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: var(--shadow-soft);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        /* Search Container */
        .search-container {
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .search-container .form-control {
            border-radius: 25px;
            border: 2px solid #e9ecef;
            padding: 10px 20px;
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.9);
        }

        .search-container .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
            transform: scale(1.02);
        }

        /* Cards */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-card);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--warning-color), var(--success-color));
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .card:hover::before {
            transform: scaleX(1);
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-strong);
        }

        /* Buttons */
        .btn {
            border-radius: 10px;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.25);
        }

        /* Status badges */
        .badge {
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 8px;
        }

        .badge-success {
            background: var(--success-color);
        }

        .badge-warning {
            background: var(--warning-color);
        }

        .badge-danger {
            background: var(--danger-color);
        }

        .badge-info {
            background: var(--info-color);
        }

        .badge-primary {
            background: var(--primary-color);
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-card);
            transition: var(--transition);
            border-left: 4px solid transparent;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-strong);
        }

        .stats-card.primary {
            border-left-color: var(--primary-color);
        }

        .stats-card.success {
            border-left-color: var(--success-color);
        }

        .stats-card.warning {
            border-left-color: var(--warning-color);
        }

        .stats-card.danger {
            border-left-color: var(--danger-color);
        }

        .stats-card.info {
            border-left-color: var(--info-color);
        }

        /* Toast */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--shadow-strong);
            backdrop-filter: blur(10px);
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toast-container"></div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="logo-wrapper">
                <i class="mdi mdi-silverware-fork-knife text-white" style="font-size: 2.5rem;"></i>
            </div>
            <h5 style="color: var(--light-color)">ZALALA BEACH<span class="text-warning"> BAR</span></h5>
            <small>Sistema de Gestão</small>
        </div>

        <div class="flex-grow-1 px-3 py-3">
            <ul class="nav nav-pills flex-column">
                <!-- Dashboard -->
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Operacional -->
                <div class="nav-dropdown">
                    <div class="nav-dropdown-toggle">
                        <i class="mdi mdi-store me-2"></i>
                        <span>OPERACIONAL</span>
                    </div>

                    <a class="nav-dropdown-item {{ request()->routeIs('pos.*') ? 'active' : '' }}"
                        href="{{ route('pos.index') }}">
                        <i class="mdi mdi-point-of-sale me-2"></i>
                        <span>PDV</span>
                        <span class="badge badge-success ms-auto">Novo</span>
                    </a>

                    <a class="nav-dropdown-item {{ request()->routeIs('orders.*') ? 'active' : '' }}"
                        href="{{ route('orders.index') }}">
                        <i class="mdi mdi-cart-outline me-2"></i>
                        <span>Pedidos</span>
                        @php
                            $pendingOrdersCount = \App\Models\Order::where('status', 'active')->count();
                        @endphp
                        @if ($pendingOrdersCount > 0)
                            <span class="badge badge-danger ms-auto">{{ $pendingOrdersCount }}</span>
                        @endif
                    </a>

                    <a class="nav-dropdown-item {{ request()->routeIs('tables.*') ? 'active' : '' }}"
                        href="{{ route('tables.index') }}">
                        <i class="mdi mdi-table-furniture me-2"></i>
                        <span>Mesas</span>
                        @php
                            $tablesAvailable = \App\Models\Table::where('status', 'free')->count();
                        @endphp
                        <span class="badge badge-{{ $tablesAvailable > 0 ? 'success' : 'danger' }} ms-auto">
                            {{ $tablesAvailable }}
                        </span>
                    </a>

                    @if (Auth::user()->role == 'admin')
                        <a class="nav-dropdown-item" href="#">
                            <i class="mdi mdi-calendar-clock me-2"></i>
                            <span>Reservas</span>
                        </a>
                    @endif
                </div>

                <!-- Menu -->
                <div class="nav-dropdown">
                    <div class="nav-dropdown-toggle">
                        <i class="mdi mdi-food-variant me-2"></i>
                        <span>MENU</span>
                    </div>

                    <a class="nav-dropdown-item {{ request()->routeIs('products.*') ? 'active' : '' }}"
                        href="{{ route('products.index') }}">
                        <i class="mdi mdi-food me-2"></i>
                        <span>Produtos</span>
                    </a>

                    <a class="nav-dropdown-item {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                        href="{{ route('categories.index') }}">
                        <i class="mdi mdi-format-list-bulleted-square me-2"></i>
                        <span>Categorias</span>
                    </a>

                    @if (Auth::user()->role == 'admin')
                        <a class="nav-dropdown-item" href="#">
                            <i class="mdi mdi-book-open-variant me-2"></i>
                            <span>Cardápios</span>
                        </a>
                    @endif
                </div>

                <!-- Financeiro -->
                <div class="nav-dropdown">
                    <div class="nav-dropdown-toggle">
                        <i class="mdi mdi-currency-usd me-2"></i>
                        <span>FINANCEIRO</span>
                    </div>

                    <a class="nav-dropdown-item {{ request()->routeIs('sales.*') ? 'active' : '' }}"
                        href="{{ route('sales.index') }}">
                        <i class="mdi mdi-cash-multiple me-2"></i>
                        <span>Vendas</span>
                    </a>

                    @if (Auth::user()->role == 'admin')
                        <a class="nav-dropdown-item {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                            href="{{ route('reports.index') }}">
                            <i class="mdi mdi-chart-bar me-2"></i>
                            <span>Relatórios</span>
                        </a>

                        <a class="nav-dropdown-item" href="#">
                            <i class="mdi mdi-cash-remove me-2"></i>
                            <span>Despesas</span>
                        </a>
                    @endif
                </div>

                <!-- Pessoas -->
                <div class="nav-dropdown">
                    <div class="nav-dropdown-toggle">
                        <i class="mdi mdi-account-group me-2"></i>
                        <span>PESSOAS</span>
                    </div>

                    <a class="nav-dropdown-item {{ request()->routeIs('clients.*') ? 'active' : '' }}"
                        href="{{ route('clients.index') }}">
                        <i class="mdi mdi-account-group me-2"></i>
                        <span>Clientes</span>
                    </a>

                    @if (Auth::user()->role == 'admin')
                        <a class="nav-dropdown-item {{ request()->routeIs('employees.*') ? 'active' : '' }}"
                            href="{{ route('employees.index') }}">
                            <i class="mdi mdi-account-tie me-2"></i>
                            <span>Funcionários</span>
                        </a>
                    @endif
                </div>

                <!-- Administração -->
                @if (Auth::user()->role == 'admin')
                    <div class="nav-dropdown">
                        <div class="nav-dropdown-toggle">
                            <i class="mdi mdi-shield-account me-2"></i>
                            <span>ADMINISTRAÇÃO</span>
                        </div>

                        <a class="nav-dropdown-item {{ request()->routeIs('users.*') ? 'active' : '' }}"
                            href="{{ route('users.index') }}">
                            <i class="mdi mdi-account-key me-2"></i>
                            <span>Usuários</span>
                        </a>

                        <a class="nav-dropdown-item" href="#">
                            <i class="mdi mdi-cog-outline me-2"></i>
                            <span>Configurações</span>
                        </a>

                        <a class="nav-dropdown-item" href="#">
                            <i class="mdi mdi-cloud-upload me-2"></i>
                            <span>Backup</span>
                        </a>
                    </div>
                @endif

                <!-- Suporte -->
                <div class="nav-dropdown">
                    <div class="nav-dropdown-toggle">
                        <i class="mdi mdi-help-circle-outline me-2"></i>
                        <span>SUPORTE</span>
                    </div>

                    <a class="nav-dropdown-item" href="#">
                        <i class="mdi mdi-help-circle me-2"></i>
                        <span>Ajuda</span>
                    </a>
                </div>
            </ul>
        </div>

        <div class="user-area">
            <div class="d-flex align-items-center text-white mb-3">
                <div class="position-relative me-3">
                    <div class="avatar bg-white text-primary rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="mdi mdi-chef-hat"></i>
                    </div>
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle">
                        <span class="visually-hidden">Online</span>
                    </span>
                </div>
                <div>
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <small class="opacity-75">{{ auth()->user()->role ?? 'Usuário' }}</small>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="btn btn-outline-light btn-sm w-100 d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-logout me-2"></i> Sair do Sistema
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light"
            style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px); border-bottom: 1px solid rgba(0, 0, 0, 0.08); box-shadow: var(--shadow-soft); position: sticky; top: 0; z-index: 1030;">
            <div class="container-fluid" style="padding: 0.5% 5px;">
                <!-- Mobile Sidebar Toggle -->
                <button class="btn btn-outline-secondary d-lg-none me-3" type="button" id="sidebar-toggle">
                    <i class="mdi mdi-menu"></i>
                </button>

                <!-- Page Title -->
                <div class="navbar-brand mb-0 h1 fw-bold text-primary d-flex align-items-center">
                    <i class="mdi @yield('title-icon', 'mdi-home') me-2"></i>
                    @yield('page-title', 'Dashboard')
                </div>

                <!-- Mobile Toggle (Bootstrap) -->
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarContent" aria-expanded="false">
                    <i class="mdi mdi-menu text-dark"></i>
                </button>

                <!-- Navbar Content -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <!-- Center Content -->
                    <div class="navbar-nav me-auto">
                        <!-- Welcome Message -->
                        <div class="nav-item d-none d-lg-flex align-items-center ms-3">
                            <div class="text-dark">
                                <div class="fw-semibold">
                                    Bem-vindo, <span class="text-warning">{{ auth()->user()->name }}</span>
                                </div>
                                <div class="small text-muted" id="current-datetime">
                                    <i class="mdi mdi-clock-outline me-1"></i>
                                    <span id="datetime-display"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content -->
                    <div class="navbar-nav">
                        <!-- Quick Actions Dropdown -->
                        <div class="nav-item dropdown me-2">
                            <a class="nav-link dropdown-toggle btn btn-warning btn-sm rounded-pill px-3 text-dark"
                                href="#" role="button" data-bs-toggle="dropdown">
                                <i class="mdi mdi-lightning-bolt me-1"></i>
                                <span class="d-none d-md-inline">Ações Rápidas</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end animate__animated animate__fadeInUp"
                                style="min-width: 200px;">
                                <li>
                                    <h6 class="dropdown-header">
                                        <i class="mdi mdi-flash text-warning me-2"></i>
                                        Ações Rápidas
                                    </h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('pos.index') }}">
                                        <i class="mdi mdi-point-of-sale text-success me-2"></i>
                                        Abrir PDV
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{-- route('orders.create') --}}">
                                        <i class="mdi mdi-cart-plus text-info me-2"></i>
                                        Novo Pedido
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{-- route('products.create') --}}">
                                        <i class="mdi mdi-food-variant text-warning me-2"></i>
                                        Adicionar Produto
                                    </a>
                                </li>
                                @if (auth()->user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('tables.index') }}">
                                            <i class="mdi mdi-table-furniture text-primary me-2"></i>
                                            Gerenciar Mesas
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('reports.index') }}">
                                            <i class="mdi mdi-chart-line text-purple me-2"></i>
                                            Relatórios
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <!-- Search -->
                        <div class="nav-item me-3 d-none d-md-block">
                            <div class="position-relative">
                                <input type="text" class="form-control form-control-sm rounded-pill ps-4"
                                    style="width: 250px; background: rgba(255,255,255,0.1); border: 1px solid rgba(0,0,0,0.1); color: #333;"
                                    placeholder="Buscar produtos, pedidos..." id="navbar-search">
                                <i class="mdi mdi-magnify position-absolute text-muted"
                                    style="left: 12px; top: 50%; transform: translateY(-50%);"></i>

                                <!-- Search Results Dropdown -->
                                <div class="dropdown-menu w-100 mt-1" id="search-results" style="display: none;">
                                    <div class="p-2 text-center text-muted">
                                        <i class="mdi mdi-magnify"></i>
                                        Digite para buscar...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fullscreen Toggle -->
                        <div class="nav-item me-2">
                            <button class="btn btn-outline-primary btn-sm rounded-circle" id="fullscreen-toggle"
                                title="Modo Tela Cheia">
                                <i class="mdi mdi-fullscreen"></i>
                            </button>
                        </div>

                        <!-- System Status -->
                        <div class="nav-item me-3 d-none d-lg-block">
                            <div class="d-flex align-items-center">
                                <div class="status-indicator bg-success rounded-circle me-2"
                                    style="width: 8px; height: 8px;" title="Sistema Online"></div>
                                <small class="text-muted">Online</small>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <div class="nav-item dropdown me-2">
                            <a class="nav-link position-relative" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-bell-outline text-dark" style="font-size: 1.2rem;"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    id="notification-count" style="font-size: 0.7rem; display: none;">
                                    0
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end animate__animated animate__fadeInUp"
                                style="min-width: 320px; max-height: 400px; overflow-y: auto;">
                                <div class="dropdown-header d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="mdi mdi-bell text-primary me-2"></i>
                                        Notificações
                                    </span>
                                    <button class="btn btn-sm btn-link text-muted p-0" id="mark-all-read">
                                        <i class="mdi mdi-check-all"></i>
                                    </button>
                                </div>
                                <div class="dropdown-divider"></div>

                                <!-- Sample Notifications -->
                                <div class="notification-item px-3 py-2 border-bottom hover-bg-light">
                                    <div class="d-flex">
                                        <div class="notification-icon me-3">
                                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;">
                                                <i class="mdi mdi-alert-circle text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold small">Estoque Baixo</div>
                                            <div class="text-muted small">5 produtos com estoque crítico</div>
                                            <div class="text-muted small">
                                                <i class="mdi mdi-clock-outline me-1"></i>
                                                5 min atrás
                                            </div>
                                        </div>
                                        <div class="notification-status">
                                            <div class="bg-primary rounded-circle" style="width: 6px; height: 6px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="notification-item px-3 py-2 border-bottom hover-bg-light">
                                    <div class="d-flex">
                                        <div class="notification-icon me-3">
                                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;">
                                                <i class="mdi mdi-cart-check text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold small">Pedido #1234 Finalizado</div>
                                            <div class="text-muted small">Mesa 12 - Total: R$ 85,50</div>
                                            <div class="text-muted small">
                                                <i class="mdi mdi-clock-outline me-1"></i>
                                                12 min atrás
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="notification-item px-3 py-2 hover-bg-light">
                                    <div class="d-flex">
                                        <div class="notification-icon me-3">
                                            <div class="bg-info rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;">
                                                <i class="mdi mdi-calendar-check text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold small">Nova Reserva</div>
                                            <div class="text-muted small">Mesa para 6 pessoas às 19:30h</div>
                                            <div class="text-muted small">
                                                <i class="mdi mdi-clock-outline me-1"></i>
                                                25 min atrás
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>
                                <div class="text-center p-2">
                                    <a href="#" class="btn btn-sm btn-primary w-100">
                                        <i class="mdi mdi-eye me-1"></i>
                                        Ver Todas
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar me-2">
                                    @if (auth()->user()->profile_photo_path)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                                            alt="Avatar" class="rounded-circle" style="width: 32px; height: 32px;">
                                    @else
                                        <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold"
                                            style="width: 32px; height: 32px;">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="position-absolute bottom-0 end-0">
                                        <div class="bg-success border border-white rounded-circle"
                                            style="width: 10px; height: 10px;" title="Online"></div>
                                    </div>
                                </div>
                                <div class="d-none d-md-block">
                                    <div class="fw-semibold text-dark small">{{ auth()->user()->name }}</div>
                                    <div class="text-muted small">{{ auth()->user()->role ?? 'Usuário' }}</div>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end animate__animated animate__fadeInUp"
                                style="min-width: 220px;">
                                <li>
                                    <div class="dropdown-header text-center">
                                        <div class="user-avatar mx-auto mb-2">
                                            @if (auth()->user()->profile_photo_path)
                                                <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                                                    alt="Avatar" class="rounded-circle"
                                                    style="width: 48px; height: 48px;">
                                            @else
                                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold mx-auto"
                                                    style="width: 48px; height: 48px;">
                                                    {{ substr(auth()->user()->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                        <div class="text-muted small">{{ auth()->user()->email }}</div>
                                    </div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile') }}">
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
                                    <a class="dropdown-item text-danger" href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="mdi mdi-logout me-2"></i>
                                        Sair do Sistema
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Search (visible only on small screens) -->
        <div class="mobile-search-container d-lg-none bg-light border-top border-secondary">
            <div class="container-fluid p-3">
                <div class="position-relative">
                    <input type="text" class="form-control rounded-pill ps-4"
                        style="background: rgba(255,255,255,0.1); border: 1px solid rgba(0,0,0,0.1); color: #333;"
                        placeholder="Buscar produtos, pedidos..." id="mobile-search">
                    <i class="mdi mdi-magnify position-absolute text-muted"
                        style="left: 12px; top: 50%; transform: translateY(-50%);"></i>
                </div>
            </div>
        </div>

        <!-- Hidden logout form -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        <style>
            .hover-bg-light:hover {
                background-color: rgba(0, 0, 0, 0.05) !important;
            }

            .notification-item {
                transition: background-color 0.2s ease;
                cursor: pointer;
            }

            .user-avatar {
                position: relative;
            }

            .status-indicator {
                animation: pulse-dot 2s ease-in-out infinite;
            }

            @keyframes pulse-dot {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.5;
                }
            }

            .dropdown-menu {
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                border: none;
                margin-top: 8px !important;
            }

            /* Search input focus effect */
            #navbar-search:focus,
            #mobile-search:focus {
                background: rgba(255, 255, 255, 0.15) !important;
                border-color: rgba(255, 193, 7, 0.5) !important;
                box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
            }

            /* Animated elements */
            .animate__animated {
                animation-duration: 0.3s;
            }

            .animate__fadeInUp {
                animation-name: fadeInUp;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translate3d(0, 20px, 0);
                }

                to {
                    opacity: 1;
                    transform: translate3d(0, 0, 0);
                }
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // ===== DATE AND TIME UPDATE =====
                function updateDateTime() {
                    const now = new Date();
                    const options = {
                        weekday: 'short',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    };

                    const datetimeElement = document.getElementById('datetime-display');
                    if (datetimeElement) {
                        datetimeElement.textContent = now.toLocaleDateString('pt-BR', options);
                    }
                }

                // Update immediately and then every minute
                updateDateTime();
                setInterval(updateDateTime, 60000);

                // ===== FULLSCREEN FUNCTIONALITY =====
                const fullscreenBtn = document.getElementById('fullscreen-toggle');

                function isFullScreen() {
                    return !!(
                        document.fullscreenElement ||
                        document.webkitFullscreenElement ||
                        document.mozFullScreenElement ||
                        document.msFullscreenElement
                    );
                }

                function toggleFullscreen() {
                    if (isFullScreen()) {
                        // Exit fullscreen
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        } else if (document.webkitExitFullscreen) {
                            document.webkitExitFullscreen();
                        } else if (document.mozCancelFullScreen) {
                            document.mozCancelFullScreen();
                        } else if (document.msExitFullscreen) {
                            document.msExitFullscreen();
                        }
                    } else {
                        // Enter fullscreen
                        const element = document.documentElement;
                        if (element.requestFullscreen) {
                            element.requestFullscreen();
                        } else if (element.webkitRequestFullscreen) {
                            element.webkitRequestFullscreen();
                        } else if (element.mozRequestFullScreen) {
                            element.mozRequestFullScreen();
                        } else if (element.msRequestFullscreen) {
                            element.msRequestFullscreen();
                        }
                    }
                }

                function updateFullscreenButton() {
                    const icon = fullscreenBtn.querySelector('i');
                    if (isFullScreen()) {
                        icon.classList.remove('mdi-fullscreen');
                        icon.classList.add('mdi-fullscreen-exit');
                        fullscreenBtn.title = 'Sair da Tela Cheia';
                    } else {
                        icon.classList.remove('mdi-fullscreen-exit');
                        icon.classList.add('mdi-fullscreen');
                        fullscreenBtn.title = 'Modo Tela Cheia';
                    }
                }

                if (fullscreenBtn) {
                    fullscreenBtn.addEventListener('click', toggleFullscreen);

                    // Listen for fullscreen change events
                    document.addEventListener('fullscreenchange', updateFullscreenButton);
                    document.addEventListener('webkitfullscreenchange', updateFullscreenButton);
                    document.addEventListener('mozfullscreenchange', updateFullscreenButton);
                    document.addEventListener('MSFullscreenChange', updateFullscreenButton);
                }

                // ===== SEARCH FUNCTIONALITY =====
                const searchInputs = ['navbar-search', 'mobile-search'];

                searchInputs.forEach(inputId => {
                    const searchInput = document.getElementById(inputId);
                    if (searchInput) {
                        let searchTimeout;

                        searchInput.addEventListener('input', function(e) {
                            clearTimeout(searchTimeout);
                            const query = e.target.value.trim();

                            if (query.length < 2) {
                                hideSearchResults();
                                return;
                            }

                            searchTimeout = setTimeout(() => {
                                performSearch(query);
                            }, 300);
                        });

                        // Close search results when clicking outside
                        document.addEventListener('click', function(e) {
                            if (!searchInput.contains(e.target)) {
                                hideSearchResults();
                            }
                        });
                    }
                });

                function performSearch(query) {
                    // Show loading state
                    showSearchResults('Buscando...');

                    // Simulate search - replace with actual API call
                    setTimeout(() => {
                        const mockResults = [{
                                type: 'product',
                                name: 'Café Expresso',
                                category: 'Bebidas',
                                price: 'R$ 4,50',
                                icon: 'mdi-coffee'
                            },
                            {
                                type: 'order',
                                name: 'Pedido #1234',
                                info: 'Mesa 12 - Em andamento',
                                time: '10 min atrás',
                                icon: 'mdi-receipt'
                            }
                        ];

                        if (mockResults.length > 0) {
                            displaySearchResults(mockResults);
                        } else {
                            showSearchResults('Nenhum resultado encontrado');
                        }
                    }, 500);
                }

                function displaySearchResults(results) {
                    const resultsHtml = results.map(result => `
            <div class="dropdown-item d-flex align-items-center py-2">
                <i class="${result.icon} text-primary me-3"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold small">${result.name}</div>
                    <div class="text-muted small">
                        ${result.category || result.info || ''}
                        ${result.price ? ' - ' + result.price : ''}
                        ${result.time ? ' - ' + result.time : ''}
                    </div>
                </div>
            </div>
        `).join('');

                    showSearchResults(resultsHtml);
                }

                function showSearchResults(content) {
                    const searchResults = document.getElementById('search-results');
                    if (searchResults) {
                        searchResults.innerHTML = content;
                        searchResults.style.display = 'block';
                    }
                }

                function hideSearchResults() {
                    const searchResults = document.getElementById('search-results');
                    if (searchResults) {
                        searchResults.style.display = 'none';
                    }
                }

                // ===== NOTIFICATIONS =====
                function loadNotifications() {
                    // Simulate loading notifications
                    const notifications = [{
                            id: 1,
                            type: 'warning',
                            title: 'Estoque Baixo',
                            message: '5 produtos com estoque crítico',
                            time: '5 min atrás',
                            unread: true
                        },
                        {
                            id: 2,
                            type: 'success',
                            title: 'Pedido Finalizado',
                            message: 'Mesa 12 - Total: R$ 85,50',
                            time: '12 min atrás',
                            unread: true
                        },
                        {
                            id: 3,
                            type: 'info',
                            title: 'Nova Reserva',
                            message: 'Mesa para 6 pessoas às 19:30h',
                            time: '25 min atrás',
                            unread: false
                        }
                    ];

                    const unreadCount = notifications.filter(n => n.unread).length;
                    updateNotificationBadge(unreadCount);
                }

                function updateNotificationBadge(count) {
                    const badge = document.getElementById('notification-count');
                    if (badge) {
                        if (count > 0) {
                            badge.textContent = count > 99 ? '99+' : count;
                            badge.style.display = 'block';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                }

                // Mark all notifications as read
                const markAllReadBtn = document.getElementById('mark-all-read');
                if (markAllReadBtn) {
                    markAllReadBtn.addEventListener('click', function() {
                        updateNotificationBadge(0);
                        // Add API call to mark notifications as read
                    });
                }

                // Load notifications on page load
                loadNotifications();

                // Refresh notifications every 30 seconds
                setInterval(loadNotifications, 30000);

                // ===== THEME TOGGLE =====
                window.toggleTheme = function() {
                    const html = document.documentElement;
                    const currentTheme = html.getAttribute('data-bs-theme') || 'light';
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

                    html.setAttribute('data-bs-theme', newTheme);
                    localStorage.setItem('theme', newTheme);

                    // Show toast notification
                    const message = newTheme === 'dark' ? 'Tema escuro ativado' : 'Tema claro ativado';
                    showToast(message, 'info');
                };

                // Load saved theme
                const savedTheme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-bs-theme', savedTheme);

                // ===== TOAST NOTIFICATIONS =====
                window.showToast = function(message, type = 'success') {
                    // Create toast container if it doesn't exist
                    let toastContainer = document.getElementById('toast-container');
                    if (!toastContainer) {
                        toastContainer = document.createElement('div');
                        toastContainer.id = 'toast-container';
                        toastContainer.className = 'position-fixed top-0 end-0 p-3';
                        toastContainer.style.zIndex = '9999';
                        document.body.appendChild(toastContainer);
                    }

                    const toastId = 'toast-' + Date.now();
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

                    // Remove toast element after it's hidden
                    toastElement.addEventListener('hidden.bs.toast', () => {
                        toastElement.remove();
                    });
                };

                // ===== POS FULLSCREEN PROMPT =====
                if (window.location.pathname.includes('/pos')) {
                    const hasChosenFullscreen = localStorage.getItem('pos-fullscreen-choice');

                    if (!hasChosenFullscreen) {
                        // Show fullscreen prompt after a short delay
                        setTimeout(() => {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Modo Tela Cheia Recomendado',
                                    text: 'Para melhor experiência no PDV, recomendamos usar o modo tela cheia. Deseja ativar?',
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sim, ativar',
                                    cancelButtonText: 'Não, obrigado',
                                    confirmButtonColor: '#FF6B35',
                                    cancelButtonColor: '#6c757d'
                                }).then((result) => {
                                    localStorage.setItem('pos-fullscreen-choice', 'true');
                                    if (result.isConfirmed) {
                                        toggleFullscreen();
                                    }
                                });
                            } else {
                                // Fallback if SweetAlert is not available
                                const useFullscreen = confirm(
                                    'Para melhor experiência no PDV, recomendamos usar o modo tela cheia. Deseja ativar?'
                                );
                                localStorage.setItem('pos-fullscreen-choice', 'true');
                                if (useFullscreen) {
                                    toggleFullscreen();
                                }
                            }
                        }, 1000);
                    }
                }

                // ===== KEYBOARD SHORTCUTS =====
                document.addEventListener('keydown', function(e) {
                    // Alt + F for fullscreen
                    if (e.altKey && e.key === 'f') {
                        e.preventDefault();
                        toggleFullscreen();
                    }

                    // Alt + S for search focus
                    if (e.altKey && e.key === 's') {
                        e.preventDefault();
                        const searchInput = document.getElementById('navbar-search') || document.getElementById(
                            'mobile-search');
                        if (searchInput) {
                            searchInput.focus();
                        }
                    }

                    // Escape to close search results
                    if (e.key === 'Escape') {
                        hideSearchResults();
                    }
                });
            });
        </script>
        <!-- Page Content -->
        <div class="container-fluid px-4 py-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb px-3 py-2"
                    style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border-radius: var(--border-radius);">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="mdi mdi-home"></i>
                        </a>
                    </li>
                    @yield('breadcrumbs')
                    @if (!View::hasSection('breadcrumbs'))
                        <li class="breadcrumb-item active">Dashboard</li>
                    @endif
                </ol>
            </nav>

            <!-- Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center fade-in"
                    role="alert">
                    <i class="mdi mdi-check-circle me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center fade-in"
                    role="alert">
                    <i class="mdi mdi-alert-circle me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')

            <!-- Footer -->
            <footer class="text-center py-4 mt-5"
                style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border-radius: var(--border-radius);">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-md-start">
                            <small class="text-muted">
                                © {{ date('Y') }} ZALALA BEACH BAR <span
                                    style="color: rgb(255, 123, 0); font-weght:bold">POS</span>| Sistema de Gestão
                            </small>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <small>
                                Versão 1.0.0 |
                                <a href="#" class="text-primary text-decoration-none">Suporte</a> |
                                <a href="#" class="text-primary text-decoration-none">Documentação</a>
                            </small>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ===== SIDEBAR MANAGER =====
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

        // ===== FULLSCREEN FUNCTIONALITY =====
        function setupFullscreen() {
            const fullscreenBtn = document.getElementById('fullscreenBtn');

            function isFullScreen() {
                return !!(
                    document.fullscreenElement ||
                    document.webkitFullscreenElement ||
                    document.mozFullScreenElement ||
                    document.msFullscreenElement
                );
            }

            function toggleFullscreen() {
                if (isFullScreen()) {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                } else {
                    const element = document.documentElement;
                    if (element.requestFullscreen) {
                        element.requestFullscreen();
                    } else if (element.webkitRequestFullscreen) {
                        element.webkitRequestFullscreen();
                    } else if (element.mozRequestFullScreen) {
                        element.mozRequestFullScreen();
                    } else if (element.msRequestFullscreen) {
                        element.msRequestFullscreen();
                    }
                }
            }

            function updateFullscreenButton() {
                const icon = fullscreenBtn.querySelector('i');
                if (isFullScreen()) {
                    icon.classList.remove('mdi-fullscreen');
                    icon.classList.add('mdi-fullscreen-exit');
                } else {
                    icon.classList.remove('mdi-fullscreen-exit');
                    icon.classList.add('mdi-fullscreen');
                }
            }

            fullscreenBtn?.addEventListener('click', toggleFullscreen);

            document.addEventListener('fullscreenchange', updateFullscreenButton);
            document.addEventListener('webkitfullscreenchange', updateFullscreenButton);
            document.addEventListener('mozfullscreenchange', updateFullscreenButton);
            document.addEventListener('MSFullscreenChange', updateFullscreenButton);
        }

        // ===== THEME TOGGLE =====
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }

        // ===== DATE AND TIME =====
        function updateDateTime() {
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };

            const dateElement = document.getElementById('current-date');
            if (dateElement) {
                dateElement.textContent = new Date().toLocaleDateString('pt-BR', options);
            }
        }

        // ===== TOAST HELPER =====
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');
            if (!toastContainer) return;

            const toastId = 'toast-' + Date.now();
            const iconMap = {
                success: 'mdi-check-circle',
                error: 'mdi-alert-circle',
                warning: 'mdi-alert-triangle',
                info: 'mdi-information'
            };

            const colorMap = {
                success: 'bg-success',
                error: 'bg-danger',
                warning: 'bg-warning',
                info: 'bg-info'
            };

            const toastHtml = `
                <div class="toast fade-in ${colorMap[type] || 'bg-primary'} text-white" role="alert" id="${toastId}">
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
                delay: 5000
            });

            toast.show();

            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        }

        // ===== GLOBAL SEARCH =====
        function setupGlobalSearch() {
            const searchInput = document.getElementById('global-search');
            if (!searchInput) return;

            let searchTimeout;

            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();

                if (query.length < 2) return;

                searchTimeout = setTimeout(() => {
                    performGlobalSearch(query);
                }, 300);
            });
        }

        function performGlobalSearch(query) {
            // Implementar busca global aqui
            console.log('Buscando por:', query);
        }

        // ===== INITIALIZATION =====
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar tema salvo
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);

            // Inicializar componentes
            try {
                new SidebarManager();
                setupFullscreen();
                setupGlobalSearch();
            } catch (error) {
                console.error('Erro ao inicializar componentes:', error);
            }

            // Atualizar data/hora
            updateDateTime();
            setInterval(updateDateTime, 60000);

            // Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto-hide alerts
            setTimeout(() => {
                document.querySelectorAll('.alert.fade.show').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    if (bsAlert) {
                        setTimeout(() => bsAlert.close(), 5000);
                    }
                });
            }, 100);

            // POS fullscreen prompt
            if (window.location.pathname.includes('/pos')) {
                const hasChosenFullscreen = localStorage.getItem('hasChosenFullscreen');
                if (!hasChosenFullscreen && typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Modo Tela Cheia',
                        text: 'Deseja ativar o modo tela cheia para melhor experiência no PDV?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sim',
                        cancelButtonText: 'Não'
                    }).then((result) => {
                        localStorage.setItem('hasChosenFullscreen', 'true');
                        if (result.isConfirmed) {
                            document.getElementById('fullscreenBtn')?.click();
                        }
                    });
                }
            }
        });

        // Expor funções globais
        window.toggleTheme = toggleTheme;
        window.showToast = showToast;
    </script>

    @stack('scripts')
</body>

</html>
