<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistema de Gestão de Restaurantes">
    <meta name="author" content="Filipe dos Santos">
    <title>{{ config('app.name', 'Restaurant System') }} | @yield('title')</title>

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
            background-image: url('{{ asset('assets/images/restaurant-bg.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.25;
            z-index: -1;
            filter: brightness(0.9);
            pointer-events: none;
        }
            /* Ajustes adicionais para layout */
        body,
        .main-panel,
        .content-wrapper {
            position: relative;
            background: rgba(255, 255, 255, 0.1);
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

    <!-- Scripts do StarAdmin -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    {{--  <script src="{{ asset('assets/js/misc.js') }}"></script> --}}
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/pos/pos.js') }}"></script>
    <script src="{{ asset('assets/pos/printRecibo.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar/sidebar.js') }}"></script>
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
    </script>
    @stack('scripts')
</body>

</html>
