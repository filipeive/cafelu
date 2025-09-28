<!-- Modern Navbar Component for Restaurant System -->
<!-- Arquivo: resources/views/layouts/navbar.blade.php -->

<nav class="navbar navbar-expand-lg navbar-dark fixed-top"  style="blur(10px); box-shadow: 0 2px 10px rgba(0,0,0,0.1); color: black !important; backdrop-filter: blur(10px);">
    <div class="container-fluid" style="padding: 0.5% 5px;">
        <!-- Brand -->
        <div class="navbar-brand d-flex align-items-center">
            <img src="{{ asset('assets/images/Logo.png') }}" alt="Logo" style="height: 35px;" class="me-2">
            <div class="d-none d-md-block">
                <span class="fw-bold text-white">Café</span>
                <span class="text-warning">Lufamina</span>
                <span class="text-muted small d-block">POS System</span>
            </div>
        </div>
        <button class="btn btn-outline-secondary d-lg-none me-3" type="button" id="sidebar-toggle">
            <i class="mdi mdi-menu"></i>
        </button>

        <div class="navbar-brand mb-0 h1 fw-bold text-primary d-flex align-items-center">
            <i class="mdi @yield('title-icon', 'mdi-home') me-2"></i>
            @yield('page-title', 'Dashboard')
        </div>
        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-expanded="false">
            <i class="mdi mdi-menu text-white"></i>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Center Content -->
            <div class="navbar-nav me-auto">
                <!-- Welcome Message -->
                <div class="nav-item d-none d-lg-flex align-items-center ms-3">
                    <div class="text-white">
                        <div class="fw-semibold">
                            Bem-vindo, <span class="text-warning">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="small text-muted" id="current-datetime">
                            <i class="mdi mdi-clock-outline me-1"></i>
                            <span id="datetime-display"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content -->
            <div class="navbar-nav">
                <!-- Quick Actions Dropdown -->
                <div class="nav-item dropdown me-2">
                    <a class="nav-link dropdown-toggle btn btn-outline-warning btn-sm rounded-pill px-3" href="#"
                        role="button" data-bs-toggle="dropdown">
                        <i class="mdi mdi-lightning-bolt me-1"></i>
                        <span class="d-none d-md-inline">Ações Rápidas</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end animate__animated animate__fadeInUp"
                        style="min-width: 200px;">
                        <li>
                            <h6 class="dropdown-header">
                                <i class="mdi mdi-flash text-warning me-2"></i>
                                Ações Rápidas
                            </h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('pos.index') }}">
                                <i class="mdi mdi-point-of-sale text-success me-2"></i>
                                Abrir PDV
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{-- route('orders.create') --}}">
                                <i class="mdi mdi-cart-plus text-info me-2"></i>
                                Novo Pedido
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{-- route('products.create') --}}">
                                <i class="mdi mdi-food-variant text-warning me-2"></i>
                                Adicionar Produto
                            </a>
                        </li>
                        @if (auth()->user()->role === 'admin')
                            <li>
                                <a class="dropdown-item" href="{{ route('tables.index') }}">
                                    <i class="mdi mdi-table-furniture text-primary me-2"></i>
                                    Gerenciar Mesas
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('reports.index') }}">
                                    <i class="mdi mdi-chart-line text-purple me-2"></i>
                                    Relatórios
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Search -->
                <div class="nav-item me-3 d-none d-md-block">
                    <div class="position-relative">
                        <input type="text" class="form-control form-control-sm rounded-pill ps-4"
                            style="width: 250px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white;"
                            placeholder="Buscar produtos, pedidos..." id="navbar-search">
                        <i class="mdi mdi-magnify position-absolute text-muted"
                            style="left: 12px; top: 50%; transform: translateY(-50%);"></i>

                        <!-- Search Results Dropdown -->
                        <div class="dropdown-menu w-100 mt-1" id="search-results" style="display: none;">
                            <div class="p-2 text-center text-muted">
                                <i class="mdi mdi-magnify"></i>
                                Digite para buscar...
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fullscreen Toggle -->
                <div class="nav-item me-2">
                    <button class="btn btn-outline-light btn-sm rounded-circle" id="fullscreen-toggle"
                        title="Modo Tela Cheia">
                        <i class="mdi mdi-fullscreen"></i>
                    </button>
                </div>

                <!-- System Status -->
                <div class="nav-item me-3 d-none d-lg-block">
                    <div class="d-flex align-items-center">
                        <div class="status-indicator bg-success rounded-circle me-2" style="width: 8px; height: 8px;"
                            title="Sistema Online"></div>
                        <small class="text-muted">Online</small>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="nav-item dropdown me-2">
                    <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="mdi mdi-bell-outline text-white" style="font-size: 1.2rem;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            id="notification-count" style="font-size: 0.7rem; display: none;">
                            0
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end animate__animated animate__fadeInUp"
                        style="min-width: 320px; max-height: 400px; overflow-y: auto;">
                        <div class="dropdown-header d-flex justify-content-between align-items-center">
                            <span>
                                <i class="mdi mdi-bell text-primary me-2"></i>
                                Notificações
                            </span>
                            <button class="btn btn-sm btn-link text-muted p-0" id="mark-all-read">
                                <i class="mdi mdi-check-all"></i>
                            </button>
                        </div>
                        <div class="dropdown-divider"></div>

                        <!-- Sample Notifications -->
                        <div class="notification-item px-3 py-2 border-bottom hover-bg-light">
                            <div class="d-flex">
                                <div class="notification-icon me-3">
                                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;">
                                        <i class="mdi mdi-alert-circle text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">Estoque Baixo</div>
                                    <div class="text-muted small">5 produtos com estoque crítico</div>
                                    <div class="text-muted small">
                                        <i class="mdi mdi-clock-outline me-1"></i>
                                        5 min atrás
                                    </div>
                                </div>
                                <div class="notification-status">
                                    <div class="bg-primary rounded-circle" style="width: 6px; height: 6px;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="notification-item px-3 py-2 border-bottom hover-bg-light">
                            <div class="d-flex">
                                <div class="notification-icon me-3">
                                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;">
                                        <i class="mdi mdi-cart-check text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">Pedido #1234 Finalizado</div>
                                    <div class="text-muted small">Mesa 12 - Total: R$ 85,50</div>
                                    <div class="text-muted small">
                                        <i class="mdi mdi-clock-outline me-1"></i>
                                        12 min atrás
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="notification-item px-3 py-2 hover-bg-light">
                            <div class="d-flex">
                                <div class="notification-icon me-3">
                                    <div class="bg-info rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;">
                                        <i class="mdi mdi-calendar-check text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">Nova Reserva</div>
                                    <div class="text-muted small">Mesa para 6 pessoas às 19:30h</div>
                                    <div class="text-muted small">
                                        <i class="mdi mdi-clock-outline me-1"></i>
                                        25 min atrás
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-divider"></div>
                        <div class="text-center p-2">
                            <a href="#" class="btn btn-sm btn-primary w-100">
                                <i class="mdi mdi-eye me-1"></i>
                                Ver Todas
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                        data-bs-toggle="dropdown">
                        <div class="user-avatar me-2">
                            @if (auth()->user()->profile_photo_path)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Avatar"
                                    class="rounded-circle" style="width: 32px; height: 32px;">
                            @else
                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold"
                                    style="width: 32px; height: 32px;">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="position-absolute bottom-0 end-0">
                                <div class="bg-success border border-white rounded-circle"
                                    style="width: 10px; height: 10px;" title="Online"></div>
                            </div>
                        </div>
                        <div class="d-none d-md-block">
                            <div class="fw-semibold text-white small">{{ auth()->user()->name }}</div>
                            <div class="text-muted small">{{ auth()->user()->role ?? 'Usuário' }}</div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end animate__animated animate__fadeInUp"
                        style="min-width: 220px;">
                        <li>
                            <div class="dropdown-header text-center">
                                <div class="user-avatar mx-auto mb-2">
                                    @if (auth()->user()->profile_photo_path)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                                            alt="Avatar" class="rounded-circle" style="width: 48px; height: 48px;">
                                    @else
                                        <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold mx-auto"
                                            style="width: 48px; height: 48px;">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                <div class="text-muted small">{{ auth()->user()->email }}</div>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="mdi mdi-account-edit text-primary me-2"></i>
                                Editar Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="toggleTheme()">
                                <i class="mdi mdi-theme-light-dark text-secondary me-2"></i>
                                Alternar Tema
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="mdi mdi-cog text-secondary me-2"></i>
                                Configurações
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="mdi mdi-logout me-2"></i>
                                Sair do Sistema
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Search (visible only on small screens) -->
<div class="mobile-search-container d-lg-none bg-dark border-top border-secondary">
    <div class="container-fluid p-3">
        <div class="position-relative">
            <input type="text" class="form-control rounded-pill ps-4"
                style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white;"
                placeholder="Buscar produtos, pedidos..." id="mobile-search">
            <i class="mdi mdi-magnify position-absolute text-muted"
                style="left: 12px; top: 50%; transform: translateY(-50%);"></i>
        </div>
    </div>
