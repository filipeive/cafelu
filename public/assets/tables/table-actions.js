// resources/js/table-actions.js

document.addEventListener('DOMContentLoaded', function() {
    // Função para ocupar mesa
    document.querySelectorAll('.occupy-table').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const tableId = this.dataset.tableId;
            
            // Confirmação antes de ocupar a mesa
            if (confirm('Deseja realmente ocupar esta mesa?')) {
                handleTableAction('occupy', tableId);
            }
        });
    });
    
    // Função para liberar mesa
    document.querySelectorAll('.free-table').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const tableId = this.dataset.tableId;
            
            // Confirmação antes de liberar a mesa
            if (confirm('Deseja realmente liberar esta mesa?')) {
                handleTableAction('free', tableId);
            }
        });
    });
    
    // Função para unir mesas
    const mergeForm = document.getElementById('merge-tables-form');
    if (mergeForm) {
        mergeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const selectedTables = [];
            document.querySelectorAll('input[name="table_ids[]"]:checked').forEach(checkbox => {
                selectedTables.push(checkbox.value);
            });
            
            if (selectedTables.length < 2) {
                alert('Selecione pelo menos duas mesas para unir.');
                return;
            }
            
            handleTableAction('merge', null, { table_ids: selectedTables });
        });
    }
    
    // Função para separar mesas
    document.querySelectorAll('.split-table').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const tableId = this.dataset.tableId;
            
            // Confirmação antes de separar a mesa
            if (confirm('Deseja realmente separar esta mesa?')) {
                handleTableAction('split', tableId);
            }
        });
    });
    
    // Função genérica para manipular ações de mesa
    function handleTableAction(action, tableId, additionalData = {}) {
        // Criar o objeto de dados para a requisição
        const data = new FormData();
        data.append('action', action);
        
        if (tableId) {
            data.append('table_id', tableId);
        }
        
        // Adicionar dados adicionais, se houver
        for (const key in additionalData) {
            if (Array.isArray(additionalData[key])) {
                additionalData[key].forEach(value => {
                    data.append(`${key}[]`, value);
                });
            } else {
                data.append(key, additionalData[key]);
            }
        }
        
        // Token CSRF
        data.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Enviar a requisição
        fetch('/api/tables/action', {
            method: 'POST',
            body: data,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Recarregar a página para refletir as mudanças
                window.location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Ocorreu um erro ao processar a solicitação.');
        });
    }
});