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
});