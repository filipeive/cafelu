@extends('layouts.app')

@section('title', 'Cozinha')
@section('title-icon', 'mdi-chef-hat')

@section('breadcrumbs')
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-chef-hat me-1"></i> Cozinha
    </li>
@endsection

@section('styles')
<style>
.kitchen-stats-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    overflow: hidden;
    background: var(--bs-card-bg);
    height: 100%;
}

.kitchen-stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.stats-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stats-value {
    font-size: 1.75rem;
    font-weight: 700;
    line-height: 1.2;
    margin: 0;
}

.stats-label {
    font-size: 0.85rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.8;
    margin: 0;
}

.order-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    border: none;
    overflow: hidden;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.order-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.order-body {
    padding: 1.5rem;
}

.item-row {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.item-row:last-child {
    border-bottom: none;
}

.item-status {
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
}

.time-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
}

.beach-accent {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899, #3b82f6);
    background-size: 300% 100%;
    animation: wave 2s ease infinite;
}

@keyframes wave {
    0% { background-position: 0% 50%; }
    100% { background-position: 300% 50%; }
}

@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
}
</style>
@endsection

@section('content')
<div class="row g-4">
    <!-- Header -->
    <div class="col-12">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="mdi mdi-chef-hat me-2"></i>
                    Dashboard da Cozinha
                </h2>
                <p class="text-muted mb-0">Gerencie todos os pedidos em preparo</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('kitchen.by-category') }}" class="btn btn-outline-primary d-flex align-items-center">
                    <i class="mdi mdi-format-list-bulleted me-2"></i>
                    <span>Por Categoria</span>
                </a>
                <a href="{{ route('kitchen.history') }}" class="btn btn-outline-secondary d-flex align-items-center">
                    <i class="mdi mdi-history me-2"></i>
                    <span>Histórico</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="col-12">
        <div class="row g-4">
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="kitchen-stats-card border-start border-4 border-primary position-relative">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stats-label">Pedidos Ativos</p>
                                <h3 class="stats-value text-primary">{{ $stats['active_orders'] }}</h3>
                                <small class="text-muted">em preparo</small>
                            </div>
                            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                <i class="mdi mdi-receipt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="beach-accent"></div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="kitchen-stats-card border-start border-4 border-warning position-relative">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stats-label">Itens Pendentes</p>
                                <h3 class="stats-value text-warning">{{ $stats['pending_items'] }}</h3>
                                <small class="text-muted">aguardando início</small>
                            </div>
                            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                <i class="mdi mdi-clock-alert"></i>
                            </div>
                        </div>
                    </div>
                    <div class="beach-accent"></div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="kitchen-stats-card border-start border-4 border-info position-relative">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stats-label">Em Preparo</p>
                                <h3 class="stats-value text-info">{{ $stats['preparing_items'] }}</h3>
                                <small class="text-muted">em execução</small>
                            </div>
                            <div class="stats-icon bg-info bg-opacity-10 text-info">
                                <i class="mdi mdi-progress-clock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="beach-accent"></div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="kitchen-stats-card border-start border-4 border-success position-relative">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stats-label">Prontos</p>
                                <h3 class="stats-value text-success">{{ $stats['ready_items'] }}</h3>
                                <small class="text-muted">aguardando entrega</small>
                            </div>
                            <div class="stats-icon bg-success bg-opacity-10 text-success">
                                <i class="mdi mdi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="beach-accent"></div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="kitchen-stats-card border-start border-4 border-secondary position-relative">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stats-label">Hoje</p>
                                <h3 class="stats-value text-secondary">{{ $stats['orders_completed_today'] }}</h3>
                                <small class="text-muted">pedidos finalizados</small>
                            </div>
                            <div class="stats-icon bg-secondary bg-opacity-10 text-secondary">
                                <i class="mdi mdi-calendar-today"></i>
                            </div>
                        </div>
                    </div>
                    <div class="beach-accent"></div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="kitchen-stats-card border-start border-4 border-primary position-relative">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stats-label">Tempo Médio</p>
                                <h3 class="stats-value text-primary">{{ $avgPrepTime }} min</h3>
                                <small class="text-muted">últimas 24h</small>
                            </div>
                            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                <i class="mdi mdi-timer"></i>
                            </div>
                        </div>
                    </div>
                    <div class="beach-accent"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Orders -->
    <div class="col-12">
        <div class="order-card">
            <div class="order-header">
                <h4 class="mb-0">
                    <i class="mdi mdi-receipt me-2 text-primary"></i>
                    Pedidos Ativos ({{ $activeOrders->count() }})
                </h4>
            </div>
            <div class="order-body">
                @if($activeOrders->count() > 0)
                    <div class="row g-3">
                        @foreach($activeOrders as $order)
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="mb-1">Pedido #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h5>
                                            <p class="mb-0">
                                                <i class="mdi mdi-table-chair me-1"></i>
                                                Mesa {{ $order->table?->number ?? 'Balcão' }}
                                                @if($order->customer_name)
                                                    • {{ $order->customer_name }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="time-badge bg-primary text-white">
                                            {{ $order->created_at->diffInMinutes(now()) }} min
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Itens:</small>
                                        <div class="mt-2">
                                            @foreach($order->items as $item)
                                                <div class="item-row">
                                                    <div class="flex-grow-1">
                                                        <div class="fw-medium">{{ $item->product->name }}</div>
                                                        <small class="text-muted">{{ $item->quantity }}x</small>
                                                        @if($item->notes)
                                                            <div class="text-muted small mt-1">Obs: {{ $item->notes }}</div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        @switch($item->status)
                                                            @case('pending')
                                                                <span class="item-status bg-warning text-dark">Pendente</span>
                                                            @break
                                                            @case('preparing')
                                                                <span class="item-status bg-info text-white">Preparando</span>
                                                            @break
                                                            @case('ready')
                                                                <span class="item-status bg-success text-white">Pronto</span>
                                                            @break
                                                        @endswitch
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('kitchen.order.show', $order) }}" class="btn btn-primary btn-sm">
                                            <i class="mdi mdi-eye me-1"></i> Detalhes
                                        </a>
                                        <button type="button" class="btn btn-info btn-sm" onclick="startAllItems({{ $order->id }})">
                                            <i class="mdi mdi-play me-1"></i> Iniciar Todos
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" onclick="finishAllItems({{ $order->id }})">
                                            <i class="mdi mdi-check me-1"></i> Finalizar Todos
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="mdi mdi-chef-hat" style="font-size: 3rem; color: #6b7280;"></i>
                        <h5 class="mt-3">Nenhum pedido ativo</h5>
                        <p class="text-muted">Aguardando novos pedidos...</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Hidden Forms for AJAX Actions -->
