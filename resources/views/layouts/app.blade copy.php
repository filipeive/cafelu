<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistema de Gestão de Restaurantes">
    <meta name="author" content="Filipe dos Santos">
    <title>{{ config('app.name', 'Restaurant System') }} | @yield('title')</title>
    <!--bootstrap css cdn-->
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    <!-- Fontes -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">

    <!-- CSS Principal -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">

    <!-- CSS Plugins -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/jquery-toast-plugin/jquery.toast.min.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/pos/pos.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/orders.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar-custom.css') }}">
    <style>
        body,
        .main-panel,
        .content-wrapper {
            background-color: #1C1C1C;
            color: #F5F5F5;
        }
        .main-panel::before,
        .content-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('assets/images/restaurant-bg.jpeg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.1;
            z-index: -1;
            filter: brightness(0.9);
            pointer-events: none;
        }
        .main-panel::before,
        .content-wrapper::before {
            backdrop-filter: blur(2px);
            background-color: rgba(0, 0, 0, 0.85);
            opacity: 1;
        }
        /* Ajustes adicionais para layout */
        body,
        .main-panel,
        .content-wrapper {
            position: relative;
            background: rgba(28, 28, 28, 0.55);
        }
        /* ========================================
           CUSTOM TOAST NOTIFICATIONS
        ======================================== */
        .toast-container {
            position: fixed;
            top: 90px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        }

        .custom-toast {
            background: rgba(26, 26, 46, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 16px 20px;
            min-width: 320px;
            max-width: 400px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            pointer-events: auto;
            animation: slideInRight 0.3s ease, fadeOut 0.3s ease 3s forwards;
            position: relative;
            overflow: hidden;
        }

        .custom-toast::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }

        .custom-toast.success::before {
            background: linear-gradient(180deg, #10b981 0%, #059669 100%);
        }

        .custom-toast.error::before {
            background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%);
        }

        .custom-toast.warning::before {
            background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
        }

        .custom-toast.info::before {
            background: linear-gradient(180deg, #3b82f6 0%, #2563eb 100%);
        }

        .toast-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .custom-toast.success .toast-icon {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .custom-toast.error .toast-icon {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .custom-toast.warning .toast-icon {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }

        .custom-toast.info .toast-icon {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .toast-message {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.4;
        }

        .toast-close {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            border: none;
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .toast-close:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, rgba(255, 165, 0, 0.5) 0%, #FFA500 100%);
            animation: progressBar 3s linear;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(400px);
            }
        }

        @keyframes progressBar {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }

        /* ========================================
           SCROLLBAR CUSTOMIZATION
        ======================================== */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 165, 0, 0.3);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 165, 0, 0.5);
        }

        /* ========================================
           LOADING OVERLAY
        ======================================== */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 15, 30, 0.95);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 165, 0, 0.2);
            border-top-color: #FFA500;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ========================================
           RESPONSIVE
        ======================================== */
        @media (max-width: 768px) {
            .main-panel {
                margin-left: 0;
            }

            .main-panel::before {
                left: 0;
            }

            .toast-container {
                right: 12px;
                left: 12px;
            }

            .custom-toast {
                min-width: auto;
                width: 100%;
            }
        }
    </style>
