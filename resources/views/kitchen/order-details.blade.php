@extends('layouts.app')

@section('title', 'Pedido #'.$order->id.' - Cozinha')
@section('title-icon', 'mdi-receipt-text')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('kitchen.dashboard') }}" class="d-flex align-items-center">
            <i class="mdi mdi-chef-hat me-1"></i> Cozinha
        </a>
    </li>
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-receipt-text me-1"></i> Pedido #{{ $order->id }}
    </li>
@endsection

@push('styles')
<style>
    .order-details-container {
        background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
        min-height: calc(100vh - 140px);
        padding: 2rem 0;
    }

    .order-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .order-header {
        background: linear-gradient(135deg, var(--primary-color), #1d4ed8);
        color: white;
        padding: 2rem;
        position: relative;
    }

    .order-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
        background-size: 30px 30px;
        opacity: 0.3;
    }

    .order-info {
        position: relative;
        z-index: 1;
    }

    .order-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin-top: 1.5rem;
    }

    .meta-item {
        text-align: center;
    }

    .meta-label {
        font-size: 0.9rem;
        opacity: 0.8;
        margin-bottom: 0.5rem;
    }

    .meta-value {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .items-section {
        padding: 2rem;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .item-card {
        background: #f8fafc;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 6px solid #e5e7eb;
        transition: all 0.3s ease;
        position: relative;
    }

    .item-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
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

    .item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .item-info {
        flex-grow: 1;
    }

    .item-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .item-details {
        display: flex;
        gap: 2rem;
        font-size: 0.9rem;
        color: #6b7280;
        flex-wrap: wrap;
    }

    .item-details span {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .item-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        min-width: 120px;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: #fef3c7;
        color: #d97706;
        border: 2px solid #fbbf24;
    }

    .status-preparing {
        background: #dbeafe;
        color: #1d4ed8;
        border: 2px solid #3b82f6;
    }

    .status-ready {
        background: #d1fae5;
        color: #065f46;
        border: 2px solid #10b981;
    }

    .action-btn {
        border: none;
        border-radius: 10px;
        padding: 0.75rem;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .btn-start {
        background: #dbeafe;
        color: #1d4ed8;
        border: 2px solid #3b82f6;
    }

    .btn-start:hover {
        background: #3b82f6;
        color: white;
    }

    .btn-finish {
        background: #d1fae5;
        color: #065f46;
        border: 2px solid #10b981;
    }

    .btn-finish:hover {
        background: #10b981;
        color: white;
    }

    .btn-reset {
        background: #fef3c7;
        color: #d97706;
        border: 2px solid #fbbf24;
    }

    .btn-reset:hover {
        background: #fbbf24;
        color: white;
    }

    .notes-section {
        margin-top: 1rem;
        padding: 1rem;
        background: #f1f5f9;
        border-radius: 10px;
        border-left: 4px solid #94a3b8;
    }

    .notes-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .notes-text {
        color: #64748b;
        font-style: italic;
    }

    .order-actions {
        padding: 2rem;
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .bulk-action-btn {
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .bulk-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }

    .timer-display {
        position: absolute;
        top: 10.5rem;
        left: 1rem;
        background: rgba(0,0,0,0.1);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .urgent-indicator {
        animation: pulse 2s infinite;
        color: #ef4444;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    @media (max-width: 768px) {
        .item-header {
            flex-direction: column;
            gap: 1rem;
        }
        
        .item-actions {
            flex-direction: row;
            flex-wrap: wrap;
        }
        
        .action-btn {
            min-width: 120px;
            flex: 1;
        }
        
        .order-meta {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="order-details-container">
    <div class="container-fluid">
        <div class="order-card">
            <!-- Header do Pedido -->
            <div class="order-header">
                <div class="order-info">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2 class="mb-2">
                                <i class="mdi mdi-receipt-text me-2"></i>
                                Pedido #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                            </h2>
                            @if($order->customer_name)
                                <h5 class="opacity-90">{{ $order->customer_name }}</h5>
                            @endif
                        </div>
                        <div class="text-end">
                            @php
                                $elapsedMinutes = $order->created_at->diffInMinutes(now());
                                $isUrgent = $elapsedMinutes > 30;
                            @endphp
                            <div class="timer-display {{ $isUrgent ? 'urgent-indicator' : '' }}" style="top: 2.7rem !important; left:-5px !important;">
                                <i class="mdi mdi-clock-outline me-1"></i>
                                {{ $elapsedMinutes }} minutos
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-meta">
                    <div class="meta-item">
                        <div class="meta-label">Mesa</div>
                        <div class="meta-value">
                            <i class="mdi mdi-table-chair me-1"></i>
                            {{ $order->table ? "Mesa {$order->table->number}" : 'Balcão' }}
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <div class="meta-label">Garçom</div>
                        <div class="meta-value">
                            <i class="mdi mdi-account me-1"></i>
                            {{ $order->user->name ?? 'Sistema' }}
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <div class="meta-label">Horário</div>
                        <div class="meta-value">
                            <i class="mdi mdi-clock me-1"></i>
                            {{ $order->created_at->format('H:i') }}
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <div class="meta-label">Total</div>
                        <div class="meta-value">
                            <i class="mdi mdi-currency-usd me-1"></i>
                            MZN {{ number_format($order->total_amount, 2, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Itens do Pedido -->
            <div class="items-section">
                <h3 class="section-title">
                    <i class="mdi mdi-food"></i>
                    Itens do Pedido ({{ $order->items->count() }})
                </h3>

                @foreach($order->items as $item)
                <div class="item-card {{ $item->status }}" data-item-id="{{ $item->id }}">
                    <div class="timer-display">
                        @if($item->started_at)
                            <i class="mdi mdi-timer me-1"></i>
                            {{ $item->started_at->diffInMinutes(now()) }}min preparo
                        @else
                            <i class="mdi mdi-clock-outline me-1"></i>
                            Aguardando
                        @endif
                    </div>

                    <div class="item-header">
                        <div class="item-info">
                            <div class="item-name">
                                {{ $item->quantity }}x {{ $item->product->name }}
                            </div>
                            <div class="item-details">
                                <span>
                                    <i class="mdi mdi-tag me-1"></i>
                                    {{ $item->product->category->name ?? 'Sem categoria' }}
                                </span>
                                <span>
                                    <i class="mdi mdi-currency-usd me-1"></i>
                                    MZN {{ number_format($item->total_price, 2, ',', '.') }}
                                </span>
                                @if($item->started_at && $item->finished_at)
                                <span>
                                    <i class="mdi mdi-speedometer me-1"></i>
                                    Preparo: {{ $item->started_at->diffInMinutes($item->finished_at) }}min
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="item-actions">
                            <div class="status-badge status-{{ $item->status }}">
                                {{ ucfirst($item->status) }}
                            </div>

                            @if($item->status == 'pending')
                                <form method="POST" action="{{ route('kitchen.update-item-status', $item) }}" class="update-form">
                                    @csrf
                                    <input type="hidden" name="status" value="preparing">
                                    <button type="submit" class="action-btn btn-start">
                                        <i class="mdi mdi-play"></i> Iniciar
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('kitchen.update-item-status', $item) }}" class="update-form">
                                    @csrf
                                    <input type="hidden" name="status" value="ready">
                                    <button type="submit" class="action-btn btn-finish">
                                        <i class="mdi mdi-check"></i> Direto p/ Pronto
                                    </button>
                                </form>
                            @elseif($item->status == 'preparing')
                                <form method="POST" action="{{ route('kitchen.update-item-status', $item) }}" class="update-form">
                                    @csrf
                                    <input type="hidden" name="status" value="ready">
                                    <button type="submit" class="action-btn btn-finish">
                                        <i class="mdi mdi-check"></i> Finalizar
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('kitchen.update-item-status', $item) }}" class="update-form">
                                    @csrf
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="action-btn btn-reset">
                                        <i class="mdi mdi-arrow-left"></i> Voltar
                                    </button>
                                </form>
                            @elseif($item->status == 'ready')
                                <div class="text-center">
                                    <i class="mdi mdi-check-circle text-success" style="font-size: 1.5rem;"></i>
                                    <div class="mt-1 text-success font-weight-bold">Pronto!</div>
                                </div>
                                <form method="POST" action="{{ route('kitchen.update-item-status', $item) }}" class="update-form">
                                    @csrf
                                    <input type="hidden" name="status" value="preparing">
                                    <button type="submit" class="action-btn btn-reset">
                                        <i class="mdi mdi-arrow-left"></i> Refazer
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    @if($item->notes)
                    <div class="notes-section">
                        <div class="notes-label">Observações:</div>
                        <div class="notes-text">{{ $item->notes }}</div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Ações em Massa -->
            <div class="order-actions">
                @php
                    $hasPending = $order->items->where('status', 'pending')->count() > 0;
                    $hasPreparing = $order->items->where('status', 'preparing')->count() > 0;
                    $allReady = $order->items->where('status', '!=', 'ready')->count() == 0;
                @endphp

                @if($hasPending)
                <form method="POST" action="{{ route('kitchen.start-all', $order) }}" class="update-form">
                    @csrf
                    <button type="submit" class="bulk-action-btn btn-warning">
                        <i class="mdi mdi-play"></i>
                        Iniciar Todos os Pendentes
                    </button>
                </form>
                @endif

                @if($hasPending || $hasPreparing)
                <form method="POST" action="{{ route('kitchen.finish-all', $order) }}" class="update-form">
                    @csrf
                    <button type="submit" class="bulk-action-btn btn-success">
                        <i class="mdi mdi-check-all"></i>
                        Finalizar Todos
                    </button>
                </form>
                @endif

                @if($allReady)
                <div class="bulk-action-btn btn-success">
                    <i class="mdi mdi-check-circle"></i>
                    Pedido Completo - Pronto para Entrega!
                </div>
                @endif

                <a href="{{ route('kitchen.dashboard') }}" class="bulk-action-btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i>
                    Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Adiciona confirmação para ações em massa
    const updateForms = document.querySelectorAll('.update-form');
    updateForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            const buttonText = button.innerHTML;
            
            // Mostrar loading
            button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Processando...';
            button.disabled = true;
            
            // Reverter após 2 segundos se não houver redirecionamento
            setTimeout(() => {
                button.innerHTML = buttonText;
                button.disabled = false;
            }, 2000);
        });
    });
    
    // Adiciona confirmação para finalizar todos (opcional)
    const finishAllButtons = document.querySelectorAll('.btn-success');
    finishAllButtons.forEach(button => {
        button.closest('form')?.addEventListener('submit', function(e) {
            if (this.querySelector('.mdi-check-all')) {
                if (!confirm('Tem certeza que deseja finalizar TODOS os itens deste pedido?')) {
                    e.preventDefault();
                }
            }
        });
    });
    async function updateItemStatus(itemId, status) {
    try {
        const response = await fetch(`/order-items/${itemId}/status`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: JSON.stringify({ status })
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            // aqui você pode atualizar a tabela sem reload
        }
    } catch (error) {
        console.error("Erro ao atualizar status:", error);
        alert("Falha na atualização do status");
    }
}

});
</script>
@endpush