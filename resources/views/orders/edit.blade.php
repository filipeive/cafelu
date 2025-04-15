@extends('layouts.app')

@section('styles')
<style>
    /* Estilos gerais */
    .page-header {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    /* Status badges */
    .status-badge {
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    
    .status-pending {
        background-color: #FFF3CD;
        color: #856404;
    }
    
    .status-preparing {
        background-color: #D1ECF1;
        color: #0C5460;
    }
    
    .status-ready {
        background-color: #D4EDDA;
        color: #155724;
    }
    
    .status-delivered {
        background-color: #CFF4FC;
        color: #055160;
    }
    
    .status-cancelled {
        background-color: #F8D7DA;
        color: #721C24;
    }
    
    /* Items table */
    .items-table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    
    .items-table th {
        background-color: #f8f9fa;
        border-top: none;
        font-weight: 600;
    }
    
    .items-table .form-control-sm {
        border-radius: 4px;
        font-size: 0.85rem;
    }
    
    /* Product cards */
    .product-card {
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
        cursor: pointer;
    }
    
    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.12);
    }
    
    /* Tabs */
    .nav-tabs {
        border-bottom: 2px solid #e9ecef;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }
    
    .nav-tabs .nav-link.active {
        color: #4B49AC;
        border-bottom: 2px solid #4B49AC;
    }
    
    .tab-content {
        padding-top: 1.5rem;
    }
    
    /* Action buttons */
    .action-btn {
        padding: 0.5rem 1.25rem;
        border-radius: 4px;
        font-weight: 500;
    }
    
    .action-btn-sm {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }
    
    /* Order info card */
    .order-info-card {
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    
    .order-info-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 1rem 1.25rem;
    }
    
    /* Order total summary */
    .order-total {
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    /* Item row hover effect */
    .item-row {
        transition: background-color 0.2s ease;
    }
    
    .item-row:hover {
        background-color: #f8f9fa;
    }
    
    /* Status selector styling */
    .item-status-select {
        border: none;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    
    .item-status-select:focus {
        box-shadow: 0 0 0 0.2rem rgba(75, 73, 172, 0.25);
    }
    
    /* Alert animations */
    .alert {
        animation: fadeIn 0.5s;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<div class="container-wrapper">
    <!-- Cabeçalho da página -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1"><i class="fas fa-receipt text-primary"></i> Pedido #{{ $order->id }}</h2>
            <p class="text-muted mb-0">
                <i class="fas fa-chair"></i> Mesa {{ $order->table->number }} | 
                <i class="fas fa-user"></i> {{ $order->customer_name ?: 'Cliente não identificado' }}
            </p>
        </div>
        <div>
            <a href="{{ route('tables.index') }}" class="btn btn-outline-secondary action-btn">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <div class="btn-group ml-2">
                <button type="button" class="btn btn-success action-btn dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-cog"></i> Ações
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <form action="{{ route('orders.complete', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-success">
                            <i class="fas fa-check-circle"></i> Finalizar Pedido
                        </button>
                    </form>
                    <form action="{{ route('orders.cancel', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger" 
                                onclick="return confirm('Tem certeza que deseja cancelar este pedido?')">
                            <i class="fas fa-times-circle"></i> Cancelar Pedido
                        </button>
                    </form>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('orders.print', $order->id) }}" class="dropdown-item text-info">
                        <i class="fas fa-print"></i> Imprimir Pedido
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <!-- Coluna dos itens do pedido -->
        <div class="col-lg-7">
            <div class="card mb-4 items-table">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list-ul text-primary"></i> Itens do Pedido</h5>
                        <span class="order-total text-success">Total: MZN {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th style="width: 80px">Qtd</th>
                                    <th style="width: 120px">Preço</th>
                                    <th style="width: 120px">Total</th>
                                    <th style="width: 150px">Status</th>
                                    <th style="width: 50px">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order->items as $item)
                                    <tr class="item-row">
                                        <td>
                                            <div>
                                                <strong>{{ $item->product->name }}</strong>
                                                @if($item->notes)
                                                    <div class="small text-muted">
                                                        <i class="fas fa-comment-alt"></i> {{ $item->notes }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->unit_price, 2, ',', '.') }} MZN</td>
                                        <td class="text-success font-weight-bold">
                                            {{ number_format($item->total_price, 2, ',', '.') }} MZN
                                        </td>
                                        <td>
                                            <form action="{{ route('orders.update-item-status', $item) }}" method="POST" class="status-form">
                                                @csrf
                                                <select name="status" class="form-control item-status-select" 
                                                    data-item-id="{{ $item->id }}" 
                                                    data-status="{{ $item->status }}">
                                                    <option value="pending" @if($item->status === 'pending') selected @endif>Pendente</option>
                                                    <option value="preparing" @if($item->status === 'preparing') selected @endif>Preparando</option>
                                                    <option value="ready" @if($item->status === 'ready') selected @endif>Pronto</option>
                                                    <option value="delivered" @if($item->status === 'delivered') selected @endif>Entregue</option>
                                                    <option value="cancelled" @if($item->status === 'cancelled') selected @endif>Cancelado</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="{{ route('orders.remove-item', $item) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm action-btn-sm" 
                                                    onclick="return confirm('Tem certeza que deseja remover este item?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                                <p>Nenhum item adicionado ao pedido</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Formulário para adicionar itens -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle text-primary"></i> Adicionar Item</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.add-item', $order) }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="product_id"><i class="fas fa-tag"></i> Produto</label>
                                <select name="product_id" id="product_id" class="form-control" required>
                                    <option value="">Selecione um produto</option>
                                    @foreach ($categories as $category)
                                        <optgroup label="{{ $category->name }}">
                                            @foreach ($category->products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                    {{ $product->name }} - {{ number_format($product->price, 2, ',', '.') }} MZN
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="quantity"><i class="fas fa-sort-numeric-up"></i> Qtd</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="item_total"><i class="fas fa-calculator"></i> Subtotal</label>
                                <input type="text" id="item_total" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="notes"><i class="fas fa-comment"></i> Observações</label>
                            <textarea name="notes" id="notes" class="form-control" rows="2" 
                                      placeholder="Ex: Sem cebola, bem passado, etc"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary action-btn">
                            <i class="fas fa-plus"></i> Adicionar Item
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Coluna lateral -->
        <div class="col-lg-5">
            <!-- Card de menu rápido -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-utensils text-primary"></i> Menu Rápido</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="categoryTabs" role="tablist">
                        @foreach ($categories as $index => $category)
                            <li class="nav-item">
                                <a class="nav-link @if($index === 0) active @endif" 
                                   id="category-{{ $category->id }}-tab" 
                                   data-toggle="tab" 
                                   href="#category-{{ $category->id }}" 
                                   role="tab">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content mt-3" id="categoryTabsContent">
                        @foreach ($categories as $index => $category)
                            <div class="tab-pane fade @if($index === 0) show active @endif" 
                                 id="category-{{ $category->id }}" 
                                 role="tabpanel">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-transparent">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control product-search" 
                                           placeholder="Buscar produtos...">
                                </div>
                                <div class="row">
                                    @foreach ($category->products as $product)
                                        <div class="col-md-6 mb-3 product-item">
                                            <div class="card product-card h-100">
                                                <div class="card-body p-3">
                                                    <h6 class="card-title product-name">{{ $product->name }}</h6>
                                                    <p class="card-text text-success font-weight-bold mb-2">
                                                        {{ number_format($product->price, 2, ',', '.') }} MZN
                                                    </p>
                                                    <form action="{{ route('orders.add-item', $order) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                                                            <i class="fas fa-plus"></i> Adicionar
                                                        </button>
                                                    </form>
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

            <!-- Card de informações do cliente -->
            <div class="card order-info-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-user text-primary"></i> Informações do Cliente</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="customer_name"><i class="fas fa-id-card"></i> Nome do Cliente</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control" 
                                value="{{ $order->customer_name }}" placeholder="Digite o nome do cliente">
                        </div>
                        <div class="form-group">
                            <label for="notes"><i class="fas fa-sticky-note"></i> Observações</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" 
                                      placeholder="Observações gerais sobre o pedido">{{ $order->notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary action-btn">
                            <i class="fas fa-save"></i> Atualizar Informações
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Estilizar os selects de status com cores diferentes
        function styleStatusSelects() {
            $('.item-status-select').each(function() {
                const status = $(this).val();
                $(this).removeClass('status-pending status-preparing status-ready status-delivered status-cancelled');
                
                switch(status) {
                    case 'pending':
                        $(this).addClass('status-pending');
                        break;
                    case 'preparing':
                        $(this).addClass('status-preparing');
                        break;
                    case 'ready':
                        $(this).addClass('status-ready');
                        break;
                    case 'delivered':
                        $(this).addClass('status-delivered');
                        break;
                    case 'cancelled':
                        $(this).addClass('status-cancelled');
                        break;
                }
            });
        }
        
        // Calcular subtotal ao mudar produto ou quantidade
        function calculateSubtotal() {
            const productPrice = $('#product_id option:selected').data('price') || 0;
            const quantity = $('#quantity').val();
            const subtotal = productPrice * quantity;
            $('#item_total').val(subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' MZN');
        }
        
        // Event listeners
        $('#product_id, #quantity').on('change', calculateSubtotal);
        
        // Atualizar status do item automaticamente
        $('.item-status-select').on('change', function() {
            const oldStatus = $(this).data('status');
            const newStatus = $(this).val();
            
            if (oldStatus !== newStatus) {
                $(this).closest('form').submit();
            }
        });
        
        // Pesquisa de produtos dentro das abas
        $('.product-search').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            const tabPane = $(this).closest('.tab-pane');
            
            tabPane.find('.product-item').each(function() {
                const productName = $(this).find('.product-name').text().toLowerCase();
                
                if (productName.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        // Inicializar estilos e cálculo
        styleStatusSelects();
        calculateSubtotal();
        
        // Efeito de clique nas cards de produtos
        $('.product-card').on('click', function(e) {
            if (!$(e.target).is('button') && !$(e.target).is('input')) {
                $(this).find('button[type="submit"]').click();
            }
        });
        
        // Notificação visual ao alterar status
        $('.status-form').on('submit', function() {
            const row = $(this).closest('tr');
            row.css('background-color', '#f8f9fa');
            setTimeout(() => {
                row.css('background-color', '');
            }, 300);
        });
    });
</script>
@endpush
@endsection