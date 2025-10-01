@extends('layouts.app')

@section('title', 'Cozinha por Categoria')
@section('page-title', 'Organização por Categoria')
@section('title-icon', 'mdi-format-list-bulleted')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('kitchen.dashboard') }}">Cozinha</a>
    </li>
    <li class="breadcrumb-item active">Por Categoria</li>
@endsection

@push('styles')
<style>
    :root {
        --primary-blue: #3b82f6;
        --primary-dark: #1d4ed8;
        --warning-yellow: #fbbf24;
        --success-green: #10b981;
        --danger-red: #ef4444;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-400: #9ca3af;
        --gray-600: #4b5563;
        --gray-700: #374151;
    }

    .kitchen-header {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .kitchen-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .filter-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .filter-label {
        font-weight: 600;
        color: var(--gray-700);
        font-size: 0.9rem;
    }

    .filter-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.5rem 1.25rem;
        border: 2px solid var(--gray-200);
        background: white;
        border-radius: 24px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-btn:hover {
        border-color: var(--primary-blue);
        color: var(--primary-blue);
        transform: translateY(-1px);
    }

    .filter-btn.active {
        background: var(--primary-blue);
        color: white;
        border-color: var(--primary-blue);
    }

    .filter-btn .badge {
        background: rgba(255,255,255,0.2);
        padding: 0.15rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
    }

    .filter-btn.active .badge {
        background: rgba(255,255,255,0.3);
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
    }

    .category-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .category-section:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }

    .category-header {
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-dark));
        color: white;
        padding: 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .category-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .category-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 1;
        margin-bottom: 1rem;
    }

    .category-title {
        margin: 0;
        font-weight: 700;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .category-icon {
        width: 48px;
        height: 48px;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .category-count {
        background: rgba(255,255,255,0.25);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 24px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .category-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .stat-card {
        text-align: center;
        padding: 0.75rem;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-2px);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        display: block;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.8rem;
        opacity: 0.95;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .items-container {
        padding: 1.5rem;
    }

    .item-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        margin-bottom: 1rem;
        background: var(--gray-50);
        border-radius: 12px;
        border-left: 4px solid var(--gray-200);
        transition: all 0.3s ease;
        position: relative;
    }

    .item-card:hover {
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transform: translateX(4px);
    }

    .item-card.pending {
        border-left-color: var(--warning-yellow);
        background: #fffbeb;
    }

    .item-card.pending:hover {
        background: #fef3c7;
    }

    .item-card.preparing {
        border-left-color: var(--primary-blue);
        background: #eff6ff;
    }

    .item-card.preparing:hover {
        background: #dbeafe;
    }

    .item-card.ready {
        border-left-color: var(--success-green);
        background: #ecfdf5;
    }

    .item-card.ready:hover {
        background: #d1fae5;
    }

    .priority-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
        position: relative;
    }

    .priority-high {
        background: var(--danger-red);
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2);
        animation: pulse-high 2s infinite;
    }

    .priority-medium {
        background: var(--warning-yellow);
        box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.2);
    }

    .priority-low {
        background: var(--success-green);
    }

    @keyframes pulse-high {
        0%, 100% {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2);
        }
        50% {
            box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
        }
    }

    .item-content {
        flex-grow: 1;
        min-width: 0;
    }

    .item-title {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        color: var(--gray-700);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quantity-badge {
        background: var(--primary-blue);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .item-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.85rem;
        color: var(--gray-600);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .meta-item i {
        font-size: 1rem;
    }

    .time-badge {
        background: var(--gray-100);
        padding: 0.25rem 0.75rem;
        border-radius: 16px;
        font-weight: 600;
    }

    .time-badge.urgent {
        background: #fee2e2;
        color: var(--danger-red);
    }

    .notes-section {
        margin-top: 0.5rem;
        padding: 0.75rem;
        background: #fef3c7;
        border-radius: 8px;
        border-left: 3px solid var(--warning-yellow);
        display: flex;
        align-items: start;
        gap: 0.5rem;
    }

    .notes-section i {
        color: var(--warning-yellow);
        flex-shrink: 0;
        margin-top: 0.15rem;
    }

    .item-actions {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .action-btn {
        border: none;
        border-radius: 10px;
        padding: 0.65rem 1.25rem;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .action-btn:active {
        transform: translateY(0);
    }

    .btn-start {
        background: #dbeafe;
        color: var(--primary-dark);
    }

    .btn-start:hover {
        background: var(--primary-blue);
        color: white;
    }

    .btn-finish {
        background: #d1fae5;
        color: #065f46;
    }

    .btn-finish:hover {
        background: var(--success-green);
        color: white;
    }

    .status-badge-ready {
        background: var(--success-green);
        color: white;
        padding: 0.65rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .category-footer {
        padding: 1.25rem 1.5rem;
        background: var(--gray-50);
        border-top: 1px solid var(--gray-200);
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .bulk-action-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .bulk-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-start-all {
        background: var(--warning-yellow);
        color: white;
    }

    .btn-finish-all {
        background: var(--success-green);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--gray-400);
    }

    .empty-state i {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .empty-state h4 {
        font-weight: 600;
        color: var(--gray-600);
        margin-bottom: 0.5rem;
    }

    .empty-category {
        padding: 3rem 1.5rem;
        text-align: center;
        color: var(--gray-400);
    }

    .empty-category i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .loading-overlay.active {
        opacity: 1;
        pointer-events: all;
    }

    .loading-spinner {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        text-align: center;
    }

    .spinner {
        border: 4px solid var(--gray-200);
        border-top-color: var(--primary-blue);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
        .kitchen-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-section {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-buttons {
            width: 100%;
        }

        .filter-btn {
            flex: 1;
            justify-content: center;
        }

        .item-card {
            flex-direction: column;
            align-items: stretch;
        }

        .item-actions {
            width: 100%;
        }

        .action-btn {
            flex: 1;
            justify-content: center;
        }

        .category-stats {
            grid-template-columns: repeat(3, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    @php
        // Calcular totais para os filtros
        $totalItems = collect($itemsByCategory)->sum(fn($c) => count($c['items']));
        $totalPending = collect($itemsByCategory)->sum(fn($c) => collect($c['items'])->where('status', 'pending')->count());
        $totalPreparing = collect($itemsByCategory)->sum(fn($c) => collect($c['items'])->where('status', 'preparing')->count());
        $totalReady = collect($itemsByCategory)->sum(fn($c) => collect($c['items'])->where('status', 'ready')->count());
    @endphp

    <!-- Header com controles -->
    <div class="kitchen-header">
        <div class="kitchen-toolbar">
            <div class="filter-section">
                <span class="filter-label">Filtrar por:</span>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-status="all">
                        Todos
                        <span class="badge">{{ $totalItems }}</span>
                    </button>
                    <button class="filter-btn" data-status="pending">
                        Pendentes
                        <span class="badge">{{ $totalPending }}</span>
                    </button>
                    <button class="filter-btn" data-status="preparing">
                        Em Preparo
                        <span class="badge">{{ $totalPreparing }}</span>
                    </button>
                    <button class="filter-btn" data-status="ready">
                        Prontos
                        <span class="badge">{{ $totalReady }}</span>
                    </button>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('kitchen.dashboard') }}" class="btn btn-outline-primary">
                    <i class="mdi mdi-view-dashboard"></i> Dashboard
                </a>
                <button class="btn btn-success" id="refreshData">
                    <i class="mdi mdi-refresh"></i> Atualizar
                </button>
            </div>
        </div>
    </div>

    <!-- Categorias -->
    @forelse($itemsByCategory as $categoryData)
    <div class="category-section" data-category="{{ $categoryData['category'] }}">
        <div class="category-header">
            <div class="category-title-row">
                <h4 class="category-title">
                    <span class="category-icon">
                        <i class="mdi mdi-{{ getCategoryIcon($categoryData['category']) }}"></i>
                    </span>
                    {{ $categoryData['category'] }}
                </h4>
                <span class="category-count">
                    {{ $categoryData['total_items'] }} {{ $categoryData['total_items'] === 1 ? 'item' : 'itens' }}
                </span>
            </div>
            
            <div class="category-stats">
                <div class="stat-card">
                    <span class="stat-number">{{ $categoryData['pending_count'] }}</span>
                    <span class="stat-label">Pendentes</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $categoryData['preparing_count'] }}</span>
                    <span class="stat-label">Em Preparo</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $categoryData['ready_count'] }}</span>
                    <span class="stat-label">Prontos</span>
                </div>
            </div>
        </div>

        <div class="items-container">
            @forelse($categoryData['items'] as $item)
            <div class="item-card {{ $item['status'] }}" data-item-id="{{ $item['id'] }}" data-status="{{ $item['status'] }}">
                <span class="priority-indicator priority-{{ $item['priority'] }}" 
                      title="Pedido há {{ $item['elapsed_minutes'] }} minutos"></span>
                
                <div class="item-content">
                    <div class="item-title">
                        <span class="quantity-badge">{{ $item['quantity'] }}x</span>
                        {{ $item['product_name'] }}
                    </div>
                    
                    <div class="item-meta">
                        <span class="meta-item">
                            <i class="mdi mdi-table-chair"></i>
                            <strong>Mesa {{ $item['table_number'] }}</strong>
                        </span>
                        <span class="meta-item">
                            <i class="mdi mdi-clock-outline"></i>
                            <span class="time-badge {{ $item['elapsed_minutes'] > 20 ? 'urgent' : '' }}">
                                {{ $item['elapsed_minutes'] }} min
                            </span>
                        </span>
                        <span class="meta-item">
                            <i class="mdi mdi-receipt"></i>
                            Pedido #{{ $item['order_id'] }}
                        </span>
                    </div>

                    @if($item['notes'])
                    <div class="notes-section">
                        <i class="mdi mdi-alert-circle"></i>
                        <span><strong>Obs:</strong> {{ $item['notes'] }}</span>
                    </div>
                    @endif
                </div>

                <div class="item-actions">
                    @if($item['status'] == 'pending')
                        <button class="action-btn btn-start update-status" 
                                data-item-id="{{ $item['id'] }}" 
                                data-status="preparing">
                            <i class="mdi mdi-play"></i>Iniciar
                        </button>
                        <button class="action-btn btn-finish update-status" 
                                data-item-id="{{ $item['id'] }}" 
                                data-status="ready">
                            <i class="mdi mdi-check"></i>Pronto
                        </button>
                    @elseif($item['status'] == 'preparing')
                        <button class="action-btn btn-finish update-status" 
                                data-item-id="{{ $item['id'] }}" 
                                data-status="ready">
                            <i class="mdi mdi-check"></i>Finalizar
                        </button>
                    @elseif($item['status'] == 'ready')
                        <span class="status-badge-ready">
                            <i class="mdi mdi-check-circle"></i>
                            Pronto para Entrega
                        </span>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-category">
                <i class="mdi mdi-food-off"></i>
                <p>Nenhum item nesta categoria</p>
            </div>
            @endforelse
        </div>

        @if($categoryData['total_items'] > 0)
        <div class="category-footer">
            @if($categoryData['pending_count'] > 0)
            <button class="bulk-action-btn btn-start-all start-all-category" 
                    data-category="{{ $categoryData['category'] }}">
                <i class="mdi mdi-play-circle"></i>
                Iniciar Todos ({{ $categoryData['pending_count'] }})
            </button>
            @endif
            
            @if($categoryData['pending_count'] > 0 || $categoryData['preparing_count'] > 0)
            <button class="bulk-action-btn btn-finish-all finish-all-category" 
                    data-category="{{ $categoryData['category'] }}">
                <i class="mdi mdi-check-all"></i>
                Finalizar Todos ({{ $categoryData['pending_count'] + $categoryData['preparing_count'] }})
            </button>
            @endif
        </div>
        @endif
    </div>
    @empty
    <div class="empty-state">
        <i class="mdi mdi-chef-hat"></i>
        <h4>Nenhum item para preparar</h4>
        <p class="mb-4">Todos os pedidos foram processados!</p>
        <a href="{{ route('kitchen.dashboard') }}" class="btn btn-primary btn-lg">
            <i class="mdi mdi-arrow-left"></i> Voltar ao Dashboard
        </a>
    </div>
    @endforelse
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner">
        <div class="spinner"></div>
        <p class="mb-0">Processando...</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadingOverlay = document.getElementById('loadingOverlay');

    // Filtros de status
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const status = this.dataset.status;
            filterItemsByStatus(status);
        });
    });

    // Atualizar status individual
    document.querySelectorAll('.update-status').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            const status = this.dataset.status;
            updateItemStatus(itemId, status);
        });
    });

    // Iniciar todos da categoria
    document.querySelectorAll('.start-all-category').forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            startAllInCategory(category);
        });
    });

    // Finalizar todos da categoria
    document.querySelectorAll('.finish-all-category').forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            finishAllInCategory(category);
        });
    });

    // Refresh
    document.getElementById('refreshData').addEventListener('click', function() {
        showLoading();
        window.location.reload();
    });

    function filterItemsByStatus(status) {
        document.querySelectorAll('.item-card').forEach(item => {
            if (status === 'all' || item.dataset.status === status) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });

        document.querySelectorAll('.category-section').forEach(section => {
            const visibleItems = section.querySelectorAll('.item-card[style*="display: flex"], .item-card:not([style*="display: none"])').length;
            section.style.display = (status !== 'all' && visibleItems === 0) ? 'none' : 'block';
        });
    }

    function updateItemStatus(itemId, status) {
        showLoading();
        
        fetch(`/kitchen/items/${itemId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast(data.message || 'Status atualizado com sucesso', 'success');
                setTimeout(() => window.location.reload(), 500);
            } else {
                showToast(data.message || 'Erro ao atualizar status', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Erro:', error);
            showToast('Erro ao atualizar status', 'error');
        });
    }

    function startAllInCategory(category) {
        const categorySection = document.querySelector(`[data-category="${category}"]`);
        const pendingItems = categorySection.querySelectorAll('.item-card[data-status="pending"]');
        
        if (pendingItems.length === 0) {
            showToast('Nenhum item pendente nesta categoria', 'warning');
            return;
        }

        if (confirm(`Deseja iniciar o preparo de ${pendingItems.length} ${pendingItems.length === 1 ? 'item' : 'itens'}?`)) {
            showLoading();
            
            // Pegar o order_id do primeiro item pendente
            const firstItem = pendingItems[0];
            const orderId = firstItem.querySelector('[data-item-id]').dataset.itemId;
            
            // Na prática, você precisaria de uma rota específica ou iterar os itens
            // Por simplicidade, vamos recarregar após atualizar
            Promise.all(
                Array.from(pendingItems).map(item => {
                    const itemId = item.dataset.itemId;
                    return fetch(`/kitchen/items/${itemId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: 'preparing' })
                    });
                })
            ).then(() => {
                showToast('Itens iniciados com sucesso', 'success');
                setTimeout(() => window.location.reload(), 500);
            }).catch(error => {
                hideLoading();
                showToast('Erro ao iniciar itens', 'error');
            });
        }
    }

    function finishAllInCategory(category) {
        const categorySection = document.querySelector(`[data-category="${category}"]`);
        const activeItems = categorySection.querySelectorAll('.item-card[data-status="pending"], .item-card[data-status="preparing"]');
        
        if (activeItems.length === 0) {
            showToast('Nenhum item para finalizar nesta categoria', 'warning');
            return;
        }

        if (confirm(`Deseja finalizar ${activeItems.length} ${activeItems.length === 1 ? 'item' : 'itens'}?`)) {
            showLoading();
            
            Promise.all(
                Array.from(activeItems).map(item => {
                    const itemId = item.dataset.itemId;
                    return fetch(`/kitchen/items/${itemId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: 'ready' })
                    });
                })
            ).then(() => {
                showToast('Itens finalizados com sucesso', 'success');
                setTimeout(() => window.location.reload(), 500);
            }).catch(error => {
                hideLoading();
                showToast('Erro ao finalizar itens', 'error');
            });
        }
    }

    function showLoading() {
        loadingOverlay.classList.add('active');
    }

    function hideLoading() {
        loadingOverlay.classList.remove('active');
    }

    function showToast(message, type = 'info') {
        if (typeof window.showToast === 'function') {
            window.showToast(message, type);
        } else {
            const alertClass = {
                'success': 'alert-success',
                'error': 'alert-danger',
                'warning': 'alert-warning',
                'info': 'alert-info'
            }[type] || 'alert-info';
            
            const toast = document.createElement('div');
            toast.className = `alert ${alertClass} position-fixed top-0 end-0 m-3`;
            toast.style.zIndex = '10000';
            toast.style.minWidth = '300px';
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <span>${message}</span>
                    <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    }

    console.log('Kitchen By Category: Inicializado');
});

@php
function getCategoryIcon($category) {
    $icons = [
        'Bebidas' => 'cup',
        'Sucos' => 'cup-water',
        'Refrigerantes' => 'bottle-soda',
        'Cafés' => 'coffee',
        'Entradas' => 'food-apple',
        'Petiscos' => 'food-variant',
        'Pratos Principais' => 'silverware-fork-knife',
        'Carnes' => 'food-steak',
        'Massas' => 'pasta',
        'Peixes' => 'fish',
        'Sobremesas' => 'cake',
        'Doces' => 'candy',
        'Lanches' => 'hamburger',
        'Sanduíches' => 'food',
        'Pizzas' => 'pizza',
        'Saladas' => 'leaf',
        'Sopas' => 'bowl-mix',
        'Grelhados' => 'grill',
    ];
    return $icons[$category] ?? 'food';
}
@endphp
</script>
@endpush