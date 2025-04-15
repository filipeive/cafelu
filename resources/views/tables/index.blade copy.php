@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Gerenciamento de Mesas</h2>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mergeTables">
                Unir Mesas
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-center">
                        @foreach ($tables as $table)
                            @php
                                $statusClass = $table->status === 'free' ? 'bg-success' : 'bg-danger';
                                $statusText = $table->status === 'free' ? 'Livre' : 'Ocupada';
                                $isGrouped = $table->group_id !== null;
                                $isMain = $table->is_main === 1;
                                
                                if ($isGrouped) {
                                    $statusClass .= ' border border-primary';
                                    if ($isMain) {
                                        $statusClass .= ' font-weight-bold';
                                    }
                                }
                            @endphp
                            
                            <div class="table-item m-2 {{ $statusClass }}" style="width: 120px; height: 120px; border-radius: 5px;">
                                <div class="d-flex flex-column justify-content-center align-items-center h-100">
                                    <h3 class="mb-0">{{ $table->number }}</h3>
                                    <small>{{ $statusText }}</small>
                                    <small>Lugares: {{ $table->merged_capacity ?? $table->capacity }}</small>
                                    
                                    @if ($isGrouped && $isMain)
                                        <small class="badge badge-info">Grupo Principal</small>
                                    @elseif ($isGrouped)
                                        <small class="badge badge-info">Agrupada</small>
                                    @endif
                                    
                                    <div class="btn-group btn-group-sm mt-2">
                                        @if ($table->status === 'free' && !$isGrouped)
                                            <form action="{{ route('tables.create-order', $table) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-plus"></i> Pedido
                                                </button>
                                            </form>
                                        @elseif ($table->status === 'occupied' && (!$isGrouped || $isMain))
                                            <a href="{{ route('orders.edit', $table->activeOrder->id ?? 0) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Editar Pedido
                                            </a>
                                        @endif
                                    </div>
                                    
                                    @if ($isGrouped && $isMain)
                                        <form action="{{ route('tables.split') }}" method="POST" class="mt-1">
                                            @csrf
                                            <input type="hidden" name="group_id" value="{{ $table->group_id }}">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-unlink"></i> Separar
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
    </div>
</div>

<!-- Modal para unir mesas -->
<div class="modal fade" id="mergeTables" tabindex="-1" role="dialog" aria-labelledby="mergeTablesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('tables.merge') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="mergeTablesLabel">Unir Mesas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Selecione as mesas para unir</label>
                        <div class="row">
                            @foreach ($tables as $table)
                                @if ($table->status === 'free' && !$table->group_id)
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input table-checkbox" 
                                                id="table-{{ $table->id }}" name="table_ids[]" value="{{ $table->id }}">
                                            <label class="custom-control-label" for="table-{{ $table->id }}">
                                                Mesa {{ $table->number }} ({{ $table->capacity }} lugares)
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="main_table_id">Mesa Principal</label>
                        <select class="form-control" id="main_table_id" name="main_table_id" required>
                            <option value="">Selecione uma mesa</option>
                        </select>
                        <small class="form-text text-muted">
                            Selecione a mesa principal onde os pedidos serão registrados.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="merge-btn" disabled>Unir Mesas</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Quando uma mesa for selecionada/deselecionada
        $('.table-checkbox').change(function() {
            const checkedTables = $('.table-checkbox:checked');
            const mainTableSelect = $('#main_table_id');
            
            // Limpar opções atuais
            mainTableSelect.empty();
            mainTableSelect.append('<option value="">Selecione uma mesa</option>');
            
            // Adicionar novas opções baseadas nos checkboxes selecionados
            checkedTables.each(function() {
                const tableId = $(this).val();
                const tableLabel = $(this).siblings('label').text();
                mainTableSelect.append(`<option value="${tableId}">${tableLabel}</option>`);
            });
            
            // Habilitar/desabilitar botão de união
            $('#merge-btn').prop('disabled', checkedTables.length < 2);
        });
        
        // Verificar se o número mínimo de mesas está selecionado
        $('#main_table_id').change(function() {
            const checkedTables = $('.table-checkbox:checked');
            const mainTableSelected = $(this).val() !== '';
            $('#merge-btn').prop('disabled', checkedTables.length < 2 || !mainTableSelected);
        });
    });
</script>
@endpush
@endsection