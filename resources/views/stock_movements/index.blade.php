@extends('layouts.app')

@section('title', 'Movimentos de Estoque')
@section('page-title', 'Movimentos de Estoque')
@section('title-icon', 'mdi-clipboard-list')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
            <i class="mdi mdi-home me-1"></i> Início
        </a>
    </li>
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-clipboard-list me-1"></i> Movimentos de Estoque
    </li>
@endsection

@push('styles')
<style>
    .stock-header {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
    }
    .stock-header::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg,#0891b2,#fbbf24);
    }
    .stock-title {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(90deg,#0891b2,#fbbf24);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .stock-title i {
        font-size: 2.2rem;
    }
    .stats-card {
        border-left: 4px solid #0891b2;
        border-radius: 12px;
        background: #f8fafc;
        padding: 1.2rem 1.5rem;
        margin-bottom: 1rem;
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .stats-card:hover {
        box-shadow: 0 4px 16px rgba(8,145,178,0.08);
        transform: translateY(-2px);
    }
    .stats-label {
        font-size: 0.95rem;
        color: #64748b;
        font-weight: 500;
    }
    .stats-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0891b2;
    }
    .filter-card {
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        background: #fff;
    }
    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .action-btn:hover {
        box-shadow: 0 2px 8px #0891b233;
        transform: translateY(-2px);
    }
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6b7280;
    }
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
    <!-- Header -->
    <div class="stock-header fade-in">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="stock-title d-flex align-items-center gap-3">
                    <i class="mdi mdi-clipboard-list"></i>
                    Movimentos de Estoque
                </h1>
                <p class="text-muted mb-0 fs-5">Acompanhe entradas, saídas e ajustes de estoque</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex gap-2 flex-wrap justify-content-lg-end">
                    <a href="{{ route('stock-movements.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus me-1"></i> Novo Movimento
                    </a>
                    <a href="{{ route('reports.inventory') }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-chart-line me-1"></i> Relatório
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-label">Total Movimentos</div>
                <div class="stats-value">{{ number_format($movements->total()) }}</div>
                <small class="text-muted">registrados</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="border-left-color:#10b981;">
                <div class="stats-label">Entradas</div>
                <div class="stats-value text-success">{{ $movements->where('movement_type', 'in')->count() }}</div>
                <small class="text-muted">produtos recebidos</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="border-left-color:#ef4444;">
                <div class="stats-label">Saídas</div>
                <div class="stats-value text-danger">{{ $movements->where('movement_type', 'out')->count() }}</div>
                <small class="text-muted">produtos enviados</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="border-left-color:#f59e0b;">
                <div class="stats-label">Ajustes</div>
                <div class="stats-value text-warning">{{ $movements->where('movement_type', 'adjustment')->count() }}</div>
                <small class="text-muted">correções feitas</small>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 fade-in">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0 d-flex align-items-center">
                <i class="mdi mdi-filter-variant me-2 text-primary"></i>
                Filtros
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('stock-movements.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="product" class="form-label fw-semibold">Pesquisar Produto</label>
                        <input type="text" class="form-control" id="product" name="product"
                            value="{{ request('product') }}" placeholder="Nome do produto...">
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label fw-semibold">Data Inicial</label>
                        <input type="date" class="form-control" id="date_from" name="date_from"
                            value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label fw-semibold">Data Final</label>
                        <input type="date" class="form-control" id="date_to" name="date_to"
                            value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="movement_type" class="form-label fw-semibold">Tipo</label>
                        <select class="form-select" id="movement_type" name="movement_type">
                            <option value="">Todos</option>
                            <option value="in" {{ request('movement_type') == 'in' ? 'selected' : '' }}>Entrada</option>
                            <option value="out" {{ request('movement_type') == 'out' ? 'selected' : '' }}>Saída</option>
                            <option value="adjustment" {{ request('movement_type') == 'adjustment' ? 'selected' : '' }}>Ajuste</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                            <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary flex-fill">
                                <i class="mdi mdi-refresh"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Movimentos -->
    <div class="card fade-in">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="padding: 0.5rem 1rem;">
                    <thead class="table-light">
                        <tr>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Produto</th>
                            <th>Tipo</th>
                            <th class="text-end">Quantidade</th>
                            <th>Usuário</th>
                            <th>Motivo</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                            <tr>
                                <td>
                                    <span class="fw-medium">{{ $movement->movement_date->format('d/m/Y') }}</span>
                                    <small class="text-muted">{{ $movement->movement_date->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="fw-medium">{{ $movement->created_at->format('H:i') }}</span>
                                </td>
                                <td>
                                    @if($movement->product_id && $movement->product)
                                        <div class="fw-medium">{{ $movement->product->name }}</div>
                                        <small class="text-muted">
                                            {{ $movement->product->type === 'service' ? 'Serviço' : 'Produto' }}
                                        </small>
                                    @else
                                        <div class="fw-medium text-muted">Produto Removido</div>
                                        <small class="text-muted">ID: {{ $movement->product_id }}</small>
                                    @endif
                                </td>
                                <td>
                                    @switch($movement->movement_type)
                                        @case('in')
                                            <span class="badge bg-success">Entrada</span>
                                        @break
                                        @case('out')
                                            <span class="badge bg-danger">Saída</span>
                                        @break
                                        @case('adjustment')
                                            <span class="badge bg-warning text-dark">Ajuste</span>
                                        @break
                                    @endswitch
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold fs-6 
                                        @if($movement->movement_type === 'in') text-success
                                        @elseif($movement->movement_type === 'out') text-danger
                                        @else text-warning @endif">
                                        @if($movement->movement_type === 'out')-@endif{{ number_format($movement->quantity) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-medium">{{ $movement->user->name ?? 'Sistema' }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($movement->reason)
                                        <span class="text-muted" title="{{ $movement->reason }}">
                                            {{ Str::limit($movement->reason, 30) }}
                                        </span>
                                    @else
                                        <span class="text-muted fst-italic">Sem motivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('stock-movements.show', $movement) }}" 
                                           class="action-btn btn btn-outline-primary"
                                           title="Ver Detalhes">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        <a href="{{ route('stock-movements.edit', $movement) }}"
                                           class="action-btn btn btn-outline-info"
                                           title="Editar">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('stock-movements.destroy', $movement) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn btn btn-outline-danger" title="Excluir" onclick="return confirm('Deseja realmente excluir este movimento?');">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <h5>Nenhum movimento encontrado</h5>
                                        <p class="mb-4">Não há movimentos que correspondam aos filtros aplicados.</p>
                                        <a href="{{ route('stock-movements.create') }}" class="btn btn-primary">
                                            <i class="mdi mdi-plus me-2"></i> Registrar Primeiro Movimento
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            @if ($movements->hasPages())
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted small">
                            Mostrando {{ $movements->firstItem() ?? 0 }} a {{ $movements->lastItem() ?? 0 }} de {{ $movements->total() }}
                        </div>
                        <div>
                            {{ $movements->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection