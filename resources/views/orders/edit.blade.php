@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Order Header Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <!-- Order Title & Status -->
                    <div class="d-flex align-items-center gap-3">
                        <h4 class="mb-0">
                            <i class="mdi mdi-receipt text-primary me-1"></i>
                            Pedido #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                        </h4>
                        <span class="badge {{ get_status_class_staradmins($order->status) }}">
                            {{ ucfirst(trans($order->status)) }}
                        </span>

                        <!-- Payment Button -->
                        @if ($order->status === 'completed' && !$order->is_paid)
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#paymentModal">
                                <i class="mdi mdi-cash-multiple me-1"></i> Registrar Pagamento
                            </button>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="order-actions">
                        <a href="{{ route('tables.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="mdi mdi-table me-1"></i> Mesas
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm ms-2">
                            <i class="mdi mdi-clipboard-list me-1"></i> Pedidos
                        </a>
                        {{-- @if ($order->status == 'paid') --}}
                        <button class="btn btn-info btn-icon btn-sm ms-2" data-bs-toggle="tooltip" title="Imprimir"
                            onclick="printRecibo({{ $order->id }})">
                            <i class="mdi mdi-printer"></i> Imprimir Conta!
                        </button>
                        {{-- @endif --}}
                        {{-- botao de cancelar pedido --}}
                        @if ($order->status == 'active')
                            <button type="button" class="btn btn-danger btn-icon btn-sm ms-2" data-bs-toggle="modal"
                                data-bs-target="#cancelOrderModal" title="Cancelar Pedido">
                                <i class="mdi mdi-delete"></i> Cancelar Pedido
                            </button>
                            <!-- Modal de Confirmação -->
                            <div class="modal fade" id="cancelOrderModal" tabindex="-1"
                                aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="cancelOrderModalLabel">Confirmar Cancelamento</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('orders.cancel', $order) }}" method="POST" id="cancelForm">
                                                @csrf
                                                @method('POST')
                                                <div class="mb-3">
                                                    <label for="cancel_reason" class="form-label">Motivo do Cancelamento</label>
                                                    <textarea class="form-control" id="cancel_reason" name="notes" rows="3" required></textarea>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Não</button>
                                            <button type="submit" form="cancelForm" class="btn btn-danger">Sim, Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Order Info Cards -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="mdi mdi-clock-outline text-muted me-2"></i>
                                    <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="mdi mdi-cash text-success me-2"></i>
                                    <span class="fw-bold">MZN {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-account text-primary me-2"></i>
                                    <span>{{ $order->customer_name ?: 'Cliente não identificado' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body p-3">
                                @if ($order->table)
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="mdi mdi-table-furniture text-info me-2"></i>
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
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-note-text text-warning me-2"></i>
                                        <span>{{ $order->notes }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
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

        <div class="row">
            <!-- Left Column - Order Details -->
            <div class="col-lg-8">
                <!-- Order Items Card -->
                <div class="card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="mdi mdi-format-list-bulleted text-primary me-2"></i>Itens do Pedido
                        </h5>
                        <span class="badge bg-primary">{{ $order->items->count() }} itens</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th class="text-center" style="width: 80px">Qtd</th>
                                    <th style="width: 120px">Preço</th>
                                    <th style="width: 120px">Total</th>
                                    <th style="width: 150px">Status</th>
                                    <th style="width: 80px" class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order->items as $item)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $item->product->name }}</strong>
                                                @if ($item->notes)
                                                    <div class="small text-muted">
                                                        <i class="mdi mdi-note-text me-1"></i>{{ $item->notes }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item->total_price, 2, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('orders.update-item-status', $item) }}"
                                                method="POST">
                                                @csrf
                                                <select name="status" class="form-select form-select-sm status-select"
                                                    onchange="this.form.submit()" style="min-width: 120px">
                                                    <option value="pending" @selected($item->status === 'pending')>Pendente</option>
                                                    <option value="preparing" @selected($item->status === 'preparing')>Preparando
                                                    </option>
                                                    <option value="ready" @selected($item->status === 'ready')>Pronto</option>
                                                    <option value="delivered" @selected($item->status === 'delivered')>Entregue
                                                    </option>
                                                    <option value="cancelled" @selected($item->status === 'cancelled')>Cancelado
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <button onclick="removeItem({{ $item->id }})"
                                                class="btn btn-action-sm btn-action-danger">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center p-4">
                                            <div class="text-muted">
                                                <i class="mdi mdi-cart-outline d-block mb-2" style="font-size: 2rem;"></i>
                                                <p>Nenhum item adicionado ao pedido</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Menu Card -->
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="mdi mdi-food text-primary me-2"></i>Menu Rápido</h5>
                        <div class="input-group" style="max-width: 300px;">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="mdi mdi-magnify text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="quickMenuSearch"
                                placeholder="Buscar produtos...">
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Category Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered" id="categoryTabs" role="tablist">
                            @foreach ($categories as $category)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                        id="category-tab-{{ $category->id }}" data-bs-toggle="tab"
                                        data-bs-target="#category-content-{{ $category->id }}" type="button"
                                        role="tab">
                                        {{ $category->name }}
                                        <span class="badge bg-primary ms-1">{{ $category->products->count() }}</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Category Content -->
                        <div class="tab-content p-3" id="categoryTabsContent">
                            @foreach ($categories as $category)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                    id="category-content-{{ $category->id }}" role="tabpanel">
                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 product-container">
                                        @foreach ($category->products as $product)
                                            <div class="col product-item">
                                                <div class="card h-100 product-card"
                                                    onclick="addProduct({{ $product->id }})">
                                                    <div class="card-body d-flex flex-column">
                                                        <h6 class="product-name">{{ $product->name }}</h6>
                                                        <div
                                                            class="mt-auto d-flex justify-content-between align-items-center">
                                                            <span class="product-price fw-bold">
                                                                MZN {{ number_format($product->price, 2, ',', '.') }}
                                                            </span>
                                                            <button class="btn btn-sm btn-primary">
                                                                <i class="mdi mdi-plus me-1"></i>Adicionar
                                                            </button>
                                                        </div>
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

            <!-- Right Column - Order Information -->
            <div class="col-lg-4">
                <!-- Customer Information Card -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="mdi mdi-account text-primary me-2"></i>Informações do Cliente</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('orders.update', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-account text-muted me-1"></i> Nome do Cliente
                                </label>
                                <input type="text" name="customer_name" class="form-control"
                                    value="{{ $order->customer_name }}" placeholder="Nome do Cliente">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-note text-muted me-1"></i> Observações
                                </label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Observações">{{ $order->notes }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="mdi mdi-content-save me-1"></i> Salvar Alterações
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Order Summary Card -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="mdi mdi-cash-register text-primary me-2"></i>Resumo do Pedido</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="h6 mb-0">Total</span>
                            <span class="h3 mb-0 text-primary">MZN
                                {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                        </div>

                        <form action="{{ route('orders.complete', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="mdi mdi-check-circle me-1"></i> Finalizar Pedido
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    @if ($order->status === 'completed' && !$order->is_paid)
        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('orders.pay', $order) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">
                                <i class="mdi mdi-cash-multiple text-success me-1"></i>
                                Registrar Pagamento #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Método de Pagamento</label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    <option value="">Selecione um método</option>
                                    <option value="cash">Dinheiro</option>
                                    <option value="card">Cartão</option>
                                    <option value="mpesa">M-Pesa</option>
                                    <option value="emola">E-Mola</option>
                                    <option value="mkesh">M-Kesh</option>
                                </select>
                            </div>

                            <!-- Cash payment fields -->
                            <div id="cashFields" style="display: none;">
                                <div class="mb-3">
                                    <label for="cash_amount" class="form-label">Valor Recebido</label>
                                    <div class="input-group">
                                        <span class="input-group-text">MZN</span>
                                        <input type="number" class="form-control" id="cash_amount" name="cash_amount"
                                            step="0.01" min="{{ $order->total_amount }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Troco</label>
                                    <div class="input-group">
                                        <span class="input-group-text">MZN</span>
                                        <input type="text" class="form-control" id="change_amount" readonly>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="amount_paid" value="{{ $order->total_amount }}">

                            <div class="mb-3">
                                <label for="notes" class="form-label">Observações</label>
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
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">
                                <i class="mdi mdi-check-circle me-1"></i> Confirmar Pagamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

    @push('scripts')
        <script>
            function removeItem(itemId) {
                Swal.fire({
                    title: 'Confirmar remoção',
                    text: "Deseja remover este item do pedido?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, remover',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`remove-item-${itemId}`).submit();
                    }
                });
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

        // Toggle cash payment fields
        document.getElementById('payment_method').addEventListener('change', function() {
            const cashFields = document.getElementById('cashFields');
            cashFields.style.display = this.value === 'cash' ? 'block' : 'none';
        });

        // Calculate change
        document.getElementById('cash_amount').addEventListener('input', function() {
            const received = parseFloat(this.value) || 0;
            const total = {{ $order->total_amount }};
            const change = received - total;
            document.getElementById('change_amount').value = change >= 0 ? change.toFixed(2) : '0.00';
        });

        // Print Function
        function printOrder() {
            window.open("{{ route('orders.print', $order) }}", '_blank');
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endpush
