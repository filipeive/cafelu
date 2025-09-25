@extends('layouts.app')

@section('title', 'Cozinha')
@section('page-title', 'Dashboard da Cozinha')
@section('title-icon', 'mdi-chef-hat')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Cozinha</li>
@endsection

@push('styles')
<style>
    .kitchen-dashboard {
        background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
        min-height: calc(100vh - 140px);
    }

    .order-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .order-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #06b6d4);
    }

    .order-card.urgent::before {
        background: linear-gradient(90deg, #ef4444, #f59e0b);
        animation: pulse 2s infinite;
    }

    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .order-header {
        background: linear-gradient(135deg, #f8fafc, #e0f7fa);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .order-time {
        font-size: 0.8rem;
        color: #6b7280;
    }

    .order-time.urgent {
        color: #dc2626;
        font-weight: 600;
        animation: blink 1s infinite;
    }

    .item-card {
        background: white;
        border-radius: 10px;
        padding: 0.75rem;
        margin: 0.5rem 0;
        border-left: 4px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .item-card.pending {
        border-left-color: #fbbf24;
        background: #fffbeb;
    }

    .item-card.preparing {
        border-left-color: #3b82f6;
        background: #eff6ff;
    }

    .item-card.ready {
        border-left-color: #10b981;
        background: #ecfdf5;
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .status-preparing {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-ready {
        background: #d1fae5;
        color: #065f46;
    }

    .quick-action-btn {
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .quick-action-btn:hover {
        transform: translateY(-1px);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .no-orders {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .category-filter {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .filter-btn {
        margin: 0.25rem;
        border: 2px solid transparent;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .filter-btn.active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
</style>
@endpush

@section('content')
<div class="container-wrapper card" style="padding: 20px">
    <!-- Estatísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                <i class="mdi mdi-receipt-text"></i>
            </div>
            <h3 class="mb-1">{{ $stats['active_orders'] }}</h3>
            <p class="text-muted mb-0">Pedidos Ativos</p>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="mdi mdi-clock-outline"></i>
            </div>
            <h3 class="mb-1">{{ $stats['pending_items'] }}</h3>
            <p class="text-muted mb-0">Itens Pendentes</p>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
                <i class="mdi mdi-fire"></i>
            </div>
            <h3 class="mb-1">{{ $stats['preparing_items'] }}</h3>
            <p class="text-muted mb-0">Em Preparo</p>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="mdi mdi-check-circle"></i>
            </div>
            <h3 class="mb-1">{{ $stats['ready_items'] }}</h3>
            <p class="text-muted mb-0">Prontos</p>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                <i class="mdi mdi-timer-outline"></i>
            </div>
            <h3 class="mb-1">{{ $avgPrepTime }}min</h3>
            <p class="text-muted mb-0">Tempo Médio</p>
        </div>
    </div>

    <!-- Controles -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" id="refreshOrders">
                <i class="mdi mdi-refresh"></i> Atualizar
            </button>
            <a href="{{ route('kitchen.by-category') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-format-list-bulleted"></i> Por Categoria
            </a>
            <a href="{{ route('kitchen.history') }}" class="btn btn-outline-info">
                <i class="mdi mdi-history"></i> Histórico
            </a>
        </div>
        
        <div class="d-flex gap-2">
            <button class="btn btn-success" id="markAllReady" {{ $stats['preparing_items'] == 0 ? 'disabled' : '' }}>
                <i class="mdi mdi-check-all"></i> Finalizar Todos
            </button>
        </div>
    </div>

    <!-- Pedidos Ativos -->
    <div class="row" id="activeOrders">
        @forelse($activeOrders as $order)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="order-card {{ $order->created_at->diffInMinutes(now()) > 30 ? 'urgent' : '' }}">
                <div class="order-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">
                                <i class="mdi mdi-table-chair me-1"></i>
                                {{ $order->table ? "Mesa {$order->table->number}" : 'Balcão' }}
                            </h6>
                            @if($order->customer_name)
                                <small class="text-muted">{{ $order->customer_name }}</small>
                            @endif
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary">{{ $order->id }}</span>
                            <div class="order-time {{ $order->created_at->diffInMinutes(now()) > 30 ? 'urgent' : '' }}">
                                {{ $order->created_at->diffForHumans() }}
                                <br><small>({{ $order->created_at->diffInMinutes(now()) }}min)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Itens do Pedido -->
                    @foreach($order->items as $item)
                    <div class="item-card {{ $item->status }}" data-item-id="{{ $item->id }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <strong>{{ $item->quantity }}x {{ $item->product->name }}</strong>
                                @if($item->notes)
                                    <br><small class="text-muted"><em>{{ $item->notes }}</em></small>
                                @endif
                                <div class="mt-1">
                                    <span class="status-badge status-{{ $item->status }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                            data-bs-toggle="dropdown">
                                        <i class="mdi mdi-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if($item->status == 'pending')
                                        <li>
                                            <button class="dropdown-item update-status" 
                                                    data-item-id="{{ $item->id }}" 
                                                    data-status="preparing">
                                                <i class="mdi mdi-play text-primary me-2"></i>Iniciar Preparo
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item update-status" 
                                                    data-item-id="{{ $item->id }}" 
                                                    data-status="ready">
                                                <i class="mdi mdi-check text-success me-2"></i>Marcar Pronto
                                            </button>
                                        </li>
                                        @elseif($item->status == 'preparing')
                                        <li>
                                            <button class="dropdown-item update-status" 
                                                    data-item-id="{{ $item->id }}" 
                                                    data-status="ready">
                                                <i class="mdi mdi-check text-success me-2"></i>Marcar Pronto
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item update-status" 
                                                    data-item-id="{{ $item->id }}" 
                                                    data-status="pending">
                                                <i class="mdi mdi-arrow-left text-warning me-2"></i>Voltar para Pendente
                                            </button>
                                        </li>
                                        @elseif($item->status == 'ready')
                                        <li>
                                            <button class="dropdown-item update-status" 
                                                    data-item-id="{{ $item->id }}" 
                                                    data-status="preparing">
                                                <i class="mdi mdi-arrow-left text-primary me-2"></i>Voltar para Preparo
                                            </button>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Ações do Pedido -->
                    <div class="mt-3 d-flex gap-2">
                        @php
                            $hasPending = $order->items->where('status', 'pending')->count() > 0;
                            $hasPreparing = $order->items->where('status', 'preparing')->count() > 0;
                        @endphp
                        
                        @if($hasPending)
                        <button class="quick-action-btn btn btn-warning btn-sm start-all" 
                                data-order-id="{{ $order->id }}">
                            <i class="mdi mdi-play"></i> Iniciar Todos
                        </button>
                        @endif
                        
                        @if($hasPending || $hasPreparing)
                        <button class="quick-action-btn btn btn-success btn-sm finish-all" 
                                data-order-id="{{ $order->id }}">
                            <i class="mdi mdi-check-all"></i> Finalizar Todos
                        </button>
                        @endif
                        
                        <a href="{{ route('kitchen.order.show', $order) }}" 
                           class="quick-action-btn btn btn-outline-info btn-sm">
                            <i class="mdi mdi-eye"></i> Detalhes
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="no-orders">
                <i class="mdi mdi-chef-hat" style="font-size: 4rem; color: #d1d5db;"></i>
                <h4 class="mt-3 mb-2">Nenhum pedido ativo</h4>
                <p>A cozinha está livre! Todos os pedidos foram processados.</p>
                <a href="{{ route('orders.index') }}" class="btn btn-primary">
                    <i class="mdi mdi-receipt"></i> Ver Todos os Pedidos
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Atualização automática a cada 30 segundos
    let refreshInterval = setInterval(refreshOrders, 30000);
    
    // Botão de refresh manual
    document.getElementById('refreshOrders')?.addEventListener('click', function() {
        refreshOrders();
        showToast('Pedidos atualizados!', 'info');
    });

    // Atualizar status de item
    document.querySelectorAll('.update-status').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            const status = this.dataset.status;
            updateItemStatus(itemId, status);
        });
    });

    // Iniciar todos os itens de um pedido
    document.querySelectorAll('.start-all').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            startAllItems(orderId);
        });
    });

    // Finalizar todos os itens de um pedido
    document.querySelectorAll('.finish-all').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            finishAllItems(orderId);
        });
    });

    // Finalizar todos os itens em preparo
    document.getElementById('markAllReady')?.addEventListener('click', function() {
        if (confirm('Marcar todos os itens em preparo como prontos?')) {
            document.querySelectorAll('.finish-all').forEach(btn => btn.click());
        }
    });

    // Funções AJAX
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
                refreshOrders();
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao atualizar status', 'error');
        });
    }

    function startAllItems(orderId) {
        fetch(`/kitchen/orders/${orderId}/start-all`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                refreshOrders();
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao iniciar itens', 'error');
        });
    }

    function finishAllItems(orderId) {
        fetch(`/kitchen/orders/${orderId}/finish-all`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                refreshOrders();
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao finalizar itens', 'error');
        });
    }

    function refreshOrders() {
        // Recarregar a página ou fazer chamada AJAX para atualizar apenas o conteúdo
        window.location.reload();
    }

    // Notificação de novos pedidos (pode ser implementado com WebSockets)
    function checkForNewOrders() {
        // Implementar verificação de novos pedidos
        // Pode usar polling ou WebSockets
    }

    // Som de notificação para novos pedidos
    function playNotificationSound() {
        const audio = new Audio('/assets/sounds/notification.mp3');
        audio.play().catch(e => console.log('Não foi possível reproduzir o som'));
    }
});
</script>
@endpush