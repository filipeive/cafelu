@extends('layouts.app')

@section('title', 'Movimentos de Estoque')
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

@section('styles')
<style>
/* =============== STOCK MOVEMENTS STYLES =============== */
.movement-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    overflow: hidden;
    background: var(--bs-card-bg);
    height: 100%;
}

.movement-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.stats-card {
    position: relative;
    padding: 1.5rem;
    min-height: 160px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.stats-card .stats-icon {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    opacity: 0.15;
    font-size: 2.5rem;
}

.stats-card .stats-value {
    font-size: 1.75rem;
    font-weight: 700;
    line-height: 1.2;
    margin: 0;
}

.stats-card .stats-label {
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.8;
    margin: 0;
}

/* Gradient Backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color), #6366f1);
    color: white;
}

.bg-gradient-success {
    background: linear-gradient(135deg, var(--success-color), #10b981);
    color: white;
}

.bg-gradient-danger {
    background: linear-gradient(135deg, var(--danger-color), #ef4444);
    color: white;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, var(--warning-color), #f59e0b);
    color: white;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
}

/* Movement Row Styles */
.movement-row {
    transition: all 0.2s ease;
    cursor: pointer;
    border-left: 4px solid transparent;
}

.movement-row:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.movement-row.movement-in {
    border-left-color: var(--success-color);
}

.movement-row.movement-out {
    border-left-color: var(--danger-color);
}

.movement-row.movement-adjustment {
    border-left-color: var(--warning-color);
}

/* Product Icon */
.product-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

/* User Avatar */
.user-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: bold;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: rgba(0, 0, 0, 0.6);
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h5 {
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
    color: rgba(0, 0, 0, 0.8);
}

/* Filter Card */
.filter-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    border: none;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.filter-card .card-header {
    background: var(--bs-card-bg);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1.25rem 1.5rem;
}

/* Action Buttons */
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

/* Responsive */
@media (max-width: 768px) {
    .stats-card {
        min-height: 140px;
        padding: 1rem;
    }
    
    .stats-card .stats-icon {
        font-size: 2rem;
        top: 1rem;
        right: 1rem;
    }
    
    .stats-card .stats-value {
        font-size: 1.5rem;
    }
}

/* Beach Theme Accents */
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
</style>
@endsection

@section('content')
<div class="row g-4">
    <!-- Header Section -->
    <div class="col-12">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="mdi mdi-clipboard-list me-2"></i>
                    Movimentos de Estoque
                </h2>
                <p class="text-muted mb-0">Acompanhe todos os movimentos de entrada, saída e ajustes de estoque</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('stock-movements.create') }}" class="btn btn-primary d-flex align-items-center">
                    <i class="mdi mdi-plus me-2"></i>
                    <span>Novo Movimento</span>
                </a>
                <a href="{{ route('reports.inventory') }}" class="btn btn-outline-primary d-flex align-items-center">
                    <i class="mdi mdi-chart-line me-2"></i>
                    <span>Relatório</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="col-12">
        <div class="row g-4">
            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="movement-card stats-card bg-gradient-primary position-relative">
                    <i class="mdi mdi-list stats-icon"></i>
                    <div>
                        <p class="stats-label">Total Movimentos</p>
                        <h3 class="stats-value">{{ number_format($movements->total()) }}</h3>
                        <small class="text-white">registrados</small>
                    </div>
                    <div class="beach-accent"></div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="movement-card stats-card bg-gradient-success position-relative">
                    <i class="mdi mdi-arrow-up stats-icon"></i>
                    <div>
                        <p class="stats-label">Entradas</p>
                        <h3 class="stats-value">{{ $movements->where('movement_type', 'in')->count() }}</h3>
                        <small class="text-white">produtos recebidos</small>
                    </div>
                    <div class="beach-accent"></div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="movement-card stats-card bg-gradient-danger position-relative">
                    <i class="mdi mdi-arrow-down stats-icon"></i>
                    <div>
                        <p class="stats-label">Saídas</p>
                        <h3 class="stats-value">{{ $movements->where('movement_type', 'out')->count() }}</h3>
                        <small class="text-white">produtos enviados</small>
                    </div>
                    <div class="beach-accent"></div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="movement-card stats-card bg-gradient-warning position-relative">
                    <i class="mdi mdi-wrench stats-icon"></i>
                    <div>
                        <p class="stats-label">Ajustes</p>
                        <h3 class="stats-value">{{ $movements->where('movement_type', 'adjustment')->count() }}</h3>
                        <small class="text-white">correções feitas</small>
                    </div>
                    <div class="beach-accent"></div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6">
                <div class="movement-card stats-card bg-gradient-info position-relative">
                    <i class="mdi mdi-calendar-today stats-icon"></i>
                    <div>
                        <p class="stats-label">Hoje</p>
                        <h3 class="stats-value">{{ $movements->where('movement_date', '>=', now()->startOfDay())->count() }}</h3>
                        <small class="text-white">movimentos hoje</small>
                    </div>
                    <div class="beach-accent"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="col-12">
        <div class="filter-card">
            <div class="card-header">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="mdi mdi-filter-variant me-2 text-primary"></i>
                    Filtros de Pesquisa
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('stock-movements.index') }}" id="filter-form">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <label for="product" class="form-label">Pesquisar Produto</label>
                            <input type="text" class="form-control" id="product" name="product"
                                value="{{ request('product') }}" placeholder="Nome do produto...">
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="date_from" class="form-label">Data Inicial</label>
                            <input type="date" class="form-control" id="date_from" name="date_from"
                                value="{{ request('date_from') }}">
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="date_to" class="form-label">Data Final</label>
                            <input type="date" class="form-control" id="date_to" name="date_to"
                                value="{{ request('date_to') }}">
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="movement_type" class="form-label">Tipo</label>
                            <select class="form-select" id="movement_type" name="movement_type">
                                <option value="">Todos</option>
                                <option value="in" {{ request('movement_type') == 'in' ? 'selected' : '' }}>Entrada</option>
                                <option value="out" {{ request('movement_type') == 'out' ? 'selected' : '' }}>Saída</option>
                                <option value="adjustment" {{ request('movement_type') == 'adjustment' ? 'selected' : '' }}>Ajuste</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-12">
                            <label class="form-label d-none d-lg-block">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="mdi mdi-magnify me-2"></i> Filtrar
                                </button>
                                <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="mdi mdi-restore me-2"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Movements Table -->
    <div class="col-12">
        <div class="movement-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
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
                            <tr class="movement-row movement-{{ $movement->movement_type }}">
                                <td>
                                    <div class="fw-medium">{{ $movement->movement_date->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $movement->movement_date->diffForHumans() }}</small>
                                </td>

                                <td>
                                    <span class="fw-medium">{{ $movement->created_at->format('H:i') }}</span>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="product-icon bg-{{ $movement->product && $movement->product->type === 'service' ? 'info' : 'secondary' }} bg-opacity-10 text-{{ $movement->product && $movement->product->type === 'service' ? 'info' : 'secondary' }} me-2">
                                            <i class="mdi {{ $movement->product && $movement->product->type === 'service' ? 'mdi-cog' : 'mdi-cube' }}"></i>
                                        </div>
                                        <div>
                                            @if($movement->product_id && $movement->product)
                                                <div class="fw-medium">{{ $movement->product->name }}</div>
                                                <small class="text-muted">
                                                    {{ $movement->product->type === 'service' ? 'Serviço' : 'Produto' }}
                                                </small>
                                            @else
                                                <div class="fw-medium text-muted">Produto Removido</div>
                                                <small class="text-muted">ID: {{ $movement->product_id }}</small>
                                            @endif
                                        </div>
                                    </div>
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
                                        <div class="user-avatar bg-primary text-white me-2">
                                            {{ substr($movement->user->name ?? 'S', 0, 1) }}
                                        </div>
                                        <small class="text-muted">{{ $movement->user->name ?? 'Sistema' }}</small>
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
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('stock-movements.show', $movement) }}" 
                                           class="action-btn btn btn-outline-primary"
                                           data-bs-toggle="tooltip"
                                           title="Ver Detalhes">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        <a href="{{ route('stock-movements.edit', $movement) }}"
                                           class="action-btn btn btn-outline-info"
                                           data-bs-toggle="tooltip"
                                           title="Editar">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <button type="button"
                                                class="action-btn btn btn-outline-danger"
                                                onclick="confirmDelete({{ $movement->id }}, '{{ $movement->product->name ?? 'Movimento' }}')"
                                                data-bs-toggle="tooltip"
                                                title="Excluir">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="mdi mdi-clipboard-text text-muted"></i>
                                        <h5>Nenhum movimento encontrado</h5>
                                        <p class="mb-4">Não há movimentos que correspondam aos filtros aplicados.</p>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('stock-movements.create') }}" class="btn btn-primary">
                                                <i class="mdi mdi-plus me-2"></i> Registrar Primeiro Movimento
                                            </a>
                                            <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-primary">
                                                <i class="mdi mdi-restore me-2"></i> Limpar Filtros
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($movements->hasPages())
                <div class="card-footer bg-light border-top-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted small">
                            Mostrando {{ $movements->firstItem() }} a {{ $movements->lastItem() }} de {{ $movements->total() }} movimentos
                        </div>
                        <div>
                            {{ $movements->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-submit form on input change
    const inputs = document.querySelectorAll('#date_from, #date_to, #movement_type');
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
    });

    // Search input debounce
    let searchTimeout;
    const searchInput = document.getElementById('product');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length === 0 || this.value.length >= 3) {
                    document.getElementById('filter-form').submit();
                }
            }, 500);
        });
    }

    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('.movement-row');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

function confirmDelete(movementId, productName) {
    Swal.fire({
        title: 'Tem certeza?',
        html: `Deseja realmente excluir o movimento do produto "<strong>${productName}</strong>"?<br><br>Esta ação não poderá ser desfeita.`,
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
            form.action = `/stock-movements/${movementId}`;
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