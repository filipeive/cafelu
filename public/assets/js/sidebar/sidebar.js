/* alert script carregado*/
$(document).ready(function() {
    // Exibir o alerta
    $('#alert-loading').show();
    // Simular um atraso de 2 segundos (2000 milissegundos)
    setTimeout(function() {
        // Ocultar o alerta após o atraso
        $('#alert-loading').fadeOut('slow');
    }, 2000);
});

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

// Função principal para configurar a sidebar
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
    
    // Adicionar efeito visual de hover
    document.querySelectorAll('.sidebar .nav-link').forEach(navLink => {
        navLink.addEventListener('mouseenter', function() {
            this.classList.add('menu-hover');
        });
        
        navLink.addEventListener('mouseleave', function() {
            this.classList.remove('menu-hover');
        });
    });
    
    // *** SOLUÇÃO PARA EXPANSÃO/COLAPSO CONFIÁVEL ***
    // Remover todos os eventos de clique existentes e implementar uma solução manual
    document.querySelectorAll('.sidebar .nav-link[data-bs-toggle="collapse"]').forEach(function(navLink) {
        // Remover o atributo data-bs-toggle para evitar conflitos com o Bootstrap
        const targetSelector = navLink.getAttribute('data-bs-target') || navLink.getAttribute('href');
        navLink.removeAttribute('data-bs-toggle');
        
        // Adicionar nosso próprio handler de clique
        navLink.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const target = document.querySelector(targetSelector);
            if (!target) return;
            
            // Alternar a classe show manualmente
            const isExpanded = target.classList.contains('show');
            
            if (isExpanded) {
                // Colapsar
                target.classList.remove('show');
                this.setAttribute('aria-expanded', 'false');
                
                // Rotacionar a seta de volta
                const arrow = this.querySelector('.menu-arrow');
                if (arrow) {
                    arrow.style.transform = '';
                }
            } else {
                // Expandir
                target.classList.add('show');
                this.setAttribute('aria-expanded', 'true');
                
                // Rotacionar a seta
                const arrow = this.querySelector('.menu-arrow');
                if (arrow) {
                    arrow.style.transform = 'rotate(90deg)';
                }
            }
        });
    });
    
    // Adicionar tooltips
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        const title = link.querySelector('.menu-title')?.textContent;
        if (title) {
            link.setAttribute('title', title);
        }
    });
    
    // Verificar se as funções existem antes de chamá-las
    if (typeof updateBadgeAnimations === 'function') {
        updateBadgeAnimations();
    }
    
    if (typeof checkForNotifications === 'function') {
        setInterval(checkForNotifications, 30000);
    }
});