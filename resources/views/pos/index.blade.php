@extends('layouts.app')


@section('page-title', 'PDV')
@section('title-icon', 'mdi-cart')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">POS - Sistema de Vendas</li>
@endsection

@section('content')
    <div class="pos-wrapper container-wrapper">
        <!-- Adicionar formulário hidden -->
        <form id="checkoutForm" action="{{ route('pos.completeCheckout') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="items" id="formItems">
            <input type="hidden" name="cashPayment" id="formCash">
            <input type="hidden" name="cardPayment" id="formCard">
            <input type="hidden" name="mpesaPayment" id="formMpesa">
            <input type="hidden" name="emolaPayment" id="formEmola">
        </form>
        <div class="row g-4">
            <!-- Products Area -->
            <div class="col-lg-8">
                <div class="search-section">
                    <div class="d-flex gap-3 align-items-center">
                        <div class="search-input-wrapper">
                            <span class="material-icons">search</span>
                            <input type="text" class="form-control search-input" placeholder="Pesquisar produtos... (F3)"
                                id="searchInput" onkeyup="filterProducts()" autocomplete="off"
                                value="{{ $searchTerm ?? '' }}">
                        </div>
                        <select id="categorySelect" class="form-select search-input" style="width: auto; min-width: 200px;"
                            onchange="filterProducts()">
                            <option value="">Todas as Categorias</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category['id'] }}"
                                    {{ $categoryFilter == $category['id'] ? 'selected' : '' }}>
                                    {{ htmlspecialchars($category['name']) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="category-filters">
                    <button class="category-btn active" data-category="all">
                        <i class="mdi mdi-view-grid"></i>Todos
                    </button>
                    @foreach ($categories as $category)
                        <button class="category-btn" data-category="{{ $category['id'] }}">
                            <i class="mdi mdi-tag"></i>{{ htmlspecialchars($category['name']) }}
                        </button>
                    @endforeach
                </div>

                <div class="row g-3" id="productsGrid">
                    @foreach ($products as $product)
                        <div class="col-md-3 product-item" data-category="{{ $product['category_id'] }}">
                            <div class="product-card" onclick="addToCart({{ json_encode($product) }})">
                                <div class="product-icon">
                                    <i class="mdi mdi-food"></i>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ htmlspecialchars($product['name']) }}</h5>
                                    <p class="price">
                                        <i class="mdi mdi-currency-usd"></i>
                                        MZN {{ number_format($product['price'], 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Cart Area -->
            <div class="col-lg-4">
                <div class="cart-wrapper">
                    <div class="cart-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="mdi mdi-cart"></i>
                                Pedido Atual
                            </h4>
                            <button class="btn btn-outline-warning btn-sm" onclick="resetSale()">
                                <i class="mdi mdi-delete"></i> Limpar
                            </button>
                        </div>
                    </div>

                    <div class="cart-items" id="cartItems">
                        <!-- Items will be added via JavaScript -->
                    </div>

                    <div class="p-3 border-top">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal:</span>
                            <span id="subtotal" class="font-weight-medium">MZN 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="h5">Total:</span>
                            <span id="total" class="h5">MZN 0.00</span>
                        </div>
                    </div>

                    <div class="payment-methods">
                        <h5 class="mb-3">
                            <i class="mdi mdi-credit-card-outline me-2"></i>
                            Método de Pagamento
                        </h5>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="payment-card" onclick="selectPayment('cash')">
                                    <i class="mdi mdi-cash"></i>
                                    <h6 class="mb-2">Dinheiro</h6>
                                    <input type="number" class="form-control form-control-sm" id="cashAmount"
                                        placeholder="0.00" onchange="calculateChange()">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="payment-card" onclick="selectPayment('card')">
                                    <i class="mdi mdi-credit-card"></i>
                                    <h6 class="mb-2">Cartão</h6>
                                    <input type="number" class="form-control form-control-sm" id="cardAmount"
                                        placeholder="0.00" onchange="calculateChange()">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="payment-card" onclick="selectPayment('mpesa')">
                                    <i class="mdi mdi-phone"></i>
                                    <h6 class="mb-2">M-Pesa</h6>
                                    <input type="number" class="form-control form-control-sm" id="mpesaAmount"
                                        placeholder="0.00" onchange="calculateChange()">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="payment-card" onclick="selectPayment('emola')">
                                    <i class="mdi mdi-wallet"></i>
                                    <h6 class="mb-2">E-mola</h6>
                                    <input type="number" class="form-control form-control-sm" id="emolaAmount"
                                        placeholder="0.00" onchange="calculateChange()">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="change-section p-3 bg-light rounded-3 border">
                                <label class="form-label d-flex align-items-center gap-2">
                                    <i class="mdi mdi-cash-refund text-success"></i>
                                    <span>Troco:</span>
                                </label>
                                <input type="text" class="form-control form-control-lg" id="changeAmount" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="btn-group m-2" style="padding: 5px; margin: 10px;">
                        <button id="btnFinalizeOrder" class="btn btn-finalize" onclick="processSale()">
                            <i class="mdi mdi-check-circle-outline"></i>
                            Finalizar Pedido
                        </button>
                        <button class="btn bg-primary btn-finalize" onclick="previewReceipt()">
                            <i class="mdi mdi-printer"></i>
                            preview Recibo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Processar mensagens de sessão como toast
            @if (session('success'))
                showToast('{{ session('success') }}', 'success');

                @if (session('change') && session('change') > 0)
                    setTimeout(() => {
                        showToast('Troco: MZN {{ number_format(session('change'), 2) }}', 'info');
                    }, 1000);
                @endif
            @endif

            @if (session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif

        });
    </script>
    @if (session('printReceipt') && session('saleId'))
        <script>
            setTimeout(() => {
                window.open(
                    '{{ route('pos.receipt', ['saleId' => session('saleId')]) }}',
                    'Recibo',
                    'width=400,height=700,scrollbars=yes,resizable=yes'
                );
            }, 500);
        </script>
    @endif
@endpush
