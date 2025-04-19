@extends('layouts.app')
@section('title', 'Mesas - Restaurante Pro')

@section('styles')
    <style>
        /* Estilos Gerais */
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
        }

        /* Cards de Mesa - Design Moderno */
        .table-card {
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            border: none;
            color: white;
            min-height: 220px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .table-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transition: all 0.5s ease;
            opacity: 0;
        }

        .table-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .table-card:hover::before {
            opacity: 1;
            transform: scale(0.8);
        }

        /* Cores dos Estados */
        .table-free {
            --primary-color: #4CAF50;
            --secondary-color: #2E7D32;
            --highlight-color: #81C784;
        }

        .table-occupied {
            --primary-color: #F44336;
            --secondary-color: #C62828;
            --highlight-color: #EF5350;
        }

        .table-grouped {
            --primary-color: #2196F3;
            --secondary-color: #1565C0;
            --highlight-color: #64B5F6;
        }

        /* Conteúdo do Card */
        .table-content {
            position: relative;
            z-index: 2;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .table-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            color: white;
        }

        .table-status {
            margin-bottom: 1rem;
        }

        .table-status .badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-color: rgba(255,255,255,0.2);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.15);
        }

        .table-capacity {
            font-size: 0.9rem;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Ícone de Mesa Agrupada */
        .grouped-icon {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255,255,255,0.2);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.15);
        }

        .grouped-icon i {
            font-size: 1rem;
            color: white;
        }

        /* Ações do Card */
        .table-actions {
            margin-top: auto;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: center;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
            position: relative;
            z-index: 3;
        }

        .table-card:hover .table-actions {
            opacity: 1;
            transform: translateY(0);
        }

        .btn-table-action {
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.9);
            color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-table-action:hover {
            background: white;
            color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-table-action i {
            font-size: 1rem;
        }

        /* Modal de Unir Mesas */
        .modal-content {
            border-radius: 12px;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .modal-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #2196F3, #1976D2);
            border-bottom: none;
        }

        .modal-title {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: none;
            padding: 1rem 1.5rem;
            background: #f8f9fa;
        }

        /* Checkboxes Estilizados */
        .table-checkbox-label {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1px solid #e9ecef;
            margin-bottom: 0.5rem;
        }

        .table-checkbox-label:hover {
            background: #f8f9fa;
            border-color: #dee2e6;
            transform: translateY(-2px);
        }

        .table-checkbox-label i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
            color: #6c757d;
        }

        .form-check-input:checked ~ .table-checkbox-label {
            background-color: #e3f2fd;
            border-color: #bbdefb;
        }

        /* Legenda de Status */
        .status-legend {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 0;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }

        .color-free {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
        }

        .color-occupied {
            background: linear-gradient(135deg, #F44336, #C62828);
        }

        .color-grouped {
            background: linear-gradient(135deg, #2196F3, #1565C0);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .tables-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }
            
            .status-legend {
                flex-direction: column;
                gap: 0.75rem;
            }
        }

        /* Efeito de Destaque para Checkbox Selecionado */
        .form-check-input:checked ~ .table-checkbox-label {
            box-shadow: 0 0 0 2px var(--primary-color);
        }

        /* Botão de Merge */
        #merge-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Animação de Carregamento */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .table-card {
            animation: fadeIn 0.4s ease forwards;
            opacity: 0;
        }

        .table-card:nth-child(1) { animation-delay: 0.1s; }
        .table-card:nth-child(2) { animation-delay: 0.2s; }
        .table-card:nth-child(3) { animation-delay: 0.3s; }
        .table-card:nth-child(4) { animation-delay: 0.4s; }
        .btn-table-action i {
            margin-right: 0.35rem;
        }

        .table-checkbox-label {
            cursor: pointer;
        }
    </style>
@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <h2 class="mb-0">
                            <i class="mdi mdi-table-furniture text-primary me-2"></i>
                            Gerenciamento de Mesas
                        </h2>
                        <p class="text-muted mb-md-0">Visualize, organize e gerencie as mesas do restaurante</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mergeTables">
                            <i class="mdi mdi-link me-1"></i> Unir Mesas
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Notifications -->
        @if (session('success'))
            <div class="toast-notification toast-success">
                <div class="toast-icon"><i class="mdi mdi-check-circle"></i></div>
                <div class="toast-message">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="toast-notification toast-error">
                <div class="toast-icon"><i class="mdi mdi-alert-circle"></i></div>
                <div class="toast-message">{{ session('error') }}</div>
            </div>
        @endif       
        <!-- Status Legend -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="status-legend">
                    <div class="legend-item">
                        <div class="legend-color color-free"></div>
                        <span>Mesa Livre</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color color-occupied"></div>
                        <span>Mesa Ocupada</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color color-grouped"></div>
                        <span>Mesa Agrupada</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="mdi mdi-view-grid me-1"></i>
                        Mesas Disponíveis
                    </h5>
                    <span class="badge bg-primary">Total: {{ count($tables) }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="tables-grid">
                    @foreach ($tables as $table)
                        @php
                            $hasActiveOrder = $table->hasActiveOrder();
                            $statusClass = $hasActiveOrder ? 'table-occupied' : 'table-free';
                            $statusText = $hasActiveOrder ? 'Ocupada' : 'Livre';
                            $isGrouped = $table->group_id !== null;
                            $isMain = $table->is_main;
                            $activeOrder = $table->activeOrder();
                        @endphp

                        <div class="table-card {{ $statusClass }} {{ $isGrouped ? 'table-grouped' : '' }}">
                            @if ($isGrouped)
                                <div class="grouped-icon">
                                    <i class="mdi mdi-link-variant"></i>
                                </div>
                            @endif

                            <div class="table-content">
                                <h3 class="table-number">{{ $table->number }}</h3>

                                <div class="table-status">
                                    <span class="badge {{ $hasActiveOrder ? 'bg-danger' : 'bg-success' }} mb-2">
                                        <i class="mdi {{ $hasActiveOrder ? 'mdi-lock' : 'mdi-lock-open' }} me-1"></i>
                                        {{ $statusText }}
                                    </span>

                                    <div class="mt-1">
                                        <span class="table-capacity">
                                            <i class="mdi mdi-account-multiple me-1"></i>
                                            {{ $table->merged_capacity ?? $table->capacity }} lugares
                                        </span>
                                    </div>
                                </div>

                                @if ($hasActiveOrder)
                                    <div class="mt-2">
                                        <span class="badge bg-warning text-dark">
                                            <i class="mdi mdi-receipt me-1"></i>
                                            Pedido #{{ $activeOrder->id }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="table-actions">
                                @if (!$hasActiveOrder && (!$isGrouped || $isMain))
                                    <form action="{{ route('tables.create-order', $table) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-table-action">
                                            <i class="mdi mdi-plus-circle"></i>Novo Pedido
                                        </button>
                                    </form>
                                @elseif($hasActiveOrder && (!$isGrouped || $isMain))
                                    <a href="{{ route('orders.edit', $activeOrder->id) }}" class="btn btn-table-action">
                                        <i class="mdi mdi-pencil"></i>Editar Pedido
                                    </a>
                                @endif

                                @if ($isGrouped && $isMain)
                                    <form action="{{ route('tables.split') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="group_id" value="{{ $table->group_id }}">
                                        <button type="submit" class="btn btn-table-action">
                                            <i class="mdi mdi-link-off"></i>Separar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
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
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="mergeTablesLabel">
                            <i class="mdi mdi-link me-2"></i>Unir Mesas
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="mdi mdi-information-outline me-2"></i>
                            Selecione pelo menos duas mesas livres para uni-las. Depois escolha qual será a mesa principal
                            onde os pedidos serão registrados.
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Selecione as mesas para unir</label>
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                                @foreach ($tables as $table)
                                    @if ($table->status === 'free' && !$table->group_id)
                                        <div class="col">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input table-checkbox"
                                                    id="table-{{ $table->id }}" name="table_ids[]"
                                                    value="{{ $table->id }}">
                                                <label class="form-check-label table-checkbox-label"
                                                    for="table-{{ $table->id }}">
                                                    <i class="mdi mdi-table-furniture"></i>
                                                    Mesa {{ $table->number }} ({{ $table->capacity }} lugares)
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
                                <option value="">Selecione uma mesa principal</option>
                            </select>
                            <div class="form-text">
                                <i class="mdi mdi-information-outline me-1 text-primary"></i>
                                A mesa principal será onde os pedidos serão registrados
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="mdi mdi-close me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" id="merge-btn" disabled>
                            <i class="mdi mdi-link me-1"></i>Unir Mesas
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

                // Update main table select options based on checkbox selection
                function updateMainTableSelect() {
                    const selectedTables = Array.from(tableCheckboxes)
                        .filter(cb => cb.checked)
                        .map(cb => ({
                            id: cb.value,
                            label: cb.nextElementSibling.textContent.trim()
                        }));

                    // Reset and update options
                    mainTableSelect.innerHTML = '<option value="">Selecione uma mesa principal</option>';
                    selectedTables.forEach(table => {
                        const option = document.createElement('option');
                        option.value = table.id;
                        option.textContent = table.label;
                        mainTableSelect.appendChild(option);
                    });

                    // Update button state
                    updateMergeButtonState(selectedTables.length);
                }

                // Enable/disable merge button based on selections
                function updateMergeButtonState(selectedCount) {
                    const hasMainTable = mainTableSelect.value !== '';
                    mergeBtn.disabled = selectedCount < 2 || !hasMainTable;
                }

                // Event listeners
                tableCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateMainTableSelect);
                });

                mainTableSelect.addEventListener('change', function() {
                    const selectedCount = Array.from(tableCheckboxes).filter(cb => cb.checked).length;
                    updateMergeButtonState(selectedCount);
                });

                // Auto-hide alerts after 5 seconds
                setTimeout(function() {
                    const alerts = document.querySelectorAll('.alert-dismissible');
                    alerts.forEach(alert => {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    });
                }, 5000);
            });
        </script>
    @endpush
@endsection
