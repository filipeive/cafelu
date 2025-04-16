<nav class="navbar navbar-dark bg-dark default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <!-- Logo e Toggle -->
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start" style="z-index: 0;">
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
        <div>
            {{-- <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Restaurant Logo" class="img-fluid"
                    style="max-height: 40px;">
            </a> --}}
            <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/Logo.png') }}" alt="Mini Logo" class="img-fluid"
                    style="max-height: 30px;">
            </a>
        </div>
    </div>

    <!-- Conteúdo Central -->
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <!-- Saudação e Breadcrumbs -->
        <ul class="navbar-nav">
            <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                <div class="d-flex flex-column">
                    <h1 class="welcome-text text-white">Bem-vindo, <span
                            class="text-warning fw-bold">{{ Auth::user()->name }}</span></h1>
                   {{--  <nav aria-label="breadcrumb" class="welcome-sub-text">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('page-title', 'Página Atual')</li>
                        </ol>
                    </nav> --}}
                </div>
            </li>
        </ul>

        <!-- Itens do Lado Direito -->
        <ul class="navbar-nav ms-auto">
            <!-- Quick Actions -->
            <li class="nav-item dropdown mx-1">
                <a class="nav-link btn btn-sm btn-outline-light rounded-pill px-3" id="quickActions" href="#"
                    data-bs-toggle="dropdown">
                    <i class="mdi mdi-plus-circle-outline me-1"></i> Ações Rápidas
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown py-2" aria-labelledby="quickActions">
                    <a class="dropdown-item" href="{{ route('orders.index') }}">
                        <i class="mdi mdi-cart-plus text-success me-2"></i> Pedidos
                    </a>
                    <a class="dropdown-item" href="{{ route('tables.index') }}">
                        <i class="mdi mdi-calendar-plus text-info me-2"></i> Nova Reserva
                    </a>
                    <a class="dropdown-item" href="{{ route('products.create') }}">
                        <i class="mdi mdi-food-variant text-warning me-2"></i> Adicionar Produto
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{-- route('reports.index') --}}">
                        <i class="mdi mdi-chart-bar text-primary me-2"></i> Gerar Relatório
                    </a>
                </div>
            </li>

            <!-- Notificações -->
            <li class="nav-item dropdown mx-1">
                <a class="nav-link count-indicator position-relative text-white" id="notificationDropdown" href="#"
                    data-bs-toggle="dropdown">
                    <i class="mdi mdi-bell-outline"></i>
                    <span class="count bg-danger">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0"
                    aria-labelledby="notificationDropdown" style="min-width: 300px;">
                    <div class="dropdown-header bg-primary text-white py-3">
                        <h6 class="mb-0">Notificações</h6>
                    </div>
                    <a class="dropdown-item preview-item py-3">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-warning rounded-circle">
                                <i class="mdi mdi-alert-circle-outline"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject fw-normal text-dark mb-1">Estoque Baixo</h6>
                            <p class="fw-light small-text mb-0">5 produtos com estoque crítico</p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item py-3">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-info rounded-circle">
                                <i class="mdi mdi-cart-outline"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject fw-normal text-dark mb-1">Novo Pedido</h6>
                            <p class="fw-light small-text mb-0">Mesa #12 fez um pedido</p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item py-3">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-success rounded-circle">
                                <i class="mdi mdi-calendar-check"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject fw-normal text-dark mb-1">Reserva Confirmada</h6>
                            <p class="fw-light small-text mb-0">Reserva para 8 pessoas às 19:30</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{-- route('notifications.index') --}}" class="dropdown-item text-center py-2">
                        <div class="d-inline-block rounded-circle border border-primary text-primary px-3 py-1">
                            Ver Todas
                        </div>
                    </a>
                </div>
            </li>

            <!-- Perfil do Usuário -->
            <li class="nav-item dropdown user-dropdown ms-2">
                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <div class="me-2">
                            @if (Auth::user()->profile_photo_path)
                                <img class="img-xs rounded-circle"
                                    src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Profile image">
                            @else
                                <div class="avatar-md mx-auto mb-2">
                                    <i class="mdi mdi-account-circle text-warning fs-2"></i>
                                </div>
                            @endif
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="fw-semibold text-white">{{ Auth::user()->name }}</span>
                            <small class="d-block text-white" style="color: #fff">{{ Auth::user()->role ?? 'Usuário' }}</small>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown py-2" aria-labelledby="UserDropdown"
                    style="min-width: 250px;">
                    <div class="dropdown-header text-center p-3">
                        @if (Auth::user()->profile_photo_path)
                            <img class="img-md rounded-circle mb-2"
                                src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Profile image">
                        @else
                            <div class="avatar-md mx-auto mb-2">
                                <i class="mdi mdi-account-circle text-warning fs-2"></i>
                            </div>
                        @endif
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </div>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="mdi mdi-account-edit-outline text-primary me-2"></i> Editar Perfil
                    </a>
                    <a class="dropdown-item" href="{{-- route('settings.index') --}}">
                        <i class="mdi mdi-cog-outline text-primary me-2"></i> Configurações
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="mdi mdi-logout-variant text-danger me-2"></i> Sair
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>

        <!-- Toggle Mobile -->
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
