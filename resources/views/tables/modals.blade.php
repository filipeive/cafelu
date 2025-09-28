<!-- Modal para Ocupar Mesa -->
<div class="modal fade" id="occupyTableModal" tabindex="-1" role="dialog" aria-labelledby="occupyTableModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="occupyTableModalLabel">Ocupar Mesa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="occupyTableForm">
                <div class="modal-body">
                    <p>Confirma a ocupação desta mesa?</p>
                    <input type="hidden" id="occupyTableId" name="table_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Liberar Mesa -->
<div class="modal fade" id="freeTableModal" tabindex="-1" role="dialog" aria-labelledby="freeTableModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="freeTableModalLabel">Liberar Mesa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="freeTableForm">
                <div class="modal-body">
                    <p>Confirma a liberação desta mesa?</p>
                    <input type="hidden" id="freeTableId" name="table_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Unir Mesas -->
<div class="modal fade" id="mergeTablesModal" tabindex="-1" role="dialog" aria-labelledby="mergeTablesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mergeTablesModalLabel">Unir Mesas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="mergeTablesForm" action="{{ route('tables.merge') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Selecione as mesas para unir</label>
                        <div class="table-checkboxes">
                            @foreach ($tables as $table)
                                @if ($table->status === 'free' && !$table->group_id)
                                    <div class="form-check mb-2">
                                        <input type="checkbox" 
                                               class="form-check-input table-checkbox" 
                                               id="table_{{ $table->id }}" 
                                               name="selected_tables[]" 
                                               value="{{ $table->id }}">
                                        <label class="form-check-label" for="table_{{ $table->id }}">
                                            Mesa {{ $table->number }} (Capacidade: {{ $table->capacity }})
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="main_table_id">Mesa Principal</label>
                        <select class="form-control" id="main_table_id" name="main_table_id" required>
                            <option value="">Selecione uma mesa principal</option>
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

<!-- Modal para Separar Mesas -->
<div class="modal fade" id="splitTablesModal" tabindex="-1" role="dialog" aria-labelledby="splitTablesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="splitTablesModalLabel">Separar Mesas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="splitTableForm">
                <div class="modal-body">
                    <p>Confirma a separação desta mesa do grupo?</p>
                    <input type="hidden" id="splitTableId" name="table_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Criar Mesa -->
<div class="modal fade" id="createTableModal" tabindex="-1" role="dialog" aria-labelledby="createTableModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTableModalLabel">Adicionar Nova Mesa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createTableForm" action="{{ route('tables.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tableNumber">Número da Mesa</label>
                        <input type="number" class="form-control" id="tableNumber" name="number" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="tableCapacity">Capacidade</label>
                        <input type="number" class="form-control" id="tableCapacity" name="capacity" required min="1">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="isMain" name="is_main" value="1">
                        <label class="form-check-label" for="isMain">Mesa Principal</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableCheckboxes = document.querySelectorAll('.table-checkbox');
        const mainTableSelect = document.getElementById('main_table_id');
        const mergeBtn = document.getElementById('merge-btn');
        // Função para atualizar o select de mesa principal
        function updateMainTableSelect() {
            console.log('Atualizando select de mesa principal');
            
            // Limpa o select
            mainTableSelect.innerHTML = '<option value="">Selecione uma mesa principal</option>';
            
            // Obtém todas as mesas selecionadas
            const selectedTables = Array.from(tableCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => {
                    const label = document.querySelector(`label[for="${cb.id}"]`).textContent.trim();
                    console.log('Mesa selecionada:', { id: cb.value, label: label });
                    return {
                        id: cb.value,
                        label: label
                    };
                });
    
            console.log('Total de mesas selecionadas:', selectedTables.length);
    
            // Adiciona as mesas selecionadas ao select
            selectedTables.forEach(table => {
                const option = document.createElement('option');
                option.value = table.id;
                option.textContent = table.label;
                mainTableSelect.appendChild(option);
            });
    
            // Habilita/desabilita o botão de unir baseado na quantidade de mesas selecionadas
            mergeBtn.disabled = selectedTables.length < 2 || !mainTableSelect.value;
        }
    
        // Event listeners
        tableCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateMainTableSelect);
        });
    
        // Listener para o select de mesa principal
        mainTableSelect.addEventListener('change', function() {
            console.log('Mesa principal selecionada:', this.value);
            mergeBtn.disabled = tableCheckboxes.filter(cb => cb.checked).length < 2 || !this.value;
        });
    
        // Listener para o formulário de unir mesas
        document.getElementById('mergeTablesForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const selectedTables = Array.from(tableCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
                
            const mainTable = mainTableSelect.value;
            
            if (selectedTables.length < 2) {
                Swal.fire('Erro', 'Selecione pelo menos duas mesas para unir', 'error');
                return;
            }
            
            if (!mainTable) {
                Swal.fire('Erro', 'Selecione uma mesa principal', 'error');
                return;
            }
    
            // Enviar requisição para unir mesas
            fetch('{{ route('tables.merge') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    selected_tables: selectedTables,
                    main_table_id: mainTable
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Sucesso', 'Mesas unidas com sucesso', 'success')
                        .then(() => window.location.reload());
                } else {
                    Swal.fire('Erro', data.message || 'Erro ao unir mesas', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                Swal.fire('Erro', 'Ocorreu um erro ao processar a requisição', 'error');
            });
        });
    });
    </script>