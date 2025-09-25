@extends('layouts.app')

@section('page-title', 'Detalhes do Produto')
@section('title-icon', 'mdi-food-variant')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('products.index') }}" class="d-flex align-items-center">
            <i class="mdi mdi-food-variant me-1"></i> Produtos
        </a>
    </li>
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-cube-outline me-1"></i> {{ $product->name }}
    </li>
@endsection

@section('styles')
<style>
.product-detail-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    background: var(--bs-card-bg);
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.product-header {
    padding: 2rem;
    text-align: center;
    position: relative;
}

.product-image {
    width: 120px;
    height: 120px;
    border-radius: 16px;
    object-fit: cover;
    margin: 0 auto 1.5rem;
    border: 3px solid var(--primary-color);
}

.product-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    color: var(--bs-body-color);
}

.product-category {
    color: var(--bs-secondary-color);
    margin: 0.5rem 0 1rem;
    font-size: 1rem;
}

.product-stats {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 1.5rem;
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--bs-body-color);
}

.stat-label {
    color: var(--bs-secondary-color);
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

.chart-container {
    height: 200px;
    position: relative;
}

.stock-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 500;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.action-buttons {
    background: var(--bs-card-bg);
    border-radius: 16px;
    padding: 1.5rem;
    margin-top: 1.5rem;
}

@media (max-width: 768px) {
    .product-header {
        padding: 1.5rem;
    }
    
    .product-stats {
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .stat-item {
        min-width: 100px;
    }
}
</style>
@endsection

@section('content')
<div class="row g-4">
    <!-- Product Header -->
    <div class="col-12">
        <div class="product-detail-card">
            <div class="product-header">
                @if($product->image_path)
                    <img src="{{ Storage::url($product->image_path) }}" 
                         class="product-image" 
                         alt="{{ $product->name }}">
                @else
                    <div class="product-image bg-secondary d-flex align-items-center justify-content-center text-white">
                        <i class="mdi mdi-food-variant" style="font-size: 2.5rem;"></i>
                    </div>
                @endif

                <h1 class="product-title">{{ $product->name }}</h1>
                <p class="product-category">
                    Categoria: {{ $product->category->name ?? 'Sem categoria' }}
                </p>

                <div class="product-stats">
                    <div class="stat-item">
                        <div class="stat-value">MZN {{ number_format($product->price, 2, ',', '.') }}</div>
                        <div class="stat-label">Preço</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">
                            <span class="stock-badge bg-{{ $product->stock_quantity <= $product->min_stock_level ? 'danger' : 'success' }} text-white">
                                {{ $product->stock_quantity }}
                            </span>
                        </div>
                        <div class="stat-label">Estoque Atual</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">
                            <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                                {{ $product->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                        <div class="stat-label">Status</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Charts -->
        <div class="row g-4">
            <div class="col-12">
                <div class="product-detail-card">
                    <div class="card-body">
                        <h5 class="mb-3">
                            <i class="mdi mdi-chart-line text-primary me-2"></i>
                            Histórico de Vendas
                        </h5>
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="product-detail-card">
                    <div class="card-body">
                        <h5 class="mb-3">
                            <i class="mdi mdi-warehouse text-success me-2"></i>
                            Histórico de Estoque
                        </h5>
                        <div class="chart-container">
                            <canvas id="stockChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Product Details -->
        <div class="product-detail-card">
            <div class="card-body">
                <h5 class="mb-3">
                    <i class="mdi mdi-information-outline text-primary me-2"></i>
                    Informações do Produto
                </h5>
                
                @if($product->description)
                    <div class="mb-3">
                        <label class="text-muted">Descrição</label>
                        <p class="mb-0">{{ $product->description }}</p>
                    </div>
                @endif
                
                <div class="mb-3">
                    <label class="text-muted">Código do Produto</label>
                    <p class="mb-0">{{ $product->code ?? 'N/A' }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted">Estoque Mínimo</label>
                    <p class="mb-0">{{ $product->min_stock_level }} unidades</p>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted">Criado em</label>
                    <p class="mb-0">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                @if($product->updated_at != $product->created_at)
                    <div class="mb-3">
                        <label class="text-muted">Última Atualização</label>
                        <p class="mb-0">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <div class="d-grid gap-2">
                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                    <i class="mdi mdi-pencil me-2"></i> Editar Produto
                </a>
                
                <a href="{{ route('stock-movements.create') }}?product_id={{ $product->id }}" class="btn btn-outline-primary">
                    <i class="mdi mdi-plus me-2"></i> Registrar Movimento
                </a>
                
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class="mdi mdi-arrow-left me-2"></i> Voltar à Lista
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Carregar dados do histórico de vendas e estoque
    loadSalesData();
    loadStockData();
});

function loadSalesData() {
    fetch(`/products/{{ $product->id }}/sales-data`)
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.dates,
                    datasets: [{
                        label: 'Vendas',
                        data: data.quantities,
                        borderColor: 'var(--primary-color)',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: 'var(--primary-color)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
}

function loadStockData() {
    fetch(`/products/{{ $product->id }}/stock-history`)
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('stockChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.dates,
                    datasets: [{
                        label: 'Nível de Estoque',
                        data: data.quantities,
                        borderColor: 'var(--success-color)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: 'var(--success-color)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
}
</script>
@endpush