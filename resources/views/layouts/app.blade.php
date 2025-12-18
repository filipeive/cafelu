<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistema de Gestão de Restaurantes">
    <title>{{ \App\Models\Setting::get('company_name', config('app.name', 'Restaurant System')) }} | @yield('title')
    </title>

    <!-- Favicon -->
    @if($favicon = \App\Models\Setting::get('system_favicon'))
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    @endif

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">

    <!-- TAILWIND CSS VIA CDN (APENAS PARA DESENVOLVIMENTO) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '{{ \App\Models\Setting::get('primary_color', '#FFA500') }}',
                        warning: '{{ \App\Models\Setting::get('primary_color', '#FFA500') }}',
                        danger: '#EF4444',
                        success: '#10B981',
                        info: '#3B82F6',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Custom Scrollbar */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background-color: #f1f5f9;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-track {
            background-color: #1e293b;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 9999px;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: #475569;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }

        /* Animações */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
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

        .toast-enter {
            animation: slideInRight 0.3s ease-out;
        }

        .toast-exit {
            animation: slideOutRight 0.3s ease-in;
        }
    </style>

    @stack('styles')
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900 font-['Inter'] antialiased text-gray-900 dark:text-gray-100" x-data="{ 
          sidebarOpen: false, 
          sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' 
      }" @resize.window="if (window.innerWidth >= 1024) sidebarOpen = false">

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-[100] space-y-2 max-w-sm w-full pointer-events-none"
        aria-live="polite">
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[999] hidden items-center justify-center"
        style="display: none;">
        <div class="relative">
            <div class="w-16 h-16 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin"></div>
        </div>
    </div>

    <div class="h-full flex flex-col">
        <!-- Navbar -->
        @include('layouts.navbar')

        <div class="flex flex-1 pt-16 overflow-hidden">
            <!-- Mobile Sidebar Backdrop -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm lg:hidden z-30" x-cloak>
            </div>

            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 transition-all duration-300"
                :class="{ 'lg:ml-64': !sidebarCollapsed, 'lg:ml-20': sidebarCollapsed }">
                <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
                    <!-- Page Header -->
                    @hasSection('page-header')
                        <div class="mb-6">
                            @yield('page-header')
                        </div>
                    @endif

                    <!-- Flash Messages -->
                    {{-- @if(session('success') || session('error') || session('warning') || session('info'))
                    <div class="mb-6 space-y-2" x-data="{ show: true }" x-show="show" x-transition>
                        @if(session('success'))
                        <div
                            class="flex items-center p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                            <i class="mdi mdi-check-circle text-green-500 text-xl mr-3"></i>
                            <span class="text-green-800 dark:text-green-200 flex-1">{{ session('success') }}</span>
                            <button @click="show = false" class="text-green-500 hover:text-green-700">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div
                            class="flex items-center p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                            <i class="mdi mdi-alert-circle text-red-500 text-xl mr-3"></i>
                            <span class="text-red-800 dark:text-red-200 flex-1">{{ session('error') }}</span>
                            <button @click="show = false" class="text-red-500 hover:text-red-700">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                        @endif

                        @if(session('warning'))
                        <div
                            class="flex items-center p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
                            <i class="mdi mdi-alert text-yellow-500 text-xl mr-3"></i>
                            <span class="text-yellow-800 dark:text-yellow-200 flex-1">{{ session('warning') }}</span>
                            <button @click="show = false" class="text-yellow-500 hover:text-yellow-700">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                        @endif

                        @if(session('info'))
                        <div
                            class="flex items-center p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                            <i class="mdi mdi-information text-blue-500 text-xl mr-3"></i>
                            <span class="text-blue-800 dark:text-blue-200 flex-1">{{ session('info') }}</span>
                            <button @click="show = false" class="text-blue-500 hover:text-blue-700">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                    @endif --}}

                    <!-- Main Content -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        /**
         * Toast System
         */
        class ToastManager {
            constructor() {
                this.container = document.getElementById('toastContainer');
                this.toasts = new Map();
            }

            show(message, type = 'info', options = {}) {
                const id = Date.now() + Math.random();
                const duration = options.duration || 3500;

                const config = {
                    success: {
                        icon: 'mdi-check-circle',
                        title: 'Sucesso',
                        classes: 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
                        iconColor: 'text-green-500'
                    },
                    error: {
                        icon: 'mdi-alert-circle',
                        title: 'Erro',
                        classes: 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-800 dark:text-red-200',
                        iconColor: 'text-red-500'
                    },
                    warning: {
                        icon: 'mdi-alert',
                        title: 'Atenção',
                        classes: 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200',
                        iconColor: 'text-yellow-500'
                    },
                    info: {
                        icon: 'mdi-information',
                        title: 'Informação',
                        classes: 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
                        iconColor: 'text-blue-500'
                    }
                };

                const settings = config[type] || config.info;
                const title = options.title || settings.title;

                const toast = document.createElement('div');
                toast.className = `${settings.classes} border rounded-lg shadow-lg p-4 mb-2 pointer-events-auto toast-enter max-w-sm`;
                toast.innerHTML = `
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <i class="mdi ${settings.icon} ${settings.iconColor} text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm">${title}</p>
                            <p class="text-sm mt-0.5 break-words">${message}</p>
                        </div>
                        <button onclick="window.toastManager.remove(${id})" class="${settings.iconColor} hover:opacity-70 transition-opacity">
                            <i class="mdi mdi-close"></i>
                        </button>
                    </div>
                `;

                this.container.appendChild(toast);
                this.toasts.set(id, toast);

                setTimeout(() => this.remove(id), duration);

                return id;
            }

            remove(id) {
                const toast = this.toasts.get(id);
                if (toast) {
                    toast.classList.remove('toast-enter');
                    toast.classList.add('toast-exit');
                    setTimeout(() => {
                        toast.remove();
                        this.toasts.delete(id);
                    }, 300);
                }
            }
        }

        window.toastManager = new ToastManager();
        window.showToast = (message, type, options) => window.toastManager.show(message, type, options);

        /**
         * Loading Functions
         */
        window.showLoading = () => {
            const overlay = document.getElementById('loadingOverlay');
            overlay.style.display = 'flex';
        };

        window.hideLoading = () => {
            const overlay = document.getElementById('loadingOverlay');
            overlay.style.display = 'none';
        };

        /**
         * Print Receipt
         */
        window.printRecibo = (id) => {
            const url = `/orders/${id}/print`;
            const printWindow = window.open(url, 'Print Receipt', 'height=600,width=400');
            // Note: The print window should handle the printing itself (e.g. window.print() on load)
        };

        /**
         * Print Sale Receipt
         */
        window.printSaleRecibo = (id) => {
            const url = `/sales/${id}/receipt`;
            const printWindow = window.open(url, 'Print Receipt', 'height=600,width=400');
        };

        /**
         * Fullscreen
         */
        window.toggleFullscreen = () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        };

        /**
         * Initialization
         */
        document.addEventListener('DOMContentLoaded', () => {
            // Theme
            const theme = localStorage.getItem('theme') || 'dark';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }

            // Show session messages
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

            console.log('✅ Sistema inicializado com Tailwind CDN');
        });
    </script>

    @stack('scripts')
</body>

</html>