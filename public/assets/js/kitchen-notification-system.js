/**
 * Sistema de Notificações e Atualizações da Cozinha
 * Sistema robusto com feedback consistente e sons
 */

class KitchenNotificationSystem {
    constructor() {
        this.sounds = {
            success: '/assets/sounds/success.mp3',
            warning: '/assets/sounds/warning.mp3',
            error: '/assets/sounds/error.mp3',
            newOrder: '/assets/sounds/new-order.mp3',
            urgent: '/assets/sounds/urgent.mp3'
        };
        
        this.updateInProgress = false;
        this.retryAttempts = 0;
        this.maxRetries = 3;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.checkForSounds();
        this.startAutoRefresh();
    }

    /**
     * Configurar listeners de eventos
     */
    setupEventListeners() {
        // Atualizar status individual
        document.querySelectorAll('.update-status').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const itemId = button.dataset.itemId;
                const status = button.dataset.status;
                this.updateItemStatus(itemId, status, button);
            });
        });

        // Iniciar todos os itens de um pedido
        document.querySelectorAll('.start-all').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const orderId = button.dataset.orderId;
                this.startAllItems(orderId, button);
            });
        });

        // Finalizar todos os itens de um pedido
        document.querySelectorAll('.finish-all').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const orderId = button.dataset.orderId;
                this.finishAllItems(orderId, button);
            });
        });

        // Refresh manual
        document.getElementById('refreshOrders')?.addEventListener('click', (e) => {
            e.preventDefault();
            this.refreshOrders(true);
        });

        // Finalizar todos em preparo
        document.getElementById('markAllReady')?.addEventListener('click', (e) => {
            e.preventDefault();
            this.markAllReady();
        });

        // Formulários tradicionais (fallback)
        document.querySelectorAll('.update-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });
        });
    }

    /**
     * Atualizar status de um item individual
     */
    async updateItemStatus(itemId, status, button = null) {
        if (this.updateInProgress) {
            this.showToast('Aguarde a operação anterior concluir', 'warning');
            return;
        }

        // Desabilitar botão e mostrar loading
        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Processando...';
        }

        this.updateInProgress = true;

        try {
            const response = await fetch(`/kitchen/items/${itemId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({ status })
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message || 'Status atualizado com sucesso', 'success');
                this.playSound('success');
                await this.refreshOrders(false);
                this.retryAttempts = 0;
            } else {
                throw new Error(data.message || 'Erro ao atualizar status');
            }

        } catch (error) {
            console.error('Erro ao atualizar status:', error);
            this.handleError(error, () => this.updateItemStatus(itemId, status, button));
        } finally {
            this.updateInProgress = false;
            
            // Restaurar botão
            if (button) {
                button.disabled = false;
                button.innerHTML = button.dataset.originalText || 'Atualizar';
            }
        }
    }

    /**
     * Iniciar todos os itens de um pedido
     */
    async startAllItems(orderId, button = null) {
        if (this.updateInProgress) {
            this.showToast('Aguarde a operação anterior concluir', 'warning');
            return;
        }

        const confirmed = await this.confirmAction(
            'Iniciar preparo de todos os itens?',
            'Todos os itens pendentes serão marcados como "Em Preparo"'
        );

        if (!confirmed) return;

        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Iniciando...';
        }

        this.updateInProgress = true;

        try {
            const response = await fetch(`/kitchen/orders/${orderId}/start-all`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message || 'Itens iniciados com sucesso', 'success');
                this.playSound('success');
                await this.refreshOrders(false);
            } else {
                throw new Error(data.message || 'Erro ao iniciar itens');
            }

        } catch (error) {
            console.error('Erro ao iniciar itens:', error);
            this.handleError(error, () => this.startAllItems(orderId, button));
        } finally {
            this.updateInProgress = false;
            
            if (button) {
                button.disabled = false;
                button.innerHTML = '<i class="mdi mdi-play"></i> Iniciar Todos';
            }
        }
    }

    /**
     * Finalizar todos os itens de um pedido
     */
    async finishAllItems(orderId, button = null) {
        if (this.updateInProgress) {
            this.showToast('Aguarde a operação anterior concluir', 'warning');
            return;
        }

        const confirmed = await this.confirmAction(
            'Finalizar todos os itens?',
            'Todos os itens pendentes e em preparo serão marcados como "Prontos"'
        );

        if (!confirmed) return;

        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Finalizando...';
        }

        this.updateInProgress = true;

        try {
            const response = await fetch(`/kitchen/orders/${orderId}/finish-all`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message || 'Itens finalizados com sucesso', 'success');
                this.playSound('success');
                await this.refreshOrders(false);
            } else {
                throw new Error(data.message || 'Erro ao finalizar itens');
            }

        } catch (error) {
            console.error('Erro ao finalizar itens:', error);
            this.handleError(error, () => this.finishAllItems(orderId, button));
        } finally {
            this.updateInProgress = false;
            
            if (button) {
                button.disabled = false;
                button.innerHTML = '<i class="mdi mdi-check-all"></i> Finalizar Todos';
            }
        }
    }

    /**
     * Marcar todos os itens em preparo como prontos
     */
    async markAllReady() {
        const preparingCount = document.querySelectorAll('.item-card.preparing').length;
        
        if (preparingCount === 0) {
            this.showToast('Nenhum item em preparo para finalizar', 'info');
            return;
        }

        const confirmed = await this.confirmAction(
            `Finalizar ${preparingCount} itens em preparo?`,
            'Esta ação marcará todos os itens em preparo como prontos'
        );

        if (!confirmed) return;

        const finishButtons = document.querySelectorAll('.finish-all');
        let successCount = 0;
        let errorCount = 0;

        for (const button of finishButtons) {
            try {
                const orderId = button.dataset.orderId;
                const response = await fetch(`/kitchen/orders/${orderId}/finish-all`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.getCsrfToken()
                    }
                });

                if (response.ok) {
                    successCount++;
                } else {
                    errorCount++;
                }
            } catch (error) {
                errorCount++;
            }
        }

        if (errorCount === 0) {
            this.showToast(`${successCount} pedidos finalizados com sucesso`, 'success');
            this.playSound('success');
        } else {
            this.showToast(
                `${successCount} finalizados, ${errorCount} com erro`,
                'warning'
            );
        }

        await this.refreshOrders(false);
    }

    /**
     * Atualizar lista de pedidos
     */
    async refreshOrders(showMessage = true) {
        try {
            if (showMessage) {
                this.showToast('Atualizando pedidos...', 'info');
            }

            // Recarregar página (pode ser substituído por AJAX)
            window.location.reload();

        } catch (error) {
            console.error('Erro ao atualizar pedidos:', error);
            this.showToast('Erro ao atualizar pedidos', 'error');
        }
    }

    /**
     * Tratamento de erros com retry
     */
    handleError(error, retryCallback) {
        this.retryAttempts++;

        if (this.retryAttempts < this.maxRetries) {
            this.showToast(
                `Erro na operação. Tentativa ${this.retryAttempts}/${this.maxRetries}`,
                'warning'
            );
            
            setTimeout(() => {
                retryCallback();
            }, 1000 * this.retryAttempts);
        } else {
            this.showToast(
                'Erro ao processar operação. Tente novamente ou recarregue a página.',
                'error'
            );
            this.playSound('error');
            this.retryAttempts = 0;
        }
    }

    /**
     * Confirmação de ação
     */
    confirmAction(title, message) {
        return new Promise((resolve) => {
            const result = confirm(`${title}\n\n${message}`);
            resolve(result);
        });
    }

    /**
     * Mostrar toast usando sistema existente
     */
    showToast(message, type = 'success') {
        // Usar função global showToast do layout
        if (typeof window.showToast === 'function') {
            window.showToast(message, type);
        } else {
            // Fallback
            alert(`[${type.toUpperCase()}] ${message}`);
        }
    }

    /**
     * Reproduzir som de notificação
     */
    playSound(soundType) {
        if (!this.soundsEnabled) return;

        try {
            const soundPath = this.sounds[soundType];
            if (soundPath) {
                const audio = new Audio(soundPath);
                audio.volume = 0.5;
                audio.play().catch(e => {
                    console.warn('Não foi possível reproduzir som:', e);
                });
            }
        } catch (error) {
            console.warn('Erro ao reproduzir som:', error);
        }
    }

    /**
     * Verificar disponibilidade de sons
     */
    checkForSounds() {
        // Verificar se os arquivos de som existem
        fetch(this.sounds.success, { method: 'HEAD' })
            .then(response => {
                this.soundsEnabled = response.ok;
            })
            .catch(() => {
                this.soundsEnabled = false;
                console.warn('Sons de notificação não disponíveis');
            });
    }

    /**
     * Auto-refresh periódico
     */
    startAutoRefresh() {
        // Refresh automático a cada 30 segundos
        setInterval(() => {
            if (!this.updateInProgress) {
                this.refreshOrders(false);
            }
        }, 30000);
    }

    /**
     * Obter CSRF Token
     */
    getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (!token) {
            console.error('CSRF token não encontrado');
            throw new Error('Token de segurança não encontrado');
        }
        return token.content;
    }

    /**
     * Validar formulário antes de submissão
     */
    validateForm(form) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value) {
                this.showToast(`Campo obrigatório: ${field.name}`, 'error');
                isValid = false;
            }
        });

        return isValid;
    }
}

// Inicializar sistema quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    window.kitchenSystem = new KitchenNotificationSystem();
    
    // Salvar textos originais dos botões
    document.querySelectorAll('button[data-item-id], button[data-order-id]').forEach(button => {
        button.dataset.originalText = button.innerHTML;
    });
});

// Prevenir múltiplas submissões de formulários
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.classList.contains('submitting')) {
        e.preventDefault();
        return false;
    }
    form.classList.add('submitting');
    
    setTimeout(() => {
        form.classList.remove('submitting');
    }, 3000);
});