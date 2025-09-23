<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistema de Gestão - ZALALA BEACH BAR">
    <meta name="author" content="ZALALA BEACH BAR">

    <title>{{ config('app.name', 'ZALALA BEACH BAR') }} - @yield('title', 'Sistema de Gestão')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    <!-- Fontes -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">

    <!-- CSS Principal -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">

     <!-- CSS Plugins -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/jquery-toast-plugin/jquery.toast.min.css') }}">

    <!-- Material Design Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Costom -->
    <link rel="stylesheet" href="{{ asset('assets/pos/pos.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/orders.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <style>
        
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
        <!-- Brand Section -->
        <div class="sidebar-brand">
            <div class="brand-logo">
                <i class="mdi mdi-waves text-white" style="font-size: 2rem;"></i>
            </div>
            <div class="brand-name">ZALALA BEACH</div>
            <div class="brand-tagline">BEACH BAR & RESTAURANT</div>

            {{-- <!-- Restaurant Info -->
            <div class="restaurant-info">
                <div class="info-item">
                    <i class="mdi mdi-map-marker"></i>
                    <span>Bairro de Zalala, ER470</span>
                </div>
                <div class="info-item">
                    <i class="mdi mdi-phone"></i>
                    <span>+258 846 885 214</span>
                </div>
                <div class="info-item">
                    <i class="mdi mdi-identifier"></i>
                    <span>NUIT: 110735901</span>
                </div>
            </div> --}}
        </div>

        <!-- Navigation -->
        <div class="flex-grow-1 py-3">
            <!-- Dashboard -->
            <div class="nav-section">
                <div class="nav-section-title">Principal</div>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Operacional -->
            <div class="nav-section">
                <div class="nav-section-title">Operacional</div>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}"
                            href="{{ route('pos.index') }}">
                            <i class="mdi mdi-point-of-sale"></i>
                            <span>PDV</span>
                            <span class="nav-badge">Novo</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"
                            href="{{ route('orders.index') }}">
                            <i class="mdi mdi-receipt"></i>
                            <span>Pedidos</span>
                            @php
                                $pendingOrders = \App\Models\Order::where('status', 'active')->count();
                            @endphp
                            @if ($pendingOrders > 0)
                                <span class="nav-badge"
                                    style="background: var(--danger-color);">{{ $pendingOrders }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tables.*') ? 'active' : '' }}"
                            href="{{ route('tables.index') }}">
                            <i class="mdi mdi-table-chair"></i>
                            <span>Mesas</span>
                            @php
                                $availableTables = \App\Models\Table::where('status', 'free')->count();
                            @endphp
                            <span class="nav-badge"
                                style="background: {{ $availableTables > 0 ? 'var(--success-color)' : 'var(--danger-color)' }};">
                                {{ $availableTables }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Menu & Produtos -->
            <div class="nav-section">
                <div class="nav-section-title">Menu & Produtos</div>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                            href="{{ route('products.index') }}">
                            <i class="mdi mdi-food-variant"></i>
                            <span>Produtos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                            href="{{ route('categories.index') }}">
                            <i class="mdi mdi-format-list-bulleted"></i>
                            <span>Categorias</span>
                        </a>
                    </li>
                    @if (Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}"
                                href="{{ route('menu.index') ?? '#' }}">
                                <i class="mdi mdi-book-open-variant"></i>
                                <span>Cardápio</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Financeiro -->
            <div class="nav-section">
                <div class="nav-section-title">Financeiro</div>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}"
                            href="{{ route('sales.index') }}">
                            <i class="mdi mdi-cash-multiple"></i>
                            <span>Vendas</span>
                        </a>
                    </li>
                    @if (Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                                href="{{ route('reports.index') }}">
                                <i class="mdi mdi-chart-line"></i>
                                <span>Relatórios</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}"
                                href="{{ route('expenses.index') ?? '#' }}">
                                <i class="mdi mdi-cash-remove"></i>
                                <span>Despesas</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Pessoas -->
            <div class="nav-section">
                <div class="nav-section-title">Gestão de Pessoas</div>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}"
                            href="{{ route('clients.index') }}">
                            <i class="mdi mdi-account-group"></i>
                            <span>Clientes</span>
                        </a>
                    </li>
                    @if (Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}"
                                href="{{ route('employees.index') }}">
                                <i class="mdi mdi-account-tie"></i>
                                <span>Funcionários</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Administração -->
            @if (Auth::user()->role == 'admin')
                <div class="nav-section">
                    <div class="nav-section-title">Administração</div>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                                href="{{ route('users.index') }}">
                                <i class="mdi mdi-account-key"></i>
                                <span>Usuários</span>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"
                                href="{{ route('settings.index') ?? '#' }}">
                                <i class="mdi mdi-cog"></i>
                                <span>Configurações</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('backup.*') ? 'active' : '' }}"
                                href="{{ route('backup.index') ?? '#' }}">
                                <i class="mdi mdi-cloud-upload"></i>
                                <span>Backup</span>
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}"
                                href="{{-- route('inventory.index') ?? '#' --}}">
                                <i class="mdi mdi-warehouse"></i>
                                <span>Estoque</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif

            <!-- Suporte -->
            <div class="nav-section">
                <div class="nav-section-title">Suporte</div>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="http://163.192.7.41/">
                            <i class="mdi mdi-help-circle"></i>
                            <span>Central de Ajuda</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{-- route( 'support.contact') ?? '#' --}}">
                            <i class="mdi mdi-headset"></i>
                            <span>Suporte Técnico</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- User Area -->
        <div class="user-area">
            <div class="user-profile">
                <div class="user-avatar">
                    @if (auth()->user()->profile_photo_path)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Avatar"
                            class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i class="mdi mdi-account text-white" style="font-size: 1.5rem;"></i>
                    @endif
                </div>
                <div class="user-info">
                    <h6>{{ auth()->user()->name }}</h6>
                    <small>{{ ucfirst(auth()->user()->role ?? 'Usuário') }}</small>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="btn btn-outline-light btn-sm w-100 d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-logout-variant me-2"></i>
                    Sair do Sistema
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg top-navbar">
            <div class="container-fluid px-4">
                <!-- Mobile Sidebar Toggle -->
                <button class="btn btn-outline-primary d-lg-none me-3" type="button" id="sidebar-toggle">
                    <i class="mdi mdi-menu"></i>
                </button>

                <!-- Page Title -->
                <div class="page-title">
                    <i class="@yield('title-icon', 'mdi-view-dashboard')"></i>
                    @yield('page-title', 'Dashboard')
                </div>

                <!-- Navbar Toggle for Mobile -->
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarContent" aria-expanded="false">
                    <i class="mdi mdi-dots-vertical text-primary"></i>
                </button>

                <!-- Navbar Content -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <!-- Center Content -->
                    <div class="navbar-nav mx-auto">
                        <!-- Search -->
                        <div class="nav-item d-none d-md-block">
                            <div class="search-container">
                                <input type="text" class="form-control search-input"
                                    placeholder="Buscar produtos, pedidos, clientes..." id="global-search">
                                <i class="mdi mdi-magnify search-icon"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content -->
                    <div class="navbar-nav">
                        <!-- Quick Actions -->
                        <div class="nav-item dropdown me-3">
                            <button class="btn quick-actions dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-lightning-bolt me-1"></i>
                                <span class="d-none d-md-inline">Ações Rápidas</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
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
                                        <i class="mdi mdi-point-of-sale text-primary me-2"></i>
                                        Abrir PDV
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.index') ?? '#' }}">
                                        <i class="mdi mdi-cart-plus text-success me-2"></i>
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
                                    <a class="dropdown-item" href="{{ route('tables.index') }}">
                                        <i class="mdi mdi-table-chair text-info me-2"></i>
                                        Gerenciar Mesas
                                    </a>
                                </li>
                                @if (auth()->user()->role === 'admin')
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

                        <!-- Weather Widget (Beach Theme) -->
                        <div class="nav-item me-3 d-none d-lg-block">
                            <div class="d-flex align-items-center text-muted">
                                <i class="mdi mdi-weather-sunny text-warning me-1"></i>
                                <small id="weather-info">28°C Zalala</small>
                            </div>
                        </div>

                        <!-- Date/Time -->
                        <div class="nav-item me-3 d-none d-lg-block">
                            <div class="text-muted">
                                <i class="mdi mdi-clock-outline me-1"></i>
                                <small id="current-datetime"></small>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <div class="nav-item dropdown me-3">
                            <button class="btn position-relative" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="mdi mdi-bell-outline"></i>
                                <span class="notification-badge" id="notification-count"
                                    style="display: none; position: relative; z-index:1050; top:-30px; left:10px">0</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end"
                                style="min-width: 350px; max-height: 400px; overflow-y: auto;">
                                <div class="dropdown-header d-flex justify-content-between align-items-center">
                                    <span><i class="mdi mdi-bell text-primary me-2"></i>Notificações</span>
                                    <button class="btn btn-sm btn-link text-muted p-0" id="mark-all-read">
                                        <i class="mdi mdi-check-all"></i>
                                    </button>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div id="notifications-list">
                                    <div class="text-center p-3 text-muted">
                                        <i class="mdi mdi-bell-off-outline" style="font-size: 2rem;"></i>
                                        <p class="mb-0 mt-2">Nenhuma notificação</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div class="nav-item dropdown">
                            <button class="btn d-flex align-items-center" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">

                                <div class="user-avatar me-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                    @if (auth()->user()->profile_photo_path)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                                            alt="Avatar" class="rounded-circle"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="mdi mdi-account text-white"></i>
                                    @endif
                                </div>

                                <div class="d-none d-md-block text-start">
                                    <div class="fw-semibold small">{{ auth()->user()->name }}</div>
                                    <div class="text-muted small">{{ ucfirst(auth()->user()->role ?? 'Usuário') }}
                                    </div>
                                </div>

                                <!-- Seta do dropdown -->
                                <i class="mdi mdi-menu-down ms-2"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <div class="dropdown-header text-center">
                                        <div class="user-avatar mx-auto mb-2" style="width: 48px; height: 48px;">
                                            @if (auth()->user()->profile_photo_path)
                                                <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                                                    alt="Avatar" class="rounded-circle"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="mdi mdi-account text-white" style="font-size: 1.5rem;"></i>
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
                                    <a class="dropdown-item" href="{{-- route('settings.index') ?? '#' --}}">
                                        <i class="mdi mdi-cog text-secondary me-2"></i>
                                        Configurações
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="mdi mdi-logout-variant me-2"></i>
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

        <!-- Mobile Search (visible only on small screens) -->
        <div class="d-lg-none">
            <div class="container-fluid p-3"
                style="background: rgba(255, 255, 255, 0.9); border-bottom: 1px solid rgba(14, 165, 233, 0.1);">
                <div class="search-container">
                    <input type="text" class="form-control search-input"
                        placeholder="Buscar produtos, pedidos, clientes..." id="mobile-search">
                    <i class="mdi mdi-magnify search-icon"></i>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="container-fluid px-4 py-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center">
                    <!-- Início -->
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                            <i class="mdi mdi-home me-1"></i> Início
                        </a>
                    </li>
                    <!-- Seção dinâmica -->
                    @yield('breadcrumbs')
                    <!-- Padrão se não tiver section -->
                    @if (!View::hasSection('breadcrumbs'))
                        <li class="breadcrumb-item active d-flex align-items-center">
                            <i class="mdi mdi-view-dashboard-outline me-1"></i> Dashboard
                        </li>
                    @endif
                </ol>
            </nav>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center fade-in"
                    role="alert">
                    <i class="mdi mdi-check-circle me-2 fs-5"></i>
                    <div class="flex-grow-1">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center fade-in"
                    role="alert">
                    <i class="mdi mdi-alert-circle me-2 fs-5"></i>
                    <div class="flex-grow-1">{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center fade-in"
                    role="alert">
                    <i class="mdi mdi-alert-triangle me-2 fs-5"></i>
                    <div class="flex-grow-1">{{ session('warning') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show d-flex align-items-center fade-in"
                    role="alert">
                    <i class="mdi mdi-information me-2 fs-5"></i>
                    <div class="flex-grow-1">{{ session('info') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="mdi mdi-alert-circle me-2 fs-5 mt-1"></i>
                        <div class="flex-grow-1">
                            <strong>Ops! Algo deu errado:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')

            <!-- Footer -->
            <footer class="footer text-center">
                <div class="row align-items-center">
                    <div class="col-md-6 text-md-start">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                            <i class="mdi mdi-waves text-primary me-2" style="font-size: 1.2rem;"></i>
                            <span>
                                <strong>ZALALA BEACH BAR</strong> - Sistema de Gestão
                            </span>
                        </div>
                        <small class="text-muted mt-1">
                            © {{ date('Y') }} Todos os direitos reservados
                        </small>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-end">
                            <small class="text-muted me-3">Versão 1.0.0</small>
                            <div class="btn-group btn-group-sm">
                                <a href="https://wa.me/258862134230" target="_blank"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="mdi mdi-whatsapp"></i> Support
                                </a>
                                <a href="{{-- route('help.index') ?? '#' --}}" class="btn btn-outline-info btn-sm"
                                    onclick="alert('em desenvolvimento!...')">
                                    <i class="mdi mdi-help-circle"></i> Ajuda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
 --}}
    {{-- Scripts Base  --}}
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>

    <!-- Scripts do StarAdmin -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    {{--  <script src="{{ asset('assets/js/misc.js') }}"></script> --}}
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

    <!-- Custom JavaScript -->
    <script src="{{ asset('assets/pos/pos.js') }}"></script>
    <script src="{{ asset('assets/pos/printRecibo.js') }}"></script>
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

        // ===== THEME TOGGLE =====
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            showToast(`Tema ${newTheme === 'dark' ? 'escuro' : 'claro'} ativado`, 'info');
        }

        // ===== DATE AND TIME =====
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };

            const datetimeElement = document.getElementById('current-datetime');
            if (datetimeElement) {
                datetimeElement.textContent = now.toLocaleDateString('pt-BR', options);
            }
        }

        // ===== TOAST NOTIFICATIONS =====
        function showToast(message, type = 'success') {
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'toast-container';
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
                        <i class="mdi ${iconMap[type] || 'mdi-information'} me-2"></i>
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

        // ===== SEARCH FUNCTIONALITY =====
        function setupGlobalSearch() {
            const searchInputs = ['global-search', 'mobile-search'];

            searchInputs.forEach(inputId => {
                const searchInput = document.getElementById(inputId);
                if (searchInput) {
                    let searchTimeout;

                    searchInput.addEventListener('input', function(e) {
                        clearTimeout(searchTimeout);
                        const query = e.target.value.trim();

                        if (query.length < 2) return;

                        searchTimeout = setTimeout(() => {
                            performGlobalSearch(query);
                        }, 300);
                    });

                    // Handle search shortcuts
                    searchInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            const query = e.target.value.trim();
                            if (query) performGlobalSearch(query);
                        }

                        if (e.key === 'Escape') {
                            e.target.value = '';
                            e.target.blur();
                        }
                    });
                }
            });

            // Add search keyboard shortcut
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const searchInput = document.getElementById('global-search') || document.getElementById(
                        'mobile-search');
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                    }
                }
            });
        }

        function performGlobalSearch(query) {
            // Show loading state
            showToast(`Buscando por: "${query}"...`, 'info');

            // Simulate API search - replace with actual implementation
            setTimeout(() => {
                const mockResults = {
                    products: ['Cerveja Skol', 'Pizza Margherita', 'Camarão Grelhado'],
                    clients: ['João Silva', 'Maria Santos'],
                    orders: ['Pedido #001', 'Pedido #002']
                };

                console.log('Search Results:', mockResults);
                displaySearchResults(mockResults, query);
            }, 500);
        }

        function displaySearchResults(results, query) {
            // This would typically show a dropdown or modal with results
            const totalResults = Object.values(results).flat().length;
            if (totalResults > 0) {
                showToast(`Encontrados ${totalResults} resultados para "${query}"`, 'success');
            } else {
                showToast(`Nenhum resultado encontrado para "${query}"`, 'warning');
            }
        }

        // ===== NOTIFICATIONS =====
        function loadNotifications() {
            // Simulate loading notifications - replace with actual API call
            const notifications = [{
                    id: 1,
                    type: 'warning',
                    title: 'Estoque Baixo',
                    message: 'Cerveja Skol está acabando (5 unidades restantes)',
                    time: '5 min atrás',
                    unread: true,
                    action: 'inventory'
                },
                {
                    id: 2,
                    type: 'success',
                    title: 'Pedido Finalizado',
                    message: 'Mesa 8 - Total: MZN 2.850,00',
                    time: '12 min atrás',
                    unread: true,
                    action: 'order'
                },
                {
                    id: 3,
                    type: 'info',
                    title: 'Reserva Confirmada',
                    message: 'Mesa 12 reservada para hoje às 19:00',
                    time: '1 hora atrás',
                    unread: false,
                    action: 'reservation'
                }
            ];

            const unreadCount = notifications.filter(n => n.unread).length;
            updateNotificationBadge(unreadCount);
            updateNotificationsList(notifications);
        }

        function updateNotificationBadge(count) {
            const badge = document.getElementById('notification-count');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        }

        function updateNotificationsList(notifications) {
            const list = document.getElementById('notifications-list');
            if (!list) return;

            if (notifications.length === 0) {
                list.innerHTML = `
                    <div class="text-center p-4 text-muted">
                        <i class="mdi mdi-bell-off-outline" style="font-size: 3rem;"></i>
                        <p class="mb-0 mt-2">Nenhuma notificação</p>
                        <small>Você está em dia!</small>
                    </div>
                `;
                return;
            }

            const iconMap = {
                success: 'mdi-check-circle text-success',
                warning: 'mdi-alert-triangle text-warning',
                error: 'mdi-alert-circle text-danger',
                info: 'mdi-information text-info'
            };

            list.innerHTML = notifications.map(notification => `
                <div class="notification-item border-bottom p-3 ${notification.unread ? 'bg-light' : ''}" 
                     data-notification-id="${notification.id}" 
                     data-action="${notification.action}"
                     style="cursor: pointer; transition: background-color 0.2s ease;">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="mdi ${iconMap[notification.type] || 'mdi-information text-info'}" 
                               style="font-size: 1.3rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold mb-1">${notification.title}</div>
                            <div class="text-muted small mb-2">${notification.message}</div>
                            <div class="text-muted small d-flex align-items-center">
                                <i class="mdi mdi-clock-outline me-1"></i>
                                ${notification.time}
                            </div>
                        </div>
                        ${notification.unread ? 
                            '<div class="notification-dot bg-primary rounded-circle ms-2" style="width: 8px; height: 8px; flex-shrink: 0;"></div>' : 
                            '<div class="ms-2" style="width: 8px;"></div>'
                        }
                    </div>
                </div>
            `).join('');

            // Add click handlers for notifications
            list.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', () => {
                    const notificationId = item.dataset.notificationId;
                    const action = item.dataset.action;
                    handleNotificationClick(notificationId, action);
                });

                item.addEventListener('mouseenter', () => {
                    item.style.backgroundColor = 'rgba(14, 165, 233, 0.05)';
                });

                item.addEventListener('mouseleave', () => {
                    item.style.backgroundColor = item.classList.contains('bg-light') ? '' : 'transparent';
                });
            });
        }

        function handleNotificationClick(notificationId, action) {
            // Mark notification as read
            markNotificationAsRead(notificationId);

            // Handle different notification actions
            switch (action) {
                case 'inventory':
                    showToast('Redirecionando para o estoque...', 'info');
                    // window.location.href = '/inventory';
                    break;
                case 'order':
                    showToast('Redirecionando para pedidos...', 'info');
                    // window.location.href = '/orders';
                    break;
                case 'reservation':
                    showToast('Redirecionando para reservas...', 'info');
                    // window.location.href = '/reservations';
                    break;
                default:
                    console.log('Notification clicked:', notificationId);
            }
        }

        function markNotificationAsRead(notificationId) {
            // Simulate API call to mark as read
            console.log('Marking notification as read:', notificationId);

            // Update UI immediately
            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.remove('bg-light');
                const dot = notificationItem.querySelector('.notification-dot');
                if (dot) {
                    dot.remove();
                }
            }

            // Update badge count
            const currentBadge = document.getElementById('notification-count');
            if (currentBadge && currentBadge.style.display !== 'none') {
                const currentCount = parseInt(currentBadge.textContent) || 0;
                const newCount = Math.max(0, currentCount - 1);
                updateNotificationBadge(newCount);
            }
        }

        function markAllNotificationsAsRead() {
            // Simulate API call
            console.log('Marking all notifications as read');

            // Update UI
            const notificationItems = document.querySelectorAll('.notification-item.bg-light');
            notificationItems.forEach(item => {
                item.classList.remove('bg-light');
                const dot = item.querySelector('.notification-dot');
                if (dot) dot.remove();
            });

            updateNotificationBadge(0);
            showToast('Todas as notificações foram marcadas como lidas', 'success');
        }

        // ===== WEATHER WIDGET =====
        function updateWeatherInfo() {
            // Simulate weather API call for Zalala Beach
            const weatherElement = document.getElementById('weather-info');
            if (weatherElement) {
                // Mock weather data - replace with actual API
                const weather = {
                    temperature: Math.floor(Math.random() * 10) + 25, // 25-35°C
                    condition: 'sunny',
                    location: 'Zalala'
                };

                const iconMap = {
                    sunny: 'mdi-weather-sunny',
                    cloudy: 'mdi-weather-cloudy',
                    rainy: 'mdi-weather-rainy'
                };

                weatherElement.innerHTML = `
                    <i class="mdi ${iconMap[weather.condition]} text-warning me-1"></i>
                    ${weather.temperature}°C ${weather.location}
                `;
            }
        }

        // ===== KEYBOARD SHORTCUTS =====
        function setupKeyboardShortcuts() {
            document.addEventListener('keydown', function(e) {
                // Only trigger shortcuts when not typing in inputs
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.contentEditable ===
                    'true') {
                    return;
                }

                // Check for modifier keys
                const hasModifier = e.ctrlKey || e.metaKey || e.altKey;

                // Global shortcuts
                if (hasModifier) {
                    switch (e.key.toLowerCase()) {
                        case 'k': // Search
                            e.preventDefault();
                            const searchInput = document.getElementById('global-search') || document.getElementById(
                                'mobile-search');
                            if (searchInput) {
                                searchInput.focus();
                                searchInput.select();
                            }
                            break;

                        case 'n': // New order (Ctrl/Cmd + N)
                            if (e.ctrlKey || e.metaKey) {
                                e.preventDefault();
                                showToast('Redirecionando para novo pedido...', 'info');
                                // window.location.href = '/orders/create';
                            }
                            break;

                        case 'p': // POS (Ctrl/Cmd + P)
                            if (e.ctrlKey || e.metaKey) {
                                e.preventDefault();
                                showToast('Abrindo PDV...', 'info');
                                // window.location.href = '/pos';
                            }
                            break;
                    }
                }

                // Quick navigation shortcuts (Alt + key)
                if (e.altKey && !e.ctrlKey && !e.metaKey) {
                    switch (e.key) {
                        case '1':
                            e.preventDefault();
                            window.location.href = '/dashboard';
                            break;
                        case '2':
                            e.preventDefault();
                            // window.location.href = '/pos';
                            showToast('Atalho: Alt + 2 para PDV', 'info');
                            break;
                        case '3':
                            e.preventDefault();
                            // window.location.href = '/orders';
                            showToast('Atalho: Alt + 3 para Pedidos', 'info');
                            break;
                        case '4':
                            e.preventDefault();
                            // window.location.href = '/tables';
                            showToast('Atalho: Alt + 4 para Mesas', 'info');
                            break;
                    }
                }

                // Sidebar toggle (F2)
                if (e.key === 'F2') {
                    e.preventDefault();
                    const sidebarToggle = document.getElementById('sidebar-toggle');
                    if (sidebarToggle) {
                        sidebarToggle.click();
                    }
                }
            });
        }

        // ===== UTILITY FUNCTIONS =====
        function formatCurrency(amount, currency = 'MZN') {
            return new Intl.NumberFormat('pt-MZ', {
                style: 'currency',
                currency: currency,
                minimumFractionDigits: 2
            }).format(amount);
        }

        function formatDate(date, options = {}) {
            const defaultOptions = {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return new Date(date).toLocaleDateString('pt-BR', {
                ...defaultOptions,
                ...options
            });
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // ===== PERFORMANCE MONITORING =====
        function setupPerformanceMonitoring() {
            // Monitor page load performance
            window.addEventListener('load', () => {
                if ('performance' in window) {
                    const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
                    console.log(`Page loaded in ${loadTime}ms`);

                    // Show warning if page loads slowly
                    if (loadTime > 3000) {
                        setTimeout(() => {
                            showToast(
                                'Sistema carregou mais lentamente que o esperado. Verifique sua conexão.',
                                'warning');
                        }, 1000);
                    }
                }
            });
        }

        // ===== ERROR HANDLING =====
        function setupErrorHandling() {
            window.addEventListener('error', (e) => {
                console.error('JavaScript Error:', e.error);
                showToast('Ocorreu um erro inesperado. Recarregue a página se necessário.', 'error');
            });

            window.addEventListener('unhandledrejection', (e) => {
                console.error('Unhandled Promise Rejection:', e.reason);
                showToast('Erro de conexão. Verifique sua internet e tente novamente.', 'error');
            });
        }

        // ===== INITIALIZATION =====
        document.addEventListener('DOMContentLoaded', function() {
            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);

            // Initialize core components
            new SidebarManager();
            setupGlobalSearch();
            setupKeyboardShortcuts();
            setupPerformanceMonitoring();
            setupErrorHandling();

            // Update date/time
            updateDateTime();
            setInterval(updateDateTime, 60000); // Update every minute

            // Update weather info
            updateWeatherInfo();
            setInterval(updateWeatherInfo, 600000); // Update every 10 minutes

            // Load notifications
            loadNotifications();
            setInterval(loadNotifications, 30000); // Update every 30 seconds

            // Mark all notifications as read handler
            const markAllReadBtn = document.getElementById('mark-all-read');
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    markAllNotificationsAsRead();
                });
            }

            // Add smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Initialize tooltips if Bootstrap is available
            if (typeof bootstrap !== 'undefined') {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Show welcome message
            /*  setTimeout(() => {
                 const userName = document.querySelector('.user-info h6')?.textContent || 'Usuário';
                 showToast(`Bem-vindo(a), ${userName}! Sistema pronto para uso.`, 'success');
             }, 1000); */

            console.log('🏖️ ZALALA BEACH BAR - Sistema de Gestão inicializado com sucesso!');
        });

        // ===== EXPORT FUNCTIONS FOR GLOBAL USE =====
        window.ZalalaSystem = {
            showToast,
            formatCurrency,
            formatDate,
            debounce,
            updateNotificationBadge,
            markAllNotificationsAsRead
        };
    </script>
    @stack('scripts')
</body>

</html>
