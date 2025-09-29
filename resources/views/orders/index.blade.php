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
        <div class="card">
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
                                        <a class="dropdown-item" href="{{ route('kitchen.dashboard') }}">
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
                                    <th style="width: 200px;" class="text-center">Ações</th>
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
                                                <!-- Ver Detalhes -->
                                                <a href="{{ route('orders.show', $order) }}"
                                                    class="btn btn-sm btn-outline-primary action-btn view-btn"
                                                    data-bs-toggle="tooltip" title="Ver Detalhes">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>

                                                <!-- Editar Pedido -->
                                                @if ($order->status !== 'paid' && $order->status !== 'canceled')
                                                    <a href="{{ route('orders.edit', $order) }}"
                                                        class="btn btn-sm btn-outline-secondary action-btn edit-btn"
                                                        data-bs-toggle="tooltip" title="Editar Pedido">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                @endif

                                                <!-- Finalizar Pedido -->
                                                @if ($order->status === 'active')
                                                    <form action="{{ route('orders.complete', $order) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-warning action-btn complete-btn"
                                                            data-bs-toggle="tooltip" title="Finalizar Pedido"
                                                            onclick="return confirm('Deseja finalizar este pedido?')">
                                                            <i class="mdi mdi-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <!-- Registrar Pagamento -->
                                                @if ($order->status === 'completed')
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-success action-btn pay-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#paymentModal{{ $order->id }}"
                                                        title="Registrar Pagamento">
                                                        <i class="mdi mdi-cash-multiple"></i>
                                                    </button>
                                                @endif

                                                <!-- Imprimir Recibo -->
                                                @if ($order->status === 'paid' || $order->status === 'completed')
                                                    <a href="{{ route('orders.print', $order) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-info action-btn print-btn"
                                                        data-bs-toggle="tooltip"
                                                        title="Imprimir {{ $order->status === 'paid' ? 'Recibo' : 'Conta' }}">
                                                        <i class="mdi mdi-printer"></i>
                                                    </a>
                                                @endif

                                                <!-- Cancelar Pedido -->
                                                @if ($order->status !== 'canceled' && $order->status !== 'paid')
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger action-btn cancel-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#cancelOrderModal{{ $order->id }}"
                                                        title="Cancelar Pedido">
                                                        <i class="mdi mdi-cancel"></i>
                                                    </button>
                                                @endif
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

    <!-- Payment Modals (um para cada pedido completed) -->
    @foreach ($orders->where('status', 'completed') as $order)
        <div class="modal fade" id="paymentModal{{ $order->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('orders.pay', $order) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="mdi mdi-cash-multiple text-success me-2"></i>
                                Registrar Pagamento - Pedido #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Método de Pagamento</label>
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
                                <label class="form-label fw-semibold">Valor Recebido</label>
                                <div class="input-group">
                                    <span class="input-group-text">MT</span>
                                    <input type="number" name="amount_paid" class="form-control" step="0.01"
                                        min="{{ $order->total_amount }}" value="{{ $order->total_amount }}" required>
                                </div>
                                <small class="text-muted">Mínimo: {{ number_format($order->total_amount, 2, ',', '.') }}
                                    MT</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Observações</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Observações do pagamento (opcional)"></textarea>
                            </div>

                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Total do Pedido:</strong>
                                    <span class="fw-bold">{{ number_format($order->total_amount, 2, ',', '.') }} MT</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="mdi mdi-close me-1"></i>
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="mdi mdi-check-circle me-1"></i>
                                Confirmar Pagamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Cancel Order Modals (um para cada pedido ativo) -->
    @foreach ($orders->whereNotIn('status', ['canceled', 'paid']) as $order)
        <div class="modal fade" id="cancelOrderModal{{ $order->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('orders.cancel', $order) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="mdi mdi-cancel me-2"></i>
                                Cancelar Pedido #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="mdi mdi-alert me-2"></i>
                                <strong>Atenção!</strong> Esta ação não pode ser desfeita.
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Cliente: <span
                                        class="text-primary">{{ $order->customer_name ?: 'Não identificado' }}</span>
                                </label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Total: <span
                                        class="text-success">{{ number_format($order->total_amount, 2, ',', '.') }}
                                        MT</span>
                                </label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Motivo do Cancelamento <span class="text-danger">*</span>
                                </label>
                                <textarea name="notes" class="form-control" rows="4" required minlength="3"
                                    placeholder="Descreva o motivo do cancelamento..."></textarea>
                                <small class="text-muted">Mínimo 3 caracteres</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="mdi mdi-close me-1"></i>
                                Fechar
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="mdi mdi-cancel me-1"></i>
                                Confirmar Cancelamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('styles')
    <style>
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
            overflow: hidden;
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
            color: var(--primary-color) !important;
            padding: 0.25rem 0.5rem;
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            font-weight: 600;
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
            border-radius: var(--border-radius);
            border-left: 3px solid #6c757d;
        }

        /* Mesa */
        .table-badge {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius);
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
            border-radius: var(--border-radius-lg);
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .status-badge:hover,
        .payment-badge:hover {
            transform: translateY(-1px);
        }

        /* Ações */
        .actions-group {
            gap: 0.25rem;
            flex-wrap: nowrap;
        }

        .action-btn {
            border-radius: var(--border-radius) !important;
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
            transition: var(--transition);
            border-width: 1.5px;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        .dropdown-menu {
            border-radius: var(--border-radius-lg);
            min-width: 200px;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: rgba(0, 123, 255, 0.1);
            color: var(--primary-color);
        }

        .dropdown-item.text-danger:hover {
            background-color: rgba(220, 53, 69, 0.1) !important;
            color: var(--danger-color) !important;
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
            border-radius: var(--border-radius-lg);
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
            border-radius: var(--border-radius-lg);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .empty-action-btn:hover {
            transform: translateY(-2px);
        }

        /* Modal Improvements */
        .modal-content {
            border-radius: var(--border-radius-lg);
            border: none;
        }

        .modal-header {
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
            padding: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-radius: 0 0 var(--border-radius-lg) var(--border-radius-lg);
            padding: 1.5rem;
        }

        /* Form Controls */
        .form-control,
        .form-select {
            border-radius: var(--border-radius);
            border: 1px solid #dee2e6;
            transition: var(--transition);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(8, 145, 178, 0.25);
        }

        .input-group-text {
            border-radius: var(--border-radius);
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        /* Button Hover Effects */
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-success:hover {
            background: #0ea770;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-outline-secondary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-outline-success:hover {
            background-color: var(--success-color);
            border-color: var(--success-color);
            transform: translateY(-2px);
        }

        .btn-outline-warning:hover {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
            transform: translateY(-2px);
        }

        .btn-outline-info:hover {
            background-color: var(--info-color);
            border-color: var(--info-color);
            transform: translateY(-2px);
        }

        .btn-outline-danger:hover {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
            transform: translateY(-2px);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .actions-group {
                flex-direction: column;
                gap: 0.25rem;
            }

            .action-btn {
                width: 100%;
                margin-bottom: 0.25rem;
            }

            .dropdown {
                width: 100%;
            }

            .dropdown-toggle {
                width: 100%;
            }

            .empty-state-container {
                margin: 0.5rem;
            }

            .modal-dialog {
                margin: 0.5rem;
            }

            .custom-orders-table th:nth-child(3),
            .custom-orders-table td:nth-child(3) {
                display: none;
                /* Hide Mesa column on mobile */
            }

            .custom-orders-table th:nth-child(4),
            .custom-orders-table td:nth-child(4) {
                font-size: 0.8rem;
                /* Smaller date on mobile */
            }
        }

        @media (max-width: 576px) {
            .stats-card .stats-content h3 {
                font-size: 1.1rem;
            }

            .stats-card .stats-label {
                font-size: 0.75rem;
            }

            .card-header h5 {
                font-size: 1rem;
            }

            .order-id-badge {
                font-size: 0.75rem;
                padding: 0.2rem 0.4rem;
            }

            .customer-name {
                font-size: 0.9rem;
            }

            .total-amount {
                font-size: 1rem;
            }

            .badge {
                font-size: 0.7rem;
                padding: 0.4rem 0.6rem;
            }

            .action-btn {
                padding: 0.25rem 0.4rem;
                font-size: 0.8rem;
            }
        }

        /* Print Styles */
        @media print {

            .sidebar,
            .top-navbar,
            .btn,
            .dropdown,
            .pagination-footer {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #000 !important;
            }

            .table {
                font-size: 0.8rem !important;
            }

            .badge {
                border: 1px solid #000 !important;
                color: #000 !important;
            }
        }

        /* Loading states */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn.loading {
            position: relative;
            color: transparent !important;
        }

        .btn.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Tooltips */
        .tooltip {
            font-size: 0.8rem;
        }

        .tooltip-inner {
            background-color: var(--dark-color);
            border-radius: var(--border-radius);
            padding: 0.5rem 0.75rem;
        }

        /* Status badge colors using CSS variables */
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

        /* Alert improvements */
        .alert {
            border-radius: var(--border-radius-lg);
            border: none;
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
            border-left: 4px solid var(--info-color);
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
            border-left: 4px solid var(--warning-color);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            border-left: 4px solid var(--danger-color);
        }

        /* Smooth transitions for all interactive elements */
        *,
        *::before,
        *::after {
            transition: var(--transition);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover'
                });
            });

            // Auto focus on search input with Ctrl+K
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const searchInput = document.querySelector('input[name="search"]');
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                    }
                }

                // Escape to clear search
                if (e.key === 'Escape') {
                    const searchInput = document.querySelector('input[name="search"]');
                    if (searchInput && searchInput === document.activeElement) {
                        searchInput.value = '';
                    }
                }
            });
        });

        // Form submission with loading state
        function addLoadingState(form) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;

                // Fallback to restore button after 10 seconds
                setTimeout(() => {
                    submitBtn.classList.remove('loading');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 10000);
            }
        }

        // Add loading state to all forms on submit
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                addLoadingState(this);
            });
        });

        // Payment amount validation
        document.querySelectorAll('input[name="amount_paid"]').forEach(function(input) {
            input.addEventListener('input', function() {
                const minAmount = parseFloat(this.min);
                const currentAmount = parseFloat(this.value);

                if (currentAmount < minAmount) {
                    this.setCustomValidity(`O valor mínimo é ${minAmount.toFixed(2)} MT`);
                } else {
                    this.setCustomValidity('');
                }
            });
        });

        // Confirmation for critical actions
        document.querySelectorAll('form[action*="complete"]').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (!confirm('Tem certeza que deseja finalizar este pedido?')) {
                    e.preventDefault();
                    return false;
                }
            });
        });
        // Auto refresh for real-time updates (optional)
        let autoRefreshInterval;

        function startAutoRefresh() {
            // Only refresh if there are active orders and user is not interacting
            const hasActiveOrders = document.querySelectorAll('.order-row').length > 0;

            if (hasActiveOrders) {
                autoRefreshInterval = setInterval(() => {
                    // Check if no modals are open and user is not typing
                    const openModals = document.querySelectorAll('.modal.show');
                    const activeInput = document.activeElement;
                    const isTyping = activeInput && (activeInput.tagName === 'INPUT' || activeInput.tagName ===
                        'TEXTAREA');

                    if (openModals.length === 0 && !isTyping) {
                        // Silent refresh of order statuses
                        fetch(window.location.href + '?ajax=1', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }).then(response => {
                            if (response.ok && response.headers.get('content-type').includes(
                                    'application/json')) {
                                // Handle partial updates here if your backend supports it
                                console.log('Orders status checked');
                            }
                        }).catch(() => {
                            // Silently fail
                        });
                    }
                }, 30000); // 30 seconds
            }
        }

        // Start auto refresh when page loads
        document.addEventListener('DOMContentLoaded', startAutoRefresh);

        // Stop auto refresh when user navigates away
        window.addEventListener('beforeunload', () => {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
        });

        // Print function for receipts
        function printOrder(orderId) {
            const printWindow = window.open(`/orders/print/${orderId}`, '_blank', 'width=800,height=600');
            if (printWindow) {
                printWindow.focus();
            } else {
                showToast('Por favor, permita pop-ups para imprimir', 'warning');
            }
        }

        // Enhanced search with debounce (if you want to add real-time search later)
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

        // Table row click handler for better UX
        document.querySelectorAll('.order-row').forEach(function(row) {
            row.addEventListener('click', function(e) {
                // Don't trigger if clicking on buttons or links
                if (e.target.closest('.btn, .dropdown, a')) {
                    return;
                }

                const orderId = this.dataset.orderId;
                window.location.href = `/orders/${orderId}`;
            });

            // Add pointer cursor
            row.style.cursor = 'pointer';
        });
    </script>
@endpush
