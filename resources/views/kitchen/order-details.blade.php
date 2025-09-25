@extends('layouts.app')

@section('title', 'Detalhes do Pedido')
@section('title-icon', 'mdi-receipt')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('kitchen.dashboard') }}" class="d-flex align-items-center">
            <i class="mdi mdi-chef-hat me-1"></i> Cozinha
        </a>
    </li>
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-receipt me-1"></i> Pedido #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
    </li>
@endsection

@section('styles')
<style>
.order-detail-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    background: var(--bs-card-bg);
    overflow: hidden;
}

.order-header {
    background: linear-gradient(135deg, var(--primary-color), #6366f1);
    color: white;
    padding: 2rem;
    position: relative;
}

.order-header::after {
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

.item-row {
    display: flex;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.item-row:last-child {
    border-bottom: none;
}

.item-status {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
    min-width: 100px;
    text-align: center;
}

.action-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.action-btn:hover {
    transform: translateY(-2px);
}
</style>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="order-detail-card">
            <div class="order-header">
                <h1 class="mb-2">Pedido #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h1>
                <p class="mb-0">
                    Mesa {{ $order->table?->number ?? 'Balcão' }}
                    @if($order->customer_name)
                        • {{ $order->customer_name }}
                    @endif
                    • {{ $order->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
            
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-0">
                            <i class="mdi mdi-food-variant me-2 text-primary"></i>
                            Itens do Pedido ({{ $order->items->count() }})
                        </h4>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('kitchen.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left me-2"></i> Voltar
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Observações</th>
                                <th>Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $item->product->name }}</div>
                                    <small class="text-muted">
                                        {{ $item->product->category->name ?? 'Sem categoria' }}
                                    </small>
                                </td>
                                <td>{{ $item->quantity }}x</td>
                                <td>
                                    @if($item->notes)
                                        <span class="text-muted">{{ $item->notes }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Sem observações</span>
                                    @endif
                                </td>
                                <td>
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
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        @if($item->status === 'pending')
                                            <button type="button" 
                                                    class="action-btn btn btn-outline-info"
                                                    onclick="updateItemStatus({{ $item->id }}, 'preparing')">
                                                <i class="mdi mdi-play"></i>
                                            </button>
                                        @elseif($item->status === 'preparing')
                                            <button type="button" 
                                                    class="action-btn btn btn-outline-success"
                                                    onclick="updateItemStatus({{ $item->id }}, 'ready')">
                                                <i class="mdi mdi-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-info" onclick="startAllItems()">
                        <i class="mdi mdi-play me-2"></i> Iniciar Todos
                    </button>
                    <button type="button" class="btn btn-success" onclick="finishAllItems()">
                        <i class="mdi mdi-check me-2"></i> Finalizar Todos
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Forms -->
<form id="startAllForm" action="{{ route('kitchen.start-all', $order) }}" method="POST" style="display: none;">
    @csrf
</form>
<form id="finishAllForm" action="{{ route('kitchen.finish-all', $order) }}" method="POST" style="display: none;">
    @csrf
</form>

@foreach($order->items as $item)
    @if($item->status === 'pending')
        <form id="updateForm{{ $item->id }}" action="{{ route('kitchen.update-item', $item) }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="status" value="preparing">
        </form>
    @elseif($item->status === 'preparing')
        <form id="updateForm{{ $item->id }}" action="{{ route('kitchen.update-item', $item) }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="status" value="ready">
        </form>
    @endif
@endforeach
@endsection

@push('scripts')
<script>
function updateItemStatus(itemId, status) {
    document.getElementById('updateForm' + itemId).submit();
}

function startAllItems() {
    if (confirm('Deseja iniciar o preparo de todos os itens deste pedido?')) {
        document.getElementById('startAllForm').submit();
    }
}

function finishAllItems() {
    if (confirm('Deseja marcar todos os itens deste pedido como prontos?')) {
        document.getElementById('finishAllForm').submit();
    }
}
</script>
@endpush