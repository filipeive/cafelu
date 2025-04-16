
@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-0">{{ $product->name }}</h4>
                            <small class="text-muted">Detalhes do Produto</small>
                        </div>
                        <div>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Voltar
                            </a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                                <i class="mdi mdi-pencil"></i> Editar
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Detalhes do Produto -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    @if($product->image_path)
                                        <img src="{{ Storage::url($product->image_path) }}" 
                                             class="img-fluid rounded mb-3" 
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="text-center p-4 bg-light rounded mb-3">
                                            <i class="mdi mdi-image-outline" style="font-size: 4rem;"></i>
                                            <p class="text-muted mb-0">Sem imagem</p>
                                        </div>
                                    @endif

                                    <h5 class="mb-3">Informações Básicas</h5>
                                    <div class="mb-3">
                                        <label class="text-muted">Categoria</label>
                                        <p class="mb-0">{{ $product->category->name ?? 'Sem categoria' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted">Preço</label>
                                        <p class="mb-0">MZN {{ number_format($product->price, 2, ',', '.') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted">Estoque Atual</label>
                                        <p class="mb-0 {{ $product->stock_quantity <= 5 ? 'text-danger' : 'text-success' }}">
                                            {{ $product->stock_quantity }} unidades
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted">Status</label>
                                        <p class="mb-0">
                                            @if($product->is_active)
                                                <span class="badge bg-success">Ativo</span>
                                            @else
                                                <span class="badge bg-danger">Inativo</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráficos e Estatísticas -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="mb-3">Histórico de Vendas</h5>
                                            <canvas id="salesChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="mb-3">Histórico de Estoque</h5>
                                            <canvas id="stockChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    fetch(`/products/${@json($product->id)}/sales-data`)
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
                        borderColor: '#0d6efd',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
}

function loadStockData() {
    fetch(`/products/${@json($product->id)}/stock-history`)
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
                        borderColor: '#198754',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
}
</script>
@endpush