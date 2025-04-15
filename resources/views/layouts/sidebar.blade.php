<nav class="sidebar sidebar-offcanvas" id="sidebar">
    {{-- <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('assets/images/Logo.png') }}" alt="Restaurant Logo" style="max-height: 35px;">
        </div>
        <div class="sidebar-brand-text mx-3">Restaurant Pro</div>
    </div>
     --}}
    <ul class="nav">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="nav-icon">
                    <i class="mdi mdi-view-dashboard-outline"></i>
                </span>
                <span class="nav-title">Dashboard</span>
                <span class="nav-badge badge bg-primary-light">Home</span>
            </a>
        </li>
        
        <!-- Operações -->
        <li class="nav-section">
            <span class="nav-section-title">OPERACIONAL</span>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{ route('pos.index') }}">
                <span class="nav-icon">
                    <i class="mdi mdi-point-of-sale"></i>
                </span>
                <span class="nav-title">PDV</span>
                <span class="nav-badge badge bg-success-light">Novo</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{ route('orders.index') }}">
                <span class="nav-icon">
                    <i class="mdi mdi-cart-outline"></i>
                </span>
                <span class="nav-title">Pedidos</span>
                @php 
                    $pendingOrdersCount = \App\Models\Order::where('status', 'active')->count();
                @endphp
                <span class="nav-badge badge bg-danger">{{ $pendingOrdersCount ?? 0 }}</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{-- route('reservations.index') --}}">
                <span class="nav-icon">
                    <i class="mdi mdi-calendar-clock"></i>
                </span>
                <span class="nav-title">Reservas</span>
                <span class="nav-badge badge bg-warning-light">{{ $todayReservationsCount ?? 0 }}</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{ route('tables.index') }}">
                <span class="nav-icon">
                    <i class="mdi mdi-table-furniture"></i>
                </span>
                <span class="nav-title">Mesas</span>
                <span class="nav-badge">
                    {{-- aplique isso Table::where('status', 'free')->count(); --}}
                    @php
                        $tablesAvailable = \App\Models\Table::where('status', 'free')->count() > 0;
                    @endphp
                    <i class="mdi mdi-circle text-{{ $tablesAvailable ? 'success' : 'danger' }}"></i>
                </span>
            </a>
        </li>
        
        <!-- Cardápio -->
        <li class="nav-section">
            <span class="nav-section-title">CARDÁPIO</span>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{ route('products.index') }}">
                <span class="nav-icon">
                    <i class="mdi mdi-food"></i>
                </span>
                <span class="nav-title">Produtos</span>
                <span class="nav-badge badge bg-danger-light">{{ $lowStockProductsCount ?? 0 }}</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{ route('categories.index') }}">
                <span class="nav-icon">
                    <i class="mdi mdi-food-variant"></i>
                </span>
                <span class="nav-title">Categorias</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{-- route('menus.index') --}}">
                <span class="nav-icon">
                    <i class="mdi mdi-book-open-variant"></i>
                </span>
                <span class="nav-title">Cardápios</span>
            </a>
        </li>
        
        <!-- Financeiro -->
        <li class="nav-section">
            <span class="nav-section-title">FINANCEIRO</span>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{ route('sales.index') }}">
                <span class="nav-icon">
                    <i class="mdi mdi-cash-multiple"></i>
                </span>
                <span class="nav-title">Vendas</span>
                <span class="nav-badge badge bg-primary-light">Hoje: MZN {{ $todaySales ?? '0,00' }}</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{-- route('expenses.index') --}}">
                <span class="nav-icon">
                    <i class="mdi mdi-cash-remove"></i>
                </span>
                <span class="nav-title">Despesas</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{-- route('reports.index') --}}">
                <span class="nav-icon">
                    <i class="mdi mdi-chart-bar"></i>
                </span>
                <span class="nav-title">Relatórios</span>
            </a>
        </li>
        
        <!-- Pessoas -->
        <li class="nav-section">
            <span class="nav-section-title">PESSOAS</span>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{ route('clients.index') }}">
                <span class="nav-icon">
                    <i class="mdi mdi-account-group"></i>
                </span>
                <span class="nav-title">Clientes</span>
                <span class="nav-badge badge bg-info-light">{{ $newClientsCount ?? 0 }}</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{ route('employees.index') }}">
                <span class="nav-icon">
                    <i class="mdi mdi-account-tie"></i>
                </span>
                <span class="nav-title">Funcionários</span>
            </a>
        </li>
        
        @if(Auth::user()->role == 'admin')
        <!-- Administração -->
        <li class="nav-section">
            <span class="nav-section-title">ADMINISTRAÇÃO</span>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.index') }}">
                <span class="nav-icon">
                    <i class="mdi mdi-account-key"></i>
                </span>
                <span class="nav-title">Usuários</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{-- route('settings.index') --}}">
                <span class="nav-icon">
                    <i class="mdi mdi-cog-outline"></i>
                </span>
                <span class="nav-title">Configurações</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{-- route('backup.index') --}}">
                <span class="nav-icon">
                    <i class="mdi mdi-cloud-upload"></i>
                </span>
                <span class="nav-title">Backup</span>
            </a>
        </li>
        @endif
        
        <!-- Suporte -->
        <li class="nav-section">
            <span class="nav-section-title">SUPORTE</span>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="{{-- route('help.index') --}}">
                <span class="nav-icon">
                    <i class="mdi mdi-help-circle"></i>
                </span>
                <span class="nav-title">Ajuda</span>
            </a>
        </li>
    </ul>
    
    <div class="sidebar-footer d-flex align-items-center justify-content-center">
        <div class="sidebar-footer-content">
            <p class="mb-1">Restaurant Pro v1.0</p>
            <small class="text-muted">© {{ date('Y') }} Todos os direitos reservados</small>
        </div>
    </div>
</nav>

<style>
   /*  .sidebar {
        background: linear-gradient(180deg, #2a3042 0%, #1a1f2e 100%);
    }
    
    .sidebar-brand-wrapper {
        padding: 20px 0;
        background: rgba(255,255,255,0.05);
    } */
    
    .sidebar-brand-text {
        font-weight: 600;
        font-size: 1.2rem;
        color: #000000;
    }
    
    .nav-section {
        padding: 10px 25px;
        margin-top: 15px;
    }
    
    .nav-section-title {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(0, 0, 0, 0.5);
    }
    
    .nav-item {
        margin-bottom: 2px;
    }
    
    .nav-link {
        display: flex;
        align-items: center;
        padding: 12px 25px;
        color: rgba(255, 163, 76, 0.8);
        transition: all 0.3s;
    }
    
    .nav-link:hover {
        color: #c95824;
        background: rgba(255,255,255,0.1); 
    }
    
    .nav-link.active {
        background: rgba(41, 121, 255, 0.2);
        color: #ff7b00;
        border-left: 3px solid #2979ff;
    }
    
    .nav-icon {
        margin-right: 15px;
        font-size: 1.2rem;
        min-width: 25px;
        display: flex;
        align-items: center;
    }
    
    .nav-title {
        flex-grow: 1;
    }
    
    .nav-badge {
        font-size: 0.7rem;
        padding: 3px 6px;
        border-radius: 10px;
    }
    
    .sidebar-footer {
        padding: 20px;
        border-top: 1px solid rgba(255,255,255,0.1);
        margin-top: auto;
    }
    
    .sidebar-footer-content {
        text-align: center;
        color: rgba(255,255,255,0.6);
        font-size: 0.8rem;
    }
</style>