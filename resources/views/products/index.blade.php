@extends('layouts.app')

@section('page-title', 'Produtos')
@section('title-icon', 'mdi-food-variant')

@section('breadcrumbs')
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-food-variant me-1"></i> Produtos
    </li>
@endsection

@push('styles')
    <style>
    .stats-card {
        transition: all 0.3s ease;
        border-left: 4px solid;
        padding: 0.8rem 1rem; /* Compacta */
        min-height: 100px; /* Mantém altura mínima */
    }
    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    .stat-label {
        font-size: 0.85rem; /* Menor */
        margin-bottom: 0.2rem;
    }
    .stat-value {
        font-size: 1.4rem; /* Menor que antes */
        font-weight: bold;
    }
    .stats-card small {
        font-size: 0.75rem;
    }
    .stats-card i {
        font-size: 1.8rem; /* Ícones menores */
    }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f4f6;
            border-top: 3px solid #0d6efd;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .btn-group .btn {
            transition: all 0.3s ease;
        }
        .btn-group .btn:hover {
            transform: translateY(-2px);
        }
    </style>
@endpush

@section('content')
    <div class="row g-4">
        <!-- Header Section -->
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">
                        <i class="mdi mdi-food-variant me-2"></i>
                        Produtos e Serviços
                    </h2>
                    <p class="text-muted mb-0">Gerencie todos os produtos e serviços do seu estabelecimento</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('products.create') }}?type=product" class="btn btn-primary d-flex align-items-center">
                        <i class="mdi mdi-plus me-2"></i>
                        <span>Novo Produto</span>
                    </a>
                    <a href="{{ route('products.create') }}?type=service" class="btn btn-info d-flex align-items-center">
                        <i class="mdi mdi-cog me-2"></i>
                        <span>Novo Serviço</span>
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-success d-flex align-items-center">
                        <i class="mdi mdi-format-list-bulleted me-2"></i>
                        <span>Categorias</span>
                    </a>
                    <a href="{{ route('products.report') }}" class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="mdi mdi-file-export me-2"></i>
                        <span>Exportar</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="card stats-card border-danger h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                 <p class="stat-label">Estoque Baixo</p>
                                    <h3 class="stat-value text-warning">{{ $lowStockCount ?? 0 }}</h3>
                                    <small class="text-muted">produtos críticos</small>
                            </div>
                            <div class="text-danger">
                                <i class="mdi mdi-cash-remove fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="card stats-card border-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                 <p class="stat-label">Categorias</p>
                                    <h3 class="stat-value text-success">{{ $categories->count() ?? 0 }}</h3>
                                    <small class="text-muted">organização</small>
                            </div>
                            <div class="text-success">
                                <i class="mdi mdi-chart-line fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="card stats-card border-warning h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="stat-label">Serviços</p>
                                    <h3 class="stat-value text-info">{{ $allProducts->where('type', 'service')->count() }}
                                    </h3>
                                    <small class="text-muted">cadastrados</small>
                            </div>
                            <div class="text-warning">
                                <i class="mdi mdi-arrow-up fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="card stats-card border-info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="stat-label">Produtos</p>
                                    <h3 class="stat-value text-primary">
                                        {{ $allProducts->where('type', 'product')->count() }}</h3>
                                    <small class="text-muted">ativos no sistema</small>
                            </div>
                            <div class="text-info">
                                <i class="mdi mdi-arrow-down fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="mdi mdi-filter-variant text-primary me-2"></i>
                        Filtros
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Pesquisar Produto</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                    placeholder="Nome do produto ou serviço...">
                            </div>
                            <div class="col-md-2">
                                <label for="type" class="form-label">Tipo</label>
                                <select class="form-select" name="type" id="type">
                                    <option value="">Todos</option>
                                    <option value="product" {{ request('type') === 'product' ? 'selected' : '' }}>Produto
                                    </option>
                                    <option value="service" {{ request('type') === 'service' ? 'selected' : '' }}>Serviço
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="category_id" class="form-label">Categoria</label>
                                <select class="form-select" name="category_id" id="category_id">
                                    <option value="">Todas</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status" id="status">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Ativo
                                    </option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inativo
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="mdi mdi-magnify me-2"></i> Filtrar
                                    </button>
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">
                                        <i class="mdi mdi-restore me-2"></i> Limpar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="col-12">
            <div class="products-table">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Produto/Serviço</th>
                                    <th>Categoria</th>
                                    <th>Tipo</th>
                                    <th>Preço</th>
                                    <th>Estoque</th>
                                    <th>Status</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-primary">#{{ $product->id }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $product->name }}</div>
                                            @if ($product->description)
                                                <small
                                                    class="text-muted">{{ Str::limit($product->description, 40) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-light text-dark">{{ $product->category?->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            @if ($product->type === 'product')
                                                <span class="type-badge bg-primary bg-opacity-10 text-primary">
                                                    <i class="mdi mdi-food-variant"></i> Produto
                                                </span>
                                            @else
                                                <span class="type-badge bg-info bg-opacity-10 text-info">
                                                    <i class="mdi mdi-cog"></i> Serviço
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold text-success">
                                                MZN {{ number_format($product->price, 2, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if ($product->type === 'product')
                                                <div
                                                    class="stock-display bg-{{ $product->isLowStock() ? 'warning' : 'success' }} bg-opacity-10 text-{{ $product->isLowStock() ? 'warning' : 'success' }}">
                                                    {{ $product->stock_quantity }} {{ $product->unit }}
                                                    @if ($product->isLowStock())
                                                        <i class="mdi mdi-alert-circle"></i>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->is_deleted)
                                                <span class="badge bg-dark text-white">
                                                    <i class="mdi mdi-trash-can-outline me-1"></i> Excluído
                                                </span>
                                            @elseif ($product->is_active)
                                                <span class="badge bg-success text-white">Ativo</span>
                                            @else
                                                <span class="badge bg-secondary text-white">Inativo</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('products.edit', $product->id) }}"
                                                    class="action-btn btn btn-outline-primary" data-bs-toggle="tooltip"
                                                    title="Editar">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>

                                                @if ($product->type === 'product')
                                                    <button type="button" class="action-btn btn btn-outline-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#stockModal{{ $product->id }}"
                                                        title="Ajustar Estoque">
                                                        <i class="mdi mdi-warehouse"></i>
                                                    </button>
                                                @endif

                                                <a href="{{ route('products.show', $product->id) }}"
                                                    class="action-btn btn btn-outline-info" data-bs-toggle="tooltip"
                                                    title="Ver Detalhes">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>

                                                <button type="button" class="action-btn btn btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $product->id }}" title="Excluir">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>

                                                <!-- Toggle Status -->
                                                <form method="POST"
                                                    action="{{ route('products.update', $product->id) }}"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="toggle_status" value="1">
                                                    <input type="hidden" name="is_active"
                                                        value="{{ $product->is_active ? '0' : '1' }}">
                                                    <button type="submit"
                                                        class="action-btn btn btn-outline-{{ $product->is_active ? 'warning' : 'success' }}"
                                                        title="{{ $product->is_active ? 'Desativar' : 'Ativar' }}">
                                                        <i
                                                            class="mdi mdi-{{ $product->is_active ? 'toggle-switch-off' : 'toggle-switch' }}"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal: Ajustar Estoque -->
                                    @if ($product->type === 'product')
                                        <div class="modal fade" id="stockModal{{ $product->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST"
                                                        action="{{ route('products.adjust-stock', $product->id) }}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                <i class="mdi mdi-warehouse me-2 text-success"></i>
                                                                Ajustar Estoque
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="alert alert-light mb-3">
                                                                <h6 class="mb-1">{{ $product->name }}</h6>
                                                                <small class="text-muted">
                                                                    Estoque atual: {{ $product->stock_quantity }}
                                                                    {{ $product->unit }}
                                                                </small>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Tipo de Ajuste
                                                                    *</label>
                                                                <select class="form-select" name="adjustment_type"
                                                                    required>
                                                                    <option value="">Selecione...</option>
                                                                    <option value="increase">Entrada (+)</option>
                                                                    <option value="decrease">Saída (-)</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Quantidade *</label>
                                                                <input type="number" class="form-control"
                                                                    name="quantity" min="1" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Motivo *</label>
                                                                <textarea class="form-control" name="reason" rows="3" maxlength="200" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary"
                                                                data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="mdi mdi-content-save me-2"></i> Confirmar Ajuste
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Modal: Exclusão -->
                                    <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST"
                                                    action="{{ route('products.destroy', $product->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <i class="mdi mdi-delete me-2 text-danger"></i>
                                                            Confirmar Exclusão
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center py-4">
                                                        <i class="mdi mdi-alert-circle text-warning"
                                                            style="font-size: 3rem;"></i>
                                                        <h5 class="mt-3">Confirmar Exclusão Permanente</h5>
                                                        <p class="text-muted">
                                                            <strong>{{ $product->name }}</strong><br>
                                                            @if ($product->stockMovements->count() > 0)
                                                                <span class="text-danger fw-bold">⚠️ ATENÇÃO: Este produto
                                                                    tem histórico de estoque!</span><br>
                                                                <small>
                                                                    O produto será <strong>marcado como excluído</strong> e
                                                                    seu estoque será zerado.<br>
                                                                    <strong>Todas as movimentações passadas serão
                                                                        preservadas.</strong>
                                                                </small>
                                                            @else
                                                                Esta ação <strong>não pode ser desfeita</strong>.
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="mdi mdi-delete me-2"></i> Excluir Produto
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="empty-state">
                                                <i class="mdi mdi-food-variant text-muted"></i>
                                                <h5>Nenhum produto encontrado</h5>
                                                <p class="mb-4">Não há produtos que correspondam aos filtros aplicados.
                                                </p>
                                                <a href="{{ route('products.create') }}" class="btn btn-primary">
                                                    <i class="mdi mdi-plus me-2"></i> Adicionar Produto
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($products->hasPages())
                        <div class="card-footer bg-light border-top-0">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="text-muted small">
                                    Mostrando {{ $products->firstItem() ?? 0 }} a {{ $products->lastItem() ?? 0 }} de
                                    {{ $products->total() }}
                                </div>
                                <div>
                                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Adiciona efeito de hover nos cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endpush
