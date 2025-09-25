@extends('layouts.app')

@section('title', 'Detalhes da Categoria')
@section('title-icon', 'mdi-format-list-bulleted')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('categories.index') }}" class="d-flex align-items-center">
            <i class="mdi mdi-format-list-bulleted me-1"></i> Categorias
        </a>
    </li>
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-folder-outline me-1"></i> {{ $category->name }}
    </li>
@endsection

@section('styles')
<style>
.category-detail-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    background: var(--bs-card-bg);
    overflow: hidden;
}

.category-header {
    background: linear-gradient(135deg, var(--primary-color), #6366f1);
    color: white;
    padding: 2rem;
    position: relative;
}

.category-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899, #3b82f6);
    background-size: 300% 100%;
    animation: wave 2s ease infinite;
}

@keyframes wave {
    0% { background-position: 0% 50%; }
    100% { background-position: 300% 50%; }
}

.category-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    margin-bottom: 1rem;
}

.category-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
}

.category-meta {
    opacity: 0.9;
    font-size: 0.95rem;
}

/* Product List */
.product-item {
    border-radius: 12px;
    padding: 1rem;
    transition: all 0.2s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.product-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border-color: rgba(79, 70, 229, 0.2);
}

.product-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.product-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-color);
}

.product-status {
    font-size: 0.85rem;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
}

/* Empty State */
.empty-product {
    text-align: center;
    padding: 3rem 1rem;
    color: rgba(0, 0, 0, 0.6);
}

.empty-product i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .category-header {
        padding: 1.5rem;
    }
    
    .category-title {
        font-size: 1.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn {
        width: 100%;
    }
}
</style>
@endsection

@section('content')
<div class="row g-4">
    <!-- Header Card -->
    <div class="col-12">
        <div class="category-detail-card">
            <div class="category-header">
                <div class="category-icon">
                    <i class="mdi mdi-folder-outline mdi-24px"></i>
                </div>
                <h1 class="category-title">{{ $category->name }}</h1>
                <p class="category-meta">
                    Criada em {{ \Carbon\Carbon::parse($category->created_at)->format('d/m/Y \à\s H:i') }}
                    • ID: {{ $category->id }}
                </p>
            </div>
            
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-0">
                            <i class="mdi mdi-food-variant me-2 text-primary"></i>
                            Produtos nesta Categoria ({{ $category->products->count() }})
                        </h4>
                        <p class="text-muted mb-0">Lista de todos os produtos associados a esta categoria</p>
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('products.create') }}?category_id={{ $category->id }}" class="btn btn-primary">
                            <i class="mdi mdi-plus me-2"></i> Adicionar Produto
                        </a>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                            <i class="mdi mdi-pencil me-2"></i> Editar Categoria
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left me-2"></i> Voltar
                        </a>
                    </div>
                </div>

                <!-- Products List -->
                @if($category->products->count() > 0)
                    <div class="row g-3">
                        @foreach($category->products as $product)
                        <div class="col-md-6 col-lg-4">
                            <div class="product-item">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="product-name">{{ $product->name }}</div>
                                    <span class="product-status bg-{{ $product->is_active ? 'success' : 'danger' }} text-white">
                                        {{ $product->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="product-price">MZN {{ number_format($product->price, 2, ',', '.') }}</div>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="mdi mdi-eye"></i> Ver
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-product">
                        <i class="mdi mdi-food-off-outline text-muted"></i>
                        <h4>Nenhum produto nesta categoria</h4>
                        <p>Adicione produtos para começar a usar esta categoria no seu cardápio.</p>
                        <a href="{{ route('products.create') }}?category_id={{ $category->id }}" class="btn btn-primary mt-3">
                            <i class="mdi mdi-plus me-2"></i> Criar Primeiro Produto
                        </a>
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
    // Inicializa tooltips se necessário
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
@endpush