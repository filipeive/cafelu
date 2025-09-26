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
                    <div>
                        <h2 class="mb-1 fw-bold text-dark">
                            <i class="mdi mdi-receipt-text text-primary me-2"></i>
                            Pedido #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                        </h2>
                        <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <span class="badge {{ get_status_class_staradmins($order->status) }}">
                        {{ ucfirst(trans($order->status)) }}
                    </span>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="btn-group" role="group">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="mdi mdi-arrow-left me-1"></i> Voltar
                    </a>
                    <button class="btn btn-outline-primary btn-sm" onclick="printRecibo({{ $order->id }})">
                        <i class="mdi mdi-printer me-1"></i> Imprimir
                    </button>
                    @if($order->status == 'active')
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
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
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="mdi mdi-format-list-bulleted text-primary me-2"></i>
                        Itens do Pedido
                    </h5>
                    <span class="badge badge-primary">{{ $order->items->count() }} itens</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produto</th>
                                <th width="80" class="text-center">Qtd</th>
                                <th width="100">Preço Unit.</th>
                                <th width="100">Total</th>
                                <th width="140">Status</th>
                                <th width="60" class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order->items as $item)
                            <tr>
                                <td>
                                    <div>
                                        <strong class="text-dark">
                                            {{ $item->product->name ?? 'Produto removido' }}
                                        </strong>
                                        @if($item->notes)
                                        <div class="small text-muted">
                                            <i class="mdi mdi-note-text me-1"></i>{{ $item->notes }}
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info">{{ $item->quantity }}</span>
                                </td>
                                <td class="fw-semibold">{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                <td class="fw-bold text-success">{{ number_format($item->total_price, 2, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('orders.update-item-status', $item) }}" method="POST" class="status-form">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm" onchange="submitWithLoading(this.form)">
                                            <option value="pending" @selected($item->status === 'pending')>Pendente</option>
                                            <option value="preparing" @selected($item->status === 'preparing')>Preparando</option>
                                            <option value="ready" @selected($item->status === 'ready')>Pronto</option>
                                            <option value="delivered" @selected($item->status === 'delivered')>Entregue</option>
                                            <option value="cancelled" @selected($item->status === 'cancelled')>Cancelado</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger" onclick="removeItem({{ $item->id }})" title="Remover item">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="mdi mdi-cart-outline d-block mb-2" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mb-0">Nenhum item no pedido</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Menu -->
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0 fw-bold">
                                <i class="mdi mdi-food text-primary me-2"></i>
                                Menu Rápido
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="search-container">
                                <input type="text" class="form-control" id="quickMenuSearch" placeholder="Buscar produtos...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Category Tabs -->
                    <ul class="nav nav-tabs mb-4">
                        @foreach ($categories as $category)
                        <li class="nav-item">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                    data-category="{{ $category->id }}"
                                    onclick="showCategory({{ $category->id }}, this)">
                                {{ $category->name }}
                                <span class="badge badge-success ms-2">{{ $category->products->count() }}</span>
                            </button>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Products Grid -->
                    @foreach ($categories as $category)
                    <div class="row category-products" 
                         data-category="{{ $category->id }}" 
                         style="{{ !$loop->first ? 'display: none;' : '' }}">
                        @foreach ($category->products as $product)
                        <div class="col-md-4 col-lg-3 mb-3">
                            <div class="card h-100 product-card" onclick="addProduct({{ $product->id }})" data-product-name="{{ $product->name }}">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $product->name }}</h6>
                                        @if(($product->stock_quantity ?? 0) <= 5)
                                        <span class="badge badge-warning">Baixo</span>
                                        @endif
                                    </div>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-success">{{ number_format($product->price, 2, ',', '.') }} MZN</span>
                                            <button class="btn btn-primary btn-sm">
                                                <i class="mdi mdi-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column - Order Info & Actions -->
        <div class="col-xl-4">
            <!-- Order Information -->
            <div class="stats-card info mb-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="mdi mdi-information-outline text-primary me-2"></i>
                    Informações do Pedido
                </h6>
                
                <div class="d-flex align-items-center mb-2">
                    <i class="mdi mdi-clock-outline text-primary me-2"></i>
                    <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                
                @if($order->table)
                <div class="d-flex align-items-center mb-2">
                    <i class="mdi mdi-table-furniture text-primary me-2"></i>
                    <span>Mesa {{ $order->table->number }}</span>
                </div>
                @endif
                
                <div class="d-flex align-items-center mb-2">
                    <i class="mdi mdi-account text-primary me-2"></i>
                    <span>{{ $order->customer_name ?: 'Cliente não informado' }}</span>
                </div>
                
                @if($order->notes)
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-note-text text-primary me-2"></i>
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
                    <form action="{{ route('orders.update', $order) }}" method="POST" id="customerForm" onsubmit="submitWithLoading(this)">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nome do Cliente</label>
                            <input type="text" name="customer_name" class="form-control" 
                                   value="{{ $order->customer_name }}" 
                                   placeholder="Digite o nome">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Observações</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                      placeholder="Observações do pedido">{{ $order->notes }}</textarea>
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
                <div class="text-center mb-4">
                    <h3 class="text-success mb-1">{{ number_format($order->total_amount, 2, ',', '.') }} MZN</h3>
                    <small class="text-muted">Total do Pedido</small>
                </div>
                
                <div class="d-grid gap-2">
                    @if($order->status !== 'completed')
                    <form action="{{ route('orders.complete', $order) }}" method="POST" onsubmit="submitWithLoading(this)">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="mdi mdi-check-circle me-2"></i>
                            Finalizar Pedido
                        </button>
                    </form>
                    @endif

                    @if($order->status === 'completed' && !$order->is_paid)
                    <button type="button" class="btn btn-success w-100" 
                            data-bs-toggle="modal" data-bs-target="#paymentModal">
                        <i class="mdi mdi-cash-multiple me-2"></i>
                        Registrar Pagamento
                    </button>
                    @endif
                    
                    @if($order->is_paid)
                    <div class="alert alert-success text-center mb-0">
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
@if($order->status === 'completed' && !$order->is_paid)
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('orders.pay', $order) }}" method="POST" onsubmit="submitWithLoading(this)">
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
                        </select>
                    </div>

                    <div id="cashFields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Valor Recebido</label>
                            <div class="input-group">
                                <span class="input-group-text">MZN</span>
                                <input type="number" class="form-control" id="cash_amount" 
                                       name="cash_amount" step="0.01" min="{{ $order->total_amount }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Troco</label>
                            <div class="input-group">
                                <span class="input-group-text">MZN</span>
                                <input type="text" class="form-control" id="change_amount" readonly>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="amount_paid" value="{{ $order->total_amount }}">

                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between">
                            <strong>Total a Pagar:</strong>
                            <span class="fw-bold">{{ number_format($order->total_amount, 2, ',', '.') }} MZN</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
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
@if($order->status == 'active')
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="submitWithLoading(this)">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        Cancelar Pedido
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Voltar</button>
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

