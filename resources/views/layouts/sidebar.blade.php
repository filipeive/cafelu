<div class="sidebar bg-dark sidebar-offcanvas" id="sidebar">
    <!-- Logo e nome do restaurante -->
    {{-- <div class="sidebar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="logo-img">
        <span class="sidebar-brand-text">{{ config('app.company', 'Café Lufamina') }}</span>
    </div> --}}

    <!-- Loader para o sistema (será mostrado no carregamento inicial) -->
    <div id="system-loader" class="system-loader">
        <div class="spinner-border text-warning" role="status">
            <span class="visually-hidden">Carregando...</span>
        </div>
    </div>

    <!-- Menu de Navegação -->
    <ul class="nav flex-column main-nav">
        <!-- Dashboard -->
        <x-sidebar.nav-item route="home" icon="mdi-home" title="Início" badge="Novo" badgeClass="badge-success" />
        <x-sidebar.nav-item route="dashboard" icon="mdi-view-dashboard" title="Dashboard" badge="Home"
            badgeClass="badge-light" />

        <!-- Seção Operacional -->
        <x-sidebar.section title="GERENCIAMENTO" />

        <x-sidebar.dropdown icon="mdi-store" title="Operacional" id="operational-menu"
            badge="{{ \App\Models\Order::where('status', 'active')->count() > 0 ? \App\Models\Order::where('status', 'active')->count() : null }}"
            badgeClass="badge-danger">
            <x-sidebar.dropdown-item route="pos.index" icon="mdi-point-of-sale" title="PDV" badge="Novo"
                badgeClass="badge-success" />

            <x-sidebar.dropdown-item route="orders.index" icon="mdi-cart" title="Pedidos" :badge="$pendingOrdersCount = \App\Models\Order::where('status', 'active')->count()"
                badgeClass="badge-danger" :showBadge="$pendingOrdersCount > 0" />

            @if (Auth::user()->role == 'admin')
                <x-sidebar.dropdown-item route="reservations.index" icon="mdi-calendar-clock" title="Reservas"
                    :badge="$todayReservationsCount ?? 0" badgeClass="badge-warning" :showBadge="($todayReservationsCount ?? 0) > 0" />
            @endif

            @php
                $tablesAvailable = \App\Models\Table::where('status', 'free')->count();
                $totalTables = \App\Models\Table::count();
            @endphp

            <x-sidebar.dropdown-item route="tables.index" icon="mdi-table-furniture" title="Mesas">
                <span class="badge {{ $tablesAvailable > 0 ? 'badge-success' : 'badge-danger' }}">
                    {{ $tablesAvailable }}/{{ $totalTables }}
                </span>
            </x-sidebar.dropdown-item>

            <x-sidebar.dropdown-item route="kitchen.index" icon="mdi-chef-hat" title="Cozinha" />
        </x-sidebar.dropdown>

        <!-- Seção Cardápio -->
        <x-sidebar.dropdown icon="mdi-food-variant" title="Cardápio" id="menu-items">
            <x-sidebar.dropdown-item route="products.index" icon="mdi-food" title="Produtos" :badge="$lowStockProductsCount ?? 0"
                badgeClass="badge-danger" :showBadge="($lowStockProductsCount ?? 0) > 0" />

            <x-sidebar.dropdown-item route="categories.index" icon="mdi-shape" title="Categorias" />

            @if (Auth::user()->role == 'admin')
                <x-sidebar.dropdown-item route="menus.index" icon="mdi-book-open-variant" title="Cardápios" />
                <x-sidebar.dropdown-item route="ingredients.index" icon="mdi-flask" title="Ingredientes" />
            @endif
        </x-sidebar.dropdown>

        <!-- Seção Financeiro -->
        <x-sidebar.dropdown icon="mdi-currency-usd" title="Financeiro" id="financial-menu">
            @php
                $todaySales = \App\Models\Sale::whereDate('created_at', now()->toDateString())->sum('total_amount');
                $formattedSales = number_format($todaySales, 2, ',', '.');
            @endphp

            <x-sidebar.dropdown-item route="sales.index" icon="mdi-cash-multiple" title="Vendas">
                <span class="badge badge-success">MZN {{ $formattedSales }}</span>
            </x-sidebar.dropdown-item>

            @if (Auth::user()->role == 'admin')
                <x-sidebar.dropdown-item route="expenses.index" icon="mdi-cash-remove" title="Despesas" />
                <x-sidebar.dropdown-item route="cashier.index" icon="mdi-cash-register" title="Caixa" />
                <x-sidebar.dropdown-item route="reports.index" icon="mdi-chart-bar" title="Relatórios" />
            @endif
        </x-sidebar.dropdown>

        <!-- Seção Pessoas -->
        <x-sidebar.section title="RELACIONAMENTOS" />

        <x-sidebar.dropdown icon="mdi-account-group" title="Pessoas" id="people-menu">
            <x-sidebar.dropdown-item route="clients.index" icon="mdi-account-multiple" title="Clientes"
                :badge="$newClientsCount ?? 0" badgePrefix="+" badgeClass="badge-info" :showBadge="($newClientsCount ?? 0) > 0" />

            @if (Auth::user()->role == 'admin')
                <x-sidebar.dropdown-item route="employees.index" icon="mdi-account-tie" title="Funcionários" />
                <x-sidebar.dropdown-item route="suppliers.index" icon="mdi-truck" title="Fornecedores" />
            @endif
        </x-sidebar.dropdown>

        <!-- Seção Marketing -->
        @if (Auth::user()->role == 'admin')
            <x-sidebar.dropdown icon="mdi-bullhorn" title="Marketing" id="marketing-menu">
                <x-sidebar.dropdown-item route="promotions.index" icon="mdi-tag" title="Promoções" />
                <x-sidebar.dropdown-item route="coupons.index" icon="mdi-ticket-percent" title="Cupons" />
                <x-sidebar.dropdown-item route="loyalty.index" icon="mdi-star" title="Fidelidade" />
            </x-sidebar.dropdown>
        @endif

        @if (Auth::user()->role == 'admin')
            <!-- Seção Administração -->
            <x-sidebar.section title="CONFIGURAÇÕES" />

            <x-sidebar.dropdown icon="mdi-shield-account" title="Administração" id="admin-menu">
                <x-sidebar.dropdown-item route="users.index" icon="mdi-account-key" title="Usuários" />
                <x-sidebar.dropdown-item route="settings.index" icon="mdi-cog" title="Configurações" />
                <x-sidebar.dropdown-item route="backups.index" icon="mdi-cloud-upload" title="Backup" />
                <x-sidebar.dropdown-item route="logs.index" icon="mdi-file-document" title="Logs" />
            </x-sidebar.dropdown>

            <x-sidebar.dropdown icon="mdi-application-cog" title="Sistema" id="system-menu">
                <x-sidebar.dropdown-item route="permissions.index" icon="mdi-account-lock" title="Permissões" />
                <x-sidebar.dropdown-item route="integrations.index" icon="mdi-puzzle" title="Integrações" />
                <x-sidebar.dropdown-item route="themes.index" icon="mdi-palette" title="Temas" />
            </x-sidebar.dropdown>
        @endif

        <!-- Seção Suporte -->
        <x-sidebar.section title="AJUDA & SUPORTE" />

        <x-sidebar.dropdown icon="mdi-help-circle" title="Suporte" id="support-menu">
            <x-sidebar.dropdown-item route="help.index" icon="mdi-help" title="Ajuda" />
            <x-sidebar.dropdown-item route="tickets.index" icon="mdi-ticket" title="Tickets" :badge="$openTicketsCount ?? 0"
                badgeClass="badge-warning" :showBadge="($openTicketsCount ?? 0) > 0" />
            <x-sidebar.dropdown-item external="true" route="https://docs.cafelufamina.com" icon="mdi-book-open"
                title="Documentação" />
        </x-sidebar.dropdown>
    </ul>

    <!-- Controles da Sidebar -->
    <div class="sidebar-controls">
        <button type="button" class="btn btn-icon" onclick="toggleSidebarCollapse()" id="sidebar-toggle"
            title="Minimizar menu">
            <i class="mdi mdi-chevron-left"></i>
        </button>
        <button type="button" class="btn btn-icon" onclick="toggleDarkMode()" id="dark-mode-toggle"
            title="Modo escuro">
            <i class="mdi mdi-weather-night"></i>
        </button>
    </div>

    <!-- Rodapé do Sidebar -->
    <div class="sidebar-footer" style="width: 11.5%;">
        <div class="system-info">
            <span class="text-warning d-block fw-bold">v1.0.0</span>
            <small class="text-warning">&copy; {{ date('Y') }} {{ config('app.company', 'Café Lufamina') }}</small>
        </div>

        <!-- Status do sistema -->
        <div class="system-status mt-2">
            <small class="text-success">
                <i class="mdi mdi-circle-small"></i> Sistema online
            </small>
        </div>
    </div>
</div>
