<!-- resources/views/tables/floor-plan.blade.php -->
@extends('layouts.app')

@section('styles')
<style>
    .floor-plan {
        position: relative;
        width: 100%;
        height: 600px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        overflow: hidden;
    }
    
    .table-item {
        position: absolute;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        cursor: pointer;
        user-select: none;
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .table-free {
        background-color: #28a745;
        color: white;
    }
    
    .table-occupied {
        background-color: #dc3545;
        color: white;
    }
    
    .table-group {
        border: 3px dashed #ffc107;
    }
    
    .table-main {
        border: 3px solid #007bff;
    }
    
    .table-actions {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        display: none;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
        z-index: 20;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        white-space: nowrap;
    }
    
    .table-item:hover .table-actions {
        display: block;
    }
    
    .table-number {
        font-weight: bold;
        font-size: 18px;
    }
    
    .table-capacity {
        font-size: 14px;
    }
    
    .group-line {
        position: absolute;
        border-top: 2px dashed #ffc107;
        z-index: 5;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Plano de Mesas</h4>
                    <div>
                        <a href="{{ route('tables.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                
                @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif
                
                <div class="floor-plan" id="floorPlan">
                    @foreach($tables as $table)
                    <div 
                        class="table-item table-{{ $table->status }} {{ $table->isInGroup() ? 'table-group' : '' }} {{ $table->is_main ? 'table-main' : '' }}"
                        id="table-{{ $table->id }}"
                        style="left: {{ $table->position_x ?? rand(50, 800) }}px; top: {{ $table->position_y ?? rand(50, 500) }}px;"
                        data-id="{{ $table->id }}"
                        data-status="{{ $table->status }}"
                        data-group="{{ $table->group_id }}"
                    >
                        <div class="table-number">{{ $table->number }}</div>
                        <div class="table-capacity">{{ $table->isInGroup() && $table->is_main ? $table->merged_capacity : $table->capacity }}</div>
                        
                        <div class="table-actions">
                            @if($table->status == 'free')
                            <button class="btn btn-sm btn-success occupy-table" data-table-id="{{ $table->id }}">Ocupar</button>
                            @else
                            <a href="{{ route('tables.orders', $table) }}" class="btn btn-sm btn-info">Pedidos</a>
                            <button class="btn btn-sm btn-danger free-table" data-table-id="{{ $table->id }}">Liberar</button>
                            @endif
                            
                            @if($table->isInGroup() && $table->is_main)
                            <button class="btn btn-sm btn-warning split-table" data-table-id="{{ $table->id }}">Separar</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    <h5>Legenda:</h5>
                    <div class="d-flex gap-4">
                        <div><span class="badge bg-success p-2">Verde</span> - Mesa Livre</div>
                        <div><span class="badge bg-danger p-2">Vermelho</span> - Mesa Ocupada</div>
                        <div><span class="badge p-2" style="border: 3px dashed #ffc107;">Tracejado</span> - Mesa Agrupada</div>
                        <div><span class="badge p-2" style="border: 3px solid #007bff;">Borda Azul</span> - Mesa Principal</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/table-actions.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const floorPlan = document.getElementById('floorPlan');
        const tables = document.querySelectorAll('.table-item');
        let isDragging = false;
        let currentTable = null;
        let offsetX = 0;
        let offsetY = 0;
        
        // Desenhar linhas entre mesas agrupadas
        drawGroupLines();
        
        // Função para desenhar linhas entre mesas agrupadas
        function drawGroupLines() {
            // Remover linhas existentes
            document.querySelectorAll('.group-line').forEach(line => line.remove());
            
            // Agrupar mesas por grupo
            const groups = {};
            tables.forEach(table => {
                const groupId = table.dataset.group;
                if (groupId && groupId !== 'null') {
                    if (!groups[groupId]) {
                        groups[groupId] = [];
                    }
                    groups[groupId].push(table);
                }
            });
            
            // Desenhar linhas para cada grupo
            for (const groupId in groups) {
                const mainTable = groups[groupId].find(table => table.classList.contains('table-main'));
                if (mainTable) {
                    const mainRect = mainTable.getBoundingClientRect();
                    const mainCenterX = mainTable.offsetLeft + mainTable.offsetWidth / 2;
                    const mainCenterY = mainTable.offsetTop + mainTable.offsetHeight / 2;
                    
                    groups[groupId].forEach(table => {
                        if (table !== mainTable) {
                            const tableCenterX = table.offsetLeft + table.offsetWidth / 2;
                            const tableCenterY = table.offsetTop + table.offsetHeight / 2;
                            
                            // Criar linha
                            const line = document.createElement('div');
                            line.className = 'group-line';
                            
                            // Calcular posição e comprimento da linha
                            const length = Math.sqrt(Math.pow(tableCenterX - mainCenterX, 2) + Math.pow(tableCenterY - mainCenterY, 2));
                            const angle = Math.atan2(tableCenterY - mainCenterY, tableCenterX - mainCenterX) * 180 / Math.PI;
                            
                            line.style.width = `${length}px`;
                            line.style.left = `${mainCenterX}px`;
                            line.style.top = `${mainCenterY}px`;
                            line.style.transform = `rotate(${angle}deg)`;
                            line.style.transformOrigin = '0 0';
                            
                            floorPlan.appendChild(line);
                        }
                    });
                }
            }
        }
        
        // Tornar as mesas arrastáveis
        tables.forEach(table => {
            table.addEventListener('mousedown', function(e) {
                // Não iniciar arrasto se clicou em um botão
                if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A') {
                    return;
                }
                
                isDragging = true;
                currentTable = table;
                const rect = table.getBoundingClientRect();
                offsetX = e.clientX - rect.left;
                offsetY = e.clientY - rect.top;
                
                // Adicionar classe para indicar arrasto
                table.classList.add('dragging');
            });
        });
        
        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            // Calcular nova posição
            const floorPlanRect = floorPlan.getBoundingClientRect();
            let newLeft = e.clientX - floorPlanRect.left - offsetX;
            let newTop = e.clientY - floorPlanRect.top - offsetY;
            
            // Limitar ao tamanho do plano
            newLeft = Math.max(0, Math.min(newLeft, floorPlanRect.width - currentTable.offsetWidth));
            newTop = Math.max(0, Math.min(newTop, floorPlanRect.height - currentTable.offsetHeight));
            
            // Aplicar nova posição
            currentTable.style.left = `${newLeft}px`;
            currentTable.style.top = `${newTop}px`;
            
            // Atualizar linhas de grupo
            drawGroupLines();
        });
        
        document.addEventListener('mouseup', function() {
            if (isDragging && currentTable) {
                // Remover classe de arrasto
                currentTable.classList.remove('dragging');
                
                // Salvar nova posição no servidor
                const tableId = currentTable.dataset.id;
                const positionX = parseInt(currentTable.style.left);
                const positionY = parseInt(currentTable.style.top);
                
                saveTablePosition(tableId, positionX, positionY);
                
                isDragging = false;
                currentTable = null;
            }
        });
        
        // Função para salvar posição da mesa
        function saveTablePosition(tableId, x, y) {
            const data = new FormData();
            data.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            data.append('_method', 'PUT');
            data.append('position_x', x);
            data.append('position_y', y);
            
            fetch(`/tables/${tableId}`, {
                method: 'POST',
                body: data,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .catch(error => {
                console.error('Erro ao salvar posição:', error);
            });
        }
    });
</script>
@endpush