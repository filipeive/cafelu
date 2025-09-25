@extends('layouts.app')

@section('title', 'POS - Sistema de Vendas')
@section('title-icon', 'point_of_sale')
@section('page-title', 'PDV - Ponto de Venda')

@section('breadcrumbs')
<li class="breadcrumb-item active">PDV</li>
@endsection

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endpush

@section('content')
<div class="pos-wrapper">
    <div class="container-wrapper">
        <div class="row g-4">
            <!-- Products Area -->
            <div class="col-lg-8">
                <!-- Search Section -->
                <div class="search-section">
                    <div class="d-flex gap-3 align-items-center">
                        <div class="search-input-wrapper">
                            <span class="material-icons">search</span>
                            <input type="text" class="form-control search-input" 
                                   placeholder="Pesquisar produtos... (F3)" 
                                   id="searchInput" 
                                   onkeyup="filterProducts()" 
                                   autocomplete="off"
                                   value="{{ $searchTerm ?? '' }}">
                        </div>
                        <select id="categorySelect" class="form-select search-input" 
                                style="width: auto; min-width: 200px;" 
                                onchange="filterProducts()">
                            <option value="">Todas as Categorias</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category['id'] }}" 
                                    {{ ($categoryFilter == $category['id']) ? 'selected' : '' }}>
                                {{ htmlspecialchars($category['name']) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Category Filter Buttons -->
                <div class="category-filters">
                    <button class="category-btn {{ !$categoryFilter ? 'active' : '' }}" 
                            data-category="all">
                        <span class="material-icons">apps</span>
                        Todos
                    </button>
                    @foreach ($categories as $category)
                    <button class="category-btn {{ ($categoryFilter == $category['id']) ? 'active' : '' }}" 
                            data-category="{{ $category['id'] }}">
                        @php
                            $categoryIcons = [
                                'Pratos Principais' => 'restaurant',
                                'Bebidas' => 'local_bar',
                                'Sobremesas' => 'cake',
                                'Entradas' => 'restaurant_menu',
                                'Lanches' => 'lunch_dining',
                                'Petiscos' => 'tapas',
                                'Vinhos' => 'wine_bar',
                                'Cervejas' => 'sports_bar',
                                'Cocktails' => 'local_bar',
                                'Cafés' => 'local_cafe',
                            ];
                            $icon = $categoryIcons[$category['name']] ?? 'category';
                        @endphp
                        <span class="material-icons">{{ $icon }}</span>
                        {{ htmlspecialchars($category['name']) }}
                    </button>
                    @endforeach
                </div>

                <!-- Products Grid -->
                <div class="row g-3" id="productsGrid">
                    @forelse ($products as $product)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 product-item" 
                         data-category="{{ $product['category_id'] }}"
                         data-name="{{ strtolower($product['name']) }}">
                        <div class="product-card" 
                             onclick="POSSystem.addToCart({{ json_encode([
                                'id' => $product['id'],
                                'name' => $product['name'],
                                'price' => $product['price'],
                                'stock_quantity' => $product['stock_quantity'] ?? 0
                             ]) }})"
                             tabindex="0"
                             role="button"
                             aria-label="Adicionar {{ $product['name'] }} ao carrinho">
                            
                            @if(isset($product['stock_quantity']) && $product['stock_quantity'] <= 5)
                                <div class="stock-warning">
                                    Estoque: {{ $product['stock_quantity'] }}
                                </div>
                            @endif
                            
                            <div class="product-icon">
                                @if($product['category_id'])
                                    @php
                                        $productIcons = [
                                            'Pratos Principais' => 'restaurant',
                                            'Bebidas' => 'local_bar',
                                            'Sobremesas' => 'cake',
                                            'Entradas' => 'restaurant_menu',
                                            'Lanches' => 'lunch_dining',
                                            'Petiscos' => 'tapas',
                                            'Vinhos' => 'wine_bar',
                                            'Cervejas' => 'sports_bar',
                                            'Cocktails' => 'local_bar',
                                            'Cafés' => 'local_cafe',
                                        ];
                                        $categoryName = $categories->firstWhere('id', $product['category_id'])['name'] ?? '';
                                        $productIcon = $productIcons[$categoryName] ?? 'fastfood';
                                    @endphp
                                    <span class="material-icons">{{ $productIcon }}</span>
                                @else
                                    <span class="material-icons">fastfood</span>
                                @endif
                            </div>
                            
                            <div class="card-body">
                                <h5 class="card-title" title="{{ htmlspecialchars($product['name']) }}">
                                    {{ htmlspecialchars($product['name']) }}
                                </h5>
                                <p class="price">
                                    <span class="material-icons">monetization_on</span>
                                    MZN {{ number_format($product['price'], 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <span class="material-icons" style="font-size: 4rem; color: var(--primary-color); opacity: 0.5;">
                                    restaurant_menu
                                </span>
                                <h5 class="mt-3">Nenhum produto disponível</h5>
                                <p>Cadastre produtos para começar as vendas</p>
                                @if(auth()->user()->role === 'admin')
                                <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">
                                    <span class="material-icons me-2">add</span>
                                    Adicionar Produto
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Main Action Buttons (Below Products Grid) -->
                <div class="main-action-buttons">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <button id="btnFinalizeOrderMain" 
                                    class="btn-finalize-main w-100" 
                                    onclick="POSSystem.processSale()" 
                                    disabled
                                    title="Finalizar pedido (Ctrl+Enter)">
                                <span class="material-icons">check_circle</span>
                                <span>Finalizar Pedido</span>
                                <small class="d-block">Confirmar venda e gerar recibo</small>
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn-preview-main w-100" 
                                    onclick="POSSystem.previewReceipt()"
                                    title="Pré-visualizar recibo (Ctrl+P)">
                                <span class="material-icons">receipt_long</span>
                                <span>Pré-visualizar Recibo</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Load More Products -->
                @if($products->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    <button class="btn btn-outline-primary" onclick="loadMoreProducts()" id="loadMoreBtn">
                        <span class="material-icons me-2">refresh</span>
                        Carregar Mais
                    </button>
                </div>
                @endif
            </div>

            <!-- Cart Area -->
            <div class="col-lg-4">
                <div class="cart-wrapper">
                    <!-- Cart Header -->
                    <div class="cart-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <span class="material-icons">shopping_cart</span>
                                Pedido Atual
                            </h4>
                            <button class="btn btn-outline-light btn-sm" 
                                    onclick="POSSystem.resetSale()" 
                                    title="Limpar Carrinho (F2)">
                                <span class="material-icons me-1">clear_all</span> 
                                Limpar
                            </button>
                        </div>
                        <div class="mt-2 text-center">
                            <small class="opacity-75">
                                <span class="material-icons me-1" style="font-size: 1rem;">schedule</span>
                                <span id="orderTime">{{ date('d/m/Y H:i') }}</span>
                            </small>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div class="cart-items" id="cartItems">
                        <div class="empty-cart">
                            <span class="material-icons">shopping_cart</span>
                            <p class="mb-0">Carrinho vazio</p>
                            <small>Adicione produtos para começar</small>
                        </div>
                    </div>

                    <!-- Cart Total -->
                    <div class="cart-total">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">
                                <span class="material-icons me-1" style="font-size: 1rem;">inventory</span>
                                Itens:
                            </span>
                            <span id="itemCount" class="fw-medium">0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Subtotal:</span>
                            <span id="subtotal" class="fw-medium">MZN 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">Total:</span>
                            <span id="total" class="h5 mb-0 text-primary">MZN 0.00</span>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="payment-methods">
                        <h5 class="mb-3">
                            <span class="material-icons me-2">payment</span>
                            Método de Pagamento
                        </h5>
                        
                        <!-- Quick Payment Buttons -->
                        <div class="mb-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <button class="btn btn-outline-success btn-sm w-100" 
                                            onclick="POSSystem.setQuickCashPayment()" 
                                            title="Pagamento exato em dinheiro (F4)">
                                        <span class="material-icons me-1">payments</span>
                                        Dinheiro Exato
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-outline-info btn-sm w-100" 
                                            onclick="clearPaymentInputs()" 
                                            title="Limpar pagamentos">
                                        <span class="material-icons me-1">refresh</span>
                                        Limpar
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Methods Grid -->
                        <div class="payment-methods-grid">
                            <div class="payment-method-item" onclick="POSSystem.selectPayment('cash')">
                                <div class="payment-method-content">
                                    <span class="material-icons payment-icon">payments</span>
                                    <span class="payment-label">Dinheiro</span>
                                    <input type="number" 
                                           class="form-control payment-input" 
                                           id="cashAmount"
                                           placeholder="0.00" 
                                           min="0" 
                                           step="0.01" 
                                           onchange="calculateChange()"
                                           onclick="event.stopPropagation()">
                                </div>
                            </div>
                            
                            <div class="payment-method-item" onclick="POSSystem.selectPayment('card')">
                                <div class="payment-method-content">
                                    <span class="material-icons payment-icon">credit_card</span>
                                    <span class="payment-label">Cartão</span>
                                    <input type="number" 
                                           class="form-control payment-input" 
                                           id="cardAmount"
                                           placeholder="0.00" 
                                           min="0" 
                                           step="0.01" 
                                           onchange="calculateChange()"
                                           onclick="event.stopPropagation()">
                                </div>
                            </div>
                            
                            <div class="payment-method-item" onclick="POSSystem.selectPayment('mpesa')">
                                <div class="payment-method-content">
                                    <span class="material-icons payment-icon">phone_android</span>
                                    <span class="payment-label">M-Pesa</span>
                                    <input type="number" 
                                           class="form-control payment-input" 
                                           id="mpesaAmount"
                                           placeholder="0.00" 
                                           min="0" 
                                           step="0.01" 
                                           onchange="calculateChange()"
                                           onclick="event.stopPropagation()">
                                </div>
                            </div>
                            
                            <div class="payment-method-item" onclick="POSSystem.selectPayment('emola')">
                                <div class="payment-method-content">
                                    <span class="material-icons payment-icon">account_balance_wallet</span>
                                    <span class="payment-label">E-mola</span>
                                    <input type="number" 
                                           class="form-control payment-input" 
                                           id="emolaAmount"
                                           placeholder="0.00" 
                                           min="0" 
                                           step="0.01" 
                                           onchange="calculateChange()"
                                           onclick="event.stopPropagation()">
                                </div>
                            </div>
                        </div>

                        <!-- Change Section -->
                        <div class="change-section">
                            <label class="form-label">
                                <span class="material-icons">receipt</span>
                                <span class="fw-semibold">Troco:</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="changeAmount" 
                                   value="MZN 0.00"
                                   readonly>
                            <small class="text-muted d-block mt-1 text-center">
                                Troco calculado automaticamente
                            </small>
                        </div>

                        <!-- Payment Summary -->
                        <div class="mt-3" id="paymentSummary" style="display: none;">
                            <div class="bg-info bg-opacity-10 border border-info rounded p-2">
                                <h6 class="text-info mb-2">
                                    <span class="material-icons me-1">info</span>
                                    Resumo do Pagamento
                                </h6>
                                <div id="paymentDetails" class="small"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button id="btnFinalizeOrder" 
                                class="btn-finalize mb-3" 
                                onclick="POSSystem.processSale()" 
                                disabled
                                title="Finalizar pedido (Ctrl+Enter)">
                            <span class="material-icons">check_circle</span>
                            Finalizar Pedido
                        </button>
                        
                        <div class="row g-2">
                            <div class="col-4">
                                <button class="btn btn-outline-secondary btn-sm w-100" 
                                        onclick="POSSystem.showPOSHelp()"
                                        title="Mostrar ajuda (F1)">
                                    <span class="material-icons">help</span>
                                    Ajuda
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-warning btn-sm w-100" 
                                        onclick="POSSystem.previewReceipt()"
                                        title="Pré-visualizar recibo (Ctrl+P)">
                                    <span class="material-icons">receipt_long</span>
                                    Recibo
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-danger btn-sm w-100" 
                                        onclick="POSSystem.resetSale()"
                                        title="Limpar carrinho (F2)">
                                    <span class="material-icons">clear_all</span>
                                    Limpar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="border-top mt-3 pt-3 px-3">
                        <div class="row g-2 text-center">
                            <div class="col-3">
                                <div class="small text-muted d-flex align-items-center justify-content-center">
                                    <span class="material-icons me-1" style="font-size: 1rem;">inventory</span>
                                    Produtos
                                </div>
                                <div class="fw-bold">{{ $products->count() }}</div>
                            </div>
                            <div class="col-3">
                                <div class="small text-muted d-flex align-items-center justify-content-center">
                                    <span class="material-icons me-1" style="font-size: 1rem;">category</span>
                                    Categorias
                                </div>
                                <div class="fw-bold">{{ $categories->count() }}</div>
                            </div>
                            <div class="col-3">
                                <div class="small text-muted d-flex align-items-center justify-content-center">
                                    <span class="material-icons me-1" style="font-size: 1rem;">today</span>
                                    Hoje
                                </div>
                                <div class="fw-bold" id="todaysSales">
                                    @php
                                        $todaysSales = \App\Models\Sale::whereDate('created_at', today())->count();
                                    @endphp
                                    {{ $todaysSales }}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="small text-muted d-flex align-items-center justify-content-center">
                                    <span class="material-icons me-1" style="font-size: 1rem;">schedule</span>
                                    Online
                                </div>
                                <div class="fw-bold text-success">
                                    <span class="material-icons" style="font-size: 1rem;">check_circle</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="d-none">
    <!-- Will be created dynamically by JavaScript -->
</div>
@endsection

@push('meta')
<meta name="user-name" content="{{ auth()->user()->name ?? 'Sistema' }}">
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/pos/pos.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('assets/pos/pos.js') }}"></script>
<script>
// Additional POS specific functions for this template
document.addEventListener('DOMContentLoaded', function() {
    // Set initial search term if provided
    @if(isset($searchTerm) && $searchTerm)
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.value = '{{ addslashes($searchTerm) }}';
        POSSystem.filterProducts();
    }
    @endif
    
    // Enhanced cart total function with item count
    const originalUpdateCartTotal = window.POSSystem?.updateCartTotal || updateCartTotal;
    if (window.POSSystem) {
        window.POSSystem.updateCartTotal = function() {
            originalUpdateCartTotal();
            
            // Update item count in cart total section
            const itemCount = cart.reduce((count, item) => count + item.quantity, 0);
            const itemCountElement = document.getElementById('itemCount');
            if (itemCountElement) {
                itemCountElement.textContent = itemCount;
            }
            
            updatePaymentSummary();
        };
    }
    
    // Setup keyboard navigation for product cards
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
    
    // Auto-update time display
    function updateCurrentTime() {
        const now = new Date();
        const timeString = now.toLocaleDateString('pt-BR') + ' ' + 
                          now.toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'});
        const orderTimeElement = document.getElementById('orderTime');
        if (orderTimeElement) {
            orderTimeElement.textContent = timeString;
        }
    }
    
    // Update time every minute
    setInterval(updateCurrentTime, 60000);
    
    // Welcome message with system status
    setTimeout(() => {
        const productCount = {{ $products->count() }};
        const categoryCount = {{ $categories->count() }};
        showToast(`Sistema carregado: ${productCount} produtos, ${categoryCount} categorias. Pressione F1 para ajuda.`, 'info');
    }, 1000);
});

// Load more products functionality
let currentPage = 1;
function loadMoreProducts() {
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.disabled = true;
        loadMoreBtn.innerHTML = '<span class="material-icons me-2">hourglass_empty</span>Carregando...';
        
        // In a real implementation, this would make an AJAX request
        // For now, we'll simulate the loading
        setTimeout(() => {
            loadMoreBtn.disabled = false;
            loadMoreBtn.innerHTML = '<span class="material-icons me-2">refresh</span>Carregar Mais';
            showToast('Funcionalidade em desenvolvimento', 'info');
        }, 1000);
    }
}

// Enhanced payment functions
function clearPaymentInputs() {
    POSSystem.clearPaymentInputs?.() || clearPaymentInputs();
}

function calculateChange() {
    return POSSystem.calculateChange?.() || 0;
}

function selectPayment(method) {
    POSSystem.selectPayment?.(method);
}

function updatePaymentSummary() {
    POSSystem.updatePaymentSummary?.();
}

// Accessibility improvements
document.addEventListener('keydown', function(e) {
    // ESC key to close modals or clear search
    if (e.key === 'Escape') {
        const activeModal = document.querySelector('.modal.show');
        if (activeModal) {
            bootstrap.Modal.getInstance(activeModal)?.hide();
        }
    }
});
</script>
@endpush