    // Sistema de Notificações
class NotificationSystem {
    constructor() {
        this.container = document.getElementById('toastContainer');
        this.init();
    }

    init() {
        // Listen for Laravel session flashes
        window.addEventListener('load', () => {
            if (window.laravelFlash) {
                this.show(window.laravelFlash.message, window.laravelFlash.type);
            }
        });
    }

    show(message, type = 'info', title = null) {
        const toast = this.createToast(message, type, title);
        this.container.appendChild(toast);
        
        // Auto remove
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    createToast(message, type, title) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type} fade-in`;
        
        const icons = {
            success: 'mdi-check-circle',
            error: 'mdi-alert-circle',
            warning: 'mdi-alert',
            info: 'mdi-information'
        };

        const titles = {
            success: title || 'Sucesso!',
            error: title || 'Erro!',
            warning: title || 'Atenção!',
            info: title || 'Informação'
        };

        toast.innerHTML = `
            <div class="toast-icon">
                <i class="mdi ${icons[type]}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${titles[type]}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="mdi mdi-close"></i>
            </button>
            <div class="toast-progress"></div>
        `;

        return toast;
    }
}

// Sistema de Loading
class LoadingSystem {
    constructor() {
        this.overlay = document.getElementById('loadingOverlay');
    }

    show(text = 'Carregando...') {
        this.overlay.querySelector('.loading-text').textContent = text;
        this.overlay.classList.add('active');
    }

    hide() {
        this.overlay.classList.remove('active');
    }
}

// Sistema de Tela Cheia
class FullscreenSystem {
    constructor() {
        this.button = document.getElementById('toggleFullscreen');
        this.init();
    }

    init() {
        if (this.button) {
            this.button.addEventListener('click', () => this.toggle());
            
            // Update icon on fullscreen change
            document.addEventListener('fullscreenchange', () => this.updateIcon());
            document.addEventListener('webkitfullscreenchange', () => this.updateIcon());
            document.addEventListener('mozfullscreenchange', () => this.updateIcon());
        }
    }

    isFullscreen() {
        return !!(document.fullscreenElement || 
                 document.webkitFullscreenElement || 
                 document.mozFullScreenElement);
    }

    toggle() {
        if (!this.isFullscreen()) {
            const elem = document.documentElement;
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            }
        }
    }

    updateIcon() {
        if (this.button) {
            const icon = this.button.querySelector('i');
            if (this.isFullscreen()) {
                icon.classList.remove('mdi-fullscreen');
                icon.classList.add('mdi-fullscreen-exit');
            } else {
                icon.classList.remove('mdi-fullscreen-exit');
                icon.classList.add('mdi-fullscreen');
            }
        }
    }
}

// Sistema de Data/Hora
class DateTimeSystem {
    constructor() {
        this.element = document.getElementById('currentDateTime');
        if (this.element) {
            this.update();
            setInterval(() => this.update(), 1000);
        }
    }

    update() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        this.element.textContent = now.toLocaleDateString('pt-BR', options);
    }
}

// Sistema Principal
class RestaurantSystem {
    constructor() {
        this.notifications = new NotificationSystem();
        this.loading = new LoadingSystem();
        this.fullscreen = new FullscreenSystem();
        this.datetime = new DateTimeSystem();
        this.init();
    }

    init() {
        // Initialize components
        this.initSidebar();
        this.initTooltips();
        this.initModals();
        
        // Global error handler
        window.addEventListener('error', (e) => {
            this.notifications.show('Ocorreu um erro inesperado', 'error');
            console.error(e.error);
        });

        // AJAX global handler
        this.setupAjax();
    }

    initSidebar() {
        const sidebarToggle = document.querySelector('[data-bs-toggle="minimize"]');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                document.body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', 
                    document.body.classList.contains('sidebar-collapsed')
                );
            });
        }

        // Load sidebar state
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }
    }

    initTooltips() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(el => new bootstrap.Tooltip(el));
    }

    initModals() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', () => {
                const input = modal.querySelector('input[autofocus]');
                if (input) input.focus();
            });
        });
    }

    setupAjax() {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            },
            beforeSend: () => this.loading.show(),
            complete: () => this.loading.hide(),
            error: (xhr) => {
                let message = 'Erro na operação';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                this.notifications.show(message, 'error');
            }
        });
    }

    // Helper functions
    formatCurrency(value) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'MZN'
        }).format(value);
    }

    formatDate(date) {
        return new Date(date).toLocaleDateString('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
}

// Initialize system when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.RestaurantSystem = new RestaurantSystem();
    
    // POS specific fullscreen prompt
    if (window.location.pathname.includes('/pos')) {
        if (!localStorage.getItem('posFullscreenAsked')) {
            Swal.fire({
                title: 'Modo Tela Cheia',
                text: 'Recomendamos usar o modo tela cheia para melhor experiência no PDV',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ativar',
                cancelButtonText: 'Depois',
                confirmButtonColor: '#FFA500'
            }).then((result) => {
                localStorage.setItem('posFullscreenAsked', 'true');
                if (result.isConfirmed) {
                    window.RestaurantSystem.fullscreen.toggle();
                }
            });
        }
    }
});