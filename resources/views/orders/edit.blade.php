@extends('layouts.app')

@section('title', 'Pedido #' . str_pad($order->id, 4, '0', STR_PAD_LEFT))
@section('page-title', 'Editar Pedido')
@section('title-icon', 'mdi-receipt-text')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Pedidos</a></li>
    <li class="breadcrumb-item active">Pedido #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Order Header -->
        <div class="stats-card primary mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stats-icon">
                            <i class="mdi mdi-receipt-text"></i>
                        </div>
                        <div>
                            <h2 class="mb-1 fw-bold text-dark">
                                Pedido #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                            </h2>
                            <small class="text-muted">
                                <i class="mdi mdi-clock-outline me-1"></i>
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <span class="badge {{ get_status_class_staradmins($order->status) }} status-badge-lg">
                            {{ ucfirst(trans($order->status)) }}
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="btn-group" role="group">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i> Voltar
                        </a>
                        <a href="{{ route('orders.print', $order) }}" target="_blank" class="btn btn-outline-primary">
                            <i class="mdi mdi-printer me-1"></i> Imprimir
                        </a>
                        @if ($order->status == 'active')
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#cancelOrderModal">
                                <i class="mdi mdi-close me-1"></i> Cancelar
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column - Order Management -->
            <div class="col-xl-8">
                <!-- Order Items -->
                <div class="card mb-4" style="padding: 10px;">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">
                                <i class="mdi mdi-format-list-bulleted text-primary me-2"></i>
                                Itens do Pedido
                            </h5>
                            <span class="badge bg-primary items-count-badge">
                                {{ $order->items->count() }} {{ $order->items->count() == 1 ? 'item' : 'itens' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @forelse ($order->items as $item)
                            <div class="order-item-card" data-item-id="{{ $item->id }}">
                                <div class="row align-items-center">
                                    <!-- Product Info -->
                                    <div class="col-md-5">
                                        <div class="product-info">
                                            <h6 class="product-name mb-1">
                                                {{ $item->product->name ?? 'Produto removido' }}
                                            </h6>
                                            @if ($item->notes)
                                                <div class="product-notes">
                                                    <i class="mdi mdi-note-text text-muted me-1"></i>
                                                    <small class="text-muted">{{ $item->notes }}</small>
                                                </div>
                                            @endif
                                            <div class="product-price">
                                                <span
                                                    class="unit-price">{{ number_format($item->unit_price, 2, ',', '.') }}
                                                    MT</span>
                                                <small class="text-muted">por unidade</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quantity Display -->
                                    <div class="col-md-2">
                                        <div class="quantity-display">
                                            <label class="form-label small fw-semibold">Quantidade</label>
                                            <div class="quantity-value">
                                                <span class="badge bg-primary quantity-badge">{{ $item->quantity }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Total Price -->
                                    <div class="col-md-2 text-end">
                                        <div class="item-total">
                                            <label class="form-label small fw-semibold">Total</label>
                                            <div class="total-price">{{ number_format($item->total_price, 2, ',', '.') }}
                                                MT</div>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-2">
                                        <div class="item-status">
                                            <label class="form-label small fw-semibold">Status</label>
                                            <form action="{{ route('orders.update-item-status', $item) }}" method="POST"
                                                class="status-form">
                                                @csrf
                                                <select name="status" class="form-select form-select-sm status-select"
                                                    onchange="this.form.submit()">
                                                    <option value="pending" @selected($item->status === 'pending')>Pendente</option>
                                                    <option value="preparing" @selected($item->status === 'preparing')>Preparando
                                                    </option>
                                                    <option value="ready" @selected($item->status === 'ready')>Pronto</option>
                                                    <option value="delivered" @selected($item->status === 'delivered')>Entregue</option>
                                                    <option value="cancelled" @selected($item->status === 'cancelled')>Cancelado
                                                    </option>
                                                </select>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-md-1 text-end">
                                        <div class="item-actions">
                                            <button class="btn btn-sm btn-outline-danger remove-item-btn"
                                                onclick="removeItem({{ $item->id }})" title="Remover item">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-items-state">
                                <div class="text-center py-5">
                                    <i class="mdi mdi-cart-outline empty-cart-icon"></i>
                                    <h6 class="text-muted mt-3">Nenhum item no pedido</h6>
                                    <p class="text-muted small">Adicione produtos usando o menu abaixo</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Menu -->
                <div class="card menu-card">
                    <div class="card-header menu-header">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="menu-title-section">
                                    <div class="menu-icon">
                                        <i class="mdi mdi-silverware-fork-knife"></i>
                                    </div>
                                    <div>
                                        <h5 class="menu-title mb-1">Menu de Produtos</h5>
                                        <small class="menu-subtitle">Adicione produtos ao pedido</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="search-section">
                                    <div class="search-input-wrapper">
                                        <i class="mdi mdi-magnify search-icon"></i>
                                        <input type="text" class="form-control search-input" id="quickMenuSearch"
                                            placeholder="Buscar produtos...">
                                        <button class="search-clear-btn" id="clearSearch" style="display: none;">
                                            <i class="mdi mdi-close"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body menu-body">
                        <!-- Category Filter -->
                        <div class="category-filter-section mb-4">
                            <div class="category-filter-wrapper">
                                <button class="category-filter-btn active" data-category="all"
                                    onclick="showAllCategories(this)">
                                    <i class="mdi mdi-view-grid me-2"></i>
                                    <span>Todos</span>
                                    <span
                                        class="category-count">{{ $categories->sum(fn($cat) => $cat->products->count()) }}</span>
                                </button>
                                @foreach ($categories as $category)
                                    <button class="category-filter-btn" data-category="{{ $category->id }}"
                                        onclick="showCategory({{ $category->id }}, this)">
                                        <i class="mdi mdi-{{ $category->icon ?? 'food-variant' }} me-2"></i>
                                        <span>{{ $category->name }}</span>
                                        <span class="category-count">{{ $category->products->count() }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Products Grid -->
                        <div class="products-container">
                            <div class="products-grid" id="allProducts">
                                @foreach ($categories as $category)
                                    @foreach ($category->products as $product)
                                        <div class="product-item" data-category="{{ $category->id }}"
                                            data-product-name="{{ strtolower($product->name) }}">
                                            <div class="product-card" onclick="addProduct({{ $product->id }})">
                                                <!-- Product Image Placeholder -->
                                                <div class="product-image">
                                                    @if ($product->image)
                                                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                                            loading="lazy">
                                                    @else
                                                        <div class="product-placeholder">
                                                            <i class="mdi mdi-silverware-variant"></i>
                                                        </div>
                                                    @endif
                                                    @if (($product->stock_quantity ?? 0) <= 5)
                                                        <div class="stock-badge low-stock">
                                                            <i class="mdi mdi-alert-circle-outline me-1"></i>
                                                            Baixo
                                                        </div>
                                                    @elseif(($product->stock_quantity ?? 0) == 0)
                                                        <div class="stock-badge out-stock">
                                                            <i class="mdi mdi-close-circle-outline me-1"></i>
                                                            Esgotado
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Product Content -->
                                                <div class="product-content">
                                                    <div class="product-info">
                                                        <h6 class="product-name">{{ $product->name }}</h6>
                                                        @if ($product->description)
                                                            <p class="product-description">
                                                                {{ Str::limit($product->description, 60) }}</p>
                                                        @endif
                                                    </div>

                                                    <div class="product-meta">
                                                        <div class="product-pricing">
                                                            <span
                                                                class="product-price">{{ number_format($product->price, 2, ',', '.') }}
                                                                MT</span>
                                                            @if ($product->stock_quantity > 5)
                                                                <span class="stock-available">
                                                                    <i class="mdi mdi-check-circle me-1"></i>
                                                                    {{ $product->stock_quantity }} disponível
                                                                </span>
                                                            @endif
                                                        </div>

                                                        <div class="product-actions">
                                                            <button type="button"
                                                                class="add-btn {{ ($product->stock_quantity ?? 0) == 0 ? 'disabled' : '' }}"
                                                                {{ ($product->stock_quantity ?? 0) == 0 ? 'disabled' : '' }}>
                                                                <i class="mdi mdi-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>

                            <!-- Empty State -->
                            <div class="empty-products-state" id="emptyState" style="display: none;">
                                <div class="empty-content">
                                    <i class="mdi mdi-food-off empty-icon"></i>
                                    <h6 class="empty-title">Nenhum produto encontrado</h6>
                                    <p class="empty-description">Tente ajustar sua busca ou selecionar outra categoria</p>
                                    <button class="btn btn-outline-primary btn-sm" onclick="clearSearch()">
                                        <i class="mdi mdi-refresh me-1"></i>
                                        Limpar Filtros
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Info & Actions -->
            <div class="col-xl-4">
                <!-- Order Information -->
                <div class="stats-card secondary mb-4">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="mdi mdi-information-outline text-primary me-2"></i>
                        Informações do Pedido
                    </h6>

                    <div class="info-item">
                        <i class="mdi mdi-clock-outline text-primary"></i>
                        <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    @if ($order->table)
                        <div class="info-item">
                            <i class="mdi mdi-table-furniture text-primary"></i>
                            <span>Mesa {{ $order->table->number }}</span>
                        </div>
                    @endif

                    <div class="info-item">
                        <i class="mdi mdi-account text-primary"></i>
                        <span>{{ $order->customer_name ?: 'Cliente não informado' }}</span>
                    </div>

                    @if ($order->notes)
                        <div class="info-item">
                            <i class="mdi mdi-note-text text-primary"></i>
                            <span>{{ $order->notes }}</span>
                        </div>
                    @endif
                </div>

                <!-- Customer Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0 fw-bold">
                            <i class="mdi mdi-account-edit text-primary me-2"></i>
                            Dados do Cliente
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('orders.update', $order) }}" method="POST" id="customerForm">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nome do Cliente</label>
                                <input type="text" name="customer_name" class="form-control"
                                    value="{{ $order->customer_name }}" placeholder="Digite o nome">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Observações</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Observações do pedido">{{ $order->notes }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="mdi mdi-content-save me-1"></i>
                                Salvar Alterações
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="stats-card success">
                    <div class="order-summary-header">
                        <h6 class="fw-bold mb-3">
                            <i class="mdi mdi-calculator text-success me-2"></i>
                            Resumo do Pedido
                        </h6>
                    </div>

                    <div class="summary-details mb-4">
                        <div class="d-flex justify-content-between align-items-center summary-row">
                            <span>Itens ({{ $order->items->count() }})</span>
                            <span>{{ number_format($order->total_amount, 2, ',', '.') }} MT</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center summary-row">
                            <span>Desconto</span>
                            <span>0,00 MT</span>
                        </div>
                        <hr class="summary-divider">
                        <div class="d-flex justify-content-between align-items-center total-row">
                            <strong>Total</strong>
                            <strong class="total-amount">{{ number_format($order->total_amount, 2, ',', '.') }}
                                MT</strong>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        @if ($order->status !== 'completed' && $order->status !== 'paid')
                            <form action="{{ route('orders.complete', $order) }}" method="POST">
                                @csrf
                                <div class="d-grid gap-2">
                                    @if ($order->status !== 'completed' && $order->status !== 'paid')
                                        @if ($order->items->count() > 0)
                                            <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal"
                                                data-bs-target="#completeOrderModal">
                                                <i class="mdi mdi-check-circle me-2"></i>
                                                Finalizar Pedido
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-warning w-100" disabled
                                                title="Adicione itens para finalizar">
                                                <i class="mdi mdi-alert-circle me-2"></i>
                                                Sem itens para finalizar
                                            </button>
                                            <small class="text-muted text-center mt-2">
                                                <i class="mdi mdi-information-outline me-1"></i>
                                                Adicione produtos ao pedido para poder finalizá-lo
                                            </small>
                                        @endif
                                    @endif

                                    @if ($order->status === 'completed')
                                        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                            data-bs-target="#paymentModal">
                                            <i class="mdi mdi-cash-multiple me-2"></i>
                                            Registrar Pagamento
                                        </button>
                                    @endif

                                    @if ($order->status === 'paid')
                                        <div class="alert alert-success text-center mb-0 paid-status">
                                            <i class="mdi mdi-check-circle me-2"></i>
                                            <strong>Pedido Pago</strong>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        @endif

                        @if ($order->status === 'completed')
                            <button type="button"
                                class="btn btn-{{ $order->items->count() === 0 ? 'danger' : 'success' }} w-100"
                                id="paymentbtn"
                                @if ($order->items->count() > 0) data-bs-toggle="modal" data-bs-target="#paymentModal" @endif>
                                <i class="mdi mdi-cash-multiple me-2"></i>
                                Registrar Pagamento
                            </button>
                        @endif


                        @if ($order->status === 'paid')
                            <div class="alert alert-success text-center mb-0 paid-status">
                                <i class="mdi mdi-check-circle me-2"></i>
                                <strong>Pedido Pago</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    @if ($order->status === 'completed')
        <div class="modal fade" id="paymentModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('orders.pay', $order) }}" method="POST">
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
                                <label class="form-label fw-semibold">Método de Pagamento</label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    <option value="">Selecione</option>
                                    <option value="cash">Dinheiro</option>
                                    <option value="card">Cartão</option>
                                    <option value="mpesa">M-Pesa</option>
                                    <option value="emola">E-Mola</option>
                                    <option value="mkesh">M-Kesh</option>
                                    <option value="outros">Outros</option>
                                </select>
                            </div>

                            <div id="cashFields" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Valor Recebido</label>
                                    <div class="input-group">
                                        <span class="input-group-text">MT</span>
                                        <input type="number" class="form-control" id="cash_amount" name="cash_amount"
                                            step="0.01" min="{{ $order->total_amount }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Troco</label>
                                    <div class="input-group">
                                        <span class="input-group-text">MT</span>
                                        <input type="text" class="form-control" id="change_amount" readonly>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="amount_paid" value="{{ $order->total_amount }}">

                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between">
                                    <strong>Total a Pagar:</strong>
                                    <span class="fw-bold">{{ number_format($order->total_amount, 2, ',', '.') }} MT</span>
                                </div>
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
    @endif

    <!-- Cancel Order Modal -->
    @if ($order->status == 'active')
        <div class="modal fade" id="cancelOrderModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('orders.cancel', $order) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="mdi mdi-alert-circle me-2"></i>
                                Cancelar Pedido
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Motivo do Cancelamento</label>
                                <textarea class="form-control" name="notes" rows="3" required
                                    placeholder="Descreva o motivo do cancelamento"></textarea>
                            </div>
                            <div class="alert alert-warning">
                                <i class="mdi mdi-alert-triangle me-2"></i>
                                Esta ação não pode ser desfeita. O pedido será cancelado permanentemente.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Voltar</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="mdi mdi-delete me-1"></i>
                                Confirmar Cancelamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <!-- Modal: Confirmar Remoção de Item -->
    <div class="modal fade" id="removeItemModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="mdi mdi-delete-alert me-2"></i>
                        Remover Item
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="mdi mdi-alert-circle-outline text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center mb-3">
                        Tem certeza que deseja remover este item do pedido?
                    </p>
                    <div class="alert alert-warning">
                        <i class="mdi mdi-information-outline me-2"></i>
                        <small>Esta ação não pode ser desfeita.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-1"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmRemoveItem">
                        <i class="mdi mdi-delete me-1"></i>
                        Sim, Remover
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Confirmar Finalização de Pedido -->
    <div class="modal fade" id="completeOrderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="mdi mdi-check-circle me-2"></i>
                        Finalizar Pedido
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="mdi mdi-clipboard-check-outline text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center mb-3">
                        Deseja finalizar este pedido?
                    </p>
                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline me-2"></i>
                        <small>Após finalizar, você poderá registrar o pagamento.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-1"></i>
                        Cancelar
                    </button>
                    <form action="{{ route('orders.complete', $order) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            <i class="mdi mdi-check-circle me-1"></i>
                            Sim, Finalizar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Hidden forms for item operations -->
    @foreach ($order->items as $item)
        <form id="remove-item-{{ $item->id }}" action="{{ route('orders.remove-item', $item) }}" method="POST"
            style="display: none;">
            @csrf
        </form>
    @endforeach
@endsection

@push('styles')
    <style>
        /* CSS Otimizado para melhor UX na página de edição de pedidos */

        /* Order Items Styling - Reduzido */
        .order-item-card {
            background: white;
            border-left: 3px solid var(--primary-color);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 0.75rem;
            box-shadow: var(--shadow-soft);
            transition: var(--transition);
        }

        .order-item-card:hover {
            transform: translateX(2px);
            box-shadow: var(--shadow-md);
        }

        .order-item-card:last-child {
            margin-bottom: 0;
        }

        .product-info .product-name {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 0.3rem;
            font-size: 0.9rem;
        }

        .product-notes {
            background: rgba(108, 117, 125, 0.1);
            padding: 0.2rem 0.4rem;
            border-radius: var(--border-radius);
            margin-bottom: 0.3rem;
            font-size: 0.8rem;
        }

        .product-price .unit-price {
            color: var(--success-color);
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* Quantity Display - Compacto */
        .quantity-display {
            text-align: center;
        }

        .quantity-value {
            margin-top: 0.3rem;
        }

        .quantity-badge {
            font-size: 0.85rem;
            padding: 0.3rem 0.6rem;
            border-radius: var(--border-radius);
            font-weight: 600;
        }

        .item-total .total-price {
            font-weight: 700;
            font-size: 1rem;
            color: var(--success-color);
        }

        /* Status Select - Menor */
        .status-select {
            border-radius: var(--border-radius);
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
        }

        /* Remove Item Button - Menor */
        .remove-item-btn {
            border-radius: 50% !important;
            width: 28px;
            height: 28px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .remove-item-btn:hover {
            background: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
            transform: scale(1.1);
        }

        /* Menu Card - Compacto */
        .menu-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            background: white;
        }

        .menu-header {
            background: var(--beach-gradient);
            color: white;
            padding: 1rem;
            border: none;
        }

        .menu-title-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .menu-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            backdrop-filter: blur(10px);
        }

        .menu-title {
            color: white;
            font-weight: 600;
            margin: 0;
            font-size: 1.1rem;
        }

        .menu-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 400;
            font-size: 0.85rem;
        }

        /* Search Section - Reduzido */
        .search-input-wrapper {
            position: relative;
            width: 100%;
            max-width: 250px;
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            pointer-events: none;
            z-index: 1;
        }

        .search-input {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.5rem 2.2rem 0.5rem 2.2rem;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            font-size: 0.85rem;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-clear-btn {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .search-clear-btn:hover {
            color: white;
        }

        /* Menu Body - Reduzido */
        .menu-body {
            padding: 1rem;
        }

        /* Category Filter - Compacto */
        .category-filter-section {
            margin-bottom: 1rem;
        }

        .category-filter-wrapper {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            padding: 0.75rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-soft);
        }

        .category-filter-btn {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 0.75rem;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: var(--border-radius);
            color: var(--dark-color);
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
            white-space: nowrap;
            font-size: 0.8rem;
        }

        .category-count {
            background: rgba(0, 0, 0, 0.1);
            padding: 0.15rem 0.4rem;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }

        /* Products Container - Reduzido */
        .products-container {
            background: white;
            border-radius: var(--border-radius);
            padding: 1rem;
            box-shadow: var(--shadow-soft);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 0.75rem;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 0.3rem;
        }

        /* Product Items - Compactos */
        .product-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            transition: var(--transition);
            cursor: pointer;
            height: 100%;
            border: 2px solid transparent;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-color);
        }

        /* Product Image - Reduzido */
        .product-image {
            position: relative;
            height: 90px;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .product-placeholder {
            font-size: 2rem;
            color: #94a3b8;
            opacity: 0.6;
        }

        /* Stock Badges - Menores */
        .stock-badge {
            position: absolute;
            top: 0.3rem;
            right: 0.3rem;
            padding: 0.15rem 0.3rem;
            border-radius: var(--border-radius);
            font-size: 0.6rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        /* Product Content - Compacto */
        .product-content {
            padding: 0.65rem;
            display: flex;
            flex-direction: column;
            height: calc(100% - 90px);
            flex: 1;
        }

        .product-info {
            flex: 1;
            margin-bottom: 0.5rem;
            min-height: 0;
        }

        .product-name {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
            font-size: 0.8rem;
            line-height: 1.2;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-description {
            color: #64748b;
            font-size: 0.7rem;
            line-height: 1.2;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Product Meta - Compacto */
        .product-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            margin-top: auto;
        }

        .product-pricing {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
            flex: 1;
            min-width: 0;
        }

        .product-price {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--success-color);
            white-space: nowrap;
        }

        .stock-available {
            font-size: 0.65rem;
            color: var(--success-color);
            display: flex;
            align-items: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Add Button - Menor */
        .add-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--primary-gradient);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            transition: var(--transition);
            box-shadow: var(--shadow-soft);
            flex-shrink: 0;
        }

        .add-btn:hover:not(.disabled) {
            background: var(--primary-dark);
            transform: scale(1.1);
            box-shadow: var(--shadow-md);
        }

        /* Info Items - Compactos */
        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            padding: 0.4rem;
            background: rgba(255, 255, 255, 0.7);
            border-radius: var(--border-radius);
            transition: var(--transition);
            font-size: 0.85rem;
        }

        .info-item i {
            width: 16px;
            margin-right: 0.5rem;
            font-size: 0.9rem;
        }

        /* Status Badge Large - Reduzido */
        .status-badge-lg {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: var(--border-radius);
            font-weight: 600;
        }

        /* Items Count Badge - Menor */
        .items-count-badge {
            font-size: 0.75rem;
            padding: 0.3rem 0.6rem;
            border-radius: var(--border-radius);
        }

        /* Order Summary - Compacto */
        .summary-details {
            background: rgba(255, 255, 255, 0.7);
            padding: 0.75rem;
            border-radius: var(--border-radius);
        }

        .summary-row {
            padding: 0.3rem 0;
            color: var(--dark-color);
            font-size: 0.85rem;
        }

        .summary-divider {
            margin: 0.5rem 0;
            border-color: rgba(8, 145, 178, 0.2);
        }

        .total-row {
            padding: 0.5rem 0;
            font-size: 1rem;
        }

        .total-amount {
            color: var(--success-color);
            font-size: 1.1rem;
        }

        /* Form Controls - Menores */
        .form-control,
        .form-select {
            border-radius: var(--border-radius);
            border: 1.5px solid #dee2e6;
            transition: var(--transition);
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
        }

        .form-label {
            color: var(--dark-color);
            font-weight: 500;
            margin-bottom: 0.4rem;
            font-size: 0.85rem;
        }

        /* Input Groups - Compactos */
        .input-group-text {
            background: #f8f9fa;
            border: 1.5px solid #dee2e6;
            color: var(--dark-color);
            font-weight: 500;
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
        }

        /* Empty State - Reduzido */
        .empty-items-state {
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: var(--border-radius);
            margin: 0.5rem;
            padding: 2rem 1rem;
        }

        .empty-cart-icon {
            font-size: 3rem;
            color: #6c757d;
            opacity: 0.5;
        }

        .empty-products-state {
            text-align: center;
            padding: 2rem 1rem;
        }

        .empty-icon {
            font-size: 3rem;
            color: #9ca3af;
            margin-bottom: 0.75rem;
            opacity: 0.6;
        }

        .empty-title {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 0.4rem;
            font-size: 0.95rem;
        }

        .empty-description {
            color: #64748b;
            margin-bottom: 1rem;
            line-height: 1.4;
            font-size: 0.85rem;
        }

        /* Cards - Headers reduzidos */
        .card-header {
            padding: 0.75rem 1rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .card-header h5,
        .card-header h6 {
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .card-body {
            padding: 1rem;
        }

        /* Responsive - Mobile ainda mais compacto */
        @media (max-width: 768px) {
            .order-item-card {
                padding: 0.75rem;
            }

            .menu-header {
                padding: 0.75rem;
            }

            .menu-body {
                padding: 0.75rem;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
                max-height: 300px;
            }

            .product-image {
                height: 70px;
            }

            .product-content {
                padding: 0.5rem;
                height: calc(100% - 70px);
            }

            .add-btn {
                width: 26px;
                height: 26px;
                font-size: 0.8rem;
            }

            .category-filter-wrapper {
                padding: 0.5rem;
                gap: 0.3rem;
            }

            .category-filter-btn {
                padding: 0.4rem 0.6rem;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .products-grid {
                max-height: 250px;
                gap: 0.4rem;
            }

            .product-name {
                font-size: 0.75rem;
            }

            .product-price {
                font-size: 0.8rem;
            }

            .stock-available {
                font-size: 0.6rem;
            }

            .card-header {
                padding: 0.5rem 0.75rem;
            }

            .card-body {
                padding: 0.75rem;
            }

            .info-item {
                padding: 0.3rem;
                font-size: 0.8rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Category and search functionality
        let currentCategory = 'all';

        function showAllCategories(btn) {
            currentCategory = 'all';
            updateActiveFilter(btn);

            const products = document.querySelectorAll('.product-item');
            products.forEach(product => {
                product.style.display = 'block';
                product.classList.add('showing');
                product.classList.remove('hiding');
            });

            updateEmptyState();
        }

        function showCategory(categoryId, btn) {
            currentCategory = categoryId;
            updateActiveFilter(btn);

            const products = document.querySelectorAll('.product-item');
            products.forEach(product => {
                const productCategory = product.dataset.category;
                if (productCategory == categoryId) {
                    product.style.display = 'block';
                    product.classList.add('showing');
                    product.classList.remove('hiding');
                } else {
                    product.classList.add('hiding');
                    product.classList.remove('showing');
                    setTimeout(() => {
                        product.style.display = 'none';
                    }, 200);
                }
            });

            updateEmptyState();
        }

        function updateActiveFilter(activeBtn) {
            document.querySelectorAll('.category-filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            activeBtn.classList.add('active');
        }

        // Search functionality
        document.getElementById('quickMenuSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            const clearBtn = document.getElementById('clearSearch');
            clearBtn.style.display = searchTerm ? 'block' : 'none';
            filterProducts(searchTerm);
        });

        function filterProducts(searchTerm) {
            const products = document.querySelectorAll('.product-item');

            products.forEach(product => {
                const productName = product.dataset.productName;
                const productCategory = product.dataset.category;

                const matchesSearch = !searchTerm || productName.includes(searchTerm);
                const matchesCategory = currentCategory === 'all' || productCategory == currentCategory;

                if (matchesSearch && matchesCategory) {
                    product.style.display = 'block';
                    product.classList.add('showing');
                    product.classList.remove('hiding');
                } else {
                    product.classList.add('hiding');
                    product.classList.remove('showing');
                    setTimeout(() => {
                        product.style.display = 'none';
                    }, 200);
                }
            });

            updateEmptyState();
        }

        function clearSearch() {
            const searchInput = document.getElementById('quickMenuSearch');
            searchInput.value = '';
            document.getElementById('clearSearch').style.display = 'none';

            if (currentCategory === 'all') {
                showAllCategories(document.querySelector('.category-filter-btn[data-category="all"]'));
            } else {
                showCategory(currentCategory, document.querySelector(
                    `.category-filter-btn[data-category="${currentCategory}"]`));
            }
        }

        function updateEmptyState() {
            const visibleProducts = document.querySelectorAll('.product-item:not([style*="none"])').length;
            const emptyState = document.getElementById('emptyState');
            emptyState.style.display = visibleProducts === 0 ? 'block' : 'none';
        }

        // Add product function
        function addProduct(productId) {
            const productCard = document.querySelector(`[onclick*="addProduct(${productId})"]`);

            if (productCard) {
                productCard.classList.add('adding');
                setTimeout(() => {
                    productCard.classList.remove('adding');
                }, 800);
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('orders.add-item', $order->id) }}`;
            form.innerHTML = `
            @csrf
            <input type="hidden" name="product_id" value="${productId}">
            <input type="hidden" name="quantity" value="1">
        `;

            document.body.appendChild(form);
            form.submit();
        }

        /// Remove item function - SUBSTITUA
        let itemIdToRemove = null;

        function removeItem(itemId) {
            itemIdToRemove = itemId;
            const modal = new bootstrap.Modal(document.getElementById('removeItemModal'));
            modal.show();
        }

        // Confirmar remoção - ADICIONE este código
        document.getElementById('confirmRemoveItem').addEventListener('click', function() {
            if (itemIdToRemove) {
                document.getElementById(`remove-item-${itemIdToRemove}`).submit();
            }
        });
        //verficarse o pedidio de tem itens 
        document.addEventListener("DOMContentLoaded", function() {
            const paymentBtn = document.getElementById("paymentbtn");

            if (paymentBtn) {
                paymentBtn.addEventListener("click", function(e) {
                    @if ($order->items->count() === 0)
                        // sem itens → só toast
                        showToast("Este pedido não pode ser pago pois não possui itens.", "error");
                    @else
                        // com itens → abre modal manualmente
                        const paymentModal = new bootstrap.Modal(document.getElementById("paymentModal"));
                        paymentModal.show();
                    @endif
                });
            }
        });

        // Payment method handler
        const paymentMethodSelect = document.getElementById('payment_method');
        if (paymentMethodSelect) {
            paymentMethodSelect.addEventListener('change', function() {
                const cashFields = document.getElementById('cashFields');
                if (cashFields) {
                    cashFields.style.display = this.value === 'cash' ? 'block' : 'none';
                    if (this.value === 'cash') {
                        document.getElementById('cash_amount')?.focus();
                    }
                }
            });
        }

        // Calculate change
        const cashAmountInput = document.getElementById('cash_amount');
        if (cashAmountInput) {
            cashAmountInput.addEventListener('input', function() {
                const received = parseFloat(this.value) || 0;
                const total = {{ $order->total_amount }};
                const change = received - total;
                const changeAmountInput = document.getElementById('change_amount');
                if (changeAmountInput) {
                    changeAmountInput.value = change >= 0 ? change.toFixed(2) : '0.00';
                }
            });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('quickMenuSearch').focus();
            }

            if (e.altKey && e.key === 'p') {
                e.preventDefault();
                window.open('{{ route('orders.print', $order) }}', '_blank');
            }

            if (e.key === 'Escape') {
                const searchInput = document.getElementById('quickMenuSearch');
                if (searchInput && searchInput === document.activeElement) {
                    clearSearch();
                    searchInput.blur();
                }
            }
        });
    </script>
@endpush
