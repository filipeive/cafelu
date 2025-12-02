<nav class="navbar navbar-dark bg-dark default-layout col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <!-- Logo e Toggle -->
    <div class="text-center bg-dark navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <!-- Logo e Nome do Sistema -->
        <div class="sidebar-brand-wrapper d-flex align-items-center justify-content-center py-3">
            <div class="sidebar-brand-icon">
                <img src="{{ asset('assets/images/Logo.png') }}" alt="Logo" class="sidebar-brand-image"
                    style="height: 40px;">
            </div>
            <a href="{{ route('dashboard') }}" class="sidebar-brand-text ml-3 d-none d-lg-block text-decoration-none">
                <span class="font-weight-bold text-white">ZALALA BB</span> <br>
                <span class="text-warning">POS</span>
            </a>
        </div>
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
        <div>
            <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/Logo.png') }}" alt="Logo" class="img-fluid"
                    style="max-height: 30px;">
            </a>
        </div>
    </div>

    <!-- Conteúdo Central -->
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <!-- Saudação e Data/Hora -->
        <ul class="navbar-nav">
            <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                <div class="d-flex flex-column">
                    <h1 class="welcome-text text-white mb-0">Bem-vindo, <span
                            class="text-warning fw-bold">{{ Auth::user()->name }}</span></h1>
                    <div id="dateTime" class="text-light small"></div>
                </div>
            </li>
        </ul>

        <!-- Itens do Lado Direito -->
        <ul class="navbar-nav ms-auto">
            <!-- Ações Rápidas -->
            <li class="nav-item dropdown mx-2">
                <a class="nav-link btn btn-sm btn-outline-light rounded-pill px-3 text-warning" href="#"
                    data-bs-toggle="dropdown">
                    <i class="mdi mdi-plus-circle-outline me-1"></i> Ações Rápidas
                </a>
                <div class="dropdown-menu dropdown-menu-end navbar-dropdown py-2">
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
                    <a class="dropdown-item" href="#">
                        <i class="mdi mdi-chart-bar text-primary me-2"></i> Gerar Relatório
                    </a>
                </div>
            </li>

            <!-- Botão Tela Cheia -->
            <li class="nav-item mx-2">
                <button id="fullscreenBtn" class="btn btn-icon btn-outline-light rounded-circle p-2 text-warning"
                    title="Ative o modo tela cheia">
                    <i class="mdi mdi-fullscreen fs-5"></i>
                </button>
            </li>

            <!-- Notificações -->
            <li class="nav-item dropdown mx-2">
                <a class="nav-link count-indicator position-relative text-white" href="#"
                    data-bs-toggle="dropdown">
                    <i class="mdi mdi-bell-outline"></i>
                    <span class="count bg-danger"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list" style="min-width: 300px;">
                    <div
                        class="dropdown-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Notificações</h6>
                        <span class="badge bg-light text-dark rounded-pill">3</span>
                    </div>
                    <div class="notification-scroll">
                        <a class="dropdown-item preview-item py-2">
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
                        <a class="dropdown-item preview-item py-2">
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
                        <a class="dropdown-item preview-item py-2">
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
                    </div>
                    <div class="dropdown-divider m-0"></div>
                    <a href="#" class="dropdown-item text-center py-2">
                        <span class="text-primary">Ver Todas</span>
                    </a>
                </div>
            </li>

            <!-- Perfil do Usuário -->
            <li class="nav-item dropdown user-dropdown ms-2">
                <a class="nav-link" href="#" data-bs-toggle="dropdown">
                    <div class="d-flex align-items-center">
                        @if (Auth::user()->profile_photo_path)
                            <img class="img-xs rounded-circle"
                                src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Perfil">
                        @else
                            <div
                                class="avatar-xs rounded-circle bg-dark d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-account-circle text-warning"></i>
                            </div>
                        @endif
                        <div class="d-none d-lg-block ms-2">
                            <span class="fw-semibold text-white">{{ Auth::user()->name }}</span>
                            <small class="d-block text-white-50">{{ Auth::user()->role ?? 'Usuário' }}</small>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end navbar-dropdown py-0" style="min-width: 250px;">
                    <div class="dropdown-header text-center bg-light p-3">
                        @if (Auth::user()->profile_photo_path)
                            <img class="img-md rounded-circle mb-2"
                                src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Perfil">
                        @else
                            <div
                                class="avatar-md mx-auto mb-2 bg-light d-flex align-items-center justify-content-center rounded-circle">
                                <i class="mdi mdi-account-circle text-warning fs-2"></i>
                            </div>
                        @endif
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </div>
                    <div class="py-2">
                        <a class="dropdown-item" href="{{ route('profile') }}">
                            <i class="mdi mdi-account-edit-outline text-primary me-2"></i> Editar Perfil
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="mdi mdi-cog-outline text-primary me-2"></i> Configurações
                        </a>
                        <div class="dropdown-divider m-0"></div>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout-variant text-danger me-2"></i> Sair
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
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

<!-- Script para mostrar data e hora atual -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('dateTime').textContent = now.toLocaleDateString('pt-BR', options);
        }

        // Atualiza imediatamente e depois a cada segundo
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Funcionalidade Fullscreen
        document.getElementById('fullscreenBtn').addEventListener('click', function() {
            if (!document.fullscreenElement) {
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen();
                } else if (document.documentElement.msRequestFullscreen) {
                    document.documentElement.msRequestFullscreen();
                }
                this.innerHTML = '<i class="mdi mdi-fullscreen-exit fs-5"></i>';
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
                this.innerHTML = '<i class="mdi mdi-fullscreen fs-5"></i>';
            }
        });
    });
</script>
