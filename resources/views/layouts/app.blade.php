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

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/pos/pos.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/orders.css') }}">
    <style>

    </style>
</head>

<body class="sidebar-dark">
    <div class="container-scroller">
        <!-- Navbar -->
        @include('layouts.navbar')

        <!-- Page Body -->
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sidebar')

            <div class="main-panel" style="   background-image: url('{{ asset('assets/images/restaurante-bg.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;">
                <div class="content-wrapper">
                    @yield('content')
                </div>

                <!-- Footer -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                            Restaurant Management System v{{ config('app.version', '1.0.0') }}
                        </span>
                        <span class="text-muted text-center text-sm-right d-block d-sm-inline-block">
                            &copy; {{ date('Y') }} {{ config('app.company', 'Restaurant Pro') }}
                        </span>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    {{-- !-- Scripts Base --> --}}
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Scripts do StarAdmin -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/pos/pos.js') }}"></script>
    <script src="{{ asset('assets/js/webpack.js') }}"></script>
    <!-- Substitua o script existente por este -->
    <script>
        // Função para verificar se está em tela cheia
        function isFullScreen() {
            return document.fullscreenElement || 
                   document.webkitFullscreenElement || 
                   document.mozFullScreenElement || 
                   document.msFullscreenElement;
        }
    
        // Função para alternar tela cheia
        function toggleFullScreen() {
            try {
                if (!isFullScreen()) {
                    if (document.documentElement.requestFullscreen) {
                        document.documentElement.requestFullscreen();
                    } else if (document.documentElement.mozRequestFullScreen) {
                        document.documentElement.mozRequestFullScreen();
                    } else if (document.documentElement.webkitRequestFullscreen) {
                        document.documentElement.webkitRequestFullscreen();
                    } else if (document.documentElement.msRequestFullscreen) {
                        document.documentElement.msRequestFullscreen();
                    }
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
                }
            } catch (error) {
                console.warn('Erro ao alternar tela cheia:', error);
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
                fullscreenBtn.addEventListener('click', toggleFullScreen);
            }
    
            // Listeners para mudança de estado da tela cheia
            document.addEventListener('fullscreenchange', updateFullscreenButton);
            document.addEventListener('webkitfullscreenchange', updateFullscreenButton);
            document.addEventListener('mozfullscreenchange', updateFullscreenButton);
            document.addEventListener('MSFullscreenChange', updateFullscreenButton);
        });
    
        // Para POS, adicione esta verificação
        if (window.location.pathname === '/pos') {
            Swal.fire({
                title: 'Modo Tela Cheia',
                text: 'Deseja ativar o modo tela cheia para melhor experiência?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.isConfirmed) {
                    toggleFullScreen();
                }
            });
        }
    </script>
    @stack('scripts')
</body>

</html>
