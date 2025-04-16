@extends('layouts.app')

@section('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .tables-wrapper {
            padding: 1.5rem;
        }
        .page-header {
            background: #fff;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            margin-bottom: 2rem;
        }
        .table-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
        }
        .table-card {
            position: relative;
            height: 180px;
            border-radius: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        .table-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .table-free {
            background: linear-gradient(135deg, #4CAF50, #81C784);
        }
        .table-occupied {
            background: linear-gradient(135deg, #f44336, #ef5350);
        }
        .table-grouped {
            background: linear-gradient(135deg, #2196F3, #64B5F6);
        }
        .table-content {
            height: 100%;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
        }
        .table-number {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .table-status {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        .badge-capacity {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            backdrop-filter: blur(4px);
        }
        .order-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #FFC107;
            color: #000;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .grouped-icon {
            position: absolute;
            top: 10px;
            left: 10px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.5rem;
        }
        .table-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.4), transparent);
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }
        .table-card:hover .table-actions {
            opacity: 1;
            transform: translateY(0);
        }
        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            transform: translateY(-2px);
        }
        .modal-content {
            border-radius: 15px;
        }
        .custom-checkbox .custom-control-label::before {
            border-radius: 6px;
        }
        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
        }
        .alert-success {
            background: #E8F5E9;
            color: #2E7D32;
        }
        .alert-danger {
            background: #FFEBEE;
            color: #C62828;
        }
    </style>
@endsection

@section('content')
    <div class="container-wrapper">
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <h2 class="text-primary">Gerenciamento de Mesas</h2>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mergeTables">
                    <i class="mdi mdi-link align-middle"></i> Unir Mesas
                </button>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            @foreach ($tables as $table)
                                @php
                                    $hasActiveOrder = $table->hasActiveOrder();
                                    $statusClass = $hasActiveOrder ? 'table-occupied' : 'table-free';
                                    $statusText = $hasActiveOrder ? 'Ocupada' : 'Livre';
                                    $isGrouped = $table->group_id !== null;
                                    $isMain = $table->is_main;
                                    $activeOrder = $table->activeOrder();
                                @endphp
                                <div
                                    class="table-item {{ $statusClass }} {{ $isGrouped ? 'table-grouped' : '' }} shadow-sm p-3 rounded">
                                    @if ($isGrouped)
                                        <i class="mdi mdi-link grouped-icon"></i>
                                    @endif
                                    <div class="table-content text-center">
                                        <h3 class="table-number text-primary">Mesa {{ $table->number }}</h3>
                                        <span class="table-status badge {{ $hasActiveOrder ? 'bg-danger' : 'bg-success' }}">
                                            <i class="mdi {{ $hasActiveOrder ? 'mdi-lock' : 'mdi-lock-open' }} align-middle"></i> {{ $statusText }}
                                        </span>
                                        <span
                                            class="badge bg-secondary mt-2">{{ $table->merged_capacity ?? $table->capacity }} lugares</span>
                                        @if ($hasActiveOrder)
                                            <div class="order-info mt-2">
                                                <span class="badge bg-warning">
                                                    <i class="mdi mdi-cart align-middle"></i> Pedido #{{ $activeOrder->id }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="table-actions mt-3">
                                            @if (!$hasActiveOrder && (!$isGrouped || $isMain))
                                                <form action="{{ route('tables.create-order', $table) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary btn-action">
                                                        <i class="mdi mdi-plus-circle align-middle"></i> Novo Pedido
                                                    </button>
                                                </form>
                                            @elseif($hasActiveOrder && (!$isGrouped || $isMain))
                                                <a href="{{ route('orders.edit', $activeOrder->id) }}"
                                                    class="btn btn-sm btn-warning btn-action">
                                                    <i class="mdi mdi-pencil align-middle"></i> Editar Pedido
                                                </a>
                                            @endif
                                            @if ($isGrouped && $isMain)
                                                <form action="{{ route('tables.split') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="group_id" value="{{ $table->group_id }}">
                                                    <button type="submit" class="btn btn-sm btn-danger btn-action">
                                                        <i class="mdi mdi-content-cut align-middle"></i> Separar
                                                    </button>
                                                </form>
                                            @endif
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

    <!-- Modal para unir mesas -->
    <div class="modal fade" id="mergeTables" tabindex="-1" aria-labelledby="mergeTablesLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('tables.merge') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="mergeTablesLabel"><i class="mdi mdi-link align-middle"></i> Unir Mesas</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Selecione as mesas para unir</label>
                            <div class="row">
                                @foreach ($tables as $table)
                                    @if ($table->status === 'free' && !$table->group_id)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input table-checkbox"
                                                    id="table-{{ $table->id }}" name="table_ids[]"
                                                    value="{{ $table->id }}">
                                                <label class="form-check-label" for="table-{{ $table->id }}">
                                                    <i class="mdi mdi-table align-middle"></i> Mesa {{ $table->number }} ({{ $table->capacity }} lugares)
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="main_table_id" class="form-label">Mesa Principal</label>
                            <select class="form-select" id="main_table_id" name="main_table_id" required>
                                <option value="">Selecione uma mesa principal</option>
                            </select>
                            <small class="form-text text-muted">
                                Selecione a mesa principal onde os pedidos serão registrados.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="mdi mdi-cancel align-middle"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" id="merge-btn" disabled>
                            <i class="mdi mdi-link align-middle"></i> Unir Mesas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                            label: cb.nextElementSibling.textContent.trim()
                        }));
                    mainTableSelect.innerHTML = '<option value="">Selecione uma mesa principal</option>';
                    selectedTables.forEach(table => {
                        const option = document.createElement('option');
                        option.value = table.id;
                        option.textContent = table.label;
                        mainTableSelect.appendChild(option);
                    });
                    updateMergeButtonState(selectedTables.length);
                }

                function updateMergeButtonState(selectedCount) {
                    const hasMainTable = mainTableSelect.value !== '';
                    mergeBtn.disabled = selectedCount < 2 || !hasMainTable;
                }

                tableCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateMainTableSelect);
                });

                mainTableSelect.addEventListener('change', function() {
                    const selectedCount = Array.from(tableCheckboxes).filter(cb => cb.checked).length;
                    updateMergeButtonState(selectedCount);
                });
            });
        </script>
    @endpush
@endsection