@extends('layouts.app')

@section('title', 'Histórico da Cozinha')
@section('page-title', 'Histórico de Pedidos')
@section('title-icon', 'mdi-history')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('kitchen.dashboard') }}">Cozinha</a>
    </li>
    <li class="breadcrumb-item active">Histórico</li>
@endsection

@push('styles')
<style>
    .history-container {
        background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
        min-height: calc(100vh - 140px);
    }

    .filters-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .history-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .order-row {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .order-row:hover {
        background: #f8fafc;
        transform: translateX(3px);
    }

    .order-row:last-child {
        border-bottom: none;
    }

    .order-header {
        display: flex;
        justify-content: between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .order-info {
        flex-grow: 1;
    }

    .order-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .order-meta {
        display: flex;
        gap: 2rem;
        font-size: 0.9rem;
        color: #6b7280;
    }

    .order-stats {
        text-align: right;
        min-width: 150px;
    }

    .completion-time {
        font-size: 1.2rem;
        font-weight: 600;
        color: #10b981;
        margin-bottom: 0.5rem;
    }

    .completion-time.slow {
        color: #f59e0b;
    }

    .completion-time.very-slow {
        color: #ef4444;
    }

    .order-summary {
        font-size: 0.8rem;
        color: #9ca3af;
    }

    .items-preview {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .item-tag {
        background: #f3f4f6;
        color: #4b5563;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        border: 1px solid #e5e7eb;
    }

    .performance-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .performance-excellent {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #10b981;
    }

    .performance-good {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #f59e0b;
    }

    .performance-slow {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #ef4444;
    }

    .stats-overview {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.9rem;
    }

    .stat-trend {
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    .trend-up {
        color: #10b981;
    }

    .trend-down {
        color: #ef4444;
    }

    .no-results {
        text-align: center;
        padding: 4rem 2rem;
        color: #9ca3af;
    }

    .export-btn {
        border: 2px solid #e5e7eb;
        background: white;
        color: #6b7280;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .export-btn:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        background: #f0f9ff;
    }
</style>
@endpush

@section('content')
<div class="history-container p-4">
    <!-- Estatísticas Resumo -->
    @if($orders->count() > 0)
    <div class="stats-overview">
        <h5 class="mb-3">
            <i class="mdi mdi-chart-line me-2"></i>
            Resumo do Período
        </h5>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value">{{ $orders->count() }}</div>
                <div class="stat-label">Pedidos Processados</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $orders->sum(fn($o) => $o->items->count()) }}</div>
                <div class="stat-label">Total de Itens</div>
            </div>
            <div class="stat-item">
                @php
                    $avgTime = $orders->filter(fn($o) => $o->completed_at && $o->created_at)
                                     ->avg(fn($o) => $o->created_at->diffInMinutes($o->completed_at));
                @endphp
                <div class="stat-value">{{ round($avgTime, 1) }}min</div>
                <div class="stat-label">Tempo Médio</div>
            </div>
            <div class="stat-item">
                @php
                    $fastOrders = $orders->filter(fn($o) => $o->completed_at && $o->created_at->diffInMinutes($o->completed_at) <= 20)->count();
                    $efficiency = $orders->count() > 0 ? round(($fastOrders / $orders->count()) * 100) : 0;
                @endphp
                <div class="stat-value">{{ $efficiency }}%</div>
                <div class="stat-label">Eficiência (&lt;20min)</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtros -->
    <div class="filters-card">
        <h5 class="mb-3">
            <i class="mdi mdi-filter me-2"></i>
            Filtros
        </h5>
        <form method="GET" class="filter-row">
            <div>
                <label class="form-label">Data Início</label>
                <input type="date" name="date_from" class="form-control" 
                       value="{{ request('date_from') }}">
            </div>
            <div>
                <label class="form-label">Data Fim</label>
                <input type="date" name="date_to" class="form-control" 
                       value="{{ request('date_to') }}">
            </div>
            <div>
                <label class="form-label">Mesa</label>
                <select name="table_id" class="form-select">
                    <option value="">Todas as mesas</option>
                    @for($i = 1; $i <= 20; $i++)
                        <option value="{{ $i }}" {{ request('table_id') == $i ? 'selected' : '' }}>
                            Mesa {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="form-label">Performance</label>
                <select name="performance" class="form-select">
                    <option value="">Todas</option>
                    <option value="excellent" {{ request('performance') == 'excellent' ? 'selected' : '' }}>
                        Excelente (&lt;15min)
                    </option>
                    <option value="good" {{ request('performance') == 'good' ? 'selected' : '' }}>
                        Boa (15-30min)
                    </option>
                    <option value="slow" {{ request('performance') == 'slow' ? 'selected' : '' }}>
                        Lenta (&gt;30min)
                    </option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-magnify"></i> Filtrar
                </button>
            </div>
            <div>
                <button type="button" class="export-btn" onclick="exportHistory()">
                    <i class="mdi mdi-download"></i> Exportar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Pedidos -->
    <div class="history-card">
        @forelse($orders as $order)
        <div class="order-row">
            @php
                $completionTime = $order->completed_at ? $order->created_at->diffInMinutes($order->completed_at) : 0;
                $performance = 'excellent';
                if ($completionTime > 30) $performance = 'slow';
                elseif ($completionTime > 15) $performance = 'good';
                
                $performanceLabels = [
                    'excellent' => 'Excelente',
                    'good' => 'Boa',
                    'slow' => 'Lenta'
                ];
            @endphp
            
            <div class="performance-badge performance-{{ $performance }}">
                {{ $performanceLabels[$performance] }}
            </div>

            <div class="order-header">
                <div class="order-info">
                    <div class="order-title">
                        <i class="mdi mdi-receipt-text me-2"></i>
                        Pedido #{{ $order->id }}
                        @if($order->customer_name)
                            - {{ $order->customer_name }}
                        @endif
                    </div>
                    <div class="order-meta">
                        <span>
                            <i class="mdi mdi-table-chair me-1"></i>
                            {{ $order->table ? "Mesa {$order->table->number}" : 'Balcão' }}
                        </span>
                        <span>
                            <i class="mdi mdi-clock me-1"></i>
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </span>
                        <span>
                            <i class="mdi mdi-account me-1"></i>
                            {{ $order->user->name ?? 'Sistema' }}
                        </span>
                        <span>
                            <i class="mdi mdi-currency-usd me-1"></i>
                            {{ number_format($order->total_amount, 2, ',', '.') }} MT
                        </span>
                    </div>
                </div>

                <div class="order-stats">
                    <div class="completion-time {{ $completionTime > 30 ? 'very-slow' : ($completionTime > 20 ? 'slow' : '') }}">
                        {{ $completionTime }}min
                    </div>
                    <div class="order-summary">
                        {{ $order->items->count() }} itens
                        @if($order->completed_at)
                            • Finalizado {{ $order->completed_at->format('H:i') }}
                        @endif
                    </div>
                </div>
            </div>

            <!-- Preview dos Itens -->
            <div class="items-preview">
                @foreach($order->items as $item)
                <span class="item-tag">
                    {{ $item->quantity }}x {{ $item->product->name }}
                </span>
                @endforeach
            </div>
        </div>
        @empty
        <div class="no-results">
            <i class="mdi mdi-history" style="font-size: 4rem;"></i>
            <h4 class="mt-3 mb-2">Nenhum histórico encontrado</h4>
            <p>Não há pedidos finalizados para o período selecionado.</p>
            <a href="{{ route('kitchen.dashboard') }}" class="btn btn-primary mt-2">
                <i class="mdi mdi-arrow-left"></i> Voltar ao Dashboard
            </a>
        </div>
        @endforelse
    </div>

    <!-- Paginação -->
    @if($orders->hasPages())
    <div class="d-flex justify-content-center">
        {{ $orders->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configurar datas padrão se não estiverem definidas
    const dateFromInput = document.querySelector('input[name="date_from"]');
    const dateToInput = document.querySelector('input[name="date_to"]');
    
    if (!dateFromInput.value) {
        // Padrão: últimos 7 dias
        const lastWeek = new Date();
        lastWeek.setDate(lastWeek.getDate() - 7);
        dateFromInput.value = lastWeek.toISOString().split('T')[0];
    }
    
    if (!dateToInput.value) {
        // Padrão: hoje
        const today = new Date();
        dateToInput.value = today.toISOString().split('T')[0];
    }

    // Auto-submit quando filtros mudarem
    document.querySelectorAll('select[name="table_id"], select[name="performance"]').forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
});

function exportHistory() {
    const form = document.querySelector('form');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData).toString();
    
    // Criar URL de exportação
    const exportUrl = `/kitchen/history/export?${params}`;
    
    // Abrir em nova janela ou fazer download
    window.open(exportUrl, '_blank');
    
    showToast('Exportação iniciada!', 'info');
}

// Função para análise rápida de performance
function analyzePerformance() {
    const orderRows = document.querySelectorAll('.order-row');
    let excellent = 0, good = 0, slow = 0;
    
    orderRows.forEach(row => {
        const badge = row.querySelector('.performance-badge');
        if (badge.classList.contains('performance-excellent')) excellent++;
        else if (badge.classList.contains('performance-good')) good++;
        else if (badge.classList.contains('performance-slow')) slow++;
    });
    
    const total = excellent + good + slow;
    if (total > 0) {
        const analysis = {
            excellent: Math.round((excellent / total) * 100),
            good: Math.round((good / total) * 100),
            slow: Math.round((slow / total) * 100)
        };
        
        console.log('Análise de Performance:', analysis);
        return analysis;
    }
    
    return null;
}

// Atalhos de teclado
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey || e.metaKey) {
        switch(e.key) {
            case 'e':
                e.preventDefault();
                exportHistory();
                break;
            case 'r':
                e.preventDefault();
                document.querySelector('form').reset();
                document.querySelector('form').submit();
                break;
            case 'b':
                e.preventDefault();
                window.location.href = "{{ route('kitchen.dashboard') }}";
                break;
        }
    }
});
</script>
@endpush