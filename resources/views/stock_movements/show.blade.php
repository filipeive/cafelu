@extends('layouts.app')

@section('title', 'Detalhes do Movimento')
@section('title-icon', 'mdi-clipboard-list')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('stock-movements.index') }}" class="d-flex align-items-center">
            <i class="mdi mdi-clipboard-list me-1"></i> Movimentos de Estoque
        </a>
    </li>
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-file-document me-1"></i> Movimento #{{ $stockMovement->id }}
    </li>
@endsection

@push('styles')
<style>
    .movement-card {
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        background: #fff;
        margin-bottom: 2rem;
        padding: 2rem;
    }
    .movement-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .movement-icon {
        font-size: 3rem;
        border-radius: 16px;
        padding: 1rem;
        margin-bottom: 1rem;
        display: inline-block;
        background: linear-gradient(90deg,#0891b2,#fbbf24);
        color: #fff;
    }
    .movement-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        background: linear-gradient(90deg,#0891b2,#fbbf24);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .movement-subtitle {
        color: #64748b;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    .quantity-display {
        font-size: 2rem;
        font-weight: 700;
        border-radius: 12px;
        padding: 1rem 2rem;
        display: inline-block;
        margin-bottom: 1rem;
        background: #f8fafc;
        color: #0891b2;
        border: 2px solid #0891b2;
    }
    .quantity-in { border-color: #10b981; color: #10b981; }
    .quantity-out { border-color: #ef4444; color: #ef4444; }
    .quantity-adjustment { border-color: #f59e0b; color: #f59e0b; }
    .detail-section {
        margin-bottom: 2rem;
    }
    .detail-label {
        font-weight: 500;
        color: #64748b;
    }
    .detail-value {
        font-weight: 600;
        color: #0891b2;
    }
    .product-preview {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .product-icon {
        font-size: 2rem;
        margin-right: 1rem;
        color: #0891b2;
    }
    .timeline {
        border-left: 3px solid #0891b2;
        padding-left: 1.5rem;
        margin-bottom: 2rem;
    }
    .timeline-item {
        margin-bottom: 1.5rem;
        position: relative;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.7rem;
        top: 0.5rem;
        width: 1rem;
        height: 1rem;
        background: #0891b2;
        border-radius: 50%;
    }
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-top: 2rem;
    }
    @media (max-width: 768px) {
        .movement-card { padding: 1rem; }
        .movement-title { font-size: 1.3rem; }
        .quantity-display { font-size: 1.2rem; padding: 0.5rem 1rem; }
        .product-preview { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')
<div class="container-wrapper fade-in">
    <div class="movement-card">
        <div class="movement-header">
            <span class="movement-icon">
                @switch($stockMovement->movement_type)
                    @case('in') <i class="mdi mdi-arrow-up"></i> @break
                    @case('out') <i class="mdi mdi-arrow-down"></i> @break
                    @case('adjustment') <i class="mdi mdi-wrench"></i> @break
                @endswitch
            </span>
            <div class="movement-title">
                @switch($stockMovement->movement_type)
                    @case('in') Entrada de Estoque @break
                    @case('out') Saída de Estoque @break
                    @case('adjustment') Ajuste de Estoque @break
                @endswitch
            </div>
            <div class="movement-subtitle">
                Movimento registrado em {{ $stockMovement->movement_date->format('d/m/Y') }} às {{ $stockMovement->created_at->format('H:i') }}
            </div>
            <div class="quantity-display quantity-{{ $stockMovement->movement_type }}">
                @if ($stockMovement->movement_type === 'out')-@endif{{ number_format($stockMovement->quantity) }}
                <div style="font-size: 0.9rem;">
                    @if ($stockMovement->movement_type === 'in')
                        unidades adicionadas
                    @elseif($stockMovement->movement_type === 'out')
                        unidades removidas
                    @else
                        unidades ajustadas
                    @endif
                </div>
            </div>
        </div>

        <div class="detail-section">
            <h5 class="mb-3"><i class="mdi mdi-cube text-primary me-1"></i> Produto</h5>
            @if ($stockMovement->product)
                <div class="product-preview">
                    <span class="product-icon">
                        <i class="mdi {{ $stockMovement->product->type === 'service' ? 'mdi-cog' : 'mdi-cube' }}"></i>
                    </span>
                    <div>
                        <div class="fw-bold">{{ $stockMovement->product->name }}</div>
                        <small class="text-muted">
                            {{ $stockMovement->product->type === 'service' ? 'Serviço' : 'Produto' }}
                            @if ($stockMovement->product->description)
                                • {{ Str::limit($stockMovement->product->description, 60) }}
                            @endif
                        </small>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="mdi mdi-alert-circle-outline me-2"></i>
                    Este produto foi removido do sistema. ID original: {{ $stockMovement->product_id }}
                </div>
            @endif
        </div>

        <div class="detail-section">
            <h5 class="mb-3"><i class="mdi mdi-information-outline text-primary me-1"></i> Detalhes</h5>
            <div class="row mb-2">
                <div class="col-6 detail-label"><i class="mdi mdi-identifier"></i> ID do Movimento</div>
                <div class="col-6 detail-value">#{{ $stockMovement->id }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-6 detail-label"><i class="mdi mdi-swap-vertical"></i> Tipo</div>
                <div class="col-6 detail-value">
                    @switch($stockMovement->movement_type)
                        @case('in') <span class="badge bg-success">Entrada</span> @break
                        @case('out') <span class="badge bg-danger">Saída</span> @break
                        @case('adjustment') <span class="badge bg-warning text-dark">Ajuste</span> @break
                    @endswitch
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-6 detail-label"><i class="mdi mdi-numeric"></i> Quantidade</div>
                <div class="col-6 detail-value">
                    @if ($stockMovement->movement_type === 'out')-@endif{{ number_format($stockMovement->quantity) }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-6 detail-label"><i class="mdi mdi-calendar"></i> Data do Movimento</div>
                <div class="col-6 detail-value">{{ $stockMovement->movement_date->format('d/m/Y') }}</div>
            </div>
            @if ($stockMovement->reason)
                <div class="row mb-2">
                    <div class="col-6 detail-label"><i class="mdi mdi-comment"></i> Motivo</div>
                    <div class="col-6 detail-value">{{ $stockMovement->reason }}</div>
                </div>
            @endif
        </div>

        <div class="detail-section">
            <h5 class="mb-3"><i class="mdi mdi-account text-primary me-1"></i> Usuário</h5>
            <div class="row mb-2">
                <div class="col-6 detail-label"><i class="mdi mdi-account"></i> Registrado por</div>
                <div class="col-6 detail-value">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                            style="width: 24px; height: 24px;">
                            <small class="text-white fw-bold">
                                {{ substr($stockMovement->user->name ?? 'S', 0, 1) }}
                            </small>
                        </div>
                        {{ $stockMovement->user->name ?? 'Sistema' }}
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-6 detail-label"><i class="mdi mdi-clock"></i> Data de Criação</div>
                <div class="col-6 detail-value">{{ $stockMovement->created_at->format('d/m/Y H:i') }}</div>
            </div>
            @if ($stockMovement->updated_at != $stockMovement->created_at)
                <div class="row mb-2">
                    <div class="col-6 detail-label"><i class="mdi mdi-update"></i> Última Atualização</div>
                    <div class="col-6 detail-value">{{ $stockMovement->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            @endif
        </div>

        <div class="detail-section">
            <h5 class="mb-3"><i class="mdi mdi-timeline-clock text-primary me-1"></i> Linha do Tempo</h5>
            <div class="timeline">
                <div class="timeline-item">
                    <div><strong>Movimento Criado</strong></div>
                    <small>{{ $stockMovement->created_at->format('d/m/Y H:i') }}</small>
                </div>
                @if ($stockMovement->updated_at != $stockMovement->created_at)
                    <div class="timeline-item">
                        <div><strong>Movimento Atualizado</strong></div>
                        <small>{{ $stockMovement->updated_at->format('d/m/Y H:i') }}</small>
                    </div>
                @endif
                <div class="timeline-item">
                    <div>
                        <strong>Estoque Atualizado</strong>
                        <div class="text-muted" style="font-size:0.95rem;">
                            @switch($stockMovement->movement_type)
                                @case('in') Estoque aumentado em {{ $stockMovement->quantity }} unidades @break
                                @case('out') Estoque reduzido em {{ $stockMovement->quantity }} unidades @break
                                @case('adjustment') Estoque ajustado em {{ $stockMovement->quantity }} unidades @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            @if (Auth::user()->role == 'admin')
                <a href="{{ route('stock-movements.edit', $stockMovement) }}" class="btn btn-primary">
                    <i class="mdi mdi-pencil me-2"></i> Editar Movimento
                </a>
            @endif
            <a href="{{ route('stock-movements.create') }}" class="btn btn-outline-success">
                <i class="mdi mdi-plus me-2"></i> Novo Movimento
            </a>
            @if ($stockMovement->product)
                <a href="{{ route('products.show', $stockMovement->product) }}" class="btn btn-outline-info">
                    <i class="mdi mdi-cube me-2"></i> Ver Produto
                </a>
            @endif
            <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-2"></i> Voltar à Lista
            </a>
            @if (Auth::user()->role == 'admin')
                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                    <i class="mdi mdi-delete me-2"></i> Excluir Movimento
                </button>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Tem certeza?',
            html: 'Deseja realmente excluir este movimento?<br><br>Esta ação não poderá ser desfeita e pode afetar os cálculos de estoque.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="mdi mdi-delete me-1"></i> Sim, excluir!',
            cancelButtonText: '<i class="mdi mdi-close me-1"></i> Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('stock-movements.destroy', $stockMovement) }}';
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush