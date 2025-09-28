@extends('layouts.app')

@section('styles')
    <style>
        /* Reset e estilos base */
        body {
            overflow-x: hidden;
        }

        /* Layout principal */
        .order-container {
            display: flex;
            height: calc(100vh - 120px);
            max-height: 100%;
        }

        .products-section {
            flex: 1;
            overflow-y: auto;
            padding-right: 1rem;
        }

        .order-summary-section {
            width: 350px;
            border-left: 1px solid #e9ecef;
            height: 100%;
            position: sticky;
            top: 0;
        }

        /* Estilos para a barra de categorias */
        .category-pills {
            display: flex;
            padding: 0.75rem 0;
            gap: 0.5rem;
            overflow-x: auto;
            scrollbar-width: thin;
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 10;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e9ecef;
        }

        .category-pill {
            padding: 0.5rem 1.25rem;
            background-color: #f8f9fa;
            border-radius: 50px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .category-pill:hover {
            background-color: #e9ecef;
        }

        .category-pill.active {
            background-color: #4B49AC;
            color: white;
            box-shadow: 0 2px 4px rgba(75, 73, 172, 0.3);
        }

        /* Estilos para a barra de busca */
        .search-field {
            padding: 1rem 0;
            position: sticky;
            top: 60px;
            background-color: white;
            z-index: 10;
        }

        .search-field .input-group {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 50px;
            overflow: hidden;
        }

        .search-field .form-control {
            border-radius: 50px;
            padding-left: 1rem;
            height: 50px;
            border: 1px solid #e9ecef;
        }

        .search-field .input-group-text {
            border-radius: 50px 0 0 50px;
            padding-left: 1.25rem;
        }

        /* Estilos para os cards de produtos */
        .product-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
            height: 100%;
        }

        .product-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .product-card .card-body {
            padding: 1.25rem;
        }

        .product-image {
            height: 80px;
            width: 80px;
            border-radius: 8px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: #4B49AC;
            font-size: 2rem;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: auto;
        }

        .quantity-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.25rem;
            transition: all 0.2s;
        }

        .quantity-btn.decrease {
            color: #dc3545;
        }

        .quantity-btn.increase {
            color: #28a745;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            font-weight: bold;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        /* Estilos para o resumo do pedido */
        .order-summary {
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
        }

        .order-summary .card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .order-items {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 1rem;
        }

        .order-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .total-section {
            padding: 1rem 0;
            border-top: 2px solid #e9ecef;
            margin-top: auto;
        }

        .btn-action {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }

        .badge-table {
            padding: 0.5rem 1rem;
            font-size: 1rem;
            border-radius: 50px;
        }

        /* Estilos responsivos */
        @media (max-width: 991.98px) {
            .order-container {
                flex-direction: column;
                height: auto;
            }

            .order-summary-section {
                width: 100%;
                border-left: none;
                border-top: 1px solid #e9ecef;
                margin-top: 2rem;
                max-height: 50vh;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="font-weight-bold mb-0">
                                    <i class="mdi mdi-cart-plus text-primary"></i> Novo Pedido
                                </h3>
                                <p class="text-muted mb-0">Adicione produtos ao carrinho de compras</p>
                            </div>
                            <div class="badge badge-primary badge-table">
                                <i class="mdi mdi-table-furniture"></i> Mesa: {{ $table->number ?? 'Não selecionada' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="order-container">
            <div class="products-section">
                <!-- Barra de busca -->
                <div class="search-field">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0">
                                <i class="mdi mdi-magnify text-primary"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" id="searchProducts"
                            placeholder="Buscar produtos pelo nome...">
                    </div>
                </div>

                <!-- Categorias -->
                <div class="category-pills">
                    <div class="category-pill active" data-category="all">
                        <i class="mdi mdi-view-grid"></i> Todos
                    </div>
                    @foreach ($categories as $category)
                        <div class="category-pill" data-category="{{ $category->id }}">
                            <i class="mdi mdi-tag"></i> {{ $category->name }}
                        </div>
                    @endforeach
                </div>

                <!-- Lista de Produtos -->
                <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
                    @csrf
                    <input type="hidden" name="table_id" value="{{ $table->id ?? '' }}">

                    <div class="row" id="productsContainer">
                        @foreach ($products as $product)
                            <div class="col-md-6 col-lg-4 col-xl-3 mb-4" data-category="{{ $product->category_id }}">
                                <div class="card product-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex flex-column h-100">
                                            <div class="product-image">
                                                <i class="mdi mdi-food"></i>
                                            </div>
                                            <h5 class="card-title mb-1">{{ $product->name }}</h5>
                                            <p class="text-muted small mb-2">
                                                {{ \Illuminate\Support\Str::limit($product->description ?? 'Sem descrição disponível', 60) }}
                                            </p>
                                            <h6 class="text-success font-weight-bold mb-3">
                                                MZN {{ number_format($product->price, 2, ',', '.') }}
                                            </h6>
                                            <div class="quantity-control mt-auto">
                                                <button type="button"
                                                    class="btn btn-icon btn-outline-primary quantity-btn decrease">
                                                    <i class="mdi mdi-minus"></i>
                                                </button>
                                                <input type="number" name="products[{{ $product->id }}][quantity]"
                                                    class="form-control quantity-input" value="0" min="0">
                                                <button type="button"
                                                    class="btn btn-icon btn-outline-primary quantity-btn increase">
                                                    <i class="mdi mdi-plus"></i>
                                                </button>
                                                <input type="hidden" name="products[{{ $product->id }}][id]"
                                                    value="{{ $product->id }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>

            <!-- Resumo do Pedido -->
            <div class="order-summary-section">
                <div class="card order-summary h-100">
                    <div class="card-body">
                        <h4 class="card-title mb-3">
                            <i class="mdi mdi-clipboard-text"></i> Resumo do Pedido
                        </h4>

                        <div id="orderItems" class="order-items">
                            <div class="text-center text-muted py-5" id="emptyOrderMessage">
                                <i class="mdi mdi-cart-outline" style="font-size: 3rem;"></i>
                                <p class="mt-2">Seu pedido está vazio</p>
                                <p class="small">Adicione itens da lista de produtos</p>
                            </div>
                            <!-- Items serão adicionados via JavaScript -->
                        </div>

                        <div class="total-section">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Total:</h5>
                                <h4 class="text-success mb-0">
                                    MZN <span id="totalPrice">0,00</span>
                                </h4>
                            </div>

                            <button type="submit" form="orderForm" class="btn btn-primary btn-lg btn-block btn-action mb-2"
                                id="confirmOrderBtn" disabled>
                                <i class="mdi mdi-check-circle"></i> Confirmar Pedido
                            </button>
                            <a href="{{ route('tables.index') }}" class="btn btn-outline-secondary btn-block btn-action">
                                <i class="mdi mdi-arrow-left"></i> Voltar para Mesas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Função para atualizar o resumo do pedido
            function updateOrderSummary() {
                const orderItems = document.getElementById('orderItems');
                const totalPriceSpan = document.getElementById('totalPrice');
                const emptyOrderMessage = document.getElementById('emptyOrderMessage');
                const confirmOrderBtn = document.getElementById('confirmOrderBtn');
                let total = 0;
                let hasItems = false;

                // Limpar o conteúdo anterior
                orderItems.innerHTML = '';
                orderItems.appendChild(emptyOrderMessage);

                // Percorrer todos os produtos
                document.querySelectorAll('.product-card').forEach(card => {
                    const quantity = parseInt(card.querySelector('.quantity-input').value);
                    if (quantity > 0) {
                        hasItems = true;
                        emptyOrderMessage.style.display = 'none';

                        const name = card.querySelector('.card-title').textContent.trim();
                        const priceText = card.querySelector('.text-success').textContent
                            .replace('MZN', '').trim();
                        const price = parseFloat(priceText.replace('.', '').replace(',', '.'));
                        const itemTotal = price * quantity;
                        total += itemTotal;

                        // Criar o elemento do item do pedido
                        const orderItem = document.createElement('div');
                        orderItem.className = 'order-item';
                        orderItem.innerHTML = `
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0">${name}</h6>
                                    <div class="d-flex align-items-center text-muted small">
                                        <span>MZN ${price.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                                        <span class="mx-1">×</span>
                                        <span>${quantity}</span>
                                    </div>
                                </div>
                                <h6 class="text-success mb-0">MZN ${itemTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</h6>
                            </div>
                        `;
                        orderItems.appendChild(orderItem);
                    }
                });

                // Mostrar ou esconder mensagem de carrinho vazio
                if (hasItems) {
                    emptyOrderMessage.style.display = 'none';
                } else {
                    emptyOrderMessage.style.display = 'block';
                }

                // Atualizar o total e habilitar/desabilitar botão de confirmação
                totalPriceSpan.textContent = total.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                confirmOrderBtn.disabled = !hasItems;
            }

            // Event Listeners para os botões de aumentar/diminuir quantidade
            document.querySelectorAll('.quantity-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('.quantity-input');
                    const currentValue = parseInt(input.value);

                    if (this.classList.contains('increase')) {
                        input.value = currentValue + 1;

                        // Aplicar efeito visual ao adicionar
                        const card = this.closest('.product-card');
                        card.style.transform = 'scale(1.03)';
                        setTimeout(() => {
                            card.style.transform = 'translateY(-2px)';
                        }, 200);

                    } else if (this.classList.contains('decrease') && currentValue > 0) {
                        input.value = currentValue - 1;
                    }

                    updateOrderSummary();
                });
            });

            // Event listener para alteração direta do campo de quantidade
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', updateOrderSummary);
            });

            // Busca de produtos
            const searchInput = document.getElementById('searchProducts');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                document.querySelectorAll('.product-card').forEach(card => {
                    const productName = card.querySelector('.card-title').textContent.toLowerCase();
                    const productContainer = card.closest('[data-category]');

                    if (productName.includes(searchTerm)) {
                        productContainer.style.display = '';
                    } else {
                        productContainer.style.display = 'none';
                    }
                });
            });

            // Filtro por categoria
            document.querySelectorAll('.category-pill').forEach(pill => {
                pill.addEventListener('click', function() {
                    document.querySelectorAll('.category-pill').forEach(p => p.classList.remove(
                        'active'));
                    this.classList.add('active');

                    const category = this.dataset.category;

                    document.querySelectorAll('[data-category]').forEach(product => {
                        if (category === 'all' || product.dataset.category === category) {
                            product.style.display = '';
                        } else {
                            product.style.display = 'none';
                        }
                    });
                });
            });

            // Inicializar o resumo do pedido
            updateOrderSummary();

            // Adicionar funcionalidade de swipe para as categorias em dispositivos móveis
            const categoryContainer = document.querySelector('.category-pills');
            let startX, scrollLeft;

            categoryContainer.addEventListener('touchstart', function(e) {
                startX = e.touches[0].pageX - categoryContainer.offsetLeft;
                scrollLeft = categoryContainer.scrollLeft;
            });

            categoryContainer.addEventListener('touchmove', function(e) {
                if (!startX) return;
                const x = e.touches[0].pageX - categoryContainer.offsetLeft;
                const walk = (x - startX) * 2;
                categoryContainer.scrollLeft = scrollLeft - walk;
            });

            // Validação do formulário antes de enviar
            document.getElementById('orderForm').addEventListener('submit', function(e) {
                const hasItems = [...document.querySelectorAll('.quantity-input')].some(input => parseInt(
                    input.value) > 0);

                if (!hasItems) {
                    e.preventDefault();
                    alert('Por favor, adicione pelo menos um produto ao pedido.');
                }
            });
        });
    </script>
@endsection
