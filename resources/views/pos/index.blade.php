@extends('layouts.app')

@section('title', 'POS - Sistema de Vendas')

@section('content')
<div class="pos-wrapper container-wrapper">
    <div class="row g-4">
        <!-- Products Area -->
        <div class="col-lg-8">
            <div class="search-section">
                <div class="d-flex gap-3 align-items-center">
                    <div class="flex-grow-1 search-input-wrapper">
                        <i class="mdi mdi-magnify"></i>
                        <input type="text" class="form-control search-input" placeholder="Pesquisar produtos..."
                            id="searchInput" onkeyup="filterProducts()">
                    </div>
                    <select id="categorySelect" class="form-select search-input" style="width: auto;"
                        onchange="filterProducts()">
                        <option value="">Todas as Categorias</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category['id'] }}">
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
                        <button class="btn btn-outline-danger btn-sm" onclick="resetSale()">
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

                <div class="action-buttons">
                    <button id="btnFinalizeOrder" class="btn-finalize" onclick="processSale()">
                        <i class="mdi mdi-check-circle-outline"></i>
                        Finalizar Pedido
                    </button>
                    <button class="btn-preview" onclick="previewReceipt()">
                        <i class="mdi mdi-printer"></i>
                        Pré-visualizar Recibo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection