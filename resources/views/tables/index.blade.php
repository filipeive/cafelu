@extends('layouts.app')

@section('title', 'Gestão de Mesas')
@section('title-icon', 'mdi-table-furniture')
@section('page-title', 'Gestão de Mesas')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}" class="text-decoration-none">
            <i class="mdi mdi-home"></i> Dashboard
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        <i class="mdi mdi-table-furniture"></i> Mesas
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="mdi mdi-table-furniture text-primary fs-2"></i>
                                    </div>
                                    <div>
                                        <h2 class="mb-1">Gestão de Mesas</h2>
                                        <p class="text-muted mb-0">
                                            Visualize, organize e gerencie as mesas do ZALALA BEACH BAR
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mergeTables">
                                    <i class="mdi mdi-link me-1"></i> Unir Mesas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Legend -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body py-3">
                        <div class="d-flex flex-wrap gap-4 justify-content-center">
                            <div class="d-flex align-items-center">
                                <div class="legend-color bg-success me-2"></div>
                                <span class="small">Mesa Livre</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="legend-color bg-danger me-2"></div>
                                <span class="small">Mesa Ocupada</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="legend-color bg-info me-2"></div>
                                <span class="small">Mesa Agrupada</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="mdi mdi-view-grid me-2"></i>
                                Mesas Disponíveis
                            </h5>
                            <span class="badge bg-primary">{{ count($tables) }} mesas</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($tables as $table)
                                @php
                                    $hasActiveOrder = $table->hasActiveOrder();
                                    $statusClass = $hasActiveOrder ? 'border-danger' : 'border-success';
                                    $statusIcon = $hasActiveOrder ? 'mdi-lock' : 'mdi-lock-open';
                                    $statusText = $hasActiveOrder ? 'Ocupada' : 'Livre';
                                    $isGrouped = $table->group_id !== null;
                                    $isMain = $table->is_main;
                                    $activeOrder = $table->activeOrder();
                                @endphp

                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <div class="card table-card h-100 {{ $statusClass }} {{ $isGrouped ? 'border-info' : '' }}">
                                        <div class="card-body">
                                            @if ($isGrouped)
                                                <div class="position-absolute top-0 end-0 m-3">
                                                    <span class="badge bg-info">
                                                        <i class="mdi mdi-link-variant me-1"></i> Agrupada
                                                    </span>
                                                </div>
                                            @endif

                                            <div class="text-center mb-3">
                                                <div class="table-number bg-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2" 
                                                     style="width: 60px; height: 60px; font-size: 1.5rem;">
                                                    {{ $table->number }}
                                                </div>
                                                <h5 class="mb-1">Mesa {{ $table->number }}</h5>
                                                <span class="badge {{ $hasActiveOrder ? 'bg-danger' : 'bg-success' }}">
                                                    <i class="mdi {{ $statusIcon }} me-1"></i>{{ $statusText }}
                                                </span>
                                            </div>

                                            <div class="table-info text-center mb-3">
                                                <div class="d-flex justify-content-around text-muted small">
                                                    <div>
                                                        <i class="mdi mdi-account-multiple"></i>
                                                        {{ $table->merged_capacity ?? $table->capacity }} lugares
                                                    </div>
                                                    @if ($isGrouped && $isMain)
                                                        <div>
                                                            <i class="mdi mdi-crown"></i> Principal
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            @if ($hasActiveOrder)
                                                <div class="alert alert-warning py-2 mb-3 text-center">
                                                    <small>
                                                        <i class="mdi mdi-receipt me-1"></i>
                                                        Pedido #{{ $activeOrder->id }}
                                                    </small>
                                                </div>
                                            @endif

                                            <div class="table-actions d-grid gap-2">
                                                @if (!$hasActiveOrder && (!$isGrouped || $isMain))
                                                    <form action="{{ route('tables.create-order', $table) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                                            <i class="mdi mdi-plus-circle me-1"></i> Novo Pedido
                                                        </button>
                                                    </form>
                                                @elseif($hasActiveOrder && (!$isGrouped || $isMain))
                                                    <a href="{{ route('orders.edit', $activeOrder->id) }}" class="btn btn-primary btn-sm w-100">
                                                        <i class="mdi mdi-pencil me-1"></i> Ver Pedido
                                                    </a>
                                                @endif

                                                @if ($isGrouped && $isMain)
                                                    <form action="{{ route('tables.split') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="group_id" value="{{ $table->group_id }}">
                                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                            <i class="mdi mdi-link-off me-1"></i> Separar Mesas
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Merge Tables Modal -->
    <div class="modal fade" id="mergeTables" tabindex="-1" aria-labelledby="mergeTablesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('tables.merge') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="mergeTablesLabel">
                            <i class="mdi mdi-link me-2"></i> Unir Mesas
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="mdi mdi-information-outline me-2"></i>
                            Selecione pelo menos duas mesas livres para uni-las. Escolha qual será a mesa principal onde os pedidos serão registrados.
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Mesas Disponíveis para União</label>
                            <div class="row g-3">
                                @foreach ($tables as $table)
                                    @if ($table->status === 'free' && !$table->group_id)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input table-checkbox" 
                                                       id="table-{{ $table->id }}" name="table_ids[]" 
                                                       value="{{ $table->id }}">
                                                <label class="form-check-label d-flex align-items-center p-2 border rounded" 
                                                       for="table-{{ $table->id }}">
                                                    <i class="mdi mdi-table-furniture text-primary me-2"></i>
                                                    <div>
                                                        <div class="fw-semibold">Mesa {{ $table->number }}</div>
                                                        <small class="text-muted">{{ $table->capacity }} lugares</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="main_table_id" class="form-label fw-bold">Mesa Principal</label>
                            <select class="form-select" id="main_table_id" name="main_table_id" required>
                                <option value="">Selecione a mesa principal</option>
                            </select>
                            <div class="form-text">
                                <i class="mdi mdi-information-outline me-1"></i>
                                A mesa principal será onde os pedidos serão registrados
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="mdi mdi-close me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" id="merge-btn" disabled>
                            <i class="mdi mdi-link me-1"></i> Unir Mesas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .table-card {
        border: 2px solid;
        border-radius: 12px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .table-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .table-number {
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .table-card:hover .table-number {
        transform: scale(1.1);
    }

    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

    .table-actions .btn {
        border-radius: 8px;
        font-weight: 500;
    }

    .form-check-label {
        cursor: pointer;
        transition: all 0.2s ease;
        border-radius: 8px;
    }

    .form-check-label:hover {
        background-color: #f8f9fa;
    }

    .form-check-input:checked + .form-check-label {
        background-color: #e3f2fd;
        border-color: #2196f3;
    }

    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        border-bottom: 1px solid #e9ecef;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    @media (max-width: 768px) {
        .table-card {
            margin-bottom: 1rem;
        }
        
        .modal-dialog {
            margin: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableCheckboxes = document.querySelectorAll('.table-checkbox');
        const mainTableSelect = document.getElementById('main_table_id');
        const mergeBtn = document.getElementById('merge-btn');

        function updateMainTableSelect() {
            const selectedTables = Array.from(tableCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => ({
                    id: cb.value,
                    number: cb.closest('.form-check').querySelector('.fw-semibold').textContent.replace('Mesa ', '')
                }));

            mainTableSelect.innerHTML = '<option value="">Selecione a mesa principal</option>';
            
            selectedTables.forEach(table => {
                const option = document.createElement('option');
                option.value = table.id;
                option.textContent = `Mesa ${table.number}`;
                mainTableSelect.appendChild(option);
            });

            updateMergeButtonState(selectedTables.length);
        }

        function updateMergeButtonState(selectedCount) {
            const hasMainTable = mainTableSelect.value !== '';
            mergeBtn.disabled = selectedCount < 2 || !hasMainTable;
            
            if (mergeBtn.disabled) {
                mergeBtn.title = selectedCount < 2 ? 'Selecione pelo menos 2 mesas' : 'Selecione a mesa principal';
            } else {
                mergeBtn.title = '';
            }
        }

        tableCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateMainTableSelect);
        });

        mainTableSelect.addEventListener('change', function() {
            const selectedCount = Array.from(tableCheckboxes).filter(cb => cb.checked).length;
            updateMergeButtonState(selectedCount);
        });

        // Auto-hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Add animation to table cards
        const tableCards = document.querySelectorAll('.table-card');
        tableCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('fade-in');
        });
    });

    // CSS Animation
    const style = document.createElement('style');
    style.textContent = `
        .fade-in {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush