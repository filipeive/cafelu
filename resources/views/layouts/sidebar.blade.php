<div 
    class="fixed top-16 left-0 h-[calc(100vh-4rem)] bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 transition-all duration-300 z-40 flex flex-col"
    :class="{ 
        'w-64': !sidebarCollapsed, 
        'w-20': sidebarCollapsed,
        '-translate-x-full lg:translate-x-0': !sidebarOpen,
        'translate-x-0': sidebarOpen
    }"
>
    <!-- Menu de Navegação Principal -->
    <ul class="flex-1 overflow-y-auto py-4 px-3 space-y-2 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700 overflow-x-hidden">
        <!-- Dashboard Principal -->
        <div class="mb-4">
            <x-sidebar.nav-item route="dashboard" icon="mdi-view-dashboard" title="Dashboard" />
            <x-sidebar.nav-item route="pos.index" icon="mdi-point-of-sale" title="PDV" badge="Novo" badgeClass="bg-warning text-white" />
        </div>

        <!-- OPERACIONAL -->
        <div class="mb-4">
            <div class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider transition-opacity duration-200"
                 x-show="!sidebarCollapsed" x-transition>
                <span>OPERACIONAL</span>
            </div>
            <!-- Separator for collapsed mode -->
            <div class="h-px bg-gray-200 dark:bg-gray-700 mx-2 mb-2" x-show="sidebarCollapsed"></div>
            
            <x-sidebar.dropdown 
                icon="mdi-store" 
                title="Operacional" 
                id="operational-menu"
                :badge="\App\Models\Order::where('status', 'active')->count() > 0 ? \App\Models\Order::where('status', 'active')->count() : null"
                badgeClass="bg-danger text-white"
            >
                @php
                    $pendingOrdersCount = \App\Models\Order::where('status', 'active')->count();
                @endphp
                
                <x-sidebar.dropdown-item 
                    route="orders.index" 
                    icon="mdi-cart" 
                    title="Pedidos" 
                    :badge="$pendingOrdersCount"
                    badgeClass="bg-danger text-white" 
                    :showBadge="$pendingOrdersCount > 0"
                />

                @php
                    $tablesAvailable = \App\Models\Table::where('status', 'free')->count();
                    $totalTables = \App\Models\Table::count();
                @endphp

                <x-sidebar.dropdown-item route="tables.index" icon="mdi-table-furniture" title="Mesas">
                    <span class="px-2 py-0.5 rounded text-xs {{ $tablesAvailable > 0 ? 'bg-success text-white' : 'bg-gray-500 text-white' }}">
                        {{ $tablesAvailable }}/{{ $totalTables }}
                    </span>
                </x-sidebar.dropdown-item>
            </x-sidebar.dropdown>
        </div>

        <!-- PRODUTOS -->
        <div class="mb-4">
            <div class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider transition-opacity duration-200"
                 x-show="!sidebarCollapsed" x-transition>
                <span>PRODUTOS</span>
            </div>
            <div class="h-px bg-gray-200 dark:bg-gray-700 mx-2 mb-2" x-show="sidebarCollapsed"></div>
            
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
                    badgeClass="bg-danger text-white" 
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
        <div class="mb-4">
            <div class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider transition-opacity duration-200"
                 x-show="!sidebarCollapsed" x-transition>
                <span>FINANCEIRO</span>
            </div>
            <div class="h-px bg-gray-200 dark:bg-gray-700 mx-2 mb-2" x-show="sidebarCollapsed"></div>
            
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
                    <span class="px-2 py-0.5 rounded text-xs bg-success text-white">MZN {{ $formattedSales }}</span>
                </x-sidebar.dropdown-item>

                <x-sidebar.dropdown-item 
                    route="expenses.index" 
                    icon="mdi-cash-minus" 
                    title="Despesas" 
                />

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
        <div class="mb-4">
            <div class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider transition-opacity duration-200"
                 x-show="!sidebarCollapsed" x-transition>
                <span>CLIENTES</span>
            </div>
            <div class="h-px bg-gray-200 dark:bg-gray-700 mx-2 mb-2" x-show="sidebarCollapsed"></div>
            
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
                    badgeClass="bg-info text-white"
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
            <div class="mb-4">
                <div class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider transition-opacity duration-200"
                     x-show="!sidebarCollapsed" x-transition>
                    <span>CONFIGURAÇÕES</span>
                </div>
                <div class="h-px bg-gray-200 dark:bg-gray-700 mx-2 mb-2" x-show="sidebarCollapsed"></div>
                
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
    <div class="p-4 border-t border-gray-200 dark:border-gray-800 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
        <button type="button" 
                class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-500 transition-colors hidden lg:block" 
                @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)" 
                title="Minimizar menu">
            <i class="mdi text-xl" :class="sidebarCollapsed ? 'mdi-chevron-right' : 'mdi-chevron-left'"></i>
        </button>
        
        @if(Auth::user()->role == 'admin')
            <button type="button" 
                    class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-500 transition-colors" 
                    onclick="toggleSystemStats()" 
                    title="Status do Sistema"
                    x-show="!sidebarCollapsed">
                <i class="mdi mdi-monitor-dashboard text-xl"></i>
            </button>
        @endif
    </div>

    <!-- Rodapé do Sidebar -->
    <div class="p-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800" x-show="!sidebarCollapsed" x-transition>
        <div class="text-center">
            <small class="text-gray-400 block text-xs">Sistema</small>
            <span class="text-warning font-bold text-sm">v1.0.0</span>
            <small class="text-gray-400 block mt-1 text-xs">&copy; {{ date('Y') }} {{ config('app.company', 'Zalala Beach Bar') }}</small>
        </div>

        <!-- Status do sistema -->
        <div class="mt-2 text-center">
            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                <i class="mdi mdi-check-circle mr-1"></i> Online
            </span>
        </div>
    </div>
</div>

<script>
function toggleSystemStats() {
    Swal.fire({
        title: 'Status do Sistema',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <small class="text-muted d-block">Usuário Online</small>
                    <strong>${document.querySelector('.navbar-brand') ? document.querySelector('.navbar-brand').innerText : '{{ Auth::user()->name }}'}</strong>
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
</script>