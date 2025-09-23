@extends('layouts.app')

@section('title', 'Dashboard')
@section('title-icon', 'mdi-view-dashboard')

@section('breadcrumbs')
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-view-dashboard-outline me-1"></i> Dashboard
    </li>
@endsection

@section('styles')
    <style>
        /* =============== DASHBOARD STYLES =============== */
        .stats-card {
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            background: var(--bs-card-bg);
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .stats-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }

        .stats-card.primary .stats-icon {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary-color);
        }

        .stats-card.secondary .stats-icon {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
        }

        .stats-card.success .stats-icon {
            background: rgba(34, 197, 94, 0.1);
            color: var(--success-color);
        }

        .stats-card.warning .stats-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .stats-card.danger .stats-icon {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .stats-card h3 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stats-card p {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 0.75rem;
        }

        .stats-card small {
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Recent Orders Table */
        .recent-orders-card {
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: none;
            overflow: hidden;
        }

        .recent-orders-card .card-header {
            background: transparent;
            border-bottom: none;
            padding: 1.5rem 1.5rem 0;
        }

        .recent-orders-card .card-body {
            padding: 0 1.5rem 1.5rem;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 100px;
            justify-content: center;
        }

        .status-badge i {
            font-size: 0.75rem;
        }

        /* Action Buttons */
        .action-btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        /* Order ID */
        .order-id {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            letter-spacing: 1px;
        }

        /* Beach Theme Accents */
        .beach-accent {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899, #3b82f6);
            background-size: 300% 100%;
            animation: wave 2s ease infinite;
        }

        @keyframes wave {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 300% 50%;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }

            .stats-card h3 {
                font-size: 1.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row g-4">
        <!-- Stats Cards -->
        <div class="col-xl-3 col-lg-6 col-md-6 animate-in" style="animation-delay: 0.1s;">
            <div class="stats-card primary position-relative">
                <div class="stats-icon">
                    <i class="mdi mdi-cash-multiple"></i>
                </div>
                <h3 class="text-primary fw-bold mb-1">MZN {{ number_format($totalSalesToday, 2, ',', '.') }}</h3>
                <p class="text-muted mb-0">Vendas Hoje</p>
                <div class="mt-2">
                    <small class="{{ $isPositive ? 'text-success' : 'text-danger' }}">
                        <i class="mdi {{ $isPositive ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
                        {{ $isPositive ? '+' : '' }}{{ number_format($salesChange, 1, ',', '.') }}% vs ontem
                    </small>
                </div>
                <div class="beach-accent"></div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 animate-in" style="animation-delay: 0.2s;">
            <div class="stats-card secondary position-relative">
                <div class="stats-icon">
                    <i class="mdi mdi-receipt"></i>
                </div>
                <h3 class="text-secondary fw-bold mb-1">{{ $completedOrdersToday }}</h3>
                <p class="text-muted mb-0">Pedidos Hoje</p>
                <div class="mt-2">
                    <small class="{{ $isOrdersPositive ? 'text-success' : 'text-danger' }}">
                        <i class="mdi {{ $isOrdersPositive ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
                        {{ $isOrdersPositive ? '+' : '' }}{{ number_format($ordersChange, 1, ',', '.') }}% vs ontem
                    </small>
                </div>
                <div class="beach-accent"></div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 animate-in" style="animation-delay: 0.3s;">
            <div class="stats-card success position-relative">
                <div class="stats-icon">
                    <i class="mdi mdi-table-chair"></i>
                </div>
                <h3 class="text-success fw-bold mb-1">{{ $occupiedTables }}/{{ $tables->count() }}</h3>
                <p class="text-muted mb-0">Mesas Ocupadas</p>
                <div class="mt-2">
                    <small class="text-info">
                        <i class="mdi mdi-information"></i> {{ $availableTables }} mesas livres
                    </small>
                </div>
                <div class="beach-accent"></div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 animate-in" style="animation-delay: 0.4s;">
            <div class="stats-card warning position-relative">
                <div class="stats-icon">
                    <i class="mdi mdi-alert-octagon"></i>
                </div>
                <h3 class="text-warning fw-bold mb-1">{{ $lowStockProducts->count() }}</h3>
                <p class="text-muted mb-0">Produtos em Alerta</p>
                <div class="mt-2">
                    <small class="text-danger">
                        <i class="mdi mdi-alert-circle"></i> Reabastecer urgente!
                    </small>
                </div>
                <div class="beach-accent"></div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row mt-4 animate-in" style="animation-delay: 0.5s;">
        <div class="col-12">
            <div class="recent-orders-card">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Pedidos Recentes</h5>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary btn-sm">
                            Ver Todos
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Mesa</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <strong
                                                class="order-id text-primary">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                        </td>
                                        <td>
                                            Mesa {{ $order->table->number ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $order->client->name ?? 'Cliente não identificado' }}
                                        </td>
                                        <td>
                                            <strong>MZN {{ number_format($order->total_amount, 2, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'completed' => 'bg-warning',
                                                    'active' => 'bg-primary',
                                                    'canceled' => 'bg-danger',
                                                    'paid' => 'bg-success',
                                                ];
                                                $statusText = [
                                                    'completed' => 'Finalizado',
                                                    'active' => 'Em Preparo',
                                                    'canceled' => 'Cancelado',
                                                    'paid' => 'Pago',
                                                ];
                                                $class = $statusClasses[$order->status] ?? 'bg-secondary';
                                                $text = $statusText[$order->status] ?? ucfirst($order->status);
                                            @endphp
                                            <span class="status-badge {{ $class }} text-white">
                                                {{ $text }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('orders.show', $order->id) }}"
                                                class="action-btn btn btn-outline-primary" data-bs-toggle="tooltip"
                                                title="Ver Detalhes">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="mdi mdi-receipt text-muted" style="font-size: 2rem;"></i>
                                            <p class="mt-2 mb-0">Nenhum pedido recente encontrado</p>
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

    <!-- Top Products -->
    <div class="row mt-4 animate-in" style="animation-delay: 0.6s;">
        <div class="col-12">
            <div class="recent-orders-card">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Produtos Mais Vendidos (Este Mês)</h5>
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-primary btn-sm">
                            Ver Relatórios
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Categoria</th>
                                    <th>Quantidade Vendida</th>
                                    <th>Receita Total</th>
                                    <th>Preço Médio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $product)
                                    <tr>
                                        <td class="fw-medium">{{ $product->name }}</td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill">
                                                {{ number_format($product->total_sold, 0, ',', '.') }} unid.
                                            </span>
                                        </td>
                                        <td>
                                            <strong>MZN {{ number_format($product->total_revenue, 2, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            MZN
                                            {{ number_format($product->total_revenue / $product->total_sold, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="mdi mdi-food-variant text-muted" style="font-size: 2rem;"></i>
                                            <p class="mt-2 mb-0">Nenhum produto vendido este mês</p>
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
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Adiciona efeito de hover nos cards
            const statCards = document.querySelectorAll('.stats-card');
            statCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endsection
