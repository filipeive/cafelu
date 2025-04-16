<nav class="sidebar sidebar-offcanvas bg-dark" id="sidebar" style="margin-top: -100px; z-index: 1098;">
    <!-- Logo e Nome do Sistema -->
   <div class="sidebar-brand-wrapper d-flex align-items-center justify-content-center py-4">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('assets/images/Logo.png') }}" alt="Restaurant Logo" style="height: 40px;">
        </div>
        <a href="{{ route('dashboard') }}" class="sidebar-brand-text ml-3 d-none d-lg-block text-decoration-none">
            <span class="font-weight-bold text-white" style="font-size: 1.2rem;">CaféLufamina</span>
            <span class="text-warning" style="font-size: 1.2rem;">POS</span>
        </a>
    </div> 

    <!-- Menu de Navegação -->
    <ul class="nav flex-column">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="mdi mdi-view-dashboard-outline nav-icon"></i>
                <span class="nav-title">Dashboard</span>
                <span class="nav-badge badge badge-light">Home</span>
            </a>
        </li>
        
        <!-- Seção Operacional -->
        <li class="nav-section">
            <span class="nav-section-title">OPERACIONAL</span>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                <i class="mdi mdi-point-of-sale nav-icon"></i>
                <span class="nav-title">PDV</span>
                <span class="nav-badge badge badge-success">Novo</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                <i class="mdi mdi-cart-outline nav-icon"></i>
                <span class="nav-title">Pedidos</span>
                @php 
                    $pendingOrdersCount = \App\Models\Order::where('status', 'active')->count();
                @endphp
                @if($pendingOrdersCount > 0)
                    <span class="nav-badge badge badge-danger">{{ $pendingOrdersCount }}</span>
                @endif
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}" href="#">
                <i class="mdi mdi-calendar-clock nav-icon"></i>
                <span class="nav-title">Reservas</span>
                @if(($todayReservationsCount ?? 0) > 0)
                    <span class="nav-badge badge badge-warning">{{ $todayReservationsCount }}</span>
                @endif
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('tables.*') ? 'active' : '' }}" href="{{ route('tables.index') }}">
                <i class="mdi mdi-table-furniture nav-icon"></i>
                <span class="nav-title">Mesas</span>
                @php
                    $tablesAvailable = \App\Models\Table::where('status', 'free')->count();
                @endphp
                <span class="nav-badge">
                    <span class="text-{{ $tablesAvailable > 0 ? 'success' : 'danger' }}">
                        {{ $tablesAvailable }} {{ $tablesAvailable == 1 ? 'disponível' : 'disponíveis' }}
                    </span>
                </span>
            </a>
        </li>
        
        <!-- Seção Cardápio -->
        <li class="nav-section">
            <span class="nav-section-title">CARDÁPIO</span>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <i class="mdi mdi-food nav-icon"></i>
                <span class="nav-title">Produtos</span>
                @if(($lowStockProductsCount ?? 0) > 0)
                    <span class="nav-badge badge badge-danger">{{ $lowStockProductsCount }}</span>
                @endif
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                <i class="mdi mdi-food-variant nav-icon"></i>
                <span class="nav-title">Categorias</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('menus.*') ? 'active' : '' }}" href="#">
                <i class="mdi mdi-book-open-variant nav-icon"></i>
                <span class="nav-title">Cardápios</span>
            </a>
        </li>
        
        <!-- Seção Financeiro -->
        <li class="nav-section">
            <span class="nav-section-title">FINANCEIRO</span>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                <i class="mdi mdi-cash-multiple nav-icon"></i>
                <span class="nav-title">Vendas</span>
                <span class="nav-badge badge badge-primary">Hoje: {{($todaySales ?? 0) }}</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="#">
                <i class="mdi mdi-cash-remove nav-icon"></i>
                <span class="nav-title">Despesas</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="#">
                <i class="mdi mdi-chart-bar nav-icon"></i>
                <span class="nav-title">Relatórios</span>
            </a>
        </li>
        
        <!-- Seção Pessoas -->
        <li class="nav-section">
            <span class="nav-section-title">PESSOAS</span>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}" href="{{ route('clients.index') }}">
                <i class="mdi mdi-account-group nav-icon"></i>
                <span class="nav-title">Clientes</span>
                @if(($newClientsCount ?? 0) > 0)
                    <span class="nav-badge badge badge-info">+{{ $newClientsCount }}</span>
                @endif
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                <i class="mdi mdi-account-tie nav-icon"></i>
                <span class="nav-title">Funcionários</span>
            </a>
        </li>
        
        @if(Auth::user()->role == 'admin')
        <!-- Seção Administração -->
        <li class="nav-section">
            <span class="nav-section-title">ADMINISTRAÇÃO</span>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="mdi mdi-account-key nav-icon"></i>
                <span class="nav-title">Usuários</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="#">
                <i class="mdi mdi-cog-outline nav-icon"></i>
                <span class="nav-title">Configurações</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('backup.*') ? 'active' : '' }}" href="#">
                <i class="mdi mdi-cloud-upload nav-icon"></i>
                <span class="nav-title">Backup</span>
            </a>
        </li>
        @endif
        
        <!-- Seção Suporte -->
        <li class="nav-section">
            <span class="nav-section-title">SUPORTE</span>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('help.*') ? 'active' : '' }}" href="#">
                <i class="mdi mdi-help-circle nav-icon"></i>
                <span class="nav-title">Ajuda</span>
            </a>
        </li>
    </ul>
    
    <!-- Rodapé do Sidebar -->
    <div class="sidebar-footer py-3 px-4">
        <{{-- div class="d-flex align-items-center">
            <div class="user-avatar">
                <img src="{{ Auth::user()->avatar_url ?? asset('assets/images/faces/face8.jpg') }}" 
                     alt="User Avatar" class="rounded-circle" width="40">
            </div>
            <div class="user-info ml-2">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <small class="user-role text-muted text-capitalize">{{ Auth::user()->role }}</small>
            </div>
        </div> --}}
        <div class="system-info mt-2 text-center">
            <small class="text-muted d-block">v1.0.0</small>
            <small class="text-muted">© {{ date('Y') }} Restaurant Pro</small>
        </div>
    </div>
