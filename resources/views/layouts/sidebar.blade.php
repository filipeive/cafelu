<div class="sidebar bg-dark sidebar-offcanvas" id="sidebar">
    <!-- Loader inicial -->
    <div id="system-loader" class="system-loader">
        <div class="spinner-border text-warning" role="status">
            <span class="visually-hidden">Carregando...</span>
        </div>
    </div>

    <!-- Menu de Navegação Principal -->
    <ul class="nav flex-column main-nav">
        <!-- Dashboard Principal -->
        <div class="sidebar-section">
            <x-sidebar.nav-item route="dashboard" icon="mdi-view-dashboard" title="Dashboard" />
            <x-sidebar.nav-item route="pos.index" icon="mdi-point-of-sale" title="PDV" badge="Novo" badgeClass="badge-warning" />
        </div>

        <!-- OPERACIONAL -->
        <div class="sidebar-section">
            <div class="sidebar-section-header">
                <span>OPERACIONAL</span>
            </div>
            
            <x-sidebar.dropdown 
                icon="mdi-store" 
                title="Operacional" 
                id="operational-menu"
                :badge="\App\Models\Order::where('status', 'active')->count() > 0 ? \App\Models\Order::where('status', 'active')->count() : null"
                badgeClass="badge-danger"
            >
                @php
                    $pendingOrdersCount = \App\Models\Order::where('status', 'active')->count();
                @endphp
                
                <x-sidebar.dropdown-item 
                    route="orders.index" 
                    icon="mdi-cart" 
                    title="Pedidos" 
                    :badge="$pendingOrdersCount"
                    badgeClass="badge-danger" 
                    :showBadge="$pendingOrdersCount > 0"
                />

                @php
                    $tablesAvailable = \App\Models\Table::where('status', 'free')->count();
                    $totalTables = \App\Models\Table::count();
                @endphp

                <x-sidebar.dropdown-item route="tables.index" icon="mdi-table-furniture" title="Mesas">
                    <span class="badge {{ $tablesAvailable > 0 ? 'badge-success' : 'badge-secondary' }}">
                        {{ $tablesAvailable }}/{{ $totalTables }}
                    </span>
                </x-sidebar.dropdown-item>
            </x-sidebar.dropdown>
        </div>

        <!-- PRODUTOS -->
        <div class="sidebar-section">
            <div class="sidebar-section-header">
                <span>PRODUTOS</span>
            </div>
            
            <x-sidebar.dropdown 
                icon="mdi-food-variant" 
                title="Cardápio" 
                id="menu-items"
            >
                @php
                    $lowStockProductsCount = \App\Models\Product::where('stock_quantity', '<', 10)->count();
                @endphp
                
                <x-sidebar.dropdown-item 
                    route="products.index" 
                    icon="mdi-food" 
                    title="Produtos" 
                    :badge="$lowStockProductsCount"
                    badgeClass="badge-danger" 
                    :showBadge="$lowStockProductsCount > 0"
                />

                <x-sidebar.dropdown-item 
                    route="categories.index" 
                    icon="mdi-shape" 
                    title="Categorias" 
                />
            </x-sidebar.dropdown>
        </div>

        <!-- FINANCEIRO -->
        <div class="sidebar-section">
            <div class="sidebar-section-header">
                <span>FINANCEIRO</span>
            </div>
            
            <x-sidebar.dropdown 
                icon="mdi-currency-usd" 
                title="Financeiro" 
                id="financial-menu"
            >
                @php
                    $todaySales = \App\Models\Sale::whereDate('created_at', today())->sum('total_amount') ?? 0;
                    $formattedSales = number_format($todaySales, 2, ',', '.');
                @endphp

                <x-sidebar.dropdown-item 
                    route="sales.index" 
                    icon="mdi-cash-multiple" 
                    title="Vendas"
                >
                    <span class="badge badge-success">MZN {{ $formattedSales }}</span>
                </x-sidebar.dropdown-item>

                @if(Auth::user()->role == 'admin')
                    <x-sidebar.dropdown-item 
                        route="reports.index" 
                        icon="mdi-chart-bar" 
                        title="Relatórios" 
                    />
                @endif
            </x-sidebar.dropdown>
        </div>

        <!-- CLIENTES -->
        <div class="sidebar-section">
            <div class="sidebar-section-header">
                <span>CLIENTES</span>
            </div>
            
            <x-sidebar.dropdown 
                icon="mdi-account-group" 
                title="Relacionamento" 
                id="clients-menu"
            >
                @php
                    $newClientsCount = \App\Models\Client::whereDate('created_at', today())->count();
                @endphp
                
                <x-sidebar.dropdown-item 
                    route="clients.index" 
                    icon="mdi-account-multiple" 
                    title="Clientes" 
                    :badge="$newClientsCount"
                    badgePrefix="+"
                    badgeClass="badge-info"
                    :showBadge="$newClientsCount > 0"
                />
                <!--funcionarios-->
                <x-sidebar.dropdown-item 
                    route="employees.index" 
                    icon="mdi-account-tie" 
                    title="Funcionários" 
                />
            </x-sidebar.dropdown>
            
        </div>

        <!-- CONFIGURAÇÕES (Admin Only) -->
        @if(Auth::user()->role == 'admin')
            <div class="sidebar-section">
                <div class="sidebar-section-header">
                    <span>CONFIGURAÇÕES</span>
                </div>
                
                <x-sidebar.dropdown 
                    icon="mdi-shield-account" 
                    title="Administração" 
                    id="admin-menu"
                >
                    <x-sidebar.dropdown-item 
                        route="users.index" 
                        icon="mdi-account-key" 
                        title="Usuários" 
                    />
                    
                    <x-sidebar.dropdown-item 
                        route="settings.index" 
                        icon="mdi-cog" 
                        title="Configurações" 
                    />
                    
                    @if(Auth::user()->role == 'super_admin')
                        <x-sidebar.dropdown-item 
                            route="employees.index" 
                            icon="mdi-account-tie" 
                            title="Funcionários" 
                        />
                    @endif
                </x-sidebar.dropdown>
            </div>
        @endif
    </ul>

    <!-- Controles da Sidebar -->
    <div class="sidebar-controls">
        <button type="button" class="btn btn-icon sidebar-toggle-btn" onclick="toggleSidebarCollapse()" id="sidebar-toggle" title="Minimizar menu">
            <i class="mdi mdi-chevron-left"></i>
        </button>
        
        @if(Auth::user()->role == 'admin')
            <button type="button" class="btn btn-icon" onclick="toggleSystemStats()" title="Status do Sistema">
                <i class="mdi mdi-monitor-dashboard"></i>
            </button>
        @endif
    </div>

    <!-- Rodapé do Sidebar -->
    <div class="sidebar-footer">
        <div class="system-info text-center">
            <small class="text-muted d-block">Sistema</small>
            <span class="text-warning fw-bold">v1.0.0</span>
            <small class="text-muted d-block mt-1">&copy; {{ date('Y') }} {{ config('app.company', 'Zalala Beach Bar') }}</small>
        </div>

        <!-- Status do sistema -->
        <div class="system-status mt-2 text-center">
            <span class="badge badge-success">
                <i class="mdi mdi-check-circle"></i> Online
            </span>
        </div>
    </div>
