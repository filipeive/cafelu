@extends('layouts.app')

@section('title', 'Pedidos')
@section('page-title', 'Gestão de Pedidos')
@section('title-icon', 'mdi-receipt')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Pedidos</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card primary">
                    <div class="stats-icon">
                        <i class="mdi mdi-receipt"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $total_orders }}</h3>
                        <p class="stats-label">Total de Pedidos</p>
                        <div class="stats-trend">
                            <i class="mdi mdi-trending-up text-success"></i>
                            <span class="text-success small">+12% hoje</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card secondary">
                    <div class="stats-icon">
                        <i class="mdi mdi-clock-outline"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $totalOpen }}</h3>
                        <p class="stats-label">Pedidos Abertos</p>
                        <div class="stats-trend">
                            <i class="mdi mdi-alert-circle text-warning"></i>
                            <span class="text-warning small">Atenção necessária</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card success">
                    <div class="stats-icon">
                        <i class="mdi mdi-cash-multiple"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ number_format($totalToday, 0, ',', '.') }} MT</h3>
                        <p class="stats-label">Vendas Hoje</p>
                        <div class="stats-trend">
                            <i class="mdi mdi-trending-up text-success"></i>
                            <span class="text-success small">Bom desempenho</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card primary">
                    <div class="stats-icon">
                        <i class="mdi mdi-chart-line"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ number_format($totalRevenueToday, 0, ',', '.') }} MT</h3>
                        <p class="stats-label">Receita Confirmada</p>
                        <div class="stats-trend">
                            <i class="mdi mdi-check-circle text-success"></i>
                            <span class="text-success small">Pagamentos confirmados</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Management Card -->
        <div class="card" style="padding: 20px !important">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="mdi mdi-clipboard-list text-primary me-2"></i>
                            Lista de Pedidos
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-md-end gap-2">
                            <!-- Search Form -->
                            <form method="GET" action="{{ route('orders.index') }}" class="d-flex">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="mdi mdi-magnify text-muted"></i>
                                    </span>
                                    <input type="text" name="search" value="{{ $search ?? '' }}"
                                        class="form-control border-start-0" placeholder="Buscar por cliente, mesa ou ID..."
                                        style="max-width: 300px;">
                                    <button type="submit" class="btn btn-outline-primary">
                                        Buscar
                                    </button>
                                </div>
                            </form>

                            <!-- Quick Actions -->
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="mdi mdi-lightning-bolt me-1"></i>
                                    Ações
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('pos.index') }}">
                                            <i class="mdi mdi-point-of-sale text-success me-2"></i>
                                            Novo Pedido (PDV)
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('orders.kitchen') }}">
                                            <i class="mdi mdi-chef-hat text-warning me-2"></i>
                                            Visão Cozinha
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('tables.index') }}">
                                            <i class="mdi mdi-table-furniture text-info me-2"></i>
                                            Gerenciar Mesas
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                @if ($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 custom-orders-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 100px;">#Pedido</th>
                                    <th>Cliente</th>
                                    <th style="width: 100px;">Mesa</th>
                                    <th style="width: 120px;">Data/Hora</th>
                                    <th style="width: 100px;" class="text-end">Total</th>
                                    <th style="width: 120px;" class="text-center">Status</th>
                                    <th style="width: 120px;" class="text-center">Pagamento</th>
                                    <th style="width: 150px;" class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr class="order-row" data-order-id="{{ $order->id }}">
                                        <!-- Order ID -->
                                        <td>
                                            <div class="fw-bold text-primary order-id-badge">
                                                #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                            </div>
                                        </td>

                                        <!-- Customer -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="customer-avatar me-2">
                                                    <i class="mdi mdi-account-circle text-muted fs-4"></i>
                                                </div>
                                                <div>
                                                    <div class="customer-name fw-semibold">
                                                        {{ $order->customer_name ?: 'Cliente não identificado' }}
                                                    </div>
                                                    @if ($order->notes)
                                                        <small class="text-muted notes-badge">
                                                            <i class="mdi mdi-note-text me-1"></i>
                                                            {{ Str::limit($order->notes, 30) }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Table -->
                                        <td>
                                            @if ($order->table)
                                                <div class="table-info">
                                                    <span class="badge bg-info table-badge">
                                                        <i class="mdi mdi-table-furniture me-1"></i>
                                                        {{ $order->table->number }}
                                                    </span>
                                                    @if ($order->table->group_id)
                                                        <small class="d-block text-muted mt-1 group-link">
                                                            <i class="mdi mdi-link-variant"></i> Unida
                                                        </small>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <!-- Date/Time -->
                                        <td>
                                            <div class="order-time">
                                                <div class="fw-semibold text-dark">
                                                    {{ $order->created_at->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>

                                        <!-- Total -->
                                        <td class="text-end">
                                            <div class="order-total">
                                                <span class="fw-bold text-success total-amount">
                                                    {{ number_format($order->total_amount, 2, ',', '.') }} MT
                                                </span>
                                                <div class="small text-muted items-count">
                                                    {{ $order->items->count() }}
                                                    {{ $order->items->count() == 1 ? 'item' : 'itens' }}
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Status -->
                                        <td class="text-center">
                                            <span
                                                class="badge status-badge {{ get_status_class_staradmins($order->status) }}">
                                                {{ ucfirst(trans($order->status)) }}
                                            </span>
                                        </td>

                                        <!-- Payment -->
                                        <td class="text-center">
                                            @if ($order->status === 'paid')
                                                <div class="payment-info">
                                                    <span class="badge bg-success payment-badge">
                                                        <i class="mdi mdi-check-circle me-1"></i>
                                                        Pago
                                                    </span>
                                                    @if ($order->payment_method)
                                                        <div class="small text-muted mt-1 payment-method">
                                                            {{ ucfirst($order->payment_method) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @elseif($order->status === 'completed')
                                                <span class="badge bg-warning payment-pending">
                                                    <i class="mdi mdi-clock-outline me-1"></i>
                                                    Pendente
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td class="text-center">
                                            <div class="btn-group actions-group" role="group">
                                                <!-- View Order -->
                                                <a href="{{ route('orders.show', $order) }}"
                                                    class="btn btn-sm btn-outline-primary action-btn view-btn"
                                                    data-bs-toggle="tooltip" title="Ver Detalhes">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>

                                                <!-- Edit Order -->
                                                @if ($order->status !== 'paid' && $order->status !== 'canceled')
                                                    <a href="{{ route('orders.edit', $order) }}"
                                                        class="btn btn-sm btn-outline-secondary action-btn edit-btn"
                                                        data-bs-toggle="tooltip" title="Editar Pedido">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                @endif

                                                <!-- Complete Order -->
                                                @if ($order->status === 'active')
                                                    <button onclick="completeOrder({{ $order->id }})"
                                                        class="btn btn-sm btn-outline-warning action-btn complete-btn"
                                                        data-bs-toggle="tooltip" title="Finalizar Pedido">
                                                        <i class="mdi mdi-check-circle"></i>
                                                    </button>
                                                @endif

                                                <!-- Pay Order -->
                                                @if ($order->status === 'completed')
                                                    <button onclick="openPaymentModal({{ $order->id }})"
                                                        class="btn btn-sm btn-outline-success action-btn pay-btn"
                                                        data-bs-toggle="tooltip" title="Registrar Pagamento">
                                                        <i class="mdi mdi-cash-multiple"></i>
                                                    </button>
                                                @endif
                                                @if ($order->status === 'paid')
                                                <!-- Print Receipt -->
                                                <button onclick="printRecibo({{ $order->id }})"
                                                    class="btn btn-sm btn-outline-info action-btn print-btn"
                                                    data-bs-toggle="tooltip" title="Imprimir Recibo">
                                                    <i class="mdi mdi-printer"></i>
                                                </button>
                                                @endif
                                                @if ($order->status === 'completed')
                                                <!-- Print Receipt -->
                                                <button onclick="printRecibo({{ $order->id }})"
                                                    class="btn btn-sm btn-outline-info action-btn print-btn"
                                                    data-bs-toggle="tooltip" title="Imprimir Conta">
                                                    <i class="mdi mdi-printer"></i>
                                                </button>
                                                @endif
                                                <!-- More Actions Dropdown -->
                                                <div class="btn-group" role="group">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split more-actions-btn"
                                                        data-bs-toggle="dropdown">
                                                        <span class="visually-hidden">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end custom-dropdown" style="z-index: 1040;">
                                                        @if ($order->status !== 'canceled' && $order->status !== 'paid')
                                                            <li>
                                                                <a class="dropdown-item text-danger cancel-item"
                                                                    href="#"
                                                                    onclick="cancelOrder({{ $order->id }})">
                                                                    <i class="mdi mdi-cancel me-2"></i>
                                                                    Cancelar Pedido
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <a class="dropdown-item history-item"
                                                                href="{{ route('orders.show', $order) }}">
                                                                <i class="mdi mdi-information-outline me-2"></i>
                                                                Ver Histórico
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer bg-white border-top pagination-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted info-text">
                                Mostrando {{ $orders->firstItem() }} a {{ $orders->lastItem() }}
                                de {{ $orders->total() }} pedidos
                            </div>
                            {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5 empty-state-container">
                        <div class="empty-state">
                            <i class="mdi mdi-receipt-outline display-1 text-muted mb-3 empty-icon"></i>
                            <h4 class="text-muted empty-title">Nenhum pedido encontrado</h4>
                            @if ($search)
                                <p class="text-muted mb-3 empty-description">
                                    Não foram encontrados pedidos para a busca:
                                    <strong>"{{ $search }}"</strong>
                                </p>
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary empty-action-btn">
                                    <i class="mdi mdi-refresh me-1"></i>
                                    Limpar Filtros
                                </a>
                            @else
                                <p class="text-muted mb-3 empty-description">
                                    Ainda não há pedidos no sistema. Que tal criar o primeiro?
                                </p>
                                <a href="{{ route('pos.index') }}" class="btn btn-primary empty-action-btn">
                                    <i class="mdi mdi-plus-circle me-1"></i>
                                    Novo Pedido
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Payment Modal -->
    <div class="modal fade" id="quickPaymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="quickPaymentForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="mdi mdi-cash-multiple text-success me-2"></i>
                            Registrar Pagamento
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Método de Pagamento</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">Selecione um método</option>
                                <option value="cash">Dinheiro</option>
                                <option value="card">Cartão</option>
                                <option value="mpesa">M-Pesa</option>
                                <option value="emola">E-Mola</option>
                                <option value="mkesh">M-Kesh</option>
                                <option value="outros">Outros</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Valor Recebido</label>
                            <div class="input-group">
                                <span class="input-group-text">MT</span>
                                <input type="number" name="amount_paid" class="form-control" step="0.01" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Observações</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="alert alert-info">
                            <strong>Total do Pedido:</strong>
                            <span id="modalOrderTotal">-</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-check-circle me-1"></i>
                            Confirmar Pagamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Stats Cards Customization */
        .stats-card {
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-soft);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            background: white;
            border-left: 4px solid transparent;
        }

        .stats-card.primary {
            border-left-color: var(--primary-color);
        }

        .stats-card.secondary {
            border-left-color: var(--secondary-color);
        }

        .stats-card.success {
            border-left-color: var(--success-color);
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stats-card .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: white;
        }

        .stats-card.primary .stats-icon {
            background: var(--primary-gradient);
        }

        .stats-card.secondary .stats-icon {
            background: var(--secondary-gradient);
        }

        .stats-card.success .stats-icon {
            background: linear-gradient(135deg, var(--success-color), #34d399);
        }

        .stats-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stats-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Order Row Styles */
        .order-row {
            transition: all 0.2s ease;
        }

        .order-row:hover {
            background-color: rgba(8, 145, 178, 0.05) !important;
            transform: translateX(2px);
        }

        .customer-avatar i {
            font-size: 1.5rem;
        }

        .customer-name {
            font-weight: 500;
            color: var(--dark-color);
        }

        /* Tabela de Pedidos */
        .custom-orders-table {
            font-size: 0.95rem;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .custom-orders-table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            padding: 1rem 0.75rem;
        }

        .custom-orders-table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        .order-id-badge {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white !important;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        /* Cliente e Notas */
        .customer-avatar i {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #dee2e6;
        }

        .notes-badge {
            background: #f8f9fa;
            padding: 0.125rem 0.5rem;
            border-radius: 0.25rem;
            border-left: 3px solid #6c757d;
        }

        /* Mesa */
        .table-badge {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
        }

        .group-link {
            font-size: 0.75rem;
            color: #6c757d !important;
        }

        /* Total e Itens */
        .total-amount {
            font-size: 1.1rem;
            display: block;
        }

        .items-count {
            font-size: 0.8rem;
        }

        /* Status e Pagamento */
        .status-badge,
        .payment-badge,
        .payment-pending {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
            border-radius: 1rem;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .status-badge:hover,
        .payment-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Ações */
        .actions-group {
            gap: 0.25rem;
        }

        .action-btn {
            border-radius: 0.375rem !important;
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            border-width: 1.5px;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .more-actions-btn {
            border-left: none !important;
        }

        .custom-dropdown {
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            min-width: 180px;
        }

        .custom-dropdown .dropdown-item {
            padding: 0.75rem 1rem;
            transition: background-color 0.2s ease;
        }

        .custom-dropdown .dropdown-item:hover {
            background-color: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }

        .cancel-item {
            color: #dc3545 !important;
        }

        .cancel-item:hover {
            background-color: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545 !important;
        }

        /* Paginação */
        .pagination-footer {
            padding: 1rem 1.25rem;
            background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
            border-top: 1px solid #e9ecef;
        }

        .info-text {
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Estado Vazio */
        .empty-state-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 0.5rem;
            margin: 1rem;
        }

        .empty-icon {
            opacity: 0.5;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }

        .empty-title {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .empty-description {
            font-size: 1rem;
            line-height: 1.5;
        }

        .empty-action-btn {
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .empty-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .custom-orders-table {
                font-size: 0.85rem;
            }

            .actions-group {
                flex-wrap: wrap;
                gap: 0.125rem;
            }

            .action-btn {
                padding: 0.25rem 0.375rem;
                font-size: 0.75rem;
            }

            .empty-state-container {
                margin: 0.5rem;
            }
        }

        .table-info .badge {
            font-size: 0.75rem;
        }

        .order-time .fw-semibold {
            font-size: 0.875rem;
        }

        .order-total {
            text-align: right;
        }

        .order-total .fw-bold {
            font-size: 1rem;
        }

        .payment-info .badge {
            font-size: 0.7rem;
        }

        /* Button Group Styles */
        .btn-group .btn-sm {
            padding: 0.25rem 0.5rem;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Empty State */
        .empty-state {
            padding: 3rem 2rem;
        }

        .empty-state i {
            opacity: 0.5;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-group .btn {
                margin-bottom: 0.25rem;
            }
        }

        /* Status Badge Colors */
        .badge.bg-success {
            background-color: var(--success-color) !important;
        }

        .badge.bg-warning {
            background-color: var(--warning-color) !important;
        }

        .badge.bg-danger {
            background-color: var(--danger-color) !important;
        }

        .badge.bg-info {
            background-color: var(--info-color) !important;
        }

        .badge.bg-secondary {
            background-color: #6c757d !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // Complete Order Function
        async function completeOrder(orderId) {
            try {
                if (!confirm('Deseja marcar este pedido como finalizado?')) {
                    return;
                }

                const response = await fetch(`/orders/${orderId}/complete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                showToast(error.message || 'Erro ao finalizar pedido', 'error');
            }
        }

        // Cancel Order Function
        async function cancelOrder(orderId) {
            try {
                const reason = prompt('Por favor, informe o motivo do cancelamento:');

                if (!reason || reason.trim() === '') {
                    showToast('O motivo do cancelamento é obrigatório!', 'warning');
                    return;
                }

                if (!confirm('Confirma o cancelamento deste pedido?')) {
                    return;
                }

                const response = await fetch(`/orders/${orderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        notes: reason
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                showToast(error.message || 'Erro ao cancelar pedido', 'error');
            }
        }

        // Open Payment Modal
        function openPaymentModal(orderId) {
            // Fetch order data first
            fetch(`/orders/data/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalOrderTotal').textContent =
                        `${data.total_amount.toLocaleString('pt-MZ')} MT`;

                    const form = document.getElementById('quickPaymentForm');
                    form.action = `/orders/${orderId}/pay`;

                    // Set default amount
                    form.querySelector('[name="amount_paid"]').value = data.total_amount;

                    const modal = new bootstrap.Modal(document.getElementById('quickPaymentModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching order data:', error);
                    showToast('Erro ao carregar dados do pedido', 'error');
                });
        }

        // Handle payment form submission
        document.getElementById('quickPaymentForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('quickPaymentModal')).hide();

                    showToast('Pagamento registrado com sucesso!', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                showToast(error.message || 'Erro ao processar pagamento', 'error');
            }
        });

        // Auto refresh every 30 seconds for active orders
        let autoRefreshInterval;

        function startAutoRefresh() {
            // Only auto-refresh if there are active orders
            const hasActiveOrders = document.querySelectorAll('.order-row').length > 0;

            if (hasActiveOrders) {
                autoRefreshInterval = setInterval(() => {
                    // Check if user is not interacting with modals
                    const openModals = document.querySelectorAll('.modal.show');
                    if (openModals.length === 0) {
                        // Subtle refresh - just update the data without full page reload
                        updateOrderStatuses();
                    }
                }, 30000); // 30 seconds
            }
        }

        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
        }

        // Update order statuses via AJAX
        async function updateOrderStatuses() {
            try {
                const response = await fetch(window.location.href + '?ajax=1', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    // You can implement a partial update here
                    // For now, we'll just show a subtle indicator
                    showStatusUpdateIndicator();
                }
            } catch (error) {
                console.error('Error updating order statuses:', error);
            }
        }

        function showStatusUpdateIndicator() {
            // Create a subtle notification
            const indicator = document.createElement('div');
            indicator.className = 'position-fixed top-0 end-0 m-3 alert alert-info alert-dismissible fade show';
            indicator.innerHTML = `
            <i class="mdi mdi-refresh me-2"></i>
            Status atualizado
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
            indicator.style.zIndex = '9999';
            document.body.appendChild(indicator);

            // Auto dismiss after 3 seconds
            setTimeout(() => {
                const alert = bootstrap.Alert.getInstance(indicator);
                if (alert) alert.close();
            }, 1000);
        }

        // Initialize auto refresh when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startAutoRefresh();
        });

        // Stop refresh when user leaves page
        window.addEventListener('beforeunload', function() {
            stopAutoRefresh();
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K for search focus
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[name="search"]');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }

            // Ctrl/Cmd + N for new order
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                window.location.href = '{{ route('pos.index') }}';
            }

            // R for refresh
            if (e.key === 'r' && !e.ctrlKey && !e.metaKey && !e.altKey) {
                const activeElement = document.activeElement;
                // Only if not focused on input
                if (!activeElement || !activeElement.matches('input, textarea, select')) {
                    e.preventDefault();
                    window.location.reload();
                }
            }
        });

        // Print function integration
        if (typeof printRecibo !== 'function') {
            window.printRecibo = function(orderId) {
                const printWindow = window.open(`/orders/print/${orderId}`, '_blank', 'width=800,height=600');
                if (printWindow) {
                    printWindow.focus();
                } else {
                    showToast('Por favor, permita pop-ups para imprimir o recibo', 'warning');
                }
            };
        }

        // Enhanced search functionality
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            // Add search suggestions/autocomplete here if needed
            searchInput.addEventListener('input', function(e) {
                if (e.target.value.length > 2) {
                    // You can implement real-time search suggestions here
                }
            });

            // Clear search on Escape
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    this.form.submit();
                }
            });
        }
    </script>
@endpush
