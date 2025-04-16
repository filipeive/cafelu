@extends('layouts.app')

@section('styles')
@endsection

@section('content')
    <div class="page-container">
        <!-- Order Header -->
        <div class="order-header card mb-4">
            <div class="card-body">
                <!-- Título e Status do Pedido -->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <h5 class="mb-0">
                            <i class="mdi mdi-receipt text-primary"></i>
                            Pedido #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                        </h5>
                        <span class="badge {{ get_status_class_staradmins($order->status) }}">
                            {{ ucfirst(trans($order->status)) }}
                        </span>

                        <!-- Botão de Pagamento -->
                        @if ($order->status === 'completed' && !$order->is_paid)
                            <button type="button" class="btn btn-success btn-sm ms-2" data-bs-toggle="modal"
                                data-bs-target="#paymentModal">
                                <i class="mdi mdi-cash-multiple me-1"></i>
                                Registrar Pagamento
                            </button>
                        @endif
                    </div>
                    <div class="order-actions">
                        <a href="{{ route('tables.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="mdi mdi-table"></i> Mesas
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="mdi mdi-clipboard-list"></i> Pedidos
                        </a>
                        <button onclick="printOrder()" class="btn btn-sm btn-outline-primary ms-2">
                            <i class="mdi mdi-printer"></i> Imprimir
                        </button>
                    </div>
                </div>

                <!-- Informações do Pedido -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card p-2">
                            <div class="order-info-grid">
                                <div class="info-item">
                                    <i class="mdi mdi-clock-outline text-muted"></i>
                                    <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="mdi mdi-cash text-success"></i>
                                    <span class="fw-bold">MZN {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="mdi mdi-account text-primary"></i>
                                    <span>{{ $order->customer_name ?: 'Cliente não identificado' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card p-2">
                            <div class="order-info-grid">
                                @if ($order->table)
                                    <div class="info-item">
                                        <i class="mdi mdi-table-furniture text-info"></i>
                                        <span>Mesa {{ $order->table->number }}
                                            @if ($order->table->group_id)
                                                <span class="badge bg-info ms-1">
                                                    <i class="mdi mdi-link-variant"></i>
                                                    {{ $order->table->groupedTablesNumbers ? 'Unida: ' . $order->table->groupedTablesNumbers : '' }}
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                @endif
                                @if ($order->notes)
                                    <div class="info-item">
                                        <i class="mdi mdi-note-text text-warning"></i>
                                        <span>{{ $order->notes }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert System -->
        @if (session('success'))
            <div class="toast-notification toast-success">
                <div class="toast-icon"><i class="mdi mdi-check-circle"></i></div>
                <div class="toast-message">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="toast-notification toast-error">
                <div class="toast-icon"><i class="mdi mdi-alert-circle"></i></div>
                <div class="toast-message">{{ session('error') }}</div>
            </div>
        @endif

        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Items Table -->
                <div class="items-table card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="mdi mdi-format-list-bulleted me-2"></i>Itens do Pedido</h5>
                        <span class="badge bg-primary">{{ $order->items->count() }} itens</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th style="width: 100px">Qtd</th>
                                    <th style="width: 120px">Preço</th>
                                    <th style="width: 120px">Total</th>
                                    <th style="width: 150px">Status</th>
                                    <th style="width: 100px">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order->items as $item)
                                    <tr class="item-row">
                                        <td>
                                            <div class="item-details">
                                                <span class="item-name">{{ $item->product->name }}</span>
                                                @if ($item->notes)
                                                    <small class="item-notes">
                                                        <i class="mdi mdi-note-text"></i>
                                                        {{ $item->notes }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item->total_price, 2, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('orders.update-item-status', $item) }}" method="POST">
                                                @csrf
                                                <select name="status" class="status-select" onchange="this.form.submit()">
                                                    <option value="pending" @selected($item->status === 'pending')>Pendente</option>
                                                    <option value="preparing" @selected($item->status === 'preparing')>Preparando
                                                    </option>
                                                    <option value="ready" @selected($item->status === 'ready')>Pronto</option>
                                                    <option value="delivered" @selected($item->status === 'delivered')>Entregue</option>
                                                    <option value="cancelled" @selected($item->status === 'cancelled')>Cancelado
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                        <button onclick="removeItem(this)"
                                            data-id="{{ $item->id }}"
                                            class="btn btn-action-sm btn-action-danger">
                                            <i class="mdi mdi-delete"></i>
                                        </button>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <i class="mdi mdi-cart-outline empty-icon"></i>
                                                <p class="empty-text">Nenhum item adicionado ao pedido</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Menu -->
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="mdi mdi-food text-primary me-2"></i>Menu Rápido</h5>
                        <div class="search-container">
                            <i class="mdi mdi-magnify search-icon"></i>
                            <input type="text" class="form-control search-input" id="quickMenuSearch"
                                placeholder="Buscar produtos...">
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Tabs para categorias -->
                        <div class="custom-tabs">
                            @foreach ($categories as $category)
                                <button class="custom-tab {{ $loop->first ? 'active' : '' }}"
                                    data-category="{{ $category->id }}">
                                    {{ $category->name }}
                                    <span class="custom-tab-badge">{{ $category->products->count() }}</span>
                                </button>
                            @endforeach
                        </div>

                        <!-- Grid de produtos -->
                        <div class="product-grid mt-3">
                            @foreach ($categories as $category)
                                <div class="category-products" data-category="{{ $category->id }}"
                                    style="{{ !$loop->first ? 'display: none;' : '' }}">
                                    @foreach ($category->products as $product)
                                        <div class="product-card" onclick="addProduct({{ $product->id }})">
                                            <div class="product-card-body">
                                                <h6 class="product-name">{{ $product->name }}</h6>
                                                <div class="product-price">MZN
                                                    {{ number_format($product->price, 2, ',', '.') }}</div>
                                                <button class="btn btn-sm btn-primary product-add">
                                                    <i class="mdi mdi-plus"></i> Adicionar
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Customer Info -->
                <div class="order-info-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-account text-primary me-2"></i>Informações do Cliente</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('orders.update', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="mdi mdi-account"></i> Nome do Cliente
                                </label>
                                <input type="text" name="customer_name" class="form-control"
                                    value="{{ $order->customer_name }}" placeholder="Nome do Cliente">
                            </div>
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="mdi mdi-note"></i> Observações
                                </label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Observações">{{ $order->notes }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-action btn-action-primary w-100">
                                <i class="mdi mdi-content-save"></i> Salvar Alterações
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-summary-footer">
                    <div>
                        <span class="order-total-label">Total do Pedido</span>
                        <div class="order-total-display">
                            MZN {{ number_format($order->total_amount, 2, ',', '.') }}
                        </div>
                    </div>
                    <form action="{{ route('orders.complete', $order) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-action btn-action-success">
                            <i class="mdi mdi-check-circle"></i>
                            Finalizar Pedido
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Adicione o Modal de Pagamento no final da página -->
    @if ($order->status === 'completed' && !$order->is_paid)
        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('orders.pay', $order) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Registrar Pagamento #{{ $order->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="payment_method">Método de Pagamento</label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    <option value="">Selecione um método</option>
                                    <option value="cash">Dinheiro</option>
                                    <option value="card">Cartão</option>
                                    <option value="mpesa">M-Pesa</option>
                                    <option value="emola">E-Mola</option>
                                    <option value="mkesh">M-Kesh</option>
                                </select>
                            </div>

                            <!-- Campo para Troco (aparece apenas quando método é dinheiro) -->
                            <div id="cashFields" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="cash_amount">Valor Recebido</label>
                                    <div class="input-group">
                                        <span class="input-group-text">MZN</span>
                                        <input type="number" class="form-control" id="cash_amount" name="cash_amount"
                                            step="0.01" min="{{ $order->total_amount }}">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Troco</label>
                                    <div class="input-group">
                                        <span class="input-group-text">MZN</span>
                                        <input type="text" class="form-control" id="change_amount" readonly>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="amount_paid" value="{{ $order->total_amount }}">

                            <div class="form-group mb-3">
                                <label for="notes">Observações</label>
                                <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Total a Pagar:</strong>
                                    <span class="h5 mb-0">MZN
                                        {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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

    @push('scripts')
        <script>
             function removeItem(button) {
                const itemId = button.getAttribute('data-id');
                const confirmDelete = confirm("Deseja remover este item do pedido?");

                if (confirmDelete) {
                    fetch(`/orders/items/${itemId}/remove`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            alert('Item removido com sucesso!');
                            location.reloadx();
                        } else {
                            alert('Erro ao remover o item.');
                        }
                    })
                    .catch(() => {
                        alert('Erro de comunicação com o servidor.');
                    });
                }
            }
            // Quick Menu Search
            document.getElementById('quickMenuSearch').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                document.querySelectorAll('.product-card').forEach(card => {
                    const productName = card.querySelector('.product-name').textContent.toLowerCase();
                    card.style.display = productName.includes(searchTerm) ? 'flex' : 'none';
                });
            });

            // Category Tabs
            document.querySelectorAll('.custom-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    const categoryId = this.dataset.category;

                    // Update active tab
                    document.querySelectorAll('.custom-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Show/hide products
                    document.querySelectorAll('.category-products').forEach(products => {
                        products.style.display = products.dataset.category === categoryId ? 'grid' :
                            'none';
                    });
                });
            });

            // Add Product Function
            function addProduct(productId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ route('orders.add-item', $order->id) }}`;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';

                const product = document.createElement('input');
                product.type = 'hidden';
                product.name = 'product_id';
                product.value = productId;

                const quantity = document.createElement('input');
                quantity.type = 'hidden';
                quantity.name = 'quantity';
                quantity.value = '1';

                form.appendChild(csrf);
                form.appendChild(product);
                form.appendChild(quantity);

                document.body.appendChild(form);
                form.submit();
            }
            // Lógica para calcular troco
            document.getElementById('payment_method').addEventListener('change', function() {
                const cashFields = document.getElementById('cashFields');
                cashFields.style.display = this.value === 'cash' ? 'block' : 'none';
            });

            document.getElementById('cash_amount').addEventListener('input', function() {
                const received = parseFloat(this.value) || 0;
                const total = {{ $order->total_amount }};
                const change = received - total;

                document.getElementById('change_amount').value =
                    change >= 0 ? change.toFixed(2) : '0.00';
            });
            // Print Function
            function printOrder() {
                window.open("{{ route('orders.print', $order) }}", '_blank');
            }
        </script>
    @endpush
    @endif