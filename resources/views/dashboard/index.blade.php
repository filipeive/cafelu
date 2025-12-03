@extends('layouts.app')

@section('title', 'Dashboard - Zalala Beach Bar')
@section('icon', 'view-dashboard')

@push('styles')
    <style>
        /* ========================================
                   DASHBOARD CONTAINER
                ======================================== */
        .dashboard-container {
            padding: 1.5rem;
            min-height: calc(100vh - 140px);
        }

        /* ========================================
                   STATS CARDS - Glassmorphism Design
                ======================================== */
        .card {
            background: rgba(26, 26, 46, 0.95) !important;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            color: #F5F5F5;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 165, 0, 0.3);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, #FFA500, #FF8C00);
        }

        .card.bg-gradient-primary::before {
            background: linear-gradient(to bottom, #4F46E5, #7C3AED);
        }

        .card.bg-gradient-warning::before {
            background: linear-gradient(to bottom, #F59E0B, #D97706);
        }

        .card.bg-gradient-danger::before {
            background: linear-gradient(to bottom, #EF4444, #DC2626);
        }

        .card.bg-gradient-info::before {
            background: linear-gradient(to bottom, #3B82F6, #2563EB);
        }

        .stats-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            background: rgba(255, 255, 255, 0.08);
        }

        .stats-label {
            font-size: 0.9rem;
            font-weight: 500;
            opacity: 0.85;
            margin-bottom: 0.25rem;
        }

        .stats-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            color: #FFA500;
        }

        /* ========================================
                   CARD STYLES
                ======================================== */
        .card {
            background: rgba(26, 26, 46, 0.95) !important;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            color: #F5F5F5;
        }

        .card-header {
            background: rgba(35, 35, 60, 0.6) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            padding: 1.25rem 1.5rem !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            color: #FFA500 !important;
            font-weight: 600 !important;
            display: flex;
            align-items: center;
            margin: 0 !important;
        }

        .card-title i {
            margin-right: 0.5rem;
            color: #FFA500;
        }

        /* ========================================
                   REAL-TIME STATUS
                ======================================== */
        .real-time-status {
            padding: 0;
        }

        .status-item {
            display: flex;
            align-items: center;
            padding: 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            transition: background 0.3s ease;
        }

        .status-item:last-child {
            border-bottom: none;
        }

        .status-item:hover {
            background: rgba(45, 45, 75, 0.5);
        }

        .status-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 1rem;
        }

        .status-icon.bg-primary {
            background: rgba(255, 165, 0, 0.2);
            color: #FFA500;
        }

        .status-icon.bg-success {
            background: rgba(16, 185, 129, 0.2);
            color: #10B981;
        }

        .status-icon.bg-info {
            background: rgba(59, 130, 246, 0.2);
            color: #3B82F6;
        }

        .status-icon.bg-warning {
            background: rgba(245, 158, 11, 0.2);
            color: #F59E0B;
        }

        .status-content {
            flex: 1;
        }

        .status-content h6 {
            font-size: 0.95rem;
            font-weight: 600;
            color: #F5F5F5;
            margin: 0 0 0.25rem 0;
        }

        /* ========================================
                   TOP PRODUCTS
                ======================================== */
        .top-products-list {
            padding: 0;
        }

        .top-product-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.85rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .top-product-item:last-child {
            border-bottom: none;
        }

        .product-rank {
            margin-right: 1rem;
        }

        .rank-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            background: rgba(255, 165, 0, 0.2);
            color: #FFA500;
        }

        .product-info {
            flex: 1;
        }

        .product-info h6 {
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0 0 0.25rem 0;
            color: #F5F5F5;
        }

        .product-info small {
            color: rgba(255, 255, 255, 0.6);
        }

        /* ========================================
                   TABLES GRID
                ======================================== */
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 0.8rem;
        }

        .table-card {
            background: rgba(35, 35, 60, 0.7);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .table-card:hover {
            transform: scale(1.03);
            border-color: rgba(255, 165, 0, 0.4);
        }

        .table-card[data-status="occupied"] {
            border-color: rgba(239, 68, 68, 0.5);
            background: rgba(89, 35, 42, 0.5);
        }

        .table-card[data-status="available"] {
            border-color: rgba(72, 187, 120, 0.5);
            background: rgba(28, 85, 61, 0.3);
        }

        .table-icon {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: #F5F5F5;
        }

        .table-info h6 {
            font-size: 0.9rem;
            font-weight: 600;
            margin: 0 0 0.25rem 0;
            color: #F5F5F5;
        }

        .table-info small {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
        }

        .table-status {
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .status-dot.occupied {
            background: #EF4444;
        }

        .status-dot.available {
            background: #10B981;
        }

        .table-status small {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.7rem;
        }

        /* ========================================
                   LOW STOCK
                ======================================== */
        .low-stock-list {
            padding: 0;
        }

        .low-stock-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .low-stock-item:last-child {
            border-bottom: none;
        }

        .product-image {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            overflow: hidden;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-placeholder {
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9CA3AF;
            font-size: 1.2rem;
        }

        .product-details {
            flex: 1;
        }

        .product-details h6 {
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0 0 0.25rem 0;
            color: #F5F5F5;
        }

        .product-details small {
            color: rgba(255, 255, 255, 0.6);
        }

        /* ========================================
                   QUICK ACTIONS
                ======================================== */
        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            border-radius: 16px;
            background: rgba(35, 35, 60, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #F5F5F5;
            text-decoration: none;
            transition: all 0.3s ease;
            height: 120px;
            text-align: center;
        }

        .quick-action-btn:hover {
            background: rgba(45, 45, 75, 0.8);
            transform: translateY(-4px);
            border-color: rgba(255, 165, 0, 0.4);
            color: #FFA500;
            text-decoration: none;
        }

        .quick-action-btn i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .quick-action-btn span {
            font-size: 0.95rem;
            font-weight: 600;
        }

        /* ========================================
                   TABLE STYLES
                ======================================== */
        .table {
            color: #F5F5F5 !important;
        }

        .table thead th {
            border-bottom: 2px solid rgba(255, 255, 255, 0.1) !important;
            color: #FFA500 !important;
            font-weight: 600 !important;
            background: transparent !important;
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .table tbody tr:hover {
            background: rgba(45, 45, 75, 0.5) !important;
        }

        .table tbody td {
            border-top: none !important;
            padding: 1rem 0.75rem;
        }

        /* ========================================
                   BADGES
                ======================================== */
        .badge {
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
        }

        .badge.bg-success {
            background-color: rgba(16, 185, 129, 0.2) !important;
            color: #10B981 !important;
        }

        .badge.bg-danger {
            background-color: rgba(239, 68, 68, 0.2) !important;
            color: #EF4444 !important;
        }

        .badge.bg-warning {
            background-color: rgba(245, 158, 11, 0.2) !important;
            color: #F59E0B !important;
        }

        .badge.bg-info {
            background-color: rgba(59, 130, 246, 0.2) !important;
            color: #3B82F6 !important;
        }

        .badge.bg-primary {
            background-color: rgba(255, 165, 0, 0.2) !important;
            color: #FFA500 !important;
        }

        /* ========================================
                   CHART CONTAINER
                ======================================== */
        .chart-container {
            height: 280px;
            position: relative;
        }

        /* ========================================
                   BUTTONS
                ======================================== */
        .btn-warning {
            background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);
            border: none;
            color: #1a1a2e;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #FF8C00 0%, #FFA500 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 165, 0, 0.4);
            color: #1a1a2e;
        }

        .btn-outline-warning {
            border: 2px solid #FFA500;
            color: #FFA500;
            background: transparent;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-warning:hover {
            background: #FFA500;
            color: #1a1a2e;
            transform: translateY(-2px);
        }

        .btn-outline-warning.active {
            background: #FFA500;
            color: #1a1a2e;
        }

        .btn-sm {
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
        }

        /* ========================================
                   RESPONSIVE
                ======================================== */
        @media (max-width: 991px) {
            .card {
                min-height: auto;
            }

            .chart-container {
                height: 240px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }

            .tables-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            }

            .card-header {
                flex-direction: column;
                gap: 0.75rem;
                align-items: flex-start !important;
            }

            .stats-value {
                font-size: 1.5rem;
            }
        }

        /* ========================================
                   MODAL STYLES
                ======================================== */
        .modal-content {
            background: rgba(26, 26, 46, 0.98) !important;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .modal-title {
            color: #FFA500 !important;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0" style="color: #FFA500; font-weight: 700; letter-spacing: -0.5px;">
                <i class="mdi mdi-view-dashboard me-2"></i> Dashboard
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('pos.index') }}" class="btn btn-warning">
                    <i class="mdi mdi-cash-register me-2"></i> Ir para POS
                </a>
                <button class="btn btn-warning" onclick="refreshDashboard()">
                    <i class="mdi mdi-refresh"></i>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Vendas Hoje -->
            <div class="col-xl-3 col-lg-6 col-md-6" style="color: #F5F5F5 !important; ">
                <div class="card bg-gradient-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon text-white">
                                <i class="mdi mdi-cash-multiple"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="stats-label text-white">Vendas Hoje</h6>
                                <h3 class="stats-value text-white">{{ number_format($todaySales ?? 0, 2, ',', '.') }} MT
                                </h3>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted text-white"
                                    style="opacity: 0.7; font-size: 0.85rem; color: #F5F5F5 !important;">Vs ontem</span>
                                <span class="badge {{ ($salesGrowth ?? 0) >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                                    {{ number_format($salesGrowth ?? 0, 1) }}%
                                    <i
                                        class="mdi mdi-arrow-{{ ($salesGrowth ?? 0) >= 0 ? 'up' : 'down' }} ms-1 text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pedidos Ativos -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card bg-gradient-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center text-white">
                            <div class="stats-icon">
                                <i class="mdi mdi-cart"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="stats-label text-white">Pedidos Ativos</h6>
                                <h3 class="stats-value text-white">{{ $openOrders ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted text-white"
                                    style="opacity: 0.7; font-size: 0.85rem; color: #F5F5F5 !important;">Pendentes:
                                    {{ $pendingOrders ?? 0 }}</span>
                                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-warning text-white">
                                    Ver Todos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estoque Baixo -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card bg-gradient-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon text-white">
                                <i class="mdi mdi-alert-circle"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="stats-label text-white">Estoque Baixo</h6>
                                <h3 class="stats-value text-white">{{ $lowStockProducts->count() ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted text-white"
                                    style="opacity: 0.7; font-size: 0.85rem; color: #F5F5F5 !important;">Total:
                                    {{ $totalProducts ?? 0 }}</span>
                                <a href="{{ route('products.index') }}" class="btn btn-sm btn-warning text-white">
                                    Gerenciar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Mesas -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center text-white">
                            <div class="stats-icon">
                                <i class="mdi mdi-table-furniture"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="stats-label text-white">Status das Mesas</h6>
                                <h3 class="stats-value text-white">{{ $availableTables ?? 0 }}/{{ $tables->count() ?? 0 }}
                                </h3>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted"
                                    style="opacity: 0.7; font-size: 0.85rem; color: #F5F5F5 !important;">{{ $occupiedTables ?? 0 }}
                                    ocupadas</span>
                                <a href="{{ route('tables.index') }}" class="btn btn-sm btn-warning">
                                    Ver Mesas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Info -->
        <div class="row g-4 mb-4">
            <!-- Sales Chart -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="mdi mdi-chart-line"></i>
                            Desempenho de Vendas
                        </h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-warning active"
                                onclick="changeChartPeriod('daily')">
                                Diário
                            </button>
                            <button type="button" class="btn btn-outline-warning" onclick="changeChartPeriod('hourly')">
                                Por Hora
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Real-time Status -->
            <div class="col-xl-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-statistic mb-4">
                            <div class="card-icon bg-primary">
                                <i class="mdi mdi-cart-plus"></i>
                            </div>
                            <div class="card-body">
                                <h6>Pedidos em Andamento</h6>
                                <span class="card-number">{{ $openOrders ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-statistic mb-4">
                            <div class="card-icon bg-success">
                                <i class="mdi mdi-check-circle"></i>
                            </div>
                            <div class="card-body">
                                <h6>Pedidos Hoje</h6>
                                <span class="card-number">{{ $completedOrdersToday ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-statistic mb-4">
                            <div class="card-icon bg-info">
                                <i class="mdi mdi-account-multiple"></i>
                            </div>
                            <div class="card-body">
                                <h6>Novos Clientes</h6>
                                <span class="card-number">+{{ $newClientsToday ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-statistic mb-4">
                            <div class="card-icon bg-warning">
                                <i class="mdi mdi-calendar-clock"></i>
                            </div>
                            <div class="card-body">
                                <h6>Vendas da Semana</h6>
                                <span class="card-number">{{ number_format($weekSales ?? 0, 2, ',', '.') }} MT</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Top Products -->
            <div class="row g-4 mb-4">
                <div class="col-xl-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="mdi mdi-trophy"></i>
                                Produtos Mais Vendidos
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="top-products-list">
                                @forelse($topProducts ?? [] as $index => $product)
                                    <div class="top-product-item">
                                        <div class="product-rank">
                                            <span class="rank-badge">{{ $index + 1 }}</span>
                                        </div>
                                        <div class="product-info flex-grow-1">
                                            <h6 class="mb-1">{{ $product->name }}</h6>
                                            <small>{{ $product->category->name ?? 'Sem categoria' }}</small>
                                        </div>
                                        <div class="product-stats">
                                            <span class="badge bg-primary">{{ $product->total_sold ?? 0 }} vendas</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-muted py-3">Nenhum produto vendido</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="col-xl-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="mdi mdi-clock-fast"></i>
                                Pedidos Recentes
                            </h5>
                            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-warning">
                                Ver Todos
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-3">ID</th>
                                            <th>Cliente</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th class="pe-3">Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders ?? [] as $order)
                                            <tr>
                                                <td class="ps-3">#{{ $order->id }}</td>
                                                <td>{{ $order->client_name ?? 'Consumidor Final' }}</td>
                                                <td class="fw-bold">{{ number_format($order->total_amount, 2, ',', '.') }}
                                                    MT
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $order->status_color ?? 'success' }}">
                                                        {{ $order->status_label ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="pe-3">{{ $order->created_at->format('H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-3">Nenhum pedido
                                                    recente
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tables and Low Stock -->
            <div class="row g-4 mb-4">
                <!-- Tables Status -->
                <div class="col-xl-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="mdi mdi-table-furniture"></i>
                                Status das Mesas
                            </h5>
                            <a href="{{ route('tables.index') }}" class="btn btn-sm btn-outline-warning">
                                Ver Todas
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @forelse($tables->take(8) ?? [] as $table)
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                                        <div class="card h-100" role="button" tabindex="0"
                                            onclick="openTableInstructionsModal({{ $table->id }}, '{{ $table->number }}', '{{ $table->status }}')"
                                            style="cursor: pointer; background: {{ $table->status === 'occupied' ? 'rgba(239, 68, 68, 0.06)' : 'rgba(16, 185, 129, 0.06)' }}; border: 1px solid {{ $table->status === 'occupied' ? 'rgba(239,68,68,0.12)' : 'rgba(16,185,129,0.12)' }};">
                                            <div class="card-body d-flex flex-column align-items-center text-center">
                                                <div class="fs-1 mb-2"
                                                    style="color: {{ $table->status === 'occupied' ? '#EF4444' : '#10B981' }};">
                                                    <i class="mdi mdi-table-furniture"></i>
                                                </div>
                                                <h6 class="mb-1">Mesa {{ $table->number }}</h6>
                                                <small class="text-muted d-block mb-2">{{ $table->capacity }}
                                                    lugares</small>

                                                @if (isset($table->current_order) && $table->current_order)
                                                    <div class="w-100 mb-3 p-2 rounded"
                                                        style="background: rgba(255,165,0,0.06); border: 1px solid rgba(255,165,0,0.12);">
                                                        <small class="d-block text-truncate"
                                                            style="font-weight:600; color:#FFA500;">
                                                            Pedido: #{{ $table->current_order->id ?? '—' }}
                                                        </small>
                                                        <small class="text-muted">Valor:
                                                            {{ number_format($table->current_order->total_amount ?? 0, 2, ',', '.') }}
                                                            MT</small>
                                                    </div>
                                                @endif

                                                <div
                                                    class="d-flex gap-2 w-100 justify-content-center align-items-center mt-auto">
                                                    <span
                                                        class="badge {{ $table->status === 'occupied' ? 'bg-danger' : 'bg-success' }}">
                                                        {{ $table->status === 'occupied' ? 'Ocupada' : 'Disponível' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-center text-muted py-3">Nenhuma mesa cadastrada</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Low Stock -->
                <div class="col-xl-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="mdi mdi-alert"></i>
                                Estoque Baixo
                            </h5>
                            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-warning">
                                Ver Todos
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @forelse($lowStockProducts->take(8) ?? [] as $product)
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                                        <div class="card h-100" role="button" tabindex="0"
                                            onclick="openProductInstructionsModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock_quantity }})">
                                            <div class="card-body">
                                                <div class="product-image mb-3">
                                                    @if ($product->image)
                                                        <img src="{{ asset('storage/' . $product->image) }}"
                                                            alt="{{ $product->name }}" class="w-100 rounded">
                                                    @else
                                                        <div class="image-placeholder">
                                                            <i class="mdi mdi-food"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <h6 class="mb-2">{{ $product->name }}</h6>
                                                <small class="text-muted d-block mb-3">Estoque:
                                                    <strong>{{ $product->stock_quantity }}</strong></small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-center text-muted py-3">Estoque adequado</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row g-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="mdi mdi-lightning-bolt"></i>
                                Ações Rápidas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                    <a href="{{ route('pos.index') }}" class="quick-action-btn">
                                        <i class="mdi mdi-cash-register"></i>
                                        <span>Novo Pedido</span>
                                    </a>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                    <a href="{{ route('tables.index') }}" class="quick-action-btn">
                                        <i class="mdi mdi-table-furniture"></i>
                                        <span>Gerenciar Mesas</span>
                                    </a>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                    <a href="{{ route('products.create') }}" class="quick-action-btn">
                                        <i class="mdi mdi-plus-circle"></i>
                                        <span>Adicionar Produto</span>
                                    </a>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                    <a href="{{ route('clients.index') }}" class="quick-action-btn">
                                        <i class="mdi mdi-account-plus"></i>
                                        <span>Novo Cliente</span>
                                    </a>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                    <a href="{{ route('reports.index') }}" class="quick-action-btn">
                                        <i class="mdi mdi-chart-bar"></i>
                                        <span>Relatórios</span>
                                    </a>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                    <a href="{{-- route('reservations.create') --}}#" class="quick-action-btn">
                                        <i class="mdi mdi-calendar-plus"></i>
                                        <span>Nova Reserva</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Instructions Modal -->
        <div class="modal fade" id="tableInstructionsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Gerenciar Mesa</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="fs-1 mb-2" id="instructionTableIcon">
                                <i class="mdi mdi-table-furniture" style="color: #10B981;"></i>
                            </div>
                            <h4 id="instructionTableNumber">Mesa</h4>
                            <span class="badge" id="instructionTableStatus" style="font-size: 0.9rem;"></span>
                        </div>

                        <div class="alert alert-info mb-4"
                            style="background: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.3); color: #3B82F6; border-radius: 8px;">
                            <i class="mdi mdi-information-outline me-2"></i>
                            <span id="instructionText"></span>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="#" id="actionButton" class="btn btn-warning">
                                <i class="mdi me-2"></i>
                                <span id="actionButtonText"></span>
                            </a>
                            <button class="btn btn-outline-light" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Instructions Modal -->
        <div class="modal fade" id="productInstructionsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reabastecer Estoque</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="fs-1 mb-2" style="color: #EF4444;">
                                <i class="mdi mdi-alert-circle"></i>
                            </div>
                            <h4 id="instructionProductName">Produto</h4>
                            <p class="text-muted mb-0">Estoque Atual: <strong id="instructionProductStock">0</strong>
                                unidades
                            </p>
                        </div>

                        <div class="alert alert-warning mb-4"
                            style="background: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.3); color: #F59E0B; border-radius: 8px;">
                            <i class="mdi mdi-alert me-2"></i>
                            Este produto está com estoque baixo. Acesse a página de produtos para realizar o
                            reabastecimento.
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('products.index') }}" class="btn btn-warning">
                                <i class="mdi mdi-package-variant me-2"></i>
                                Ir para Produtos
                            </a>
                            <button class="btn btn-outline-light" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Container -->
        <div id="toastContainer" class="toast-container"></div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="loading-overlay">
            <div class="loading-spinner"></div>
        </div>
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                initializeChart();
            });

            let salesChart = null;
            const dailySalesData = @json($dailySales ?? []);
            const hourlySalesData = @json($hourlySales ?? []);

            function initializeChart(type = 'daily') {
                const ctx = document.getElementById('salesChart');
                if (!ctx) return;

                const data = type === 'daily' ? dailySalesData : hourlySalesData;
                const labels = data.map(item => type === 'daily' ? item.day : item.hour);
                const values = data.map(item => parseFloat(item.sales) || 0);

                if (salesChart) salesChart.destroy();

                salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Vendas (MT)',
                            data: values,
                            borderColor: '#FFA500',
                            backgroundColor: 'rgba(255, 165, 0, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#FFA500',
                            pointBorderColor: '#1A1A2E',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 15, 30, 0.9)',
                                titleColor: '#FFA500',
                                bodyColor: '#F5F5F5',
                                borderColor: 'rgba(255, 165, 0, 0.3)',
                                borderWidth: 1,
                                padding: 12,
                                callbacks: {
                                    label: ctx => `${ctx.parsed.y.toFixed(2)} MT`
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.05)'
                                },
                                ticks: {
                                    color: '#F5F5F5',
                                    callback: value => value.toFixed(0) + ' MT'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#F5F5F5'
                                }
                            }
                        }
                    }
                });
            }

            function changeChartPeriod(type) {
                document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
                event.target.classList.add('active');
                initializeChart(type);
            }

            function refreshDashboard() {
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i>';

                fetch('{{ route('dashboard.stats') }}', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        showToast('Dashboard atualizado com sucesso!', 'success');
                        setTimeout(() => location.reload(), 500);
                    })
                    .catch(err => {
                        console.error('Erro:', err);
                        showToast('Erro ao atualizar dashboard', 'error');
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                    });
            }

            function openTableInstructionsModal(tableId, tableNumber, status) {
                const modal = new bootstrap.Modal(document.getElementById('tableInstructionsModal'));
                const isOccupied = status === 'occupied';
                const statusColor = isOccupied ? '#EF4444' : '#10B981';
                const statusText = isOccupied ? 'Ocupada' : 'Disponível';
                const statusBadge = isOccupied ? 'bg-danger' : 'bg-success';

                document.getElementById('instructionTableIcon').innerHTML =
                    `<i class="mdi mdi-table-furniture" style="color: ${statusColor};"></i>`;
                document.getElementById('instructionTableNumber').textContent = `Mesa ${tableNumber}`;
                document.getElementById('instructionTableStatus').className = `badge ${statusBadge}`;
                document.getElementById('instructionTableStatus').textContent = statusText;

                if (isOccupied) {
                    document.getElementById('instructionText').innerHTML = `
                <strong>Esta mesa está ocupada.</strong> Você pode visualizar o pedido atual ou fechar a conta na página de gerenciamento de mesas.
            `;
                    document.getElementById('actionButton').href = `{{ route('tables.index') }}`;
                    document.getElementById('actionButton').innerHTML =
                        '<i class="mdi mdi-table-furniture me-2"></i><span>Ir para Mesas</span>';
                } else {
                    // mensagem informativa
                    document.getElementById('instructionText').innerHTML =
                        `<strong>Esta mesa está livre.</strong> Clique abaixo para iniciar um novo pedido.`;

                    // monta a URL usando a rota blade com placeholder e substitui pelo id real
                    const createOrderUrlTemplate = "{{ route('tables.create-order', ':tableId') }}";
                    const createOrderUrl = createOrderUrlTemplate.replace(':tableId', tableId);

                    const actionButton = document.getElementById('actionButton');
                    actionButton.href = createOrderUrl;
                    actionButton.innerHTML = '<i class="mdi mdi-cart-plus me-2"></i><span>Iniciar Pedido</span>';
                }

                modal.show();
            }

            function openProductInstructionsModal(productId, productName, stockQuantity) {
                const modal = new bootstrap.Modal(document.getElementById('productInstructionsModal'));

                document.getElementById('instructionProductName').textContent = productName;
                document.getElementById('instructionProductStock').textContent = stockQuantity;

                modal.show();
            }
        </script>
    @endpush
