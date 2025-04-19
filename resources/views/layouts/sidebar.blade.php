<div class="sidebar sidebar-offcanvas bg-dark" id="sidebar">
    <!-- Menu de Navegação -->
    <ul class="nav flex-column">
        <!-- Dashboard -->
        <!-- Adicione o ícone e o título aqui -->
        {{-- home.index --}}
        <x-sidebar.nav-item route="home" icon="mdi-home" title="Início" badge="Novo" badgeClass="badge-success" />
        <x-sidebar.nav-item route="dashboard" icon="mdi-view-dashboard-outline" title="Dashboard" badge="Home"
            badgeClass="badge-light" />

        <!-- Seção Operacional -->
        <x-sidebar.dropdown icon="mdi-store" title="OPERACIONAL" id="operational-menu">
            <x-sidebar.dropdown-item route="pos.index" icon="mdi-point-of-sale" title="PDV" badge="Novo"
                badgeClass="badge-success" />

            <x-sidebar.dropdown-item route="orders.index" icon="mdi-cart-outline" title="Pedidos" :badge="$pendingOrdersCount = \App\Models\Order::where('status', 'active')->count()"
                badgeClass="badge-danger" :showBadge="$pendingOrdersCount > 0" />
            @if (Auth::user()->role == 'admin')
                <x-sidebar.dropdown-item route="#" icon="mdi-calendar-clock" title="Reservas" :badge="$todayReservationsCount ?? 0"
                    badgeClass="badge-warning" :showBadge="($todayReservationsCount ?? 0) > 0" />
            @endif
            @php
                $tablesAvailable = \App\Models\Table::where('status', 'free')->count();
            @endphp
            <x-sidebar.dropdown-item route="tables.index" icon="mdi-table-furniture" title="Mesas">
                <span class="text-{{ $tablesAvailable > 0 ? 'success' : 'danger' }}">
                    {{ $tablesAvailable }} {{ $tablesAvailable == 1 ? 'disponível' : 'disponíveis' }}
                </span>
            </x-sidebar.dropdown-item>
        </x-sidebar.dropdown>

        <!-- Seção Cardápio -->
        <x-sidebar.dropdown icon="mdi-food-variant" title="MENU" id="menu-items">
            <x-sidebar.dropdown-item route="products.index" icon="mdi-food" title="Produtos" :badge="$lowStockProductsCount ?? 0"
                badgeClass="badge-danger" :showBadge="($lowStockProductsCount ?? 0) > 0" />
            <x-sidebar.dropdown-item route="categories.index" icon="mdi-food-variant" title="Categorias" />
            @if (Auth::user()->role == 'admin')
                <x-sidebar.dropdown-item route="#" icon="mdi-book-open-variant" title="Cardápios" />
            @endif
        </x-sidebar.dropdown>

        <!-- Seção Financeiro -->
        <x-sidebar.dropdown icon="mdi-currency-usd" title="FINANCEIRO" id="financial-menu">
            @php
                $todaySales = \App\Models\Sale::whereDate('created_at', now()->toDateString())->sum('total_amount');
            @endphp
            <x-sidebar.dropdown-item route="sales.index" icon="mdi-cash-multiple" title="Vendas">
                <span class="badge badge-primary">Hoje: {{ $todaySales ?? 0 }}</span>
            </x-sidebar.dropdown-item>
            @if (Auth::user()->role == 'admin')
                <x-sidebar.dropdown-item route="#" icon="mdi-cash-remove" title="Despesas" />

                <x-sidebar.dropdown-item route="reports.index" icon="mdi-chart-bar" title="Relatórios" />
            @endif
        </x-sidebar.dropdown>

        <!-- Seção Pessoas -->
        <x-sidebar.dropdown icon="mdi-account-group" title="PESSOAS" id="people-menu">
            <x-sidebar.dropdown-item route="clients.index" icon="mdi-account-group" title="Clientes" :badge="$newClientsCount ?? 0"
                badgePrefix="+" badgeClass="badge-info" :showBadge="($newClientsCount ?? 0) > 0" />
            @if (Auth::user()->role == 'admin')
                <x-sidebar.dropdown-item route="employees.index" icon="mdi-account-tie" title="Funcionários" />
            @endif
        </x-sidebar.dropdown>

        @if (Auth::user()->role == 'admin')
            <!-- Seção Administração -->
            <x-sidebar.dropdown icon="mdi-shield-account" title="ADMINISTRAÇÃO" id="admin-menu">
                <x-sidebar.dropdown-item route="users.index" icon="mdi-account-key" title="Usuários" />

                <x-sidebar.dropdown-item route="#" icon="mdi-cog-outline" title="Configurações" />

                <x-sidebar.dropdown-item route="#" icon="mdi-cloud-upload" title="Backup" />
            </x-sidebar.dropdown>
        @endif

        <!-- Seção Suporte -->
        <x-sidebar.dropdown icon="mdi-help-circle-outline" title="SUPORTE" id="support-menu">
            <x-sidebar.dropdown-item route="#" icon="mdi-help-circle" title="Ajuda" />
        </x-sidebar.dropdown>
    </ul>

    <!-- Rodapé do Sidebar -->
    <div class="sidebar-footer py-3 px-4">
        <div class="system-info text-center text-white">
            <small class="text-warning d-block">v1.0.0</small>
            <small class="text-warning"> &copy; {{ date('Y') }} {{ config('app.company', 'Café Lufamina') }}</small>

        </div>
    </div>
</div>
