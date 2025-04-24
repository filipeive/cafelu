/* alert script carregado*/
$(document).ready(function() {
    // Exibir o alerta
    $('#alert-loading').show();
    // Simular um atraso de 2 segundos (2000 milissegundos)
    setTimeout(function() {
        // Ocultar o alerta após o atraso
        $('#alert-loading').fadeOut('slow');
    }, 2000);
// Exibir o alerta
    $('#alert-loading').show();
    // Simular um atraso de 2 segundos (2000 milissegundos)
    setTimeout(function() {
        // Ocultar o alerta após o atraso
        $('#alert-loading').fadeOut('slow');
    }, 2000);
}
);
// Mostrar o loader e ocultar após 1s
const loader = document.getElementById('system-loader');
if (loader) {
    loader.style.display = 'flex';
    setTimeout(() => {
        loader.style.opacity = '0';
        setTimeout(() => {
            loader.style.display = 'none';
        }, 300);
    }, 1000);
}
// Adicionar evento de clique para os links do menu lateral
document.addEventListener('DOMContentLoaded', function() {
    // Garantir que o dropdown com item ativo esteja sempre aberto
    const activeNavLink = document.querySelector('.sidebar .sub-menu .nav-link.active');
    if (activeNavLink) {
        // Encontrar o dropdown pai
        const parentCollapse = activeNavLink.closest('.collapse');
        if (parentCollapse) {
            // Adicionar classe show para manter aberto
            parentCollapse.classList.add('show');
            
            // Ajustar o atributo aria-expanded do botão de controle
            const controlButton = document.querySelector(`[aria-controls="${parentCollapse.id}"]`);
            if (controlButton) {
                controlButton.setAttribute('aria-expanded', 'true');
                // Garantir que a seta esteja rotacionada
                const arrow = controlButton.querySelector('.menu-arrow');
                if (arrow) {
                    arrow.style.transform = 'rotate(90deg)';
                }
            }
        }
    }

    // Garantir que os ícones de seta apareçam corretamente
    document.querySelectorAll('.sidebar .nav-link[data-bs-toggle="collapse"]').forEach(function(navLink) {
        // Verificar se já tem um ícone de seta
        if (!navLink.querySelector('.menu-arrow')) {
            // Adicionar ícone de seta se estiver faltando
            const arrow = document.createElement('i');
            arrow.className = 'menu-arrow';
            navLink.appendChild(arrow);
        }
    });
    function initSidebar() {
        // Garantir que o dropdown com item ativo esteja sempre aberto
        const activeNavLink = document.querySelector('.sidebar .sub-menu .nav-link.active');
        if (activeNavLink) {
            // Encontrar o dropdown pai
            const parentCollapse = activeNavLink.closest('.collapse');
            if (parentCollapse) {
                // Adicionar classe show para manter aberto
                parentCollapse.classList.add('show');
                
                // Ajustar o atributo aria-expanded do botão de controle
                const controlButton = document.querySelector(`[aria-controls="${parentCollapse.id}"]`);
                if (controlButton) {
                    controlButton.setAttribute('aria-expanded', 'true');
                }
            }
        }
        
        // Adicionar tooltips aos itens do menu quando minimizado
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            const title = link.querySelector('.menu-title')?.textContent;
            if (title) {
                link.setAttribute('data-bs-toggle', 'tooltip');
                link.setAttribute('data-bs-placement', 'right');
                link.setAttribute('title', title);
            }
        });
        
        // Inicializar tooltips do Bootstrap
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        }
        
        // Adicionar efeitos de hover e foco
        document.querySelectorAll('.sidebar .nav-link').forEach(navLink => {
            navLink.addEventListener('mouseenter', function() {
                this.classList.add('menu-hover');
            });
            
            navLink.addEventListener('mouseleave', function() {
                this.classList.remove('menu-hover');
            });
        });
        
        // Adicionar contador para badges animadas
        updateBadgeAnimations();
        
        // Verificar notificações pendentes a cada 30s
        setInterval(() => {
            checkForNotifications();
        }, 30000);
            // Inicializar colapsáveis do Bootstrap
            document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(trigger => {
                trigger.addEventListener('click', function (e) {
                    const targetId = this.getAttribute('data-bs-target') || this.getAttribute('href');
                    const target = document.querySelector(targetId);
        
                    if (target) {
                        const bsCollapse = new bootstrap.Collapse(target, {
                            toggle: true
                        });
                    }
                });
        });  
    }
});


/**
 * Café Lufamina - Sidebar Controller
 * Este script gerencia o comportamento da sidebar do sistema de restaurante
 */
