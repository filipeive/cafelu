@extends('layouts.app')

@section('title', 'Editar Pedido')
@section('title-icon', 'mdi-receipt-edit')
@section('page-title', 'Editar Pedido')
@section('page-subtitle', 'Gerenciar itens e informações do pedido')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="order-header mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">
                    <i class="mdi mdi-receipt text-primary me-2"></i>
                    Pedido #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                </h1>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge {{ get_status_class_staradmins($order->status) }} fs-6">
                        {{ ucfirst(trans($order->status)) }}
                    </span>
                    @if($order->is_paid)
                    <span class="badge bg-success fs-6">
                        <i class="mdi mdi-check-circle me-1"></i>Pago
                    </span>
                    @endif
                </div>
            </div>
            
            <div class="order-actions">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="mdi mdi-arrow-left me-1"></i>Voltar
                </a>
                <button class="btn btn-info btn-sm ms-2" onclick="printRecibo({{ $order->id }})">
                    <i class="mdi mdi-printer me-1"></i>Imprimir
                </button>
                @if($order->status == 'active')
                <button type="button" class="btn btn-danger btn-sm ms-2" data-bs-toggle="modal" 
                        data-bs-target="#cancelOrderModal">
                    <i class="mdi mdi-cancel me-1"></i>Cancelar
                </button>
                @endif
            </div>
        </div>

        <!-- Order Info Cards -->
        <div class="row g-3">
            <div class="col-xl-3 col-md-6">
                <div class="info-card">
                    <div class="info-icon bg-primary">
                        <i class="mdi mdi-clock"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Data/Hora</div>
                        <div class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="info-card">
                    <div class="info-icon bg-success">
                        <i class="mdi mdi-cash"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Total</div>
                        <div class="info-value">MZN {{ number_format($order->total_amount, 2, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="info-card">
                    <div class="info-icon bg-info">
                        <i class="mdi mdi-table-furniture"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Mesa</div>
                        <div class="info-value">
                            @if($order->table)
                                Mesa {{ $order->table->number }}
                                @if($order->table->group_id)
                                <small class="text-info">(Unida)</small>
                                @endif
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="info-card">
                    <div class="info-icon bg-warning">
                        <i class="mdi mdi-account"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Cliente</div>
                        <div class="info-value">{{ $order->customer_name ?: 'Não informado' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="mdi mdi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="mdi mdi-alert-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="mdi mdi-alert-circle me-2"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Left Column - Order Items -->
        <div class="col-lg-8">
            <!-- Order Items Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-cart-outline text-primary me-2"></i>
                        Itens do Pedido
                        <span class="badge bg-primary ms-2">{{ $order->items->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($order->items->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th class="text-center" width="100">Qtd</th>
                                    <th class="text-end" width="120">Preço Unit.</th>
                                    <th class="text-end" width="120">Subtotal</th>
                                    <th class="text-center" width="150">Status</th>
                                    <th class="text-center" width="80">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <strong class="d-block">
                                                    {{ $item->product->name ?? 'Produto não encontrado' }}
                                                </strong>
                                                @if($item->notes)
                                                <small class="text-muted">
                                                    <i class="mdi mdi-note-text me-1"></i>{{ $item->notes }}
                                                </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-end">
                                        MZN {{ number_format($item->unit_price, 2, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold">
                                        MZN {{ number_format($item->total_price, 2, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('orders.update-item-status', $item) }}" method="POST" class="status-form">
                                            @csrf
                                            <select name="status" class="form-select form-select-sm" 
                                                    onchange="this.form.submit()" 
                                                    {{ $order->status === 'completed' ? 'disabled' : '' }}>
                                                <option value="pending" @selected($item->status === 'pending')>Pendente</option>
                                                <option value="preparing" @selected($item->status === 'preparing')>Preparando</option>
                                                <option value="ready" @selected($item->status === 'ready')>Pronto</option>
                                                <option value="delivered" @selected($item->status === 'delivered')>Entregue</option>
                                                <option value="cancelled" @selected($item->status === 'cancelled')>Cancelado</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        @if($order->status === 'active')
                                        <form action="{{ route('orders.remove-item', $item) }}" method="POST" 
                                              class="d-inline remove-item-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn"
                                                    data-item-name="{{ $item->product->name ?? 'Item' }}">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="mdi mdi-cart-off text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2 mb-0">Nenhum item adicionado ao pedido</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Menu Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-plus-circle text-success me-2"></i>
                        Adicionar Itens
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Search Bar -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="mdi mdi-magnify text-muted"></i>
                                </span>
                                <input type="text" class="form-control" id="productSearch" 
                                       placeholder="Buscar produtos...">
                            </div>
                        </div>
                    </div>

                    <!-- Category Tabs -->
                    <ul class="nav nav-tabs mb-3" id="categoryTabs" role="tablist">
                        @foreach($categories as $category)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                    id="tab-{{ $category->id }}" data-bs-toggle="tab" 
                                    data-bs-target="#content-{{ $category->id }}" type="button">
                                {{ $category->name }}
                                <span class="badge bg-primary ms-1">{{ $category->products->count() }}</span>
                            </button>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Products Grid -->
                    <div class="tab-content" id="categoryTabsContent">
                        @foreach($categories as $category)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                             id="content-{{ $category->id }}" role="tabpanel">
                            <div class="row g-2 product-grid">
                                @foreach($category->products as $product)
                                <div class="col-xl-3 col-lg-4 col-md-6 product-item">
                                    <div class="product-card" data-product-id="{{ $product->id }}">
                                        <div class="product-body">
                                            <h6 class="product-name">{{ $product->name }}</h6>
                                            <div class="product-price">
                                                MZN {{ number_format($product->price, 2, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="product-actions">
                                            <button class="btn btn-sm btn-primary add-product-btn"
                                                    data-product-id="{{ $product->id }}">
                                                <i class="mdi mdi-plus me-1"></i>Adicionar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Order Management -->
        <div class="col-lg-4">
            <!-- Customer Information Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-account-edit text-primary me-2"></i>
                        Informações do Pedido
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Nome do Cliente</label>
                            <input type="text" name="customer_name" class="form-control" 
                                   value="{{ $order->customer_name }}" placeholder="Nome do cliente">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Observações</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                      placeholder="Observações do pedido">{{ $order->notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="mdi mdi-content-save me-1"></i>Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-cog text-primary me-2"></i>
                        Ações do Pedido
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($order->status === 'active')
                        <form action="{{ route('orders.complete', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg w-100 mb-2">
                                <i class="mdi mdi-check-circle me-1"></i>Finalizar Pedido
                            </button>
                        </form>
                        @endif

                        @if($order->status === 'completed' && !$order->is_paid)
                        <button type="button" class="btn btn-warning btn-lg w-100 mb-2" 
                                data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <i class="mdi mdi-cash-multiple me-1"></i>Registrar Pagamento
                        </button>
                        @endif

                        @if($order->is_paid)
                        <div class="alert alert-success text-center">
                            <i class="mdi mdi-check-circle me-1"></i>
                            <strong>Pedido Pago</strong>
                        </div>
                        @endif
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary mt-3 p-3 bg-light rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold">Subtotal:</span>
                            <span>MZN {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold">Taxas:</span>
                            <span>MZN 0,00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0 fw-bold">Total:</span>
                            <span class="h5 mb-0 text-primary">
                                MZN {{ number_format($order->total_amount, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
@if($order->status == 'active')
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('orders.cancel', $order) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="mdi mdi-cancel text-danger me-2"></i>
                        Cancelar Pedido
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        Esta ação não pode ser desfeita. Todos os itens serão removidos.
                    </div>
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Motivo do Cancelamento</label>
                        <textarea class="form-control" id="cancelReason" name="notes" rows="3" required 
                                  placeholder="Descreva o motivo do cancelamento..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Cancelamento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Payment Modal -->
@if($order->status === 'completed' && !$order->is_paid)
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
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Total a Pagar:</strong>
                            <span class="h5 mb-0">MZN {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Método de Pagamento</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">Selecione...</option>
                            <option value="cash">Dinheiro</option>
                            <option value="card">Cartão</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="emola">E-Mola</option>
                            <option value="mkesh">M-Kesh</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observações</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Observações do pagamento..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Confirmar Pagamento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
/* Custom Styles for Order Edit Page */
.order-header {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.info-card {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid var(--primary-color);
}

.info-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
    font-size: 1.25rem;
}

.info-content .info-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.info-content .info-value {
    font-weight: 600;
    color: #343a40;
}

.product-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.product-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.product-name {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.product-price {
    color: var(--success-color);
    font-weight: 600;
    font-size: 1.1rem;
}

.order-summary {
    background: #f8f9fa !important;
    border-left: 4px solid var(--primary-color);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .order-header {
        padding: 1rem;
    }
    
    .info-card {
        margin-bottom: 1rem;
    }
    
    .product-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Product search functionality
    const productSearch = document.getElementById('productSearch');
    if (productSearch) {
        productSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.product-item').forEach(item => {
                const productName = item.querySelector('.product-name').textContent.toLowerCase();
                item.style.display = productName.includes(searchTerm) ? 'block' : 'none';
            });
        });
    }

    // Add product functionality
    document.querySelectorAll('.add-product-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            addProductToOrder(productId);
        });
    });

    // Remove item confirmation
    document.querySelectorAll('.remove-item-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemName = this.dataset.itemName;
            if (confirm(`Deseja remover "${itemName}" do pedido?`)) {
                this.closest('form').submit();
            }
        });
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('show')) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });
});

function addProductToOrder(productId) {
    if (!confirm('Adicionar este produto ao pedido?')) {
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ route('orders.add-item', $order->id) }}`;

    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';

    const productInput = document.createElement('input');
    productInput.type = 'hidden';
    productInput.name = 'product_id';
    productInput.value = productId;

    const quantityInput = document.createElement('input');
    quantityInput.type = 'hidden';
    quantityInput.name = 'quantity';
    quantityInput.value = 1;

    form.appendChild(csrf);
    form.appendChild(productInput);
    form.appendChild(quantityInput);
    document.body.appendChild(form);
    form.submit();
}

function printRecibo(orderId) {
    window.open(`/orders/${orderId}/print`, '_blank');
}
</script>
@endpush