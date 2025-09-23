
@props([
    'company' => 'CaféLufamina',
    'year' => null,
    'version' => '1.0.0',
    'showStats' => true
])

@php
    $currentYear = $year ?? date('Y');
@endphp

<div class="sidebar-footer">
    @if($showStats)
        <!-- Quick Stats -->
        <div class="footer-stats mb-3">
            <div class="row g-1 text-center">
                <div class="col-6">
                    <div class="footer-stat-item">
                        <div class="stat-value text-warning">
                            {{ \App\Models\Order::whereDate('created_at', today())->count() }}
                        </div>
                        <div class="stat-label">Pedidos Hoje</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="footer-stat-item">
                        <div class="stat-value text-success">
                            {{ \App\Models\Table::where('status', 'occupied')->count() }}
                        </div>
                        <div class="stat-label">Mesas Ocupadas</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- System Info -->
    <div class="system-info text-center text-white">
        <div class="d-flex align-items-center justify-content-center mb-2">
            <i class="mdi mdi-shield-check text-success me-2"></i>
            <small>Sistema Seguro</small>
        </div>
        
        <div class="copyright-info">
            <small class="text-warning d-block">v{{ $version }}</small>
            <small class="text-light opacity-75">
                © {{ $currentYear }} {{ $company }}
            </small>
        </div>
        
        <!-- Quick Links -->
        <div class="footer-links mt-2">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-link text-light p-1" 
                        onclick="showHelp()" title="Ajuda">
                    <i class="mdi mdi-help-circle-outline"></i>
                </button>
                <button type="button" class="btn btn-link text-light p-1" 
                        onclick="showSupport()" title="Suporte">
                    <i class="mdi mdi-headset"></i>
                </button>
                <button type="button" class="btn btn-link text-light p-1" 
                        onclick="showAbout()" title="Sobre">
                    <i class="mdi mdi-information-outline"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.sidebar-footer {
    padding: 1rem;
    background: rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(10px);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin-top: auto;
}

.footer-stat-item {
    padding: 0.5rem 0.25rem;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.1);
}

.stat-value {
    font-weight: 700;
    font-size: 1.1rem;
}

.stat-label {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.7);
}

.footer-links .btn-link {
    text-decoration: none;
    opacity: 0.7;
    transition: opacity 0.2s ease;
}

.footer-links .btn-link:hover {
    opacity: 1;
    color: var(--bs-warning) !important;
}

.copyright-info small {
    line-height: 1.3;
}
</style>

<script>
function showHelp() {
    // Implementar modal de ajuda
    alert('Ajuda: Em desenvolvimento');
}

function showSupport() {
    // Implementar modal de suporte
    alert('Suporte: Entre em contato pelo WhatsApp (11) 99999-9999');
}

function showAbout() {
    // Implementar modal sobre o sistema
    const aboutInfo = `
        CaféLufamina POS v1.0.0
        Sistema de Gestão Gastronômica
        
        Desenvolvido com Laravel + Bootstrap
        © ${new Date().getFullYear()} - Todos os direitos reservados
    `;
    alert(aboutInfo);
}
</script>