</head>
<body class="sidebar-dark">
    <div class="container-scroller">
        <!-- Navbar -->
        @include('layouts.navbar')

        <!-- Page Body -->
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sidebar')

            <div class="main-panel"
                style="   background-image: url('{{ asset('assets/images/restaurant-bg.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;">
                <div class="content-wrapper">
                    @yield('content')
                </div>

                <!-- Footer -->
                {{-- <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                            Sistema de Gerenciamento de Restaurantes v{{ config('app.version', '1.0.0') }}
                        </span>
                        <span class="text-muted text-center text-sm-right d-block d-sm-inline-block">
                            &copy; {{ date('Y') }} {{ config('app.company', 'Café Lufamina') }}
                        </span>
                    </div>
                </footer> --}}
            </div>
        </div>
    </div>
    {{-- Scripts Base  --}}
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
    {{-- toast --}}
    <script src="{{ asset('assets/vendors/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Scripts do StarAdmin -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    {{--  <script src="{{ asset('assets/js/misc.js') }}"></script> --}}
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/pos/pos.js') }}"></script>
    <script src="{{ asset('assets/pos/printRecibo.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar/sidebar.js') }}"></script>
    @livewireScripts
    <script>
        // Função para verificar se o documento está em modo tela cheia
        function isFullScreen() {
            return !!(
                document.fullscreenElement ||
                document.webkitFullscreenElement ||
                document.mozFullScreenElement ||
                document.msFullscreenElement
            );
        }

        // Função para alternar o modo tela cheia
        function toggleFullscreen() {
            if (isFullScreen()) {
                // Sai do modo tela cheia
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
                // Entra no modo tela cheia
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

        // Atualizar ícone do botão
        function updateFullscreenButton() {
            const btn = document.getElementById('fullscreenBtn');
            if (btn) {
                const icon = btn.querySelector('i');
                if (isFullScreen()) {
                    icon.classList.remove('mdi-fullscreen');
                    icon.classList.add('mdi-fullscreen-exit');
                } else {
                    icon.classList.remove('mdi-fullscreen-exit');
                    icon.classList.add('mdi-fullscreen');
                }
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const fullscreenBtn = document.getElementById('fullscreenBtn');
            if (fullscreenBtn) {
                fullscreenBtn.addEventListener('click', toggleFullscreen);
            }

            // Listeners para mudança de estado da tela cheia
            document.addEventListener('fullscreenchange', updateFullscreenButton);
            document.addEventListener('webkitfullscreenchange', updateFullscreenButton);
            document.addEventListener('mozfullscreenchange', updateFullscreenButton);
            document.addEventListener('MSFullscreenChange', updateFullscreenButton);
        });
        // Para POS, adicione esta verificação
        if (window.location.pathname === '/pos') {
            // Verifica se o usuário já escolheu uma opção anteriormente
            const hasChosenFullscreen = localStorage.getItem('hasChosenFullscreen');
            if (!hasChosenFullscreen) {
                Swal.fire({
                    title: 'Modo Tela Cheia',
                    text: 'Deseja ativar o modo tela cheia para melhor experiência?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Não'
                }).then((result) => {
                    localStorage.setItem('hasChosenFullscreen', 'true'); // Salva a escolha
                    if (result.isConfirmed) {
                        toggleFullscreen(); // Ativa o modo tela cheia
                    }
                });
            }
        }
        /**
         * ========================================
         * CUSTOM TOAST SYSTEM
         * ========================================
         */
        function showToast(message, type = 'info', title = null) {
            const container = document.getElementById('toastContainer');
            
            const icons = {
                success: 'mdi-check-circle',
                error: 'mdi-alert-circle',
                warning: 'mdi-alert',
                info: 'mdi-information'
            };

            const titles = {
                success: title || 'Sucesso',
                error: title || 'Erro',
                warning: title || 'Atenção',
                info: title || 'Informação'
            };

            const toast = document.createElement('div');
            toast.className = `custom-toast ${type}`;
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="mdi ${icons[type]}"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">${titles[type]}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="mdi mdi-close"></i>
                </button>
                <div class="toast-progress"></div>
            `;

            container.appendChild(toast);

            // Auto remove após 3.5 segundos
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 3500);
        }

        // Sobrescrever jQuery Toast para usar o sistema customizado
        if (typeof $ !== 'undefined' && $.toast) {
            const originalToast = $.toast;
            $.toast = function(options) {
                if (typeof options === 'object') {
                    showToast(
                        options.text || options.message || '',
                        options.icon || options.type || 'info',
                        options.heading || options.title
                    );
                } else {
                    showToast(options);
                }
            };
        }

        /**
         * ========================================
         * LOADING OVERLAY
         * ========================================
         */
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }

        /**
         * ========================================
         * FULLSCREEN FUNCTIONS
         * ========================================
         */
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
            const btn = document.getElementById('fullscreenBtn');
            if (btn) {
                const icon = btn.querySelector('i');
                if (isFullScreen()) {
                    icon.classList.remove('mdi-fullscreen');
                    icon.classList.add('mdi-fullscreen-exit');
                } else {
                    icon.classList.remove('mdi-fullscreen-exit');
                    icon.classList.add('mdi-fullscreen');
                }
            }
        }

        /**
         * ========================================
         * INITIALIZATION
         * ========================================
         */
        document.addEventListener('DOMContentLoaded', function() {
            const fullscreenBtn = document.getElementById('fullscreenBtn');
            if (fullscreenBtn) {
                fullscreenBtn.addEventListener('click', toggleFullscreen);
            }

            // Fullscreen listeners
            document.addEventListener('fullscreenchange', updateFullscreenButton);
            document.addEventListener('webkitfullscreenchange', updateFullscreenButton);
            document.addEventListener('mozfullscreenchange', updateFullscreenButton);
            document.addEventListener('MSFullscreenChange', updateFullscreenButton);

            // POS fullscreen prompt
            if (window.location.pathname === '/pos') {
                const hasChosenFullscreen = localStorage.getItem('hasChosenFullscreen');
                if (!hasChosenFullscreen) {
                    Swal.fire({
                        title: 'Modo Tela Cheia',
                        text: 'Deseja ativar o modo tela cheia para melhor experiência?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sim',
                        cancelButtonText: 'Não',
                        confirmButtonColor: '#FFA500',
                        cancelButtonColor: '#6b7280',
                        background: '#1a1a2e',
                        color: '#ffffff'
                    }).then((result) => {
                        localStorage.setItem('hasChosenFullscreen', 'true');
                        if (result.isConfirmed) {
                            toggleFullscreen();
                        }
                    });
                }
            }

            // Show welcome toast
            @if(session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif

            @if(session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif

            @if(session('warning'))
                showToast('{{ session('warning') }}', 'warning');
            @endif

            @if(session('info'))
                showToast('{{ session('info') }}', 'info');
            @endif
        });

    </script>
    @stack('scripts')
</body>
</html>
