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
        /* Header com gradiente */
        .products-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .products-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--beach-gradient);
        }

        .products-title {
            font-size: 2rem;
            font-weight: 800;
            background: var(--beach-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        /* Stats cards compactos */
        .stats-card {
            transition: var(--transition);
            border-left: 4px solid transparent;
        }

        .stats-card.low-stock {
            border-left-color: var(--danger-color);
        }

        .stats-card.categories {
            border-left-color: var(--success-color);
        }

        .stats-card.services {
            border-left-color: var(--warning-color);
        }

        .stats-card.products {
            border-left-color: var(--primary-color);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        /* Type badges */
        .type-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Stock display */
        .stock-display {
            padding: 0.3rem 0.6rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Action buttons */
        .action-btn {
            width: 35px;
            height: 35px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: var(--transition);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Responsive buttons */
        .header-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .header-actions {
                width: 100%;
                justify-content: center;
            }

            .header-actions .btn {
                flex: 1;
                min-width: auto;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Header -->
    <div class="products-header fade-in">
        <div class="row align-items-center mb-4">
            <!-- Título e descrição -->
            <div class="col-lg-8">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-3">
                    <h1 class="products-title d-flex align-items-center gap-2 mb-0">
                        <i class="mdi mdi-food-variant text-primary fs-1"></i>
                        <span>Produtos e Serviços</span>
                    </h1>
                </div>
                <p class="text-muted mt-2 mb-0 fs-6">
                    Gerencie todos os produtos e serviços <br> de forma simples e rápida.
                </p>
            </div>

            <!-- Ações -->
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="btn-toolbar justify-content-lg-end gap-2" role="toolbar">
                    <!-- Grupo: Adicionar -->
                    <div class="btn-group" role="group" aria-label="Adicionar">
                        <a href="{{ route('products.create') }}?type=product" class="btn btn-primary"
                            data-bs-toggle="tooltip" title="Adicionar novo produto">
                            <i class="mdi mdi-plus"></i> Produto
                        </a>
                        <a href="{{ route('products.create') }}?type=service" class="btn btn-info" data-bs-toggle="tooltip"
                            title="Adicionar novo serviço">
                            <i class="mdi mdi-cog"></i> Serviço
                        </a>
                        <!-- Categorias -->
                        <a href="{{ route('categories.index') }}" class="btn btn-success" data-bs-toggle="tooltip"
                            title="Gerenciar categorias">
                            <i class="mdi mdi-format-list-bulleted"></i>
                            <span class="d-none d-md-inline">Categorias</span>
                        </a>
                        @can('view_reports')
                            <!-- Exportar -->
                            <a href="{{ route('reports.inventory') }}" class="btn btn-outline-secondary"
                                data-bs-toggle="tooltip" title="Exportar lista de produtos e serviços">
                                <i class="mdi mdi-file-export"></i>
                                <span class="d-none d-md-inline">Exportar</span>
                            </a>
                        @endcan
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card stats-card low-stock h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 fw-semibold">Estoque Baixo</h6>
                            <h3 class="mb-0 text-danger fw-bold">{{ $lowStockCount ?? 0 }}</h3>
                            <small class="text-muted">produtos críticos</small>
                        </div>
                        <div class="text-danger">
                            <i class="mdi mdi-alert-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card stats-card categories h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 fw-semibold">Categorias</h6>
                            <h3 class="mb-0 text-success fw-bold">{{ $categories->count() ?? 0 }}</h3>
                            <small class="text-muted">organização</small>
                        </div>
                        <div class="text-success">
                            <i class="mdi mdi-format-list-bulleted fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card stats-card services h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 fw-semibold">Serviços</h6>
                            <h3 class="mb-0 text-warning fw-bold">{{ $allProducts->where('type', 'service')->count() }}</h3>
                            <small class="text-muted">cadastrados</small>
                        </div>
                        <div class="text-warning">
                            <i class="mdi mdi-cog fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="card stats-card products h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 fw-semibold">Produtos</h6>
                            <h3 class="mb-0 text-primary fw-bold">{{ $allProducts->where('type', 'product')->count() }}</h3>
                            <small class="text-muted">ativos no sistema</small>
                        </div>
                        <div class="text-primary">
                            <i class="mdi mdi-food-variant fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 fade-in">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0 d-flex align-items-center">
                <i class="mdi mdi-filter-variant me-2 text-primary"></i>
                Filtros
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label fw-semibold">Pesquisar Produto</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                            placeholder="Nome do produto ou serviço...">
                    </div>
                    <div class="col-md-2">
                        <label for="type" class="form-label fw-semibold">Tipo</label>
                        <select class="form-select" name="type" id="type">
                            <option value="">Todos</option>
                            <option value="product" {{ request('type') === 'product' ? 'selected' : '' }}>Produto</option>
                            <option value="service" {{ request('type') === 'service' ? 'selected' : '' }}>Serviço</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="category_id" class="form-label fw-semibold">Categoria</label>
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
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status" id="status">
                            <option value="">Todos</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary flex-fill">
                                <i class="mdi mdi-refresh"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Produtos -->
    <div class="card fade-in">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
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
                                        <small class="text-muted">{{ Str::limit($product->description, 40) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $product->category?->name ?? 'N/A' }}</span>
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
                                        <span
                                            class="stock-display bg-{{ $product->isLowStock() ? 'warning' : 'success' }} bg-opacity-10 text-{{ $product->isLowStock() ? 'warning' : 'success' }}">
                                            {{ $product->stock_quantity }} {{ $product->unit }}
                                            @if ($product->isLowStock())
                                                <i class="mdi mdi-alert-circle"></i>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($product->is_deleted)
                                        <span class="badge bg-dark">
                                            <i class="mdi mdi-trash-can-outline me-1"></i> Excluído
                                        </span>
                                    @elseif ($product->is_active)
                                        <span class="badge bg-success">Ativo</span>
                                    @else
                                        <span class="badge bg-secondary">Inativo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="action-btn btn btn-outline-primary" data-bs-toggle="tooltip"
                                            title="Editar">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        @if ($product->type === 'product')
                                            <button type="button" class="action-btn btn btn-outline-success"
                                                data-bs-toggle="modal" data-bs-target="#stockModal{{ $product->id }}"
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
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}"
                                            title="Excluir">
                                            <i class="mdi mdi-delete"></i>
                                        </button>

                                        <!-- Toggle Status -->
                                        <form method="POST" action="{{ route('products.update', $product->id) }}"
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
                                                        <label class="form-label fw-semibold">Tipo de Ajuste *</label>
                                                        <select class="form-select" name="adjustment_type" required>
                                                            <option value="">Selecione...</option>
                                                            <option value="increase">Entrada (+)</option>
                                                            <option value="decrease">Saída (-)</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Quantidade *</label>
                                                        <input type="number" class="form-control" name="quantity"
                                                            min="1" required>
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
                                        <form method="POST" action="{{ route('products.destroy', $product->id) }}">
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
                                                <i class="mdi mdi-alert-circle text-warning" style="font-size: 3rem;"></i>
                                                <h5 class="mt-3">Confirmar Exclusão Permanente</h5>
                                                <p class="text-muted">
                                                    <strong>{{ $product->name }}</strong><br>
                                                    @if ($product->stockMovements->count() > 0)
                                                        <span class="text-danger fw-bold">⚠️ ATENÇÃO: Este produto tem
                                                            histórico de estoque!</span><br>
                                                        <small>
                                                            O produto será <strong>marcado como excluído</strong> e seu
                                                            estoque será zerado.<br>
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
                                        <i class="mdi mdi-food-variant"></i>
                                        <h5>Nenhum produto encontrado</h5>
                                        <p class="mb-4">Não há produtos que correspondam aos filtros aplicados.</p>
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
                <div class="card-footer bg-light">
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Hover effects nos stats cards
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Hover effects nos action buttons
            const actionBtns = document.querySelectorAll('.action-btn');
            actionBtns.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                btn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endpush
