@extends('layouts.app')

@section('title', 'Dashboard')
@section('title-icon', 'mdi-view-dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Visão geral das operações')

@section('breadcrumbs')
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-view-dashboard-outline me-1"></i> Dashboard
    </li>
@endsection

@push('styles')
<style>
    /* ========== DASHBOARD ENHANCED STYLES ========== */
    
    /* Dashboard Header */
    .dashboard-welcome {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .dashboard-welcome::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--beach-gradient);
    }

    .welcome-text {
        font-size: 2.2rem;
        font-weight: 800;
        background: var(--beach-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .welcome-subtitle {
        color: #6b7280;
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 0;
    }

    .current-time {
        background: rgba(8, 145, 178, 0.1);
        padding: 0.75rem 1.25rem;
        border-radius: 50px;
        font-weight: 600;
        color: var(--primary-color);
        border: 2px solid rgba(8, 145, 178, 0.2);
        backdrop-filter: blur(10px);
        font-size: 0.95rem;
    }

    /* Enhanced Stats Cards - REDUZIDOS */
    .enhanced-stats-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: var(--border-radius-lg);
        padding: 1.5rem;  /* Reduzido de 2rem */
        box-shadow: var(--shadow-soft);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        height: 100%;
    }

    .enhanced-stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        transition: transform 0.4s ease;
        transform: scaleX(0);
        transform-origin: left;
    }

    .enhanced-stats-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
    }

    .enhanced-stats-card:hover::before {
        transform: scaleX(1);
    }

    .enhanced-stats-card.revenue::before {
        background: var(--primary-gradient);
    }

    .enhanced-stats-card.orders::before {
        background: var(--secondary-gradient);
    }

    .enhanced-stats-card.tables::before {
        background: linear-gradient(135deg, var(--success-color), #34d399);
    }

    .enhanced-stats-card.alerts::before {
        background: linear-gradient(135deg, var(--danger-color), #f87171);
    }

    .enhanced-stats-icon {
        width: 56px;  /* Reduzido de 64px */
        height: 56px;  /* Reduzido de 64px */
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;  /* Reduzido de 1.8rem */
        margin-bottom: 1.2rem;  /* Reduzido de 1.5rem */
        position: relative;
    }

    .enhanced-stats-card.revenue .enhanced-stats-icon {
        background: rgba(8, 145, 178, 0.15);
        color: var(--primary-color);
        box-shadow: 0 8px 32px rgba(8, 145, 178, 0.3);
    }

    .enhanced-stats-card.orders .enhanced-stats-icon {
        background: rgba(245, 158, 11, 0.15);
        color: var(--secondary-color);
        box-shadow: 0 8px 32px rgba(245, 158, 11, 0.3);
    }

    .enhanced-stats-card.tables .enhanced-stats-icon {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success-color);
        box-shadow: 0 8px 32px rgba(16, 185, 129, 0.3);
    }

    .enhanced-stats-card.alerts .enhanced-stats-icon {
        background: rgba(239, 68, 68, 0.15);
        color: var(--danger-color);
        box-shadow: 0 8px 32px rgba(239, 68, 68, 0.3);
    }

    .enhanced-stats-value {
        font-size: 2.1rem;  /* Reduzido de 2.5rem */
        font-weight: 800;
        margin-bottom: 0.4rem;  /* Reduzido de 0.5rem */
        line-height: 1;
    }

    .enhanced-stats-label {
        color: #6b7280;
        font-size: 0.95rem;  /* Reduzido de 1rem */
        font-weight: 600;
        margin-bottom: 0.8rem;  /* Reduzido de 1rem */
    }

    .enhanced-stats-trend {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        border-radius: 50px;
        backdrop-filter: blur(10px);
    }

    .enhanced-stats-trend.positive {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .enhanced-stats-trend.negative {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger-color);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .enhanced-stats-trend.neutral {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
        border: 1px solid rgba(107, 114, 128, 0.2);
    }

    .enhanced-stats-trend i {
        margin-right: 0.5rem;
        font-size: 1rem;
    }

    /* Quick Actions Enhanced */
    .quick-actions-enhanced {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-soft);
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .quick-actions-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--sunset-gradient);
    }

    .action-btn-enhanced {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(255, 255, 255, 0.6) 100%);
        border: 2px solid rgba(8, 145, 178, 0.1);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        text-decoration: none;
        transition: var(--transition);
        display: flex;
        align-items: center;
        backdrop-filter: blur(10px);
        color: inherit;
        height: 100%;
    }

    .action-btn-enhanced:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
        color: inherit;
        text-decoration: none;
    }

    .action-btn-enhanced.primary:hover {
        border-color: var(--primary-color);
    }

    .action-btn-enhanced.secondary:hover {
        border-color: var(--secondary-color);
    }

    .action-btn-enhanced.success:hover {
        border-color: var(--success-color);
    }

    .action-btn-enhanced.info:hover {
        border-color: var(--info-color);
    }

    .action-icon-enhanced {
        width: 52px;
        height: 52px;
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .action-btn-enhanced.primary .action-icon-enhanced {
        background: rgba(8, 145, 178, 0.15);
        color: var(--primary-color);
    }

    .action-btn-enhanced.secondary .action-icon-enhanced {
        background: rgba(245, 158, 11, 0.15);
        color: var(--secondary-color);
    }

    .action-btn-enhanced.success .action-icon-enhanced {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success-color);
    }

    .action-btn-enhanced.info .action-icon-enhanced {
        background: rgba(59, 130, 246, 0.15);
        color: var(--info-color);
    }

    .action-text-enhanced {
        flex: 1;
    }

    .action-title-enhanced {
        font-weight: 700;
        color: var(--dark-color);
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
    }

    .action-subtitle-enhanced {
        font-size: 0.85rem;
        color: #6b7280;
        margin: 0;
        line-height: 1.3;
    }

    /* Recent Activity Enhanced */
    .activity-card-enhanced {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-soft);
        overflow: hidden;
        position: relative;
    }

    .activity-card-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--beach-gradient);
    }

    .activity-header-enhanced {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .activity-list-enhanced {
        max-height: 400px;
        overflow-y: auto;
    }

    .activity-item-enhanced {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        transition: var(--transition);
    }

    .activity-item-enhanced:hover {
        background: rgba(8, 145, 178, 0.03);
    }

    .activity-item-enhanced:last-child {
        border-bottom: none;
    }

    .activity-icon-enhanced {
        width: 48px;
        height: 48px;
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .activity-item-enhanced.order .activity-icon-enhanced {
        background: rgba(245, 158, 11, 0.15);
        color: var(--secondary-color);
    }

    .activity-item-enhanced.sale .activity-icon-enhanced {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success-color);
    }

    .activity-item-enhanced.kitchen .activity-icon-enhanced {
        background: rgba(59, 130, 246, 0.15);
        color: var(--info-color);
    }

    .activity-content-enhanced {
        flex: 1;
    }

    .activity-title-enhanced {
        font-weight: 600;
        color: var(--dark-color);
        margin: 0 0 0.25rem 0;
        font-size: 0.95rem;
    }

    .activity-subtitle-enhanced {
        font-size: 0.85rem;
        color: #6b7280;
        margin: 0;
        line-height: 1.3;
    }

    .activity-meta-enhanced {
        text-align: right;
        flex-shrink: 0;
    }

    .activity-value-enhanced {
        font-weight: 700;
        color: var(--success-color);
        font-size: 0.95rem;
    }

    .activity-time-enhanced {
        font-size: 0.8rem;
        color: #9ca3af;
        font-weight: 500;
        margin-top: 0.25rem;
    }

    /* Enhanced Status Badges */
    .status-badge-enhanced {
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        backdrop-filter: blur(10px);
    }

    .status-badge-enhanced.success {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success-color);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-badge-enhanced.warning {
        background: rgba(245, 158, 11, 0.15);
        color: var(--warning-color);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-badge-enhanced.danger {
        background: rgba(239, 68, 68, 0.15);
        color: var(--danger-color);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .status-badge-enhanced.primary {
        background: rgba(8, 145, 178, 0.15);
        color: var(--primary-color);
        border: 1px solid rgba(8, 145, 178, 0.3);
    }

    .status-badge-enhanced.info {
        background: rgba(59, 130, 246, 0.15);
        color: var(--info-color);
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dashboard-welcome {
            padding: 1.5rem;
            text-align: center;
        }

        .welcome-text {
            font-size: 1.8rem;
        }

        .enhanced-stats-value {
            font-size: 1.8rem;
        }

        .quick-actions-enhanced {
            padding: 1.5rem;
        }

        .action-btn-enhanced {
            padding: 1rem;
        }

        .activity-header-enhanced {
            padding: 1rem 1.5rem;
        }

        .activity-item-enhanced {
            padding: 1rem 1.5rem;
        }
    }

    /* Loading animations */
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .fade-in-delay-1 { animation-delay: 0.1s; }
    .fade-in-delay-2 { animation-delay: 0.2s; }
    .fade-in-delay-3 { animation-delay: 0.3s; }
    .fade-in-delay-4 { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
    <!-- Dashboard Welcome -->
    <div class="dashboard-welcome fade-in-up">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="welcome-text">Bem-vindo de volta, {{ auth()->user()->name }}!</h1>
                <p class="welcome-subtitle">Aqui está o resumo das suas operações de hoje</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="current-time">
                    <i class="mdi mdi-clock-outline me-2"></i>
                    <span id="current-time">{{ date('H:i - d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Cards - TAMANHO REDUZIDO -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="enhanced-stats-card revenue fade-in-up fade-in-delay-1">
                <div class="enhanced-stats-icon">
                    <i class="mdi mdi-cash-multiple"></i>
                </div>
                <div class="enhanced-stats-value text-primary">
                    MZN {{ number_format($totalSalesToday, 2, ',', '.') }}
                </div>
                <div class="enhanced-stats-label">Vendas Hoje</div>
                <div class="enhanced-stats-trend {{ $isPositive ? 'positive' : 'negative' }}">
                    <i class="mdi {{ $isPositive ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
                    {{ $isPositive ? '+' : '' }}{{ number_format($salesChange, 1, ',', '.') }}% vs ontem
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="enhanced-stats-card orders fade-in-up fade-in-delay-2">
                <div class="enhanced-stats-icon">
                    <i class="mdi mdi-receipt-text"></i>
                </div>
                <div class="enhanced-stats-value text-warning">
                    {{ $completedOrdersToday }}
                </div>
                <div class="enhanced-stats-label">Pedidos Hoje</div>
                <div class="enhanced-stats-trend {{ $isOrdersPositive ? 'positive' : 'negative' }}">
                    <i class="mdi {{ $isOrdersPositive ? 'mdi-trending-up' : 'mdi-trending-down' }}"></i>
                    {{ $isOrdersPositive ? '+' : '' }}{{ number_format($ordersChange, 1, ',', '.') }}% vs ontem
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="enhanced-stats-card tables fade-in-up fade-in-delay-3">
                <div class="enhanced-stats-icon">
                    <i class="mdi mdi-table-chair"></i>
                </div>
                <div class="enhanced-stats-value text-success">
                    {{ $occupiedTables }}/{{ $tables->count() }}
                </div>
                <div class="enhanced-stats-label">Mesas Ocupadas</div>
                <div class="enhanced-stats-trend neutral">
                    <i class="mdi mdi-information-outline"></i>
                    {{ $availableTables }} mesas livres
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="enhanced-stats-card alerts fade-in-up fade-in-delay-4">
                <div class="enhanced-stats-icon">
                    <i class="mdi mdi-alert-circle"></i>
                </div>
                <div class="enhanced-stats-value text-danger">
                    {{ $lowStockProducts->count() }}
                </div>
                <div class="enhanced-stats-label">Produtos em Alerta</div>
                <div class="enhanced-stats-trend negative">
                    <i class="mdi mdi-alert"></i>
                    Reabastecer urgente!
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Enhanced -->
    <div class="quick-actions-enhanced mb-4 fade-in-up fade-in-delay-2">
        <h3 class="mb-3">
            <i class="mdi mdi-lightning-bolt text-warning me-2"></i>
            Ações Rápidas
        </h3>
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('pos.index') }}" class="action-btn-enhanced primary">
                    <div class="action-icon-enhanced">
                        <i class="mdi mdi-point-of-sale"></i>
                    </div>
                    <div class="action-text-enhanced">
                        <h6 class="action-title-enhanced">Abrir PDV</h6>
                        <p class="action-subtitle-enhanced">Realizar nova venda direta</p>
                    </div>
                </a>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('tables.index') }}" class="action-btn-enhanced secondary">
                    <div class="action-icon-enhanced">
                        <i class="mdi mdi-plus-circle"></i>
                    </div>
                    <div class="action-text-enhanced">
                        <h6 class="action-title-enhanced">Novo Pedido</h6>
                        <p class="action-subtitle-enhanced">Criar pedido para mesa</p>
                    </div>
                </a>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('products.create') }}" class="action-btn-enhanced success">
                    <div class="action-icon-enhanced">
                        <i class="mdi mdi-food-variant"></i>
                    </div>
                    <div class="action-text-enhanced">
                        <h6 class="action-title-enhanced">Adicionar Produto</h6>
                        <p class="action-subtitle-enhanced">Cadastrar novo item</p>
                    </div>
                </a>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('reports.index') }}" class="action-btn-enhanced info">
                    <div class="action-icon-enhanced">
                        <i class="mdi mdi-chart-line"></i>
                    </div>
                    <div class="action-text-enhanced">
                        <h6 class="action-title-enhanced">Ver Relatórios</h6>
                        <p class="action-subtitle-enhanced">Análises detalhadas</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row">
        <!-- Recent Orders Table Enhanced -->
        <div class="col-lg-8 mb-4">
            <div class="activity-card-enhanced fade-in-up fade-in-delay-3">
                <div class="activity-header-enhanced">
                    <h5 class="mb-0">
                        <i class="mdi mdi-receipt text-primary me-2"></i>
                        Pedidos Recentes
                    </h5>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="mdi mdi-arrow-right"></i>
                        Ver Todos
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="border-0">Pedido</th>
                                <th class="border-0">Mesa</th>
                                <th class="border-0">Cliente</th>
                                <th class="border-0">Total</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <strong class="text-primary order-id">
                                            #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            Mesa {{ $order->table->number ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $order->client->name ?? 'Cliente não identificado' }}
                                    </td>
                                    <td>
                                        <strong>MZN {{ number_format($order->total_amount, 2, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusConfig = [
                                                'completed' => ['class' => 'warning', 'text' => 'Finalizado', 'icon' => 'mdi-check'],
                                                'active' => ['class' => 'primary', 'text' => 'Em Preparo', 'icon' => 'mdi-clock'],
                                                'pending' => ['class' => 'info', 'text' => 'Pendente', 'icon' => 'mdi-clock-outline'],
                                                'canceled' => ['class' => 'danger', 'text' => 'Cancelado', 'icon' => 'mdi-close'],
                                                'paid' => ['class' => 'success', 'text' => 'Pago', 'icon' => 'mdi-check-all'],
                                            ];
                                            $config = $statusConfig[$order->status] ?? ['class' => 'secondary', 'text' => ucfirst($order->status), 'icon' => 'mdi-help'];
                                        @endphp
                                        <span class="status-badge-enhanced {{ $config['class'] }}">
                                            <i class="{{ $config['icon'] }}"></i>
                                            {{ $config['text'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" 
                                           class="btn btn-outline-primary btn-sm"
                                           data-bs-toggle="tooltip"
                                           title="Ver Detalhes">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                         <a href="{{ route('orders.edit', $order->id) }}" 
                                           class="btn btn-outline-warning btn-sm"
                                           data-bs-toggle="tooltip"
                                           title="Editar Pedido">
                                            <i class="mdi mdi-pen"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="mdi mdi-receipt text-muted" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0 text-muted">Nenhum pedido recente encontrado</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-lg-4">
            <div class="activity-card-enhanced fade-in-up fade-in-delay-4">
                <div class="activity-header-enhanced">
                    <h5 class="mb-0">
                        <i class="mdi mdi-star text-warning me-2"></i>
                        Top Produtos
                    </h5>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="mdi mdi-chart-bar"></i>
                        Relatórios
                    </a>
                </div>
                <div class="activity-list-enhanced">
                    @forelse($topProducts as $index => $product)
                        <div class="activity-item-enhanced">
                            <div class="activity-icon-enhanced" style="
                                background: {{ $index == 0 ? 'rgba(245, 158, 11, 0.15)' : ($index == 1 ? 'rgba(107, 114, 128, 0.15)' : 'rgba(194, 65, 12, 0.15)') }};
                                color: {{ $index == 0 ? '#f59e0b' : ($index == 1 ? '#6b7280' : '#c2410c') }};
                            ">
                                <i class="mdi {{ $index == 0 ? 'mdi-crown' : ($index == 1 ? 'mdi-medal' : 'mdi-numeric-' . ($index + 1)) }}"></i>
                            </div>
                            <div class="activity-content-enhanced">
                                <h6 class="activity-title-enhanced">{{ $product->name }}</h6>
                                <p class="activity-subtitle-enhanced">
                                    {{ number_format($product->total_sold) }} vendidos
                                </p>
                            </div>
                            <div class="activity-meta-enhanced">
                                <div class="activity-value-enhanced">
                                    MZN {{ number_format($product->total_revenue, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="mdi mdi-food-variant text-muted" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0 text-muted">Nenhum produto vendido este mês</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    @if($lowStockProducts->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="activity-card-enhanced fade-in-up fade-in-delay-4">
                    <div class="activity-header-enhanced">
                        <h5 class="mb-0">
                            <i class="mdi mdi-alert-circle text-danger me-2"></i>
                            Produtos com Estoque Baixo
                        </h5>
                        <a href="{{ route('products.index') }}?filter=low_stock" class="btn btn-outline-danger btn-sm">
                            <i class="mdi mdi-package-variant"></i>
                            Gerenciar Estoque
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="border-0">Produto</th>
                                    <th class="border-0">Categoria</th>
                                    <th class="border-0">Estoque Atual</th>
                                    <th class="border-0">Estoque Mínimo</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts->take(5) as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="activity-icon-enhanced me-2" style="width: 32px; height: 32px; background: rgba(239, 68, 68, 0.1); color: var(--danger-color);">
                                                    <i class="mdi mdi-package-variant-closed" style="font-size: 1rem;"></i>
                                                </div>
                                                <strong>{{ $product->name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $product->category->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $product->stock_quantity == 0 ? 'bg-danger' : 'bg-warning' }} text-white">
                                                {{ $product->stock_quantity }} {{ $product->unit ?? 'un' }}
                                            </span>
                                        </td>
                                        <td>{{ $product->min_stock_level }} {{ $product->unit ?? 'un' }}</td>
                                        <td>
                                            @if($product->stock_quantity == 0)
                                                <span class="status-badge-enhanced danger">
                                                    <i class="mdi mdi-close-circle"></i>
                                                    Esgotado
                                                </span>
                                            @else
                                                <span class="status-badge-enhanced warning">
                                                    <i class="mdi mdi-alert"></i>
                                                    Baixo
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('products.edit', $product->id) }}" 
                                               class="btn btn-outline-primary btn-sm"
                                               data-bs-toggle="tooltip"
                                               title="Editar Produto">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($lowStockProducts->count() > 5)
                        <div class="text-center p-3 border-top">
                            <a href="{{ route('products.index') }}?filter=low_stock" class="btn btn-link">
                                Ver todos os {{ $lowStockProducts->count() }} produtos com estoque baixo
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Atualizar relógio em tempo real
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleString('pt-PT', {
                hour: '2-digit',
                minute: '2-digit',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }

        // Inicializar relógio
        updateTime();
        setInterval(updateTime, 60000); // Atualizar a cada minuto

        // Inicializar tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Adicionar efeitos de hover suaves nos cards
        const enhancedCards = document.querySelectorAll('.enhanced-stats-card, .action-btn-enhanced');
        enhancedCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                if (!this.style.transform.includes('translateY')) {
                    this.style.transform = 'translateY(-5px)';
                }
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Sistema de notificações toast
        function showDashboardNotification(message, type = 'info') {
            const toastContainer = document.getElementById('toast-container') || createToastContainer();
            
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-bg-${type} border-0`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="mdi mdi-information me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
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

        // Expor função globalmente
        window.showDashboardNotification = showDashboardNotification;
    });
</script>
@endpush