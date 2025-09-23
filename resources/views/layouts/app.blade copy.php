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
    {{-- <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet"> --}}
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #fef3c7 100%);
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
            font-family: 'Dancing Script', cursive;
            font-size: 1.8rem;
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
            font-family: 'Dancing Script', cursive;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
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
            0%, 100% {
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
                z-index: 1035;
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
            <div class="brand-logo">
                <div class="logo-icon">
                    <i class="mdi mdi-beach text-white" style="font-size: 2rem;"></i>
                </div>
                <h1 class="brand-title">ZALALA BEACH BAR</h1>
                <div class="brand-subtitle">Restaurante • Bar • Gestão</div>
            </div>
            
           {{--  <div class="business-info">
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
            </div> --}}
        </div>

        <!-- Navigation -->
        <div class="sidebar-nav">
            <!-- Dashboard -->
            <div class="nav-section">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dashboard') ?? '#' }}">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Operacional -->
            <div class="nav-section">
                <div class="nav-section-title">
                    <i class="mdi mdi-store"></i>
                    <span>Operacional</span>
                </div>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pos.index') ?? '#' }}">
                            <i class="mdi mdi-point-of-sale"></i>
                            <span>PDV (Ponto de Venda)</span>
                            <span class="badge bg-success ms-auto">Live</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.index') ?? '#' }}">
                            <i class="mdi mdi-receipt"></i>
                            <span>Pedidos</span>
                            <span class="badge bg-warning ms-auto">5</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tables.index') ?? '#' }}">
                            <i class="mdi mdi-table-furniture"></i>
                            <span>Mesas</span>
                            <span class="badge bg-info ms-auto">12</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="mdi mdi-calendar-check"></i>
                            <span>Reservas</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Menu & Produtos -->
            <div class="nav-section">
                <div class="nav-section-title">
                    <i class="mdi mdi-food-variant"></i>
                    <span>Menu & Produtos</span>
                </div>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') ?? '#' }}">
                            <i class="mdi mdi-food"></i>
                            <span>Produtos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') ?? '#' }}">
                            <i class="mdi mdi-format-list-bulleted"></i>
                            <span>Categorias</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="mdi mdi-glass-cocktail"></i>
                            <span>Bebidas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="mdi mdi-book-open-variant"></i>
                            <span>Cardápio Digital</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Financeiro -->
            <div class="nav-section">
                <div class="nav-section-title">
                    <i class="mdi mdi-cash-multiple"></i>
                    <span>Financeiro</span>
                </div>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sales.index') ?? '#' }}">
                            <i class="mdi mdi-currency-usd"></i>
                            <span>Vendas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reports.index') ?? '#' }}">
                            <i class="mdi mdi-chart-line"></i>
                            <span>Relatórios</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="mdi mdi-cash-remove"></i>
                            <span>Despesas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="mdi mdi-bank"></i>
                            <span>Fluxo de Caixa</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Clientes -->
            <div class="nav-section">
                <div class="nav-section-title">
                    <i class="mdi mdi-account-group"></i>
                    <span>Relacionamento</span>
                </div>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('clients.index') ?? '#' }}">
                            <i class="mdi mdi-account-heart"></i>
                            <span>Clientes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="mdi mdi-gift"></i>
                            <span>Programa Fidelidade</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Administração -->
            <div class="nav-section">
                <div class="nav-section-title">
                    <i class="mdi mdi-shield-account"></i>
                    <span>Administração</span>
                </div>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('employees.index') ?? '#' }}">
                            <i class="mdi mdi-account-tie"></i>
                            <span>Funcionários</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') ?? '#' }}">
                            <i class="mdi mdi-account-key"></i>
                            <span>Usuários</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="mdi mdi-cog"></i>
                            <span>Configurações</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="mdi mdi-backup-restore"></i>
                            <span>Backup</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- User Area -->
        <div class="user-area">
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="mdi mdi-chef-hat text-white"></i>
                    <div class="status-dot"></div>
                </div>
                <div class="user-info flex-grow-1">
                    <div class="user-name">{{ auth()->user()->name ?? 'Administrador' }}</div>
                    <div class="user-role">{{ auth()->user()->role ?? 'Gerente' }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') ?? '#' }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100 d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-logout me-2"></i>
                    Sair do Sistema
                </button>
            </form>
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
                            <input type="text" class="form-control search-input" placeholder="Buscar produtos, pedidos, clientes..." id="global-search">
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Quick Actions -->
                        <div class="dropdown d-none d-md-block">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="mdi mdi-lightning-bolt me-1"></i>
                                Ações Rápidas
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><h6 class="dropdown-header">Ações Rápidas</h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('pos.index') ?? '#' }}">
                                        <i class="mdi mdi-point-of-sale text-success me-2"></i>
                                        Abrir PDV
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="mdi mdi-plus-circle text-primary me-2"></i>
                                        Novo Pedido
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="mdi mdi-food-variant text-warning me-2"></i>
                                        Adicionar Produto
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
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
                            <button class="btn btn-outline-primary position-relative" type="button" data-bs-toggle="dropdown">
                                <i class="mdi mdi-bell-outline"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    3
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" style="min-width: 320px;">
                                <div class="dropdown-header d-flex justify-content-between">
                                    <span>Notificações</span>
                                    <button class="btn btn-link btn-sm p-0">Marcar como lidas</button>
                                </div>
                                <div class="dropdown-divider"></div>
                                
                                <div class="px-3 py-2 border-bottom">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="bg-warning rounded-circle p-2">
                                                <i class="mdi mdi-alert-circle text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 small">Estoque Baixo</h6>
                                            <p class="mb-1 small text-muted">3 produtos com estoque crítico</p>
                                            <small class="text-muted">5 min atrás</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-3 py-2 border-bottom">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="bg-success rounded-circle p-2">
                                                <i class="mdi mdi-check-circle text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 small">Pedido Finalizado</h6>
                                            <p class="mb-1 small text-muted">Mesa 12 - Total: 2,500 MZN</p>
                                            <small class="text-muted">12 min atrás</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-3 py-2">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="bg-info rounded-circle p-2">
                                                <i class="mdi mdi-calendar-check text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 small">Nova Reserva</h6>
                                            <p class="mb-1 small text-muted">Mesa para 6 pessoas às 19:30h</p>
                                            <small class="text-muted">25 min atrás</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>
                                <div class="text-center p-2">
                                    <a href="#" class="btn btn-sm btn-primary w-100">Ver Todas</a>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
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
                                        <div class="text-muted small">{{ auth()->user()->email ?? 'admin@zalalabeachbar.com' }}</div>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
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
                                <li><hr class="dropdown-divider"></li>
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
   <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->
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
            const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 4000 });
            
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

        // ===== SEARCH FUNCTIONALITY =====
        function setupSearch() {
            const searchInputs = document.querySelectorAll('#global-search, .search-input');
            
            searchInputs.forEach(input => {
                let searchTimeout;
                
                input.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    const query = e.target.value.trim();
                    
                    if (query.length < 2) return;
                    
                    searchTimeout = setTimeout(() => {
                        performSearch(query);
                    }, 300);
                });
            });
        }

        function performSearch(query) {
            // Implement search logic here
            console.log('Searching for:', query);
            // You can add AJAX call to Laravel backend here
        }

        // ===== INITIALIZATION =====
        document.addEventListener('DOMContentLoaded', function() {
            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);

            // Initialize components
            try {
                new SidebarManager();
                setupSearch();
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
                showToast('Bem-vindo ao ZALALA BEACH BAR!', 'info');
            }, 1000);
        });

        // Expose functions globally
        window.toggleTheme = toggleTheme;
        window.showToast = showToast;
    </script>

    @stack('scripts')
</body>
</html>
        