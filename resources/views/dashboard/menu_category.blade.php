@extends('layouts.app')

@section('title', $category->name)
@section('page-title', $category->name)
@section('title-icon', $category->icon ?? 'mdi-food-variant')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('menu.index') }}" class="text-decoration-none">
            <i class="mdi mdi-book-open-variant"></i> Cardápio
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        <i class="mdi {{ $category->icon ?? 'mdi-food-variant' }}"></i> {{ $category->name }}
    </li>
@endsection

@push('styles')
<style>
    /* Category Header Enhanced */
    .category-header-enhanced {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .category-header-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--beach-gradient);
    }

    .category-title-enhanced {
        font-size: 2rem;
        font-weight: 800;
        background: var(--beach-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .category-title-enhanced i {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 2.2rem;
    }

    .category-description-enhanced {
        color: #6b7280;
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .category-stats {
        display: flex;
        gap: 1.5rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .category-stat-item {
        background: rgba(8, 145, 178, 0.1);
        padding: 0.75rem 1.25rem;
        border-radius: 50px;
        font-weight: 600;
        color: var(--primary-color);
        border: 2px solid rgba(8, 145, 178, 0.2);
        backdrop-filter: blur(10px);
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .back-button-enhanced {
        background: rgba(107, 114, 128, 0.1);
        border: 2px solid rgba(107, 114, 128, 0.2);
        color: #6b7280;
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        backdrop-filter: blur(10px);
    }

    .back-button-enhanced:hover {
        background: rgba(107, 114, 128, 0.2);
        color: #4b5563;
        text-decoration: none;
        transform: translateX(-3px);
        box-shadow: var(--shadow-md);
    }

    /* Enhanced Product Cards */
    .product-grid-enhanced {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .product-card-enhanced {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-soft);
        overflow: hidden;
        transition: var(--transition);
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .product-card-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--beach-gradient);
        transform: scaleX(0);
        transition: transform 0.4s ease;
        transform-origin: left;
    }

    .product-card-enhanced:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
    }

    .product-card-enhanced:hover::before {
        transform: scaleX(1);
    }

    .product-card-body {
        padding: 1.75rem;
    }

    .product-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 1rem;
        gap: 1rem;
    }

    .product-icon-enhanced {
        width: 56px;
        height: 56px;
        border-radius: var(--border-radius);
        background: rgba(8, 145, 178, 0.15);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        box-shadow: 0 8px 32px rgba(8, 145, 178, 0.2);
    }

    .product-info {
        flex: 1;
    }

    .product-name-enhanced {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark-color);
        margin: 0 0 0.5rem 0;
        line-height: 1.3;
    }

    .product-description-enhanced {
        color: #6b7280;
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .product-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .product-price-enhanced {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--success-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .product-price-enhanced i {
        font-size: 1.2rem;
    }

    .product-availability {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        backdrop-filter: blur(10px);
    }

    .product-availability.available {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success-color);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .product-availability.unavailable {
        background: rgba(239, 68, 68, 0.15);
        color: var(--danger-color);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .product-availability.low-stock {
        background: rgba(245, 158, 11, 0.15);
        color: var(--warning-color);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    /* Product Actions */
    .product-actions {
        padding: 1rem 1.75rem;
        background: rgba(248, 250, 252, 0.8);
        backdrop-filter: blur(10px);
        display: flex;
        gap: 0.75rem;
        justify-content: space-between;
        align-items: center;
    }

    .product-stock-info {
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 500;
    }

    .add-to-cart-btn {
        background: var(--primary-gradient);
        border: none;
        color: white;
        border-radius: 50px;
        padding: 0.5rem 1.25rem;
        font-size: 0.9rem;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .add-to-cart-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        color: white;
    }

    .add-to-cart-btn:disabled {
        background: #d1d5db;
        color: #9ca3af;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Empty State */
    .empty-state-enhanced {
        text-align: center;
        padding: 4rem 2rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
    }

    .empty-state-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--beach-gradient);
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(107, 114, 128, 0.1);
        color: #9ca3af;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 1.5rem;
    }

    .empty-state-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.75rem;
    }

    .empty-state-description {
        color: #6b7280;
        font-size: 1rem;
        margin-bottom: 2rem;
        line-height: 1.5;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .category-header-enhanced {
            padding: 1.5rem;
            text-align: center;
        }

        .category-title-enhanced {
            font-size: 1.5rem;
            justify-content: center;
        }

        .category-stats {
            justify-content: center;
        }

        .product-grid-enhanced {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .product-header {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .product-meta {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .product-actions {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }

    /* Loading animations */
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .fade-in-delay-1 { animation-delay: 0.1s; }
    .fade-in-delay-2 { animation-delay: 0.2s; }
    .fade-in-delay-3 { animation-delay: 0.3s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
    <!-- Category Header Enhanced -->
    <div class="category-header-enhanced fade-in-up">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <a href="{{ route('menu.index') }}" class="back-button-enhanced">
                        <i class="mdi mdi-arrow-left"></i>
                        Voltar ao Cardápio
                    </a>
                </div>
                
                <h1 class="category-title-enhanced">
                    <i class="mdi {{ $category->icon ?? 'mdi-food-variant' }}"></i>
                    {{ $category->name }}
                </h1>
                
                @if($category->description)
                    <p class="category-description-enhanced">{{ $category->description }}</p>
                @endif
            </div>
            
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="category-stats">
                    <div class="category-stat-item">
                        <i class="mdi mdi-food-variant"></i>
                        {{ $products->count() }} produto{{ $products->count() == 1 ? '' : 's' }}
                    </div>
                    <div class="category-stat-item">
                        <i class="mdi mdi-check-circle"></i>
                        {{ $products->where('is_available', true)->count() }} disponível{{ $products->where('is_available', true)->count() == 1 ? '' : 'is' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="product-grid-enhanced">
            @foreach($products as $index => $product)
                <div class="product-card-enhanced fade-in-up fade-in-delay-{{ ($index % 3) + 1 }}">
                    <div class="product-card-body">
                        <div class="product-header">
                            <div class="product-icon-enhanced">
                                <i class="mdi {{ $product->icon ?? 'mdi-food-variant' }}"></i>
                            </div>
                            
                            <div class="product-info">
                                <h3 class="product-name-enhanced">{{ $product->name }}</h3>
                                
                                @if($product->description)
                                    <p class="product-description-enhanced">{{ $product->description }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="product-meta">
                            <div class="product-price-enhanced">
                                <i class="mdi mdi-currency-usd"></i>
                                {{ number_format($product->selling_price, 2, ',', '.') }} MZN
                            </div>

                            @php
                                $availabilityStatus = 'available';
                                $availabilityText = 'Disponível';
                                
                                if (!$product->is_available) {
                                    $availabilityStatus = 'unavailable';
                                    $availabilityText = 'Indisponível';
                                } elseif (isset($product->stock_quantity) && $product->stock_quantity <= $product->min_stock_level) {
                                    $availabilityStatus = 'low-stock';
                                    $availabilityText = 'Estoque Baixo';
                                }
                            @endphp

                            <div class="product-availability {{ $availabilityStatus }}">
                                <i class="mdi {{ $availabilityStatus === 'available' ? 'mdi-check-circle' : ($availabilityStatus === 'unavailable' ? 'mdi-close-circle' : 'mdi-alert-circle') }}"></i>
                                {{ $availabilityText }}
                            </div>
                        </div>
                    </div>

                    <div class="product-actions">
                        <div class="product-stock-info">
                            @if(isset($product->stock_quantity))
                                <i class="mdi mdi-package-variant"></i>
                                Estoque: {{ $product->stock_quantity }} {{ $product->unit ?? 'un' }}
                            @else
                                <i class="mdi mdi-information-outline"></i>
                                Produto sem controle de estoque
                            @endif
                        </div>

                        <button class="add-to-cart-btn" 
                                {{ !$product->is_available ? 'disabled' : '' }}
                                onclick="addToCart({{ $product->id }})">
                            <i class="mdi {{ !$product->is_available ? 'mdi-close' : 'mdi-cart-plus' }}"></i>
                            {{ !$product->is_available ? 'Indisponível' : 'Adicionar' }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state-enhanced fade-in-up">
            <div class="empty-state-icon">
                <i class="mdi mdi-food-off"></i>
            </div>
            <h3 class="empty-state-title">Nenhum produto encontrado</h3>
            <p class="empty-state-description">
                Esta categoria ainda não possui produtos cadastrados.<br>
                Que tal adicionar alguns itens deliciosos ao cardápio?
            </p>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus-circle me-2"></i>
                Adicionar Produto
            </a>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    function addToCart(productId) {
        // Implementar lógica de adicionar ao carrinho
        // Por enquanto, apenas uma notificação
        showToast('Funcionalidade de carrinho em desenvolvimento', 'info');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Adicionar efeitos de hover suaves nos cards
        const productCards = document.querySelectorAll('.product-card-enhanced');
        productCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
</script>
@endpush