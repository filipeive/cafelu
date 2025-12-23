<nav
    class="fixed top-0 left-0 right-0 h-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 flex z-50 transition-colors duration-300">
    <!-- Logo Area -->
    <div class="flex items-center justify-center border-r border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 transition-all duration-300"
        :class="sidebarCollapsed ? 'w-20' : 'w-64'">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-decoration-none">
            @if($logo = \App\Models\Setting::get('system_logo'))
                <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="h-8 w-auto">
            @else
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-8 w-auto">
            @endif
            <div class="hidden lg:block leading-tight" x-show="!sidebarCollapsed" x-transition>
                <span class="block font-bold text-gray-800 dark:text-white tracking-wide">
                    {{ \App\Models\Setting::get('company_name', 'ZALALA BB') }}
                </span>
                <span class="block text-xs text-warning font-semibold">POS SYSTEM</span>
            </div>
        </a>
    </div>

    <!-- Main Navbar Content -->
    <div class="flex-1 flex items-center justify-between px-6 bg-white dark:bg-gray-900 transition-colors duration-300">
        <!-- Left Side: Toggle & Welcome -->
        <div class="flex items-center gap-4">
            <button class="lg:hidden p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg"
                type="button" @click="sidebarOpen = !sidebarOpen">
                <i class="mdi mdi-menu text-2xl"></i>
            </button>

            @if(auth()->user()->role !== 'customer')
                <!-- Global Search -->
                <form action="{{ route('search') }}" method="GET" class="hidden lg:block relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="mdi mdi-magnify text-gray-400 group-focus-within:text-warning transition-colors"></i>
                    </div>
                    <input type="text" name="q" placeholder="{{ __('messages.search_placeholder') }}"
                        class="block w-64 pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-full leading-5 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-warning/50 focus:border-warning sm:text-sm transition-all duration-300 focus:w-80">
                </form>
            @endif

            <div class="hidden xl:block">
                <h1 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    {{ __('messages.welcome') }}, <span class="text-warning">{{ Auth::user()->name }}</span>
                </h1>
            </div>
        </div>

        <!-- Right Side: Actions & Profile -->
        <ul class="flex items-center gap-3">
            @if(auth()->user()->role !== 'customer')
                <!-- Quick Actions Dropdown -->
                <li class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="hidden md:flex items-center gap-2 px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-medium text-warning hover:bg-orange-50 dark:hover:bg-gray-800 transition-colors">
                        <i class="mdi mdi-plus-circle-outline text-lg"></i>
                        <span>{{ __('messages.quick_actions') }}</span>
                    </button>

                    <div x-show="open" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50"
                        style="display: none;">
                        <a href="{{ route('orders.index') }}"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <i class="mdi mdi-cart-plus text-success"></i> {{ __('messages.orders') }}
                        </a>
                        <a href="{{ route('tables.index') }}"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <i class="mdi mdi-calendar-plus text-info"></i> {{ __('messages.tables') }}
                        </a>
                        <a href="{{ route('products.index', ['create' => 1]) }}"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <i class="mdi mdi-food-variant text-warning"></i> {{ __('messages.add_product') }}
                        </a>
                    </div>
                </li>
            @endif

            <!-- Language Switcher -->
            <li class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                    class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors flex items-center gap-2">
                    @if(app()->getLocale() == 'pt')
                        <span class="text-xl">🇵🇹</span>
                    @else
                        <span class="text-xl">🇬🇧</span>
                    @endif
                    <i class="mdi mdi-chevron-down text-xs"></i>
                </button>

                <div x-show="open" x-transition
                    class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50"
                    style="display: none;">
                    <a href="{{ route('lang.switch', 'pt') }}"
                        class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 {{ app()->getLocale() == 'pt' ? 'bg-orange-50 dark:bg-gray-700 font-bold' : '' }}">
                        <span class="text-lg">🇵🇹</span> {{ __('messages.portuguese') }}
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 {{ app()->getLocale() == 'en' ? 'bg-orange-50 dark:bg-gray-700 font-bold' : '' }}">
                        <span class="text-lg">🇬🇧</span> {{ __('messages.english') }}
                    </a>
                </div>
            </li>

            <!-- Theme Toggle -->
            <li>
                <button id="themeToggleBtn"
                    class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    title="Alternar Tema">
                    <i class="mdi mdi-weather-sunny text-xl"></i>
                </button>
            </li>

            <!-- Fullscreen -->
            <li class="hidden md:block">
                <button id="fullscreenBtn"
                    class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    title="Tela Cheia">
                    <i class="mdi mdi-fullscreen text-xl"></i>
                </button>
            </li>

            <!-- Notifications -->
            <li class="relative" x-data="{ 
                open: false,
                unreadCount: {{ Auth::user()->unreadNotifications->count() }},
                notifications: [
                    @foreach(Auth::user()->unreadNotifications->take(5) as $notification)
                        {
                            id: '{{ $notification->id }}',
                            message: '{{ $notification->data['message'] }}',
                            icon: '{{ $notification->data['icon'] }}',
                            color: '{{ $notification->data['color'] }}',
                            link: '{{ $notification->data['link'] }}',
                            time: '{{ $notification->created_at->diffForHumans() }}'
                        },
                    @endforeach
                ],
                init() {
                    // Poll for new notifications every 30 seconds
                    setInterval(() => {
                        this.fetchNotifications();
                    }, 30000);
                },
                fetchNotifications() {
                    fetch('{{ route('notifications.unread') }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.unreadCount > this.unreadCount) {
                            // Optional: Show a toast or play a sound
                            console.log('New notification received!');
                        }
                        this.unreadCount = data.unreadCount;
                        this.notifications = data.notifications;
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
                },
                markAsRead(id, link) {
                    fetch(`/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        window.location.href = link;
                    });
                }
            }">
                <button @click="open = !open" @click.outside="open = false"
                    class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors relative">
                    <i class="mdi mdi-bell-outline text-xl"></i>
                    <template x-if="unreadCount > 0">
                        <span class="absolute top-2 right-2 w-2 h-2 bg-danger rounded-full"></span>
                    </template>
                </button>

                <div x-show="open" x-transition
                    class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
                    style="display: none;">
                    <div
                        class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50 rounded-t-lg">
                        <h6 class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                            {{ __('messages.notifications') }}
                        </h6>
                        <span x-text="unreadCount + ' {{ __('messages.new_plural') }}'"
                            class="px-2 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300"></span>
                    </div>

                    <div class="max-h-96 overflow-y-auto">
                        <template x-if="notifications.length > 0">
                            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                                <template x-for="notification in notifications" :key="notification.id">
                                    <button @click="markAsRead(notification.id, notification.link)"
                                        class="w-full text-left px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex gap-3">
                                        <div :class="notification.color + ' mt-1'">
                                            <i :class="'mdi ' + notification.icon + ' text-xl'"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-800 dark:text-gray-200 leading-snug"
                                                x-text="notification.message"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                                x-text="notification.time"></p>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </template>

                        <template x-if="notifications.length === 0">
                            <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                <i class="mdi mdi-bell-off-outline text-3xl mb-2 block"></i>
                                <p class="text-sm">{{ __('messages.no_new_notifications') }}</p>
                            </div>
                        </template>
                    </div>

                    <div
                        class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 text-center bg-gray-50 dark:bg-gray-900/50 rounded-b-lg">
                        <a href="{{ route('notifications.index') }}"
                            class="text-xs font-semibold text-warning hover:text-orange-600">
                            {{ __('messages.view_all_notifications') }}
                        </a>
                    </div>
                </div>
            </li>

            <!-- User Profile -->
            <li class="relative ml-2" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                    class="flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-800 p-1.5 rounded-lg transition-colors">
                    @if (Auth::user()->profile_photo_path)
                        <img class="h-8 w-8 rounded-full object-cover border border-gray-200 dark:border-gray-700"
                            src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Perfil">
                    @else
                        <div
                            class="h-8 w-8 rounded-full bg-orange-100 dark:bg-gray-700 flex items-center justify-center text-warning border border-orange-200 dark:border-gray-600">
                            <i class="mdi mdi-account text-lg"></i>
                        </div>
                    @endif
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200 leading-none">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ Auth::user()->role ?? 'Usuário' }}
                        </p>
                    </div>
                    <i class="mdi mdi-chevron-down text-gray-400"></i>
                </button>

                <div x-show="open" x-transition
                    class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50"
                    style="display: none;">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <i class="mdi mdi-account-edit-outline text-primary"></i> {{ __('messages.edit_profile') }}
                        </a>
                        @if(auth()->user()->role !== 'customer')
                            <a href="{{ route('settings.index') }}"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i class="mdi mdi-cog-outline text-primary"></i> {{ __('messages.system_settings') }}
                            </a>
                        @endif
                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                            <i class="mdi mdi-logout-variant"></i> {{ __('messages.logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>

<!-- Script para mostrar data e hora atual -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
            document.getElementById('dateTime').textContent = now.toLocaleDateString('{{ app()->getLocale() }}', options);
        }

        // Atualiza imediatamente e depois a cada segundo
        updateDateTime();
        setInterval(updateDateTime, 1000);
    });
</script>