</div>

<style>
/* ========================================
   SIDEBAR STYLES
======================================== */
#sidebar {
    width: 260px;
    min-height: 100vh;
    background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
    border-right: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
    position: fixed;
    z-index: 1000;
}

#sidebar.collapsed {
    width: 70px;
}

#sidebar.collapsed .sidebar-section-header,
#sidebar.collapsed .nav-link-text,
#sidebar.collapsed .dropdown-toggle::after,
#sidebar.collapsed .badge:not(.fixed-badge) {
    display: none;
}

#sidebar.collapsed .sidebar-footer {
    display: none;
}

/* Seções do Sidebar */
.sidebar-section {
    padding: 0.5rem 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.03);
}

.sidebar-section-header {
    padding: 0.75rem 0 0.5rem 0;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 1px;
    color: rgba(255, 255, 255, 0.4);
    border-bottom: 1px solid rgba(255, 165, 0, 0.2);
}

/* Itens de navegação */
.nav-link {
    color: rgba(255, 255, 255, 0.7);
    padding: 0.75rem 1rem;
    border-radius: 8px;
    margin: 0.125rem 0;
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.nav-link:hover {
    background: rgba(255, 165, 0, 0.1);
    color: #FFA500;
    transform: translateX(5px);
}

.nav-link.active {
    background: linear-gradient(90deg, rgba(255, 165, 0, 0.15) 0%, transparent 100%);
    color: #FFA500;
    border-left: 3px solid #FFA500;
}

.nav-link i {
    font-size: 1.25rem;
    width: 24px;
    text-align: center;
}

.nav-link-text {
    flex: 1;
    font-size: 0.95rem;
    font-weight: 500;
}

/* Dropdowns */
.dropdown-menu {
    background: rgba(26, 26, 46, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 0.5rem;
    margin-left: 0.5rem;
}

.dropdown-item {
    color: rgba(255, 255, 255, 0.7);
    padding: 0.6rem 1rem;
    border-radius: 6px;
    margin: 0.125rem 0;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: rgba(255, 165, 0, 0.1);
    color: #FFA500;
}

.dropdown-item.active {
    background: rgba(255, 165, 0, 0.2);
    color: #FFA500;
}

/* Badges */
.badge {
    font-size: 0.65rem;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-weight: 600;
}

.badge-danger {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.badge-success {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.badge-warning {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.badge-info {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

/* Controles da Sidebar */
.sidebar-controls {
    position: absolute;
    bottom: 120px;
    left: 0;
    right: 0;
    padding: 1rem;
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    background: rgba(26, 26, 46, 0.5);
}

.sidebar-toggle-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.6);
    transition: all 0.3s ease;
}

.sidebar-toggle-btn:hover {
    background: rgba(255, 165, 0, 0.2);
    color: #FFA500;
    transform: rotate(180deg);
}

#sidebar.collapsed .sidebar-toggle-btn {
    transform: rotate(180deg);
}

.btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.6);
    transition: all 0.3s ease;
}

.btn-icon:hover {
    background: rgba(255, 165, 0, 0.2);
    color: #FFA500;
    transform: scale(1.1);
}

/* Rodapé do Sidebar */
.sidebar-footer {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 1rem;
    background: rgba(26, 26, 46, 0.5);
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.system-info {
    padding-bottom: 0.5rem;
}

.system-status .badge {
    font-size: 0.7rem;
    padding: 0.35rem 0.75rem;
}

/* Loader do sistema */
.system-loader {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(26, 26, 46, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1001;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.system-loader.show {
    opacity: 1;
    visibility: visible;
}

/* Responsividade */
@media (max-width: 768px) {
    #sidebar {
        transform: translateX(-100%);
        width: 280px;
    }
    
    #sidebar.mobile-open {
        transform: translateX(0);
    }
    
    .sidebar-controls {
        position: fixed;
        bottom: 0;
        background: rgba(26, 26, 46, 0.95);
    }
}

/* Animação de entrada dos itens */
.nav-link, .dropdown-item {
    animation: slideInLeft 0.3s ease forwards;
    opacity: 0;
    transform: translateX(-10px);
}

@keyframes slideInLeft {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Ordem das animações */
.nav-link:nth-child(1) { animation-delay: 0.1s; }
.nav-link:nth-child(2) { animation-delay: 0.2s; }
.nav-link:nth-child(3) { animation-delay: 0.3s; }
.dropdown-item:nth-child(1) { animation-delay: 0.4s; }
.dropdown-item:nth-child(2) { animation-delay: 0.5s; }
.dropdown-item:nth-child(3) { animation-delay: 0.6s; }
</style>

<script>
// Funções para controle da sidebar
function toggleSidebarCollapse() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
    
    // Salvar preferência no localStorage
    const isCollapsed = sidebar.classList.contains('collapsed');
    localStorage.setItem('sidebarCollapsed', isCollapsed);
    
    // Atualizar ícone do botão
    const toggleBtn = document.getElementById('sidebar-toggle');
    const icon = toggleBtn.querySelector('i');
    
    if (isCollapsed) {
        icon.classList.remove('mdi-chevron-left');
        icon.classList.add('mdi-chevron-right');
    } else {
        icon.classList.remove('mdi-chevron-right');
        icon.classList.add('mdi-chevron-left');
    }
    
    // Ajustar conteúdo principal
    const mainPanel = document.querySelector('.main-panel');
    if (mainPanel) {
        if (isCollapsed) {
            mainPanel.style.marginLeft = '70px';
        } else {
            mainPanel.style.marginLeft = '260px';
        }
    }
}

function toggleSystemStats() {
    // Implementar modal com estatísticas do sistema
    Swal.fire({
        title: 'Status do Sistema',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <small class="text-muted d-block">Usuário Online</small>
                    <strong>${document.querySelector('.navbar-brand').innerText}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Última Atualização</small>
                    <strong>${new Date().toLocaleTimeString('pt-BR')}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Status</small>
                    <span class="badge badge-success">Operacional</span>
                </div>
            </div>
        `,
        icon: 'info',
        confirmButtonColor: '#FFA500',
        background: '#1a1a2e',
        color: '#ffffff'
    });
}

// Inicializar sidebar
document.addEventListener('DOMContentLoaded', function() {
    // Restaurar estado da sidebar
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (sidebarCollapsed) {
        toggleSidebarCollapse();
    }
    
    // Esconder loader após carregamento
    setTimeout(() => {
        const loader = document.getElementById('system-loader');
        if (loader) loader.classList.remove('show');
    }, 500);
    
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Mobile: fechar sidebar ao clicar fora
    if (window.innerWidth < 768) {
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar.contains(e.target) && !e.target.closest('.navbar-toggler')) {
                sidebar.classList.remove('mobile-open');
            }
        });
    }
});

// Mobile: alternar sidebar
function toggleMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('mobile-open');
}
</script>