<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ __('messages.system_description_meta') }}">
    <title>{{ \App\Models\Setting::get('company_name', config('app.name', 'Restaurant System')) }} | @yield('title')
    </title>

    <!-- Favicon -->
    @if($favicon = \App\Models\Setting::get('system_favicon'))
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    @endif

    <!-- PWA Manifest & Mobile Meta -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#f97316">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="RestPOS">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">

    <!-- TAILWIND CSS VIA CDN (APENAS PARA DESENVOLVIMENTO) -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '{{ \App\Models\Setting::get('primary_color', '#FFA500') }}',
                        secondary: '{{ \App\Models\Setting::get('secondary_color', '#10B981') }}',
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

    <!-- Alpine.js Plugins & Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        <!-- Impersonation Support Banner -->
        @if(session('is_support_mode'))
        <div class="bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500 text-gray-950 px-4 py-2 z-50 flex items-center justify-between text-xs font-bold shadow-md">
            <div class="flex items-center gap-2">
                <i class="mdi mdi-shield-account text-lg"></i>
                <span>MODO DE SUPORTE ATIVO: A navegar no restaurante <u>{{ session('current_tenant_name', 'Selecionado') }}</u> como SuperAdmin</span>
            </div>
            <form action="{{ route('admin.tenants.leave-impersonate') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-3 py-1 bg-gray-950 hover:bg-gray-800 text-white rounded-lg text-[11px] font-black uppercase transition flex items-center gap-1 shadow-xs">
                    <i class="mdi mdi-logout-variant"></i>
                    <span>Sair e Voltar ao Painel do Dono</span>
                </button>
            </form>
        </div>
        @endif

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
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 transition-all duration-300 pb-24 lg:pb-6"
                :class="{ 'lg:ml-64': !sidebarCollapsed, 'lg:ml-20': sidebarCollapsed }">
                <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
                    <!-- Page Header -->
                    @hasSection('page-header')
                        <div class="mb-6">
                            @yield('page-header')
                        </div>
                    @endif
                    <!-- Main Content -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile PWA Bottom Navigation Bar (Smartphones & Tablets < 1024px) -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-gray-900/95 dark:bg-gray-950/95 backdrop-blur-xl border-t border-gray-800/80 px-2 sm:px-4 py-1.5 flex items-center justify-around text-gray-400 shadow-2xl transition-all pb-[max(0.6rem,env(safe-area-inset-bottom))]">
        <a href="{{ route('dashboard') }}" 
           class="flex flex-col items-center justify-center gap-0.5 py-1 px-2 rounded-xl transition {{ request()->routeIs('dashboard*') ? 'text-orange-500 font-bold' : 'hover:text-gray-200' }}">
            <i class="mdi mdi-view-dashboard text-xl"></i>
            <span class="text-[10px]">Início</span>
        </a>

        <a href="{{ route('tables.index') }}" 
           class="flex flex-col items-center justify-center gap-0.5 py-1 px-2 rounded-xl transition {{ request()->routeIs('tables*') ? 'text-orange-500 font-bold' : 'hover:text-gray-200' }}">
            <i class="mdi mdi-table-chair text-xl"></i>
            <span class="text-[10px]">Mesas</span>
        </a>

        <!-- Botão Central Elevado de Ação Rápida: Frente de Caixa POS -->
        <a href="{{ route('pos.index') }}" 
           class="flex flex-col items-center justify-center -mt-5 bg-gradient-to-tr from-orange-600 to-amber-500 text-white w-14 h-14 rounded-full shadow-lg shadow-orange-500/40 border-4 border-gray-900 dark:border-gray-950 active:scale-95 transition-transform"
           title="Frente de Caixa POS">
            <i class="mdi mdi-cash-register text-2xl"></i>
            <span class="text-[8px] font-black uppercase tracking-wider mt-0.5">PDV</span>
        </a>

        <a href="{{ route('kitchen.index') }}" 
           class="flex flex-col items-center justify-center gap-0.5 py-1 px-2 rounded-xl transition {{ request()->routeIs('kitchen*') || request()->routeIs('orders.kitchen*') ? 'text-orange-500 font-bold' : 'hover:text-gray-200' }}">
            <i class="mdi mdi-chef-hat text-xl"></i>
            <span class="text-[10px]">Cozinha</span>
        </a>

        <button @click="sidebarOpen = !sidebarOpen" 
                type="button" 
                class="flex flex-col items-center justify-center gap-0.5 py-1 px-2 rounded-xl transition hover:text-gray-200 cursor-pointer"
                title="Menu Completo">
            <i class="mdi mdi-menu text-xl"></i>
            <span class="text-[10px]">Menu</span>
        </button>
    </nav>

    <!-- PWA Install Prompt Banner (Mobile & Tablets) -->
    <div x-data="{
             showInstallPrompt: false,
             deferredPrompt: null,
             init() {
                 if (window.matchMedia('(display-mode: standalone)').matches || sessionStorage.getItem('restpos_install_dismissed')) {
                     return;
                 }
                 window.addEventListener('beforeinstallprompt', (e) => {
                     e.preventDefault();
                     this.deferredPrompt = e;
                     this.showInstallPrompt = true;
                 });
             },
             installApp() {
                 if (this.deferredPrompt) {
                     this.deferredPrompt.prompt();
                     this.deferredPrompt.userChoice.then(() => {
                         this.deferredPrompt = null;
                         this.showInstallPrompt = false;
                     });
                 }
             },
             dismissPrompt() {
                 this.showInstallPrompt = false;
                 sessionStorage.setItem('restpos_install_dismissed', '1');
             }
         }"
         x-show="showInstallPrompt"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         style="display: none;"
         class="lg:hidden fixed bottom-20 left-3 right-3 z-50 bg-gray-900/95 dark:bg-gray-950/95 backdrop-blur-xl border border-orange-500/30 rounded-2xl p-3 shadow-2xl flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-10 h-10 rounded-xl bg-orange-500/20 border border-orange-500/40 flex items-center justify-center text-orange-400 shrink-0">
                <i class="mdi mdi-cellphone-arrow-down text-2xl"></i>
            </div>
            <div class="min-w-0">
                <h4 class="text-xs font-black text-white truncate">Instalar App RestPOS</h4>
                <p class="text-[11px] text-gray-300 leading-tight">Aceda mais rápido e trabalhe em ecrã inteiro no seu celular.</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button @click="dismissPrompt()" class="px-2.5 py-1.5 rounded-xl text-gray-400 hover:text-white text-xs font-bold cursor-pointer">
                Depois
            </button>
            <button @click="installApp()" class="px-3.5 py-1.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-black shadow-md shadow-orange-500/30 cursor-pointer">
                Instalar
            </button>
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
                        title: '{{ __('messages.success') }}',
                        classes: 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
                        iconColor: 'text-green-500'
                    },
                    error: {
                        icon: 'mdi-alert-circle',
                        title: '{{ __('messages.error') }}',
                        classes: 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-800 dark:text-red-200',
                        iconColor: 'text-red-500'
                    },
                    warning: {
                        icon: 'mdi-alert',
                        title: '{{ __('messages.warning') }}',
                        classes: 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200',
                        iconColor: 'text-yellow-500'
                    },
                    info: {
                        icon: 'mdi-information',
                        title: '{{ __('messages.info') }}',
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
            const url = `{{ url('/orders') }}/${id}/print`;
            const printWindow = window.open(url, 'Print Receipt', 'height=600,width=400');
            // Note: The print window should handle the printing itself (e.g. window.print() on load)
        };

        /**
         * Print Sale Receipt
         */
        window.printSaleRecibo = (id) => {
            const url = `{{ url('/sales') }}/${id}/receipt`;
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

            // Theme Toggle
            const themeToggleBtn = document.getElementById('themeToggleBtn');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', () => {
                    document.documentElement.classList.toggle('dark');
                    const isDark = document.documentElement.classList.contains('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                });
            }

            // Fullscreen Toggle
            const fullscreenBtn = document.getElementById('fullscreenBtn');
            if (fullscreenBtn) {
                fullscreenBtn.addEventListener('click', () => {
                    window.toggleFullscreen();
                });
            }

            // Show session messages
            @if(session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif

            @if(session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif

            @if(isset($errors) && $errors->any())
                @foreach($errors->all() as $error)
                    showToast('{{ addslashes($error) }}', 'error');
                @endforeach
            @endif

            @if(session('warning'))
                showToast('{{ session('warning') }}', 'warning');
            @endif

            @if(session('info'))
                showToast('{{ session('info') }}', 'info');
            @endif

            console.log('Sistema inicializado com sucesso');
        });

        /**
         * Global File Upload Validation & Smart Image Compression
         * Prevents 413 Request Entity Too Large errors across all forms
         */
        (function() {
            const MAX_UPLOAD_BYTES = 4.5 * 1024 * 1024; // 4.5 MB hard limit after compression
            const MAX_RAW_BYTES = 25 * 1024 * 1024;     // 25 MB raw phone camera limit
            const COMPRESS_THRESHOLD = 1.2 * 1024 * 1024; // Auto-compress images > 1.2 MB

            function formatBytes(bytes) {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1048576) return (bytes / 1024).toFixed(0) + ' KB';
                return (bytes / 1048576).toFixed(2) + ' MB';
            }

            function compressImageFile(file, maxDimension = 1920, quality = 0.82) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        const img = new Image();
                        img.onload = () => {
                            let width = img.width;
                            let height = img.height;

                            if (width > maxDimension || height > maxDimension) {
                                if (width > height) {
                                    height = Math.round((height * maxDimension) / width);
                                    width = maxDimension;
                                } else {
                                    width = Math.round((width * maxDimension) / height);
                                    height = maxDimension;
                                }
                            }

                            const canvas = document.createElement('canvas');
                            canvas.width = width;
                            canvas.height = height;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, width, height);

                            const outputType = file.type === 'image/png' ? 'image/jpeg' : file.type;
                            canvas.toBlob((blob) => {
                                if (!blob) return reject(new Error('Falha ao comprimir imagem'));
                                const ext = outputType === 'image/jpeg' ? '.jpg' : '';
                                const cleanName = file.name.replace(/\.[^/.]+$/, '') + (ext || '.jpg');
                                const compressedFile = new File([blob], cleanName, {
                                    type: outputType,
                                    lastModified: Date.now()
                                });
                                resolve(compressedFile);
                            }, outputType, quality);
                        };
                        img.onerror = reject;
                        img.src = event.target.result;
                    };
                    reader.onerror = reject;
                    reader.readAsDataURL(file);
                });
            }

            document.addEventListener('change', async function(e) {
                const input = e.target;
                if (!input || input.tagName !== 'INPUT' || input.type !== 'file') return;
                if (!input.files || input.files.length === 0) return;

                const file = input.files[0];
                const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon'];

                if (input.accept && input.accept.includes('image') && file.type && !file.type.startsWith('image/')) {
                    window.showToast('Formato inválido. Por favor, selecione um ficheiro de imagem (JPG, PNG ou WEBP).', 'error');
                    input.value = '';
                    return;
                }

                if (file.size > MAX_RAW_BYTES) {
                    window.showToast(`O ficheiro "${file.name}" (${formatBytes(file.size)}) é demasiado grande! Selecione uma imagem até 15 MB.`, 'error', { duration: 6000 });
                    input.value = '';
                    return;
                }

                const isCompressibleImage = ['image/jpeg', 'image/png', 'image/webp'].includes(file.type);

                if (isCompressibleImage && file.size > COMPRESS_THRESHOLD && typeof DataTransfer !== 'undefined') {
                    try {
                        const originalSize = file.size;
                        const compressed = await compressImageFile(file);
                        if (compressed.size < originalSize) {
                            const dt = new DataTransfer();
                            dt.items.add(compressed);
                            input.files = dt.files;
                            window.showToast(`Imagem otimizada automaticamente (${formatBytes(originalSize)} -> ${formatBytes(compressed.size)}) para envio rápido.`, 'info', { duration: 4000 });
                        }
                    } catch (err) {
                        console.warn('Compressão automática ignorada:', err);
                    }
                }

                // Final size check after optional compression
                const finalFile = input.files[0];
                if (finalFile && finalFile.size > MAX_UPLOAD_BYTES) {
                    window.showToast(`O ficheiro "${finalFile.name}" (${formatBytes(finalFile.size)}) excede o limite máximo de 4 MB. Escolha uma imagem mais leve.`, 'error', { duration: 6000 });
                    input.value = '';
                }
            }, true);
        })();

        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('{{ asset("sw.js") }}')
                    .then(reg => console.log('RestPOS PWA ServiceWorker ativo:', reg.scope))
                    .catch(err => console.warn('RestPOS PWA ServiceWorker indisponível:', err));
            });
        }
    </script>

    @stack('scripts')
</body>

</html>