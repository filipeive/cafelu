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
    .category-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .category-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .category-header {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .category-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
        background-size: 20px 20px;
        opacity: 0.3;
        animation: backgroundMove 20s linear infinite;
    }

    @keyframes backgroundMove {
        0% { transform: translate(0, 0) rotate(0deg); }
        100% { transform: translate(-20px, -20px) rotate(360deg); }
    }

    .category-title {
        position: relative;
        z-index: 1;
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .category-stats {
        display: flex;
        gap: 2rem;
        margin-top: 1rem;
        position: relative;
        z-index: 1;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        display: block;
    }

    .stat-label {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .items-grid {
        padding: 1.5rem;
    }

    .item-row {
        display: flex;
        align-items: center;
        padding: 1rem;
        margin: 0.5rem 0;
        background: #f8fafc;
        border-radius: 12px;
        border-left: 4px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .item-row:hover {
        background: #f1f5f9;
        transform: translateX(5px);
    }

    .item-row.pending {
        border-left-color: #fbbf24;
        background: #fffbeb;
    }

    .item-row.preparing {
        border-left-color: #3b82f6;
        background: #eff6ff;
    }

    .item-row.ready {
        border-left-color: #10b981;
        background: #ecfdf5;
    }

    .item-info {
        flex-grow: 1;
    }

    .item-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .item-meta {
        font-size: 0.85rem;
        color: #6b7280;
        display: flex;
        gap: 1rem;
    }

    .status-controls {
        display: flex;
        gap: 0.5rem;
    }

    .status-btn {
        border: none;
        border-radius: 8px;
        padding: 0.5rem;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 80px;
    }

    .status-btn:hover {
        transform: translateY(-1px);
    }

    .status-btn.start {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-btn.start:hover {
        background: #3b82f6;
        color: white;
    }

    .status-btn.finish {
        background: #d1fae5;
        color: #065f46;
    }

    .status-btn.finish:hover {
        background: #10b981;
        color: white;
    }

    .empty-category {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #9ca3af;
    }

    .category-actions {
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .filter-bar {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .filter-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-btn.active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .priority-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
    }

    .priority-high {
        background: #ef4444;
        animation: pulse 2s infinite;
    }

    .priority-medium {
        background: #f59e0b;
    }

    .priority-low {
        background: #10b981;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Controles -->
    <div class="filter-bar">
        <div class="d-flex align-items-center gap-3">
            <h6 class="mb-0">Filtrar por status:</h6>
            <div class="filter-buttons">
                <button class="filter-btn active" data-status="all">Todos</button>
                <button class="filter-btn" data-status="pending">Pendentes</button>
                <button class="filter-btn" data-status="preparing">Em Preparo</button>
                <button class="filter-btn" data-status="ready">Prontos</button>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('kitchen.dashboard') }}" class="btn btn-outline-primary">
                <i class="mdi mdi-view-dashboard"></i> Dashboard
            </a>
            <button class="btn btn-success" id="refreshData">
                <i class="mdi mdi-refresh"></i> Atualizar
            </button>
        </div>
    </div>

    <!-- Categorias -->
    @forelse($itemsByCategory as $category)
    <div class="category-section" data-category="{{ $category['category'] }}">
        <div class="category-header">
            <h4 class="category-title">
                <span>
                   <i class="mdi mdi-{{ getCategoryIcon($category['category']) }} me-2"></i>
                </span>
                <span class="badge bg-light text-dark">{{ $category['items']->count() }} itens</span>
            </h4>
            
            <div class="category-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $category['items']->where('status', 'pending')->count() }}</span>
                    <span class="stat-label">Pendentes</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $category['items']->where('status', 'preparing')->count() }}</span>
                    <span class="stat-label">Em Preparo</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $category['items']->where('status', 'ready')->count() }}</span>
                    <span class="stat-label">Prontos</span>
                </div>
            </div>
        </div>

        <div class="items-grid">
            @forelse($category['items'] as $item)
            <div class="item-row {{ $item['status'] }}" data-item-id="{{ $item['id'] }}" data-status="{{ $item['status'] }}">
                <div class="me-3">
                    @php
                        $priority = 'low';
                        if ($item['elapsed_minutes'] > 30) $priority = 'high';
                        elseif ($item['elapsed_minutes'] > 15) $priority = 'medium';
                    @endphp
                    <span class="priority-indicator priority-{{ $priority }}" 
                          title="Pedido há {{ $item['elapsed_minutes'] }} minutos"></span>
                </div>
                
                <div class="item-info">
                    <div class="item-name">
                        {{ $item['quantity'] }}x {{ $item['product_name'] }}
                    </div>
                    <div class="item-meta">
                        <span>
                            <i class="mdi mdi-table-chair me-1"></i>
                            {{ $item['table_number'] }}
                        </span>
                        <span>
                            <i class="mdi mdi-clock-outline me-1"></i>
                            {{ $item['elapsed_minutes'] }}min
                        </span>
                        <span>
                            <i class="mdi mdi-receipt me-1"></i>
                            Pedido #{{ $item['order_id'] }}
                        </span>
                        @if($item['notes'])
                        <span class="text-warning">
                            <i class="mdi mdi-note-text me-1"></i>
                            {{ $item['notes'] }}
                        </span>
                        @endif
                    </div>
                </div>

                <div class="status-controls">
                    @if($item['status'] == 'pending')
                    <button class="status-btn start update-status" 
                            data-item-id="{{ $item['id'] }}" 
                            data-status="preparing">
                        <i class="mdi mdi-play me-1"></i>Iniciar
                    </button>
                    <button class="status-btn finish update-status" 
                            data-item-id="{{ $item['id'] }}" 
                            data-status="ready">
                        <i class="mdi mdi-check me-1"></i>Pronto
                    </button>
                    @elseif($item['status'] == 'preparing')
                    <button class="status-btn finish update-status" 
                            data-item-id="{{ $item['id'] }}" 
                            data-status="ready">
                        <i class="mdi mdi-check me-1"></i>Finalizar
                    </button>
                    @elseif($item['status'] == 'ready')
                    <span class="badge bg-success">
                        <i class="mdi mdi-check-circle me-1"></i>Pronto para Entrega
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-category">
                <i class="mdi mdi-food-off" style="font-size: 3rem;"></i>
                <p class="mt-2">Nenhum item nesta categoria</p>
            </div>
            @endforelse
        </div>

        @if($category['items']->count() > 0)
        <div class="category-actions">
            @php
                $hasPending = $category['items']->where('status', 'pending')->count() > 0;
                $hasPreparing = $category['items']->where('status', 'preparing')->count() > 0;
            @endphp
            
            @if($hasPending)
            <button class="btn btn-warning start-all-category" 
                    data-category="{{ $category['category'] }}">
                <i class="mdi mdi-play"></i> Iniciar Todos desta Categoria
            </button>
            @endif
            
            @if($hasPending || $hasPreparing)
            <button class="btn btn-success finish-all-category" 
                    data-category="{{ $category['category'] }}">
                <i class="mdi mdi-check-all"></i> Finalizar Todos desta Categoria
            </button>
            @endif
        </div>
        @endif
    </div>
    @empty
    <div class="text-center py-5">
        <i class="mdi mdi-chef-hat" style="font-size: 4rem; color: #d1d5db;"></i>
        <h4 class="mt-3 mb-2">Nenhum item para preparar</h4>
        <p class="text-muted">Todos os pedidos foram processados!</p>
        <a href="{{ route('kitchen.dashboard') }}" class="btn btn-primary">
            <i class="mdi mdi-arrow-left"></i> Voltar ao Dashboard
        </a>
    </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtros de status
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Atualizar botão ativo
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Filtrar itens
            const status = this.dataset.status;
            filterItemsByStatus(status);
        });
    });

    // Atualizar status de item individual
    document.querySelectorAll('.update-status').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            const status = this.dataset.status;
            updateItemStatus(itemId, status);
        });
    });

    // Iniciar todos os itens de uma categoria
    document.querySelectorAll('.start-all-category').forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            startAllInCategory(category);
        });
    });

    // Finalizar todos os itens de uma categoria
    document.querySelectorAll('.finish-all-category').forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            finishAllInCategory(category);
        });
    });

    // Refresh data
    document.getElementById('refreshData').addEventListener('click', function() {
        window.location.reload();
    });

    // Funções
    function filterItemsByStatus(status) {
        document.querySelectorAll('.item-row').forEach(item => {
            if (status === 'all' || item.dataset.status === status) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });

        // Esconder categorias vazias
        document.querySelectorAll('.category-section').forEach(section => {
            const visibleItems = section.querySelectorAll('.item-row[style="display: flex"], .item-row:not([style])').length;
            if (status !== 'all' && visibleItems === 0) {
                section.style.display = 'none';
            } else {
                section.style.display = 'block';
            }
        });
    }

    function updateItemStatus(itemId, status) {
        fetch(`/kitchen/items/${itemId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                updateItemRow(itemId, status);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao atualizar status', 'error');
        });
    }

    function startAllInCategory(category) {
        const categorySection = document.querySelector(`[data-category="${category}"]`);
        const pendingItems = categorySection.querySelectorAll('.item-row[data-status="pending"]');
        
        if (pendingItems.length === 0) {
            showToast('Nenhum item pendente nesta categoria', 'warning');
            return;
        }

        if (confirm(`Iniciar preparo de ${pendingItems.length} itens da categoria ${category}?`)) {
            pendingItems.forEach(item => {
                const itemId = item.dataset.itemId;
                updateItemStatus(itemId, 'preparing');
            });
        }
    }

    function finishAllInCategory(category) {
        const categorySection = document.querySelector(`[data-category="${category}"]`);
        const activeItems = categorySection.querySelectorAll('.item-row[data-status="pending"], .item-row[data-status="preparing"]');
        
        if (activeItems.length === 0) {
            showToast('Nenhum item para finalizar nesta categoria', 'warning');
            return;
        }

        if (confirm(`Finalizar ${activeItems.length} itens da categoria ${category}?`)) {
            activeItems.forEach(item => {
                const itemId = item.dataset.itemId;
                updateItemStatus(itemId, 'ready');
            });
        }
    }

    function updateItemRow(itemId, newStatus) {
        const itemRow = document.querySelector(`[data-item-id="${itemId}"]`);
        if (itemRow) {
            // Atualizar classes CSS
            itemRow.className = itemRow.className.replace(/\b(pending|preparing|ready)\b/g, '');
            itemRow.classList.add(newStatus);
            itemRow.dataset.status = newStatus;

            // Atualizar controles
            const controls = itemRow.querySelector('.status-controls');
            let newControls = '';
            
            switch(newStatus) {
                case 'pending':
                    newControls = `
                        <button class="status-btn start update-status" data-item-id="${itemId}" data-status="preparing">
                            <i class="mdi mdi-play me-1"></i>Iniciar
                        </button>
                        <button class="status-btn finish update-status" data-item-id="${itemId}" data-status="ready">
                            <i class="mdi mdi-check me-1"></i>Pronto
                        </button>
                    `;
                    break;
                case 'preparing':
                    newControls = `
                        <button class="status-btn finish update-status" data-item-id="${itemId}" data-status="ready">
                            <i class="mdi mdi-check me-1"></i>Finalizar
                        </button>
                    `;
                    break;
                case 'ready':
                    newControls = `
                        <span class="badge bg-success">
                            <i class="mdi mdi-check-circle me-1"></i>Pronto para Entrega
                        </span>
                    `;
                    break;
            }
            
            controls.innerHTML = newControls;
            
            // Re-adicionar event listeners
            controls.querySelectorAll('.update-status').forEach(button => {
                button.addEventListener('click', function() {
                    updateItemStatus(this.dataset.itemId, this.dataset.status);
                });
            });
        }
    }

    // Auto-refresh a cada 60 segundos
    setInterval(() => {
        window.location.reload();
    }, 60000);
});

// Helper function para ícones de categoria
@php
function getCategoryIcon($category) {
    $icons = [
        'Bebidas' => 'cup',
        'Entradas' => 'food-apple',
        'Pratos Principais' => 'silverware-fork-knife',
        'Sobremesas' => 'cake',
        'Lanches' => 'hamburger',
        'Pizzas' => 'pizza',
        'Saladas' => 'leaf',
    ];
    return $icons[$category] ?? 'food';
}
@endphp
</script>
@endpush