/* document.addEventListener('DOMContentLoaded', function() {
    // Configuração de temas e modos visuais
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
    }
    
    // Inicialização do menu
    initSidebar();
    
    // Mostrar o loader e ocultar após 1s
    const loader = document.getElementById('system-loader');
    if (loader) {
        loader.style.display = 'flex';
        setTimeout(() => {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 300);
        }, 1000);
    }
    
    // Controles do menu para dispositivos móveis
    const menuToggler = document.querySelector('.navbar-toggler');
    const sidebar = document.querySelector('.sidebar');
    
    if (menuToggler && sidebar) {
        menuToggler.addEventListener('click', function() {
            sidebar.classList.toggle('show-mobile');
            document.body.classList.toggle('sidebar-open');
        });
    }
});

/**
 * Inicializa a sidebar com configurações e comportamento

function initSidebar() {
    // Garantir que o dropdown com item ativo esteja sempre aberto
    const activeNavLink = document.querySelector('.sidebar .sub-menu .nav-link.active');
    if (activeNavLink) {
        // Encontrar o dropdown pai
        const parentCollapse = activeNavLink.closest('.collapse');
        if (parentCollapse) {
            // Adicionar classe show para manter aberto
            parentCollapse.classList.add('show');
            
            // Ajustar o atributo aria-expanded do botão de controle
            const controlButton = document.querySelector(`[aria-controls="${parentCollapse.id}"]`);
            if (controlButton) {
                controlButton.setAttribute('aria-expanded', 'true');
            }
        }
    }
    
    // Adicionar tooltips aos itens do menu quando minimizado
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        const title = link.querySelector('.menu-title')?.textContent;
        if (title) {
            link.setAttribute('data-bs-toggle', 'tooltip');
            link.setAttribute('data-bs-placement', 'right');
            link.setAttribute('title', title);
        }
    });
    
    // Inicializar tooltips do Bootstrap
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
    
    // Adicionar efeitos de hover e foco
    document.querySelectorAll('.sidebar .nav-link').forEach(navLink => {
        navLink.addEventListener('mouseenter', function() {
            this.classList.add('menu-hover');
        });
        
        navLink.addEventListener('mouseleave', function() {
            this.classList.remove('menu-hover');
        });
    });
    
    // Adicionar contador para badges animadas
    updateBadgeAnimations();
    
    // Verificar notificações pendentes a cada 30s
    setInterval(() => {
        checkForNotifications();
    }, 30000);
        // Inicializar colapsáveis do Bootstrap
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(trigger => {
            trigger.addEventListener('click', function (e) {
                const targetId = this.getAttribute('data-bs-target') || this.getAttribute('href');
                const target = document.querySelector(targetId);
    
                if (target) {
                    const bsCollapse = new bootstrap.Collapse(target, {
                        toggle: true
                    });
                }
            });
        });  
}

/**
 // Adiciona animações para badges conforme a prioridade
 
function updateBadgeAnimations() {
    document.querySelectorAll('.sidebar .badge').forEach(badge => {
        if (badge.classList.contains('badge-danger')) {
            badge.classList.add('pulse-animation');
        } else if (badge.classList.contains('badge-warning')) {
            badge.classList.add('blink-animation');
        }
    });
}

/**
 * Verifica se há novas notificações no sistema
 * Esta é uma função de exemplo que poderia se conectar a uma API real
 
function checkForNotifications() {
    // Simular verificação de notificações - substituir por uma chamada de API real
    const hasUnreadNotifications = Math.random() > 0.7;
    
    // Atualizar o indicador de notificações
    const notificationsIndicator = document.querySelector('#notifications-indicator');
    if (notificationsIndicator) {
        if (hasUnreadNotifications) {
            notificationsIndicator.classList.add('has-notifications');
            notificationsIndicator.setAttribute('data-count', Math.floor(Math.random() * 5) + 1);
        } else {
            notificationsIndicator.classList.remove('has-notifications');
        }
    }
}

/**
 * Alternador de modo escuro/claro
 
function toggleDarkMode() {
    const isDarkMode = document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);
    
    // Atualizar ícone do toggle
    const darkModeToggle = document.querySelector('#dark-mode-toggle');
    if (darkModeToggle) {
        const icon = darkModeToggle.querySelector('i');
        if (isDarkMode) {
            icon.classList.replace('mdi-weather-sunny', 'mdi-weather-night');
        } else {
            icon.classList.replace('mdi-weather-night', 'mdi-weather-sunny');
        }
    }
}

/**
 * Minimiza a sidebar para modo compacto
 
function toggleSidebarCollapse() {
    document.body.classList.toggle('sidebar-mini');
    localStorage.setItem('sidebarMini', document.body.classList.contains('sidebar-mini'));
    
    // Reinicializar tooltips após colapso
    setTimeout(() => {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: document.body.classList.contains('sidebar-mini') ? 'hover' : 'manual'
            }));
        }
    }, 300);
} */