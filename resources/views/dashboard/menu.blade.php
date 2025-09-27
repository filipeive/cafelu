@extends('layouts.app')

@section('title', 'Cardápio Digital')
@section('page-title', 'Cardápio Digital')
@section('title-icon', 'mdi-book-open-variant')

@section('breadcrumbs')
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-book-open-variant me-1"></i> Cardápio Digital
    </li>
@endsection

@push('styles')
<style>
    /* Menu Header Enhanced */
    .menu-header-enhanced {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .menu-header-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--beach-gradient);
    }

    .menu-welcome-text {
        font-size: 2.2rem;
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

    .menu-welcome-text i {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 2.4rem;
    }

    .menu-subtitle {
        color: #6b7280;
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .menu-stats {
        display: flex;
        gap: 1.5rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .menu-stat-item {
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

    .menu-stat-item.secondary {
        background: rgba(245, 158, 11, 0.1);
        color: var(--secondary-color);
        border-color: rgba(245, 158, 11, 0.2);
    }

    .menu-stat-item.success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
        border-color: rgba(16, 185, 129, 0.2);
    }

    /* Enhanced Category Grid */
    .category-grid-enhanced {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .category-card-enhanced {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-soft);
        overflow: hidden;
        transition: var(--transition);
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.2);
        min-height: 280px;
        display: flex;
        flex-direction: column;
    }

    .category-card-enhanced::before {
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

    .category-card-enhanced:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
    }

    .category-card-enhanced:hover::before {
        transform: scaleX(1);
    }

    .category-header-enhanced {
        background: var(--beach-gradient);
        color: white;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .category-header-enhanced::after {
        content: '';
        position: absolute;
        top: 0;
        right: -50px;
        width: 100px;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        transform: skewX(-15deg);
    }

    .category-title-enhanced {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .category-title-enhanced i {
        font-size: 1.6rem;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    }

    .category-body-enhanced {
        flex: 1;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
    }

    .category-description {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 1.25rem;
        line-height: 1.5;
    }

    .products-preview {
        flex: 1;
        margin-bottom: 1rem;
    }

    .product-preview-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        gap: 1rem;
    }

    .product-preview-item:last-child {
        border-bottom: none;
    }

    .product-preview-info {
        flex: 1;
        min-width: 0;
    }

    .product-preview-name {
        font-weight: 600;
        color: var(--dark-color);
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .product-preview-desc {
        color: #6b7280;
        font-size: 0.85rem;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .product-preview-price {
        font-weight: 700;
        color: var(--success-color);
        font-size: 1rem;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .more-products-indicator {
        text-align: center;
        color: #9ca3af;
        font-size: 0.85rem;
        font-style: italic;
        padding: 0.5rem 0;
        border-top: 1px dashed rgba(0, 0, 0, 0.1);
    }

    .category-footer-enhanced {
        padding: 1.25rem 1.5rem;
        background: rgba(248, 250, 252, 0.8);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .category-stats-enhanced {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .category-badge-enhanced {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        backdrop-filter: blur(10px);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .category-badge-enhanced.primary {
        background: rgba(8, 145, 178, 0.15);
        color: var(--primary-color);
        border: 1px solid rgba(8, 145, 178, 0.3);
    }

    .category-badge-enhanced.success {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success-color);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .view-category-btn {
        background: var(--primary-gradient);
        border: none;
        color: white;
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .view-category-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        color: white;
        text-decoration: none;
    }

    /* Empty State Enhanced */
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
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(245, 158, 11, 0.1);
        color: var(--secondary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        margin: 0 auto 2rem;
        box-shadow: 0 8px 32px rgba(245, 158, 11, 0.2);
    }

    .empty-state-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 1rem;
    }

    .empty-state-description {
        color: #6b7280;
        font-size: 1.1rem;
        margin-bottom: 2rem;
        line-height: 1.6;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .empty-state-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .empty-state-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .empty-state-btn.primary {
        background: var(--primary-gradient);
        color: white;
        border: none;
    }

    .empty-state-btn.primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        color: white;
        text-decoration: none;
    }

    .empty-state-btn.secondary {
        background: rgba(245, 158, 11, 0.1);
        color: var(--secondary-color);
        border: 2px solid rgba(245, 158, 11, 0.2);
    }

    .empty-state-btn.secondary:hover {
        background: rgba(245, 158, 11, 0.2);
        color: var(--secondary-color);
        text-decoration: none;
        transform: translateY(-2px);
    }

    /* Quick Actions Bar */
    .quick-actions-bar {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-soft);
        padding: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .quick-actions-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--sunset-gradient);
    }

    .quick-actions-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .quick-actions-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dark-color);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }

    .quick-actions-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .quick-action-btn {
        background: rgba(8, 145, 178, 0.1);
        border: 2px solid rgba(8, 145, 178, 0.2);
        color: var(--primary-color);
        border-radius: 50px;
        padding: 0.5rem 1.25rem;
        font-size: 0.9rem;
        font-weight: 600;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quick-action-btn:hover {
        background: rgba(8, 145, 178, 0.2);
        color: var(--primary-color);
        text-decoration: none;
        transform: translateY(-1px);
    }

    .quick-action-btn.secondary {
        background: rgba(245, 158, 11, 0.1);
        border-color: rgba(245, 158, 11, 0.2);
        color: var(--secondary-color);
    }

    .quick-action-btn.secondary:hover {
        background: rgba(245, 158, 11, 0.2);
        color: var(--secondary-color);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .menu-header-enhanced {
            padding: 1.5rem;
            text-align: center;
        }

        .menu-welcome-text {
            font-size: 1.8rem;
            justify-content: center;
        }

        .menu-stats {
            justify-content: center;
        }

        .category-grid-enhanced {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .quick-actions-content {
            flex-direction: column;
            text-align: center;
        }

        .category-footer-enhanced {
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
    .fade-in-delay-4 { animation-delay: 0.4s; }

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
    <!-- Menu Header Enhanced -->
    <div class="menu-header-enhanced fade-in-up">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="menu-welcome-text">
                    <i class="mdi mdi-book-open-variant"></i>
                    Cardápio Digital ZALALA
                </h1>
                <p class="menu-subtitle">Descubra nossos deliciosos pratos e bebidas especiais</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="menu-stats">
                    <div class="menu-stat-item">
                        <i class="mdi mdi-view-grid"></i>
                        {{ $categories->count() }} categoria{{ $categories->count() == 1 ? '' : 's' }}
                    </div>
                    <div class="menu-stat-item secondary">
                        <i class="mdi mdi-food-variant"></i>
                        {{ $categories->sum(function($cat) { return $cat->products->count(); }) }} produto{{ $categories->sum(function($cat) { return $cat->products->count(); }) == 1 ? '' : 's' }}
                    </div>
                    <div class="menu-stat-item success">
                        <i class="mdi mdi-check-circle"></i>
                        {{ $categories->sum(function($cat) { return $cat->products->where('is_available', true)->count(); }) }} disponível{{ $categories->sum(function($cat) { return $cat->products->where('is_available', true)->count(); }) == 1 ? '' : 'is' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Bar -->
    <div class="quick-actions-bar fade-in-up fade-in-delay-1">
        <div class="quick-actions-content">
            <h3 class="quick-actions-title">
                <i class="mdi mdi-lightning-bolt text-warning"></i>
                Ações Rápidas
            </h3>
            <div class="quick-actions-buttons">
                <a href="{{ route('pos.index') }}" class="quick-action-btn">
                    <i class="mdi mdi-point-of-sale"></i>
                    Abrir PDV
                </a>
                <a href="{{ route('products.create') }}" class="quick-action-btn secondary">
                    <i class="mdi mdi-plus-circle"></i>
                    Novo Produto
                </a>
                <a href="{{ route('categories.create') }}" class="quick-action-btn">
                    <i class="mdi mdi-folder-plus"></i>
                    Nova Categoria
                </a>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    @if($categories->count() > 0)
        <div class="category-grid-enhanced">
            @foreach($categories as $index => $category)
                <div class="category-card-enhanced fade-in-up fade-in-delay-{{ ($index % 4) + 1 }}">
                    <!-- Category Header -->
                    <div class="category-header-enhanced">
                        <h3 class="category-title-enhanced">
                            <i class="mdi {{ $category->icon ?? 'mdi-food-variant' }}"></i>
                            {{ $category->name }}
                        </h3>
                    </div>

                    <!-- Category Body -->
                    <div class="category-body-enhanced">
                        @if($category->description)
                            <p class="category-description">{{ Str::limit($category->description, 120) }}</p>
                        @endif

                        <!-- Products Preview -->
                        <div class="products-preview">
                            @forelse($category->products->take(3) as $product)
                                <div class="product-preview-item">
                                    <div class="product-preview-info">
                                        <div class="product-preview-name">{{ $product->name }}</div>
                                        @if($product->description)
                                            <div class="product-preview-desc">{{ Str::limit($product->description, 50) }}</div>
                                        @endif
                                    </div>
                                    <div class="product-preview-price">
                                        {{ number_format($product->selling_price, 2, ',', '.') }} MZN
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-3 text-muted">
                                    <i class="mdi mdi-food-off-outline" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">Nenhum produto nesta categoria</p>
                                </div>
                            @endforelse

                            @if($category->products->count() > 3)
                                <div class="more-products-indicator">
                                    + {{ $category->products->count() - 3 }} produto{{ ($category->products->count() - 3) == 1 ? '' : 's' }} a mais...
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Category Footer -->
                    <div class="category-footer-enhanced">
                        <div class="category-stats-enhanced">
                            <span class="category-badge-enhanced primary">
                                <i class="mdi mdi-food-variant"></i>
                                {{ $category->products->count() }} item{{ $category->products->count() == 1 ? '' : 's' }}
                            </span>
                            @if($category->products->where('is_available', true)->count() > 0)
                                <span class="category-badge-enhanced success">
                                    <i class="mdi mdi-check"></i>
                                    {{ $category->products->where('is_available', true)->count() }} ativo{{ $category->products->where('is_available', true)->count() == 1 ? '' : 's' }}
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('menu.category', $category->id) }}" class="view-category-btn">
                            <i class="mdi mdi-arrow-right"></i>
                            Ver Categoria
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state-enhanced fade-in-up">
            <div class="empty-state-icon">
                <i class="mdi mdi-book-open-blank-variant"></i>
            </div>
            <h2 class="empty-state-title">Cardápio em Construção</h2>
            <p class="empty-state-description">
                Nosso cardápio digital está sendo preparado com muito carinho!<br>
                Comece criando categorias e adicionando seus produtos deliciosos.
            </p>
            <div class="empty-state-actions">
                <a href="{{ route('categories.create') }}" class="empty-state-btn primary">
                    <i class="mdi mdi-folder-plus"></i>
                    Criar Primeira Categoria
                </a>
                <a href="{{ route('products.create') }}" class="empty-state-btn secondary">
                    <i class="mdi mdi-food-variant"></i>
                    Adicionar Produtos
                </a>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Adicionar efeitos de hover suaves nos cards
        const categoryCards = document.querySelectorAll('.category-card-enhanced');
        categoryCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Efeito parallax sutil no header
        const menuHeader = document.querySelector('.menu-header-enhanced');
        if (menuHeader) {
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const rate = scrolled * -0.1;
                menuHeader.style.transform = `translateY(${rate}px)`;
            });
        }

        // Contador animado para estatísticas
        function animateCounter(element, target, duration = 1000) {
            let start = 0;
            const increment = target / (duration / 16);
            const timer = setInterval(() => {
                start += increment;
                if (start >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(start);
                }
            }, 16);
        }

        // Aplicar animação aos contadores quando visíveis
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.menu-stat-item');
                    counters.forEach((counter, index) => {
                        setTimeout(() => {
                            const numberSpan = counter.querySelector('span') || counter;
                            const text = numberSpan.textContent;
                            const number = parseInt(text.match(/\d+/)?.[0] || '0');
                            if (number > 0) {
                                animateCounter(numberSpan, number, 800 + (index * 200));
                            }
                        }, index * 100);
                    });
                    observer.unobserve(entry.target);
                }
            });
        });

        const menuHeader2 = document.querySelector('.menu-header-enhanced');
        if (menuHeader2) {
            observer.observe(menuHeader2);
        }
    });
</script>
@endpush