@foreach($activeOrders as $order)
<form id="startAllForm{{ $order->id }}" action="{{-- route('kitchen.start-all', $order) --}}" method="POST" style="display: none;">
    @csrf
</form>
<form id="finishAllForm{{ $order->id }}" action="{{-- route('kitchen.finish-all', $order) --}}" method="POST" style="display: none;">
    @csrf
</form>
@endforeach
@endsection

@push('scripts')
<script>
// Função para iniciar todos os itens de um pedido
function startAllItems(orderId) {
    if (confirm('Deseja iniciar o preparo de todos os itens deste pedido?')) {
        document.getElementById('startAllForm' + orderId).submit();
    }
}

// Função para finalizar todos os itens de um pedido
function finishAllItems(orderId) {
    if (confirm('Deseja marcar todos os itens deste pedido como prontos?')) {
        document.getElementById('finishAllForm' + orderId).submit();
    }
}

// Atualização em tempo real (opcional)
// setInterval(function() {
//     fetch('{{ route('kitchen.active-orders') }}')
//         .then(response => response.json())
//         .then(data => {
//             // Atualizar o dashboard com os novos dados
//             console.log('Atualização em tempo real:', data);
//         })
//         .catch(error => console.error('Erro na atualização:', error));
// }, 30000); // Atualiza a cada 30 segundos
</script>
@endpush