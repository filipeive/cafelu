<div 
    class="fixed top-16 left-0 h-[calc(100vh-4rem)] bg-white dark:bg-gray-900 border-r border-gray-200/80 dark:border-gray-800 transition-all duration-300 z-40 flex flex-col shadow-xs"
    :class="{ 
        'w-64': !sidebarCollapsed, 
        'w-20': sidebarCollapsed,
        '-translate-x-full lg:translate-x-0': !sidebarOpen,
        'translate-x-0': sidebarOpen
    }"
>
    <!-- Menu de Navegação Principal -->
    <ul class="flex-1 overflow-y-auto py-3 px-3 space-y-1 scrollbar-thin scrollbar-thumb-gray-200 dark:scrollbar-thumb-gray-800 overflow-x-hidden">
        
        @if(Auth::user()->role == 'customer')
            <!-- ÁREA DO CLIENTE -->
            <div class="mb-2">
                <x-sidebar.nav-item route="customer.dashboard" icon="mdi-view-dashboard-outline" title="Início" />
                <x-sidebar.nav-item route="customer.orders" icon="mdi-shopping-outline" title="Meus Pedidos" />
                <x-sidebar.nav-item route="welcome" icon="mdi-silverware-fork-knife" title="Fazer Pedido" />
                <x-sidebar.nav-item route="customer.profile" icon="mdi-account-circle-outline" title="Meu Perfil" />
            </div>
        @else
            <!-- PRINCIPAL -->
            <div class="mb-2">
                <x-sidebar.nav-item route="dashboard" icon="mdi-view-dashboard-outline" title="{{ __('messages.dashboard') }}" />
                <x-sidebar.nav-item route="pos.index" icon="mdi-point-of-sale" title="{{ __('messages.pos') }}" badge="PDV" badgeClass="bg-orange-500 text-white font-bold" />
            </div>

            <!-- OPERACIONAL -->
            <div class="pt-3 pb-1">
                <div class="px-3 mb-1.5 flex items-center justify-between" x-show="!sidebarCollapsed" x-transition>
                    <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                        {{ __('messages.operational') }}
                    </span>
                </div>
                <div class="h-px bg-gray-200/80 dark:bg-gray-800 my-2 mx-2" x-show="sidebarCollapsed"></div>

                @php
                    $pendingOrdersCount = \App\Models\Order::where('status', 'active')->count();
                    $onlineOrdersCount = \App\Models\Order::where('status', 'active')->whereNull('table_id')->count();
                    $tablesAvailable = \App\Models\Table::where('status', 'free')->count();
                    $totalTables = \App\Models\Table::count();
                @endphp

                <x-sidebar.nav-item 
                    route="orders.index" 
                    icon="mdi-clipboard-text-clock-outline" 
                    title="{{ __('messages.orders') }}" 
                    :badge="$pendingOrdersCount"
                    badgeClass="bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200/60"
                    :showBadge="$pendingOrdersCount > 0"
                />

                <x-sidebar.nav-item 
                    url="{{ route('orders.index', ['filter' => 'online']) }}" 
                    icon="mdi-earth" 
                    title="Pedidos Online" 
                    :badge="$onlineOrdersCount"
                    badgeClass="bg-purple-50 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400 border border-purple-200/60"
                    :showBadge="$onlineOrdersCount > 0"
                />

                @php
                    $kitchenOrdersCount = \App\Models\Order::whereIn('status', ['active', 'preparing'])->count();
                @endphp

                <x-sidebar.nav-item 
                    route="orders.kitchen" 
                    icon="mdi-chef-hat" 
                    title="Ordem de Cozinha (KDS)" 
                    :badge="$kitchenOrdersCount"
                    badgeClass="bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200/60"
                    :showBadge="$kitchenOrdersCount > 0"
                />

                <x-sidebar.nav-item 
                    route="tables.index" 
                    icon="mdi-table-furniture" 
                    title="{{ __('messages.tables') }}"
                    :badge="$tablesAvailable . '/' . $totalTables"
                    :badgeClass="$tablesAvailable > 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200/60' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400'"
                />
            </div>

            <!-- CARDÁPIO & ESTOQUE -->
            <div class="pt-3 pb-1">
                <div class="px-3 mb-1.5 flex items-center justify-between" x-show="!sidebarCollapsed" x-transition>
                    <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                        {{ __('messages.menu') }}
                    </span>
                </div>
                <div class="h-px bg-gray-200/80 dark:bg-gray-800 my-2 mx-2" x-show="sidebarCollapsed"></div>

                @php
                    $lowStockProductsCount = \App\Models\Product::where('stock_quantity', '<', 10)->count();
                @endphp

                <x-sidebar.nav-item 
                    route="products.index" 
                    icon="mdi-food-fork-drink" 
                    title="{{ __('messages.products') }}" 
                    :badge="$lowStockProductsCount"
                    badgeClass="bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200/60"
                    :showBadge="$lowStockProductsCount > 0"
                />

                <x-sidebar.nav-item 
                    route="categories.index" 
                    icon="mdi-shape-outline" 
                    title="{{ __('messages.categories') }}" 
                />

                <x-sidebar.nav-item 
                    route="stock.index" 
                    icon="mdi-package-variant-closed" 
                    title="{{ __('messages.stock_management') }}" 
                />
            </div>

            <!-- FINANCEIRO -->
            <div class="pt-3 pb-1">
                <div class="px-3 mb-1.5 flex items-center justify-between" x-show="!sidebarCollapsed" x-transition>
                    <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                        {{ __('messages.financial') }}
                    </span>
                </div>
                <div class="h-px bg-gray-200/80 dark:bg-gray-800 my-2 mx-2" x-show="sidebarCollapsed"></div>

                @php
                    $todaySales = \App\Models\Sale::whereDate('created_at', today())->sum('total_amount') ?? 0;
                    $formattedSales = number_format($todaySales, 0, ',', '.');
                    $debtsCount = \App\Models\CustomerDebt::where('status', '!=', 'paid')->count();
                @endphp

                <x-sidebar.nav-item 
                    route="sales.index" 
                    icon="mdi-cash-register" 
                    title="{{ __('messages.sales') }}"
                    :badge="'MZN ' . $formattedSales"
                    badgeClass="bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/60 text-[10px]"
                />

                <x-sidebar.nav-item 
                    route="expenses.index" 
                    icon="mdi-cash-minus" 
                    title="{{ __('messages.expenses') }}" 
                />

                <x-sidebar.nav-item 
                    route="debts.index" 
                    icon="mdi-account-cash-outline" 
                    title="Contas a Receber" 
                    :badge="$debtsCount"
                    badgeClass="bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200/60"
                    :showBadge="$debtsCount > 0"
                />

                @if(Auth::user()->role == 'admin')
                    <x-sidebar.nav-item 
                        route="reports.index" 
                        icon="mdi-chart-box-outline" 
                        title="{{ __('messages.reports') }}" 
                    />
                @endif
            </div>

            <!-- PESSOAS & EQUIPE -->
            <div class="pt-3 pb-1">
                <div class="px-3 mb-1.5 flex items-center justify-between" x-show="!sidebarCollapsed" x-transition>
                    <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                        {{ __('messages.clients') }}
                    </span>
                </div>
                <div class="h-px bg-gray-200/80 dark:bg-gray-800 my-2 mx-2" x-show="sidebarCollapsed"></div>

                @php
                    $newClientsCount = \App\Models\Client::whereDate('created_at', today())->count();
                @endphp

                <x-sidebar.nav-item 
                    route="clients.index" 
                    icon="mdi-account-group-outline" 
                    title="{{ __('messages.clients') }}" 
                    :badge="$newClientsCount"
                    badgePrefix="+"
                    badgeClass="bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400 border border-blue-200/60"
                    :showBadge="$newClientsCount > 0"
                />

                <x-sidebar.nav-item 
                    route="employees.index" 
                    icon="mdi-badge-account-outline" 
                    title="{{ __('messages.employees') }}" 
                />
            </div>

            <!-- ADMINISTRAÇÃO (Admin Only) -->
            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'super_admin')
                <div class="pt-3 pb-1">
                    <div class="px-3 mb-1.5 flex items-center justify-between" x-show="!sidebarCollapsed" x-transition>
                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                            {{ __('messages.settings') }}
                        </span>
                    </div>
                    <div class="h-px bg-gray-200/80 dark:bg-gray-800 my-2 mx-2" x-show="sidebarCollapsed"></div>

                    <x-sidebar.nav-item 
                        route="users.index" 
                        icon="mdi-account-cog-outline" 
                        title="{{ __('messages.users') }}" 
                    />
                    
                    <x-sidebar.nav-item 
                        route="settings.index" 
                        icon="mdi-cog-outline" 
                        title="{{ __('messages.system_settings') }}" 
                    />

                    <x-sidebar.nav-item 
                        route="audit_logs.index" 
                        icon="mdi-history" 
                        title="{{ __('messages.audit') }}" 
                    />
                </div>
            @endif
        @endif
    </ul>

    <!-- Rodapé Unificado da Sidebar -->
    <div class="p-3 border-t border-gray-200/80 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-900/60 flex items-center justify-between transition-all">
        <!-- Status Online com pulso suave -->
        <div class="flex items-center gap-2 pl-1" x-show="!sidebarCollapsed" x-transition>
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <div class="leading-none">
                <span class="text-[11px] font-medium text-gray-600 dark:text-gray-300 block">Online</span>
                <span class="text-[9px] text-gray-400 dark:text-gray-500 block mt-0.5">v1.0.0</span>
            </div>
        </div>

        <!-- Ações do rodapé -->
        <div class="flex items-center gap-1" :class="{ 'w-full justify-center': sidebarCollapsed }">
            @if(Auth::user()->role == 'admin')
                <button type="button" 
                        class="p-2 rounded-xl hover:bg-gray-200/80 dark:hover:bg-gray-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors" 
                        onclick="toggleSystemStats()" 
                        title="Status do Sistema"
                        x-show="!sidebarCollapsed">
                    <i class="mdi mdi-information-outline text-lg"></i>
                </button>
            @endif

            <button type="button" 
                    class="p-2 rounded-xl hover:bg-gray-200/80 dark:hover:bg-gray-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors hidden lg:flex items-center justify-center" 
                    @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)" 
                    :title="sidebarCollapsed ? 'Expandir menu' : 'Recolher menu'">
                <i class="mdi text-lg transition-transform duration-200" :class="sidebarCollapsed ? 'mdi-chevron-double-right' : 'mdi-chevron-double-left'"></i>
            </button>
        </div>
    </div>
</div>

<script>
function toggleSystemStats() {
    Swal.fire({
        title: '{{ __('messages.system_status') }}',
        html: `
            <div class="text-left space-y-3 p-2">
                <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded-xl">
                    <small class="text-gray-500 dark:text-gray-400 block text-xs">Utilizador Conectado</small>
                    <strong class="text-gray-800 dark:text-white text-sm">{{ Auth::user()->name }} ({{ Auth::user()->role }})</strong>
                </div>
                <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded-xl">
                    <small class="text-gray-500 dark:text-gray-400 block text-xs">Estabelecimento</small>
                    <strong class="text-gray-800 dark:text-white text-sm">{{ \App\Models\Setting::get('company_name', 'Zalala Beach Bar') }}</strong>
                </div>
                <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded-xl flex items-center justify-between">
                    <div>
                        <small class="text-gray-500 dark:text-gray-400 block text-xs">Status do Servidor</small>
                        <strong class="text-emerald-500 text-sm">Operacional</strong>
                    </div>
                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
            </div>
        `,
        icon: 'info',
        confirmButtonColor: '#FFA500',
        confirmButtonText: 'Fechar',
        customClass: {
            popup: 'rounded-2xl'
        }
    });
}
</script>