<!-- Hidden forms for item removal -->
@foreach($order->items as $item)
<form id="remove-item-{{ $item->id }}" action="{{ route('orders.remove-item', $item) }}" method="POST" style="display: none;">
    @csrf
</form>
@endforeach
@endsection

@push('scripts')
<script>
// Show toasts for Laravel session messages
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        showToast('{{ session("success") }}', 'success');
    @endif

    @if(session('error'))
        showToast('{{ session("error") }}', 'error');
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            showToast('{{ $error }}', 'error');
        @endforeach
    @endif
});

// Toast function
function showToast(message, type = 'success') {
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
        <div class="toast ${colorMap[type]} border-0" role="alert" data-bs-delay="4000">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="${iconMap[type]} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    const toastElement = toastContainer.lastElementChild;
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

// Category Management
function showCategory(categoryId, tabElement) {
    document.querySelectorAll('.nav-link').forEach(tab => tab.classList.remove('active'));
    tabElement.classList.add('active');
    
    document.querySelectorAll('.category-products').forEach(products => {
        products.style.display = products.dataset.category == categoryId ? 'flex' : 'none';
    });
}

// Product Search
document.getElementById('quickMenuSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        const productName = card.dataset.productName.toLowerCase();
        const parentGrid = card.closest('.category-products');
        
        if (productName.includes(searchTerm)) {
            card.parentElement.style.display = 'block';
            if (searchTerm !== '') {
                parentGrid.style.display = 'flex';
            }
        } else {
            card.parentElement.style.display = 'none';
        }
    });
    
    if (searchTerm === '') {
        document.querySelectorAll('.category-products').forEach(products => {
            products.style.display = 'none';
        });
        document.querySelector('.nav-link.active').click();
    }
});

// Add Product - NO confirmation (better UX)
function addProduct(productId) {
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
    
    // Show immediate feedback
    showToast('Produto adicionado ao pedido!', 'success');
}

// Remove Item - WITH confirmation (for safety)
function removeItem(itemId) {
    if (confirm('Tem certeza que deseja remover este item do pedido?')) {
        document.getElementById(`remove-item-${itemId}`).submit();
    }
}

// Submit form with loading state
function submitWithLoading(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn && !submitBtn.disabled) {
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Processando...';
        submitBtn.disabled = true;
        
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    }
    
    if (typeof form.submit === 'function') {
        form.submit();
    }
    return true;
}

// Payment Method Handler
document.getElementById('payment_method').addEventListener('change', function() {
    const cashFields = document.getElementById('cashFields');
    cashFields.style.display = this.value === 'cash' ? 'block' : 'none';
    
    if (this.value === 'cash') {
        document.getElementById('cash_amount').focus();
    }
});

// Calculate Change
document.getElementById('cash_amount').addEventListener('input', function() {
    const received = parseFloat(this.value) || 0;
    const total = {{ $order->total_amount }};
    const change = received - total;
    document.getElementById('change_amount').value = change >= 0 ? change.toFixed(2) : '0.00';
});

// Print Function
function printRecibo(orderId) {
    const printWindow = window.open(`{{ route('orders.print', $order) }}`, '_blank');
    if (printWindow) {
        printWindow.focus();
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.altKey && e.key === 'p') {
        e.preventDefault();
        printRecibo({{ $order->id }});
    }
    
    if (e.altKey && e.key === 'f') {
        e.preventDefault();
        document.getElementById('quickMenuSearch').focus();
    }
    
    if (e.key === 'Escape') {
        const searchInput = document.getElementById('quickMenuSearch');
        if (searchInput.value !== '') {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
        }
    }
});
</script>
@endpush