</nav>

<style>
    .sidebar {
        background: #ffffff;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
    }
    
    .sidebar-brand-wrapper {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 10px;
    }
    
    .sidebar-brand-text {
        font-weight: 600;
        color: #2c3e50;
    }
    
    .nav-section {
        padding: 10px 30px;
        margin-top: 10px;
    }
    
    .nav-section-title {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        color: #7f8c8d;
    }
    
    .nav-item {
        margin-bottom: 2px;
    }
    
    .nav-link {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: #34495e;
        transition: all 0.2s;
        border-radius: 4px;
        margin: 0 10px;
    }
    
    .nav-link:hover {
        color: #e67e22;
        background-color: rgba(230, 126, 34, 0.1);
    }
    
    .nav-link.active {
        background-color: rgba(230, 126, 34, 0.15);
        color: #e67e22;
        font-weight: 500;
    }
    
    .nav-link.active .nav-icon {
        color: #e67e22;
    }
    
    .nav-icon {
        margin-right: 12px;
        font-size: 1.2rem;
        color: #7f8c8d;
        width: 24px;
        text-align: center;
    }
    
    .nav-title {
        flex-grow: 1;
        font-size: 0.9rem;
    }
    
    .nav-badge {
        font-size: 0.7rem;
        padding: 3px 6px;
        border-radius: 10px;
        font-weight: 500;
    }
    
    .sidebar-footer {
        margin-top: auto;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding-top: 15px;
    }
    
    .user-avatar img {
        border: 2px solid rgba(230, 126, 34, 0.3);
    }
    
    .user-name {
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .user-role {
        font-size: 0.7rem;
    }
    
    .system-info {
        font-size: 0.7rem;
    }
</style>