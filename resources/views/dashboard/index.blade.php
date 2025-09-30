@extends('layouts.app')

@section('title', 'Dashboard')
@section('title-icon', 'mdi-view-dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Visão Geral do Sistema')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
    <style>
        /* Dashboard Welcome - Simplificado */
        .dashboard-welcome {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 8px 24px rgba(8, 145, 178, 0.3);
        }

        .welcome-text {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            opacity: 0.9;
            font-size: 1rem;
        }

        .current-time {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        /* Metric Cards - Simplificados */
        .metric-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid;
            height: 100%;
        }

        .metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        }

        .metric-card.sales {
            border-color: #0891b2;
        }

        .metric-card.orders {
            border-color: #f59e0b;
        }

        .metric-card.products {
            border-color: #10b981;
        }

        .metric-card.tables {
            border-color: #8b5cf6;
        }

        .metric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .metric-icon.sales {
            background: #0891b2;
        }

        .metric-icon.orders {
            background: #f59e0b;
        }

        .metric-icon.products {
            background: #10b981;
        }

        .metric-icon.tables {
            background: #8b5cf6;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0.5rem 0;
        }

        .metric-label {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .metric-footer {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Quick Actions - Grid Melhorado */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .action-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 2px solid #f3f4f6;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .action-card:hover {
            border-color: #0891b2;
            box-shadow: 0 4px 12px rgba(8, 145, 178, 0.15);
            transform: translateY(-2px);
            text-decoration: none;
            color: inherit;
        }

        .action-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
        }

        .action-card.primary .action-icon {
            background: #e0f2fe;
            color: #0891b2;
        }

        .action-card.warning .action-icon {
            background: #fef3c7;
            color: #f59e0b;
        }

        .action-card.success .action-icon {
            background: #d1fae5;
            color: #10b981;
        }

        .action-card.info .action-icon {
            background: #dbeafe;
            color: #3b82f6;
        }

        .action-title {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .action-subtitle {
            font-size: 0.85rem;
            color: #6b7280;
            margin: 0;
        }

        /* Charts - Container melhorado */
        .chart-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .chart-header {
            padding: 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            background: #fafafa;
        }

        .chart-body {
            padding: 1.5rem;
        }

        .chart-container {
            position: relative;
            height: 280px;
        }

        /* Tables Section */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Table Status Grid */
        .table-status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 1rem;
            max-height: 400px;
            overflow-y: auto;
        }

        .table-card {
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid;
        }

        .table-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table-card.available {
            background: #ecfdf5;
            border-color: #10b981;
        }

        .table-card.occupied {
            background: #fef3c7;
            border-color: #f59e0b;
        }

        .table-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        /* Top Products */
        .product-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.2s;
        }

        .product-item:hover {
            background: #f9fafb;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-rank {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            margin-right: 1rem;
        }

        .rank-1 {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
        }

        .rank-2 {
            background: linear-gradient(135deg, #94a3b8, #64748b);
            color: white;
        }

        .rank-3 {
            background: linear-gradient(135deg, #fb923c, #f97316);
            color: white;
        }

        .rank-other {
            background: #f3f4f6;
            color: #6b7280;
        }

        /* Alerts */
        .alert-card {
            background: #fef3c7;
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .alert-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .alert-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fbbf24;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        /* Trend Indicator */
        .trend {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .trend.up {
            background: #d1fae5;
            color: #065f46;
        }

        .trend.down {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-welcome {
                padding: 1.5rem;
            }

            .welcome-text {
                font-size: 1.5rem;
            }

            .metric-value {
                font-size: 1.5rem;
            }

            .quick-actions-grid {
                grid-template-columns: 1fr;
            }

            .chart-container {
                height: 220px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Welcome Header -->
    <div class="dashboard-welcome">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="welcome-text">Olá, {{ auth()->user()->name }}!</h1>
                <p class="welcome-subtitle mb-0">Resumo das operações de hoje</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="current-time">
                    <i class="mdi mdi-clock-outline me-2"></i>
                    <span id="current-time">{{ date('H:i - d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs Principais -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="metric-card sales">
                <div class="metric-header">
                    <div class="metric-icon sales">
                        <i class="mdi mdi-cash-multiple"></i>
                    </div>
                </div>
                <div class="metric-label">Vendas Hoje</div>
                <div class="metric-value">{{ number_format($totalSalesToday, 0) }}</div>
                <div class="metric-footer">
                    <small class="text-muted">Ontem: {{ number_format($yesterdaySales, 0) }}</small>
                    <span class="trend {{ $isPositive ? 'up' : 'down' }}">
                        <i class="mdi mdi-{{ $isPositive ? 'trending-up' : 'trending-down' }}"></i>
                        {{ number_format(abs($salesChange), 1) }}%
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="metric-card orders">
                <div class="metric-header">
                    <div class="metric-icon orders">
                        <i class="mdi mdi-calendar-month"></i>
                    </div>
                </div>
                <div class="metric-label">Vendas do Mês</div>
                <div class="metric-value">{{ number_format($totalSalesThisMonth, 0) }}</div>
                <div class="metric-footer">
                    <small class="text-muted">{{ \Carbon\Carbon::now()->format('F Y') }}</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="metric-card products">
                <div class="metric-header">
                    <div class="metric-icon products">
                        <i class="mdi mdi-receipt"></i>
                    </div>
                </div>
                <div class="metric-label">Pedidos Hoje</div>
                <div class="metric-value">{{ $openOrders }}</div>
                <div class="metric-footer">
                    <small class="text-muted">{{ $completedOrdersToday }} finalizados</small>
                    @if ($ordersChange != 0)
                        <span class="trend {{ $isOrdersPositive ? 'up' : 'down' }}">
                            <i class="mdi mdi-{{ $isOrdersPositive ? 'trending-up' : 'trending-down' }}"></i>
                            {{ number_format(abs($ordersChange), 1) }}%
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="metric-card expenses">
                <div class="metric-header">
                    <div class="metric-icon expenses">
                        <i class="mdi mdi-cash-minus"></i>
                    </div>
                </div>
                <div class="metric-label">Despesas Hoje</div>
                <div class="metric-value">{{ number_format($totalExpensesToday ?? 0, 0) }}</div>
                <div class="metric-footer">
                    <small class="text-muted">Mês: {{ number_format($totalExpensesMonth ?? 0, 0) }} MZN</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-grid">
        <a href="{{ route('pos.index') }}" class="action-card primary">
            <div class="action-icon">
                <i class="mdi mdi-point-of-sale"></i>
            </div>
            <div>
                <div class="action-title">Abrir PDV</div>
                <p class="action-subtitle">Nova venda rápida</p>
            </div>
        </a>

        <a href="{{ route('tables.index') }}" class="action-card warning">
            <div class="action-icon">
                <i class="mdi mdi-plus-circle"></i>
            </div>
            <div>
                <div class="action-title">Novo Pedido</div>
                <p class="action-subtitle">Criar pedido mesa</p>
            </div>
        </a>

        <a href="{{ route('products.create') }}" class="action-card success">
            <div class="action-icon">
                <i class="mdi mdi-food-variant"></i>
            </div>
            <div>
                <div class="action-title">Add Produto</div>
                <p class="action-subtitle">Cadastrar item</p>
            </div>
        </a>

        <a href="{{ route('reports.index') }}" class="action-card info">
            <div class="action-icon">
                <i class="mdi mdi-chart-line"></i>
            </div>
            <div>
                <div class="action-title">Relatórios</div>
                <p class="action-subtitle">Ver análises</p>
            </div>
        </a>
    </div>

    <!-- Alerta Estoque Baixo -->
    @if ($lowStockProducts->count() > 0)
        <div class="alert-card mb-4">
            <div class="alert-header">
                <div class="alert-icon">
                    <i class="mdi mdi-alert-circle"></i>
                </div>
                <h5 class="mb-0">{{ $lowStockProducts->count() }} produto(s) com estoque baixo</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Estoque</th>
                            <th>Mínimo</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowStockProducts->take(5) as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td><span class="badge bg-danger">{{ $product->stock_quantity }}</span></td>
                                <td>{{ $product->min_stock_level }}</td>
                                <td>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                        Ajustar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Charts -->
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="mb-0">
                        <i class="mdi mdi-chart-line text-primary"></i>
                        Vendas por Hora - Hoje
                    </h6>
                </div>
                <div class="chart-body">
                    <div class="chart-container">
                        <canvas id="hourlySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="mb-0">
                        <i class="mdi mdi-chart-bar text-info"></i>
                        Últimos 7 Dias
                    </h6>
                </div>
                <div class="chart-body">
                    <div class="chart-container">
                        <canvas id="dailySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="row g-3">
        <!-- Top 5 Produtos -->
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="mb-0">
                        <i class="mdi mdi-trophy text-warning"></i>
                        Top 5 Produtos
                    </h6>
                </div>
                <div class="chart-body p-0">
                    @forelse($topProducts as $index => $product)
                        <div class="product-item">
                            <div class="product-rank rank-{{ $index < 3 ? $index + 1 : 'other' }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $product->name }}</div>
                                <small class="text-muted">{{ $product->total_sold }} vendidos</small>
                            </div>
                            <div class="text-end">
                                <strong>{{ number_format($product->total_revenue, 0) }}</strong>
                                <small class="d-block text-muted">MZN</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="mdi mdi-information-outline display-4 text-muted"></i>
                            <p class="text-muted mt-2">Sem dados</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <!-- Status das Mesas -->
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="mdi mdi-table-chair text-success"></i>
                            Mesas
                        </h6>
                        <div>
                            <span class="badge bg-success">{{ $availableTables }}</span>
                            <span class="badge bg-warning">{{ $occupiedTables }}</span>
                        </div>
                    </div>
                </div>
                <div class="chart-body">
                    <div class="table-status-grid">
                        @foreach ($tables as $table)
                            @php
                                $activeOrder = $table->orders->whereIn('status', ['active', 'completed'])->first();
                                $hasActiveOrder = !is_null($activeOrder);
                            @endphp

                            <div class="table-card {{ $hasActiveOrder ? 'occupied' : 'available' }}">
                                <div class="d-flex flex-column align-items-center">
                                    <i
                                        class="mdi mdi-table-chair {{ $hasActiveOrder ? 'text-warning' : 'text-success' }} fs-3"></i>
                                    <div class="table-number">Mesa {{ $table->number }}</div>
                                    <small class="text-muted">{{ $table->capacity }} lug</small>

                                    @if ($hasActiveOrder)
                                        <span class="badge bg-primary mt-1">
                                            Pedido #{{ $activeOrder->id }}
                                        </span>

                                        <!-- Botão para ver pedido -->
                                        <a href="{{ route('orders.edit', $activeOrder->id) }}"
                                            class="btn btn-sm btn-outline-primary mt-2">
                                            Ver Pedido
                                        </a>
                                    @else
                                        <span class="badge bg-success mt-1">Disponível</span>

                                        <!-- Botão para abrir mesa (criar pedido) -->
                                        <a href="{{ route('tables.index') }}"
                                            class="btn btn-sm btn-outline-success mt-2">
                                            Abrir Mesa
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 mt-2">
        <!-- Pedidos Recentes -->
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="mdi mdi-receipt text-info"></i>
                            Pedidos Hoje
                        </h6>
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-link">
                            Ver todos
                        </a>
                    </div>
                </div>
                <div class="chart-body p-0" style="max-height: 400px; overflow-y: auto;">
                    @forelse($recentOrders as $order)
                        <div class="product-item">
                            <div>
                                <strong>#{{ $order->id }}</strong>
                                <small class="d-block text-muted">Mesa {{ $order->table->number ?? '-' }}</small>
                            </div>
                            <div class="ms-auto text-end">
                                <strong>{{ number_format($order->total_amount, 0) }}</strong>
                                <small class="d-block">
                                    @php
                                        $badges = [
                                            'pending' => 'secondary',
                                            'active' => 'info',
                                            'completed' => 'success',
                                            'paid' => 'success',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $badges[$order->status] ?? 'secondary' }}">
                                        {{ $order->status }}
                                    </span>
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="mdi mdi-information-outline display-4 text-muted"></i>
                            <p class="text-muted mt-2">Sem pedidos</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Atualizar relógio
        function updateTime() {
            const now = new Date();
            document.getElementById('current-time').textContent =
                now.toLocaleString('pt-PT', {
                    hour: '2-digit',
                    minute: '2-digit',
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
        }
        updateTime();
        setInterval(updateTime, 60000);

        // Gráficos
        const chartConfig = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (val) => val + ' MZN'
                    }
                }
            }
        };

        // Vendas por Hora
        new Chart(document.getElementById('hourlySalesChart'), {
            type: 'bar',
            data: {
                labels: @json($hourlySalesData->pluck('hour')),
                datasets: [{
                    data: @json($hourlySalesData->pluck('value')),
                    backgroundColor: '#0891b2',
                    borderRadius: 6
                }]
            },
            options: chartConfig
        });

        // Vendas 7 Dias
        new Chart(document.getElementById('dailySalesChart'), {
            type: 'line',
            data: {
                labels: @json($dailySalesData->pluck('date')),
                datasets: [{
                    data: @json($dailySalesData->pluck('value')),
                    borderColor: '#0891b2',
                    backgroundColor: 'rgba(8, 145, 178, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: chartConfig
        });
    </script>
@endpush