</div>

<!-- Hidden logout form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<style>
    .hover-bg-light:hover {
        background-color: rgba(0, 0, 0, 0.05) !important;
    }

    .notification-item {
        transition: background-color 0.2s ease;
        cursor: pointer;
    }

    .user-avatar {
        position: relative;
    }

    .status-indicator {
        animation: pulse-dot 2s ease-in-out infinite;
    }

    @keyframes pulse-dot {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    .dropdown-menu {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border: none;
        margin-top: 8px !important;
    }

    /* Search input focus effect */
    #navbar-search:focus,
    #mobile-search:focus {
        background: rgba(255, 255, 255, 0.15) !important;
        border-color: rgba(255, 193, 7, 0.5) !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
    }

    /* Animated elements */
    .animate__animated {
        animation-duration: 0.3s;
    }

    .animate__fadeInUp {
        animation-name: fadeInUp;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 20px, 0);
        }

        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== DATE AND TIME UPDATE =====
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };

            const datetimeElement = document.getElementById('datetime-display');
            if (datetimeElement) {
                datetimeElement.textContent = now.toLocaleDateString('pt-BR', options);
            }
        }

        // Update immediately and then every minute
        updateDateTime();
        setInterval(updateDateTime, 60000);

        // ===== FULLSCREEN FUNCTIONALITY =====
        const fullscreenBtn = document.getElementById('fullscreen-toggle');

        function isFullScreen() {
            return !!(
                document.fullscreenElement ||
                document.webkitFullscreenElement ||
                document.mozFullScreenElement ||
                document.msFullscreenElement
            );
        }

        function toggleFullscreen() {
            if (isFullScreen()) {
                // Exit fullscreen
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            } else {
                // Enter fullscreen
                const element = document.documentElement;
                if (element.requestFullscreen) {
                    element.requestFullscreen();
                } else if (element.webkitRequestFullscreen) {
                    element.webkitRequestFullscreen();
                } else if (element.mozRequestFullScreen) {
                    element.mozRequestFullScreen();
                } else if (element.msRequestFullscreen) {
                    element.msRequestFullscreen();
                }
            }
        }

        function updateFullscreenButton() {
            const icon = fullscreenBtn.querySelector('i');
            if (isFullScreen()) {
                icon.classList.remove('mdi-fullscreen');
                icon.classList.add('mdi-fullscreen-exit');
                fullscreenBtn.title = 'Sair da Tela Cheia';
            } else {
                icon.classList.remove('mdi-fullscreen-exit');
                icon.classList.add('mdi-fullscreen');
                fullscreenBtn.title = 'Modo Tela Cheia';
            }
        }

        if (fullscreenBtn) {
            fullscreenBtn.addEventListener('click', toggleFullscreen);

            // Listen for fullscreen change events
            document.addEventListener('fullscreenchange', updateFullscreenButton);
            document.addEventListener('webkitfullscreenchange', updateFullscreenButton);
            document.addEventListener('mozfullscreenchange', updateFullscreenButton);
            document.addEventListener('MSFullscreenChange', updateFullscreenButton);
        }

        // ===== SEARCH FUNCTIONALITY =====
        const searchInputs = ['navbar-search', 'mobile-search'];

        searchInputs.forEach(inputId => {
            const searchInput = document.getElementById(inputId);
            if (searchInput) {
                let searchTimeout;

                searchInput.addEventListener('input', function(e) {
                    clearTimeout(searchTimeout);
                    const query = e.target.value.trim();

                    if (query.length < 2) {
                        hideSearchResults();
                        return;
                    }

                    searchTimeout = setTimeout(() => {
                        performSearch(query);
                    }, 300);
                });

                // Close search results when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target)) {
                        hideSearchResults();
                    }
                });
            }
        });

        function performSearch(query) {
            // Show loading state
            showSearchResults('Buscando...');

            // Simulate search - replace with actual API call
            setTimeout(() => {
                const mockResults = [{
                        type: 'product',
                        name: 'Café Expresso',
                        category: 'Bebidas',
                        price: 'R$ 4,50',
                        icon: 'mdi-coffee'
                    },
                    {
                        type: 'order',
                        name: 'Pedido #1234',
                        info: 'Mesa 12 - Em andamento',
                        time: '10 min atrás',
                        icon: 'mdi-receipt'
                    }
                ];

                if (mockResults.length > 0) {
                    displaySearchResults(mockResults);
                } else {
                    showSearchResults('Nenhum resultado encontrado');
                }
            }, 500);
        }

        function displaySearchResults(results) {
            const resultsHtml = results.map(result => `
            <div class="dropdown-item d-flex align-items-center py-2">
                <i class="${result.icon} text-primary me-3"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold small">${result.name}</div>
                    <div class="text-muted small">
                        ${result.category || result.info || ''}
                        ${result.price ? ' - ' + result.price : ''}
                        ${result.time ? ' - ' + result.time : ''}
                    </div>
                </div>
            </div>
        `).join('');

            showSearchResults(resultsHtml);
        }

        function showSearchResults(content) {
            const searchResults = document.getElementById('search-results');
            if (searchResults) {
                searchResults.innerHTML = content;
                searchResults.style.display = 'block';
            }
        }

        function hideSearchResults() {
            const searchResults = document.getElementById('search-results');
            if (searchResults) {
                searchResults.style.display = 'none';
            }
        }

        // ===== NOTIFICATIONS =====
        function loadNotifications() {
            // Simulate loading notifications
            const notifications = [{
                    id: 1,
                    type: 'warning',
                    title: 'Estoque Baixo',
                    message: '5 produtos com estoque crítico',
                    time: '5 min atrás',
                    unread: true
                },
                {
                    id: 2,
                    type: 'success',
                    title: 'Pedido Finalizado',
                    message: 'Mesa 12 - Total: R$ 85,50',
                    time: '12 min atrás',
                    unread: true
                },
                {
                    id: 3,
                    type: 'info',
                    title: 'Nova Reserva',
                    message: 'Mesa para 6 pessoas às 19:30h',
                    time: '25 min atrás',
                    unread: false
                }
            ];

            const unreadCount = notifications.filter(n => n.unread).length;
            updateNotificationBadge(unreadCount);
        }

        function updateNotificationBadge(count) {
            const badge = document.getElementById('notification-count');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
            }
        }

        // Mark all notifications as read
        const markAllReadBtn = document.getElementById('mark-all-read');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function() {
                updateNotificationBadge(0);
                // Add API call to mark notifications as read
            });
        }

        // Load notifications on page load
        loadNotifications();

        // Refresh notifications every 30 seconds
        setInterval(loadNotifications, 30000);

        // ===== THEME TOGGLE =====
        window.toggleTheme = function() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Show toast notification
            const message = newTheme === 'dark' ? 'Tema escuro ativado' : 'Tema claro ativado';
            showToast(message, 'info');
        };

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);

        // ===== TOAST NOTIFICATIONS =====
        window.showToast = function(message, type = 'success') {
            // Create toast container if it doesn't exist
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '9999';
                document.body.appendChild(toastContainer);
            }

            const toastId = 'toast-' + Date.now();
            const iconMap = {
                success: 'mdi-check-circle',
                error: 'mdi-alert-circle',
                warning: 'mdi-alert-triangle',
                info: 'mdi-information'
            };

            const colorMap = {
                success: 'text-bg-success',
                error: 'text-bg-danger',
                warning: 'text-bg-warning',
                info: 'text-bg-info'
            };

            const toastHtml = `
            <div class="toast ${colorMap[type] || 'text-bg-primary'}" role="alert" id="${toastId}">
                <div class="toast-body d-flex align-items-center">
                    <i class="${iconMap[type] || 'mdi-information'} me-2"></i>
                    <span class="flex-grow-1">${message}</span>
                    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

            toastContainer.insertAdjacentHTML('beforeend', toastHtml);

            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 4000
            });

            toast.show();

            // Remove toast element after it's hidden
            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        };

        // ===== POS FULLSCREEN PROMPT =====
        if (window.location.pathname.includes('/pos')) {
            const hasChosenFullscreen = localStorage.getItem('pos-fullscreen-choice');

            if (!hasChosenFullscreen) {
                // Show fullscreen prompt after a short delay
                setTimeout(() => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Modo Tela Cheia Recomendado',
                            text: 'Para melhor experiência no PDV, recomendamos usar o modo tela cheia. Deseja ativar?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Sim, ativar',
                            cancelButtonText: 'Não, obrigado',
                            confirmButtonColor: '#FF6B35',
                            cancelButtonColor: '#6c757d'
                        }).then((result) => {
                            localStorage.setItem('pos-fullscreen-choice', 'true');
                            if (result.isConfirmed) {
                                toggleFullscreen();
                            }
                        });
                    } else {
                        // Fallback if SweetAlert is not available
                        const useFullscreen = confirm(
                            'Para melhor experiência no PDV, recomendamos usar o modo tela cheia. Deseja ativar?'
                            );
                        localStorage.setItem('pos-fullscreen-choice', 'true');
                        if (useFullscreen) {
                            toggleFullscreen();
                        }
                    }
                }, 1000);
            }
        }

        // ===== KEYBOARD SHORTCUTS =====
        document.addEventListener('keydown', function(e) {
            // Alt + F for fullscreen
            if (e.altKey && e.key === 'f') {
                e.preventDefault();
                toggleFullscreen();
            }

            // Alt + S for search focus
            if (e.altKey && e.key === 's') {
                e.preventDefault();
                const searchInput = document.getElementById('navbar-search') || document.getElementById(
                    'mobile-search');
                if (searchInput) {
                    searchInput.focus();
                }
            }

            // Escape to close search results
            if (e.key === 'Escape') {
                hideSearchResults();
            }
        });
    });
</script>
