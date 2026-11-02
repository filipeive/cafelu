<nav
    class="fixed top-0 left-0 right-0 h-16 bg-card/95 backdrop-blur-md border-b border-border/80 flex z-50 transition-colors duration-200">
    @php
        $isOwnerConsole = function_exists('is_owner_console') ? is_owner_console() : (auth()->check() && auth()->user()->isSuperAdmin() && !session('is_support_mode'));
        $displayUserName = trim(preg_replace('/\s*\(.*?\)\s*/', '', Auth::user()->name));
    @endphp
    <!-- Logo Area -->
    <div class="flex items-center justify-center border-r border-border/80 bg-card transition-all duration-300"
        :class="sidebarCollapsed ? 'w-20' : 'w-64'">
        <a href="{{ $isOwnerConsole ? route('admin.tenants.index') : route('dashboard') }}" class="flex items-center gap-2 text-decoration-none">
            @if(!$isOwnerConsole && ($logo = \App\Models\Setting::get('system_logo')))
                <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="h-8 w-auto">
            @else
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-8 w-auto">
            @endif
            <div class="hidden lg:block leading-tight" x-show="!sidebarCollapsed" x-transition>
                <span class="block font-bold text-card-foreground tracking-tight truncate max-w-[160px]">
                    {{ $isOwnerConsole ? 'RestPOS SaaS' : (current_tenant()?->name ?? \App\Models\Setting::get('company_name', 'RestPOS')) }}
                </span>
                <span class="block text-[10px] text-primary font-bold uppercase tracking-wider">
                    {{ $isOwnerConsole ? 'OWNER CONSOLE' : 'POS SYSTEM' }}
                </span>
            </div>
        </a>
    </div>

    <!-- Main Navbar Content -->
    <div class="flex-1 flex items-center justify-between px-6 bg-card/95 transition-colors duration-200">
        <!-- Left Side: Toggle & Welcome -->
        <div class="flex items-center gap-4">
            <button class="lg:hidden p-2 text-muted-foreground hover:bg-accent hover:text-accent-foreground rounded-lg transition-colors"
                type="button" @click="sidebarOpen = !sidebarOpen">
                <i class="mdi mdi-menu text-2xl"></i>
            </button>

            @if(auth()->user()->role !== 'customer' && !$isOwnerConsole)
                <!-- Global Search -->
                <form action="{{ route('search') }}" method="GET" class="hidden md:block relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="mdi mdi-magnify text-muted-foreground group-focus-within:text-primary transition-colors text-base"></i>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Pesquisar produto, pedido, factura, cliente..."
                        class="block w-72 lg:w-80 pl-9 pr-4 py-1.5 border border-input rounded-lg leading-5 bg-muted/40 text-foreground placeholder:text-muted-foreground focus:outline-none focus:bg-background focus:ring-1 focus:ring-ring focus:border-ring text-xs font-medium transition-all duration-200 focus:w-96 shadow-2xs">
                </form>
            @endif

            <div class="hidden xl:block">
                <h1 class="text-sm font-medium text-muted-foreground">
                    {{ __('messages.welcome') }}, <span class="text-foreground font-semibold">{{ $displayUserName }}</span>
                </h1>
            </div>
        </div>

        <!-- Right Side: Actions & Profile -->
        <ul class="flex items-center gap-2">
            @if(!$isOwnerConsole && auth()->user()->role !== 'customer')
                <!-- Quick Actions Dropdown (Atalhos) -->
                <li class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border/80 text-xs font-medium text-foreground hover:bg-accent hover:text-accent-foreground transition-colors cursor-pointer shadow-2xs">
                        <i class="mdi mdi-lightning-bolt-outline text-base text-primary"></i>
                        <span>Atalhos</span>
                        <i class="mdi mdi-chevron-down text-xs text-muted-foreground"></i>
                    </button>

                    <div x-show="open" x-transition
                        class="absolute right-0 mt-2 w-52 bg-popover text-popover-foreground rounded-xl shadow-lg border border-border p-1 z-50"
                        style="display: none;">
                        <a href="{{ route('orders.index') }}"
                            class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg hover:bg-accent hover:text-accent-foreground transition-colors">
                            <i class="mdi mdi-cart-plus text-emerald-500"></i> {{ __('messages.orders') }}
                        </a>
                        <a href="{{ route('quotations.create') }}"
                            class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg hover:bg-accent hover:text-accent-foreground transition-colors">
                            <i class="mdi mdi-file-document-plus-outline text-sky-500"></i> Nova Cotação
                        </a>
                        <a href="{{ route('tables.index') }}"
                            class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg hover:bg-accent hover:text-accent-foreground transition-colors">
                            <i class="mdi mdi-qrcode-scan text-blue-500"></i> Mesas & QR Codes
                        </a>
                        <a href="{{ route('products.index', ['create' => 1]) }}"
                            class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg hover:bg-accent hover:text-accent-foreground transition-colors">
                            <i class="mdi mdi-food-variant text-amber-500"></i> {{ __('messages.add_product') }}
                        </a>
                        @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                            <div class="h-px bg-border/60 my-1"></div>
                            <a href="{{ route('settings.index') }}"
                                class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg hover:bg-accent hover:text-accent-foreground transition-colors">
                                <i class="mdi mdi-store-cog text-primary"></i> {{ __('messages.change_establishment_name') }}
                            </a>
                        @endif
                    </div>
                </li>
            @endif

            <!-- Language Switcher -->
            <li class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                    class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors flex items-center gap-1.5 text-xs font-bold uppercase">
                    <i class="mdi mdi-translate text-lg text-warning"></i>
                    <span>{{ app()->getLocale() == 'pt' ? 'PT' : 'EN' }}</span>
                    <i class="mdi mdi-chevron-down text-xs"></i>
                </button>

                <div x-show="open" x-transition
                    class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50"
                    style="display: none;">
                    <a href="{{ route('lang.switch', 'pt') }}"
                        class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 {{ app()->getLocale() == 'pt' ? 'bg-orange-50 dark:bg-gray-700 font-bold' : '' }}">
                        <span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-black">PT</span> {{ __('messages.portuguese') }}
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 {{ app()->getLocale() == 'en' ? 'bg-orange-50 dark:bg-gray-700 font-bold' : '' }}">
                        <span class="px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 text-[10px] font-black">EN</span> {{ __('messages.english') }}
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

            <!-- Notifications & Live Waiter Call / Order Alert -->
            <li class="relative" x-data="{ 
                open: false,
                unreadCount: {{ Auth::user()->unreadNotifications->count() }},
                activeWaiterAlert: null,
                knownIds: [
                    @foreach(Auth::user()->unreadNotifications->take(10) as $notification)
                        '{{ $notification->id }}',
                    @endforeach
                ],
                notifications: [
                    @foreach(Auth::user()->unreadNotifications->take(10) as $notification)
                        {
                            id: '{{ $notification->id }}',
                            type: '{{ $notification->data['type'] ?? 'general' }}',
                            table_name: '{{ addslashes($notification->data['table_name'] ?? '') }}',
                            customer_name: '{{ addslashes($notification->data['customer_name'] ?? '') }}',
                            reason: '{{ addslashes($notification->data['reason'] ?? '') }}',
                            message: '{{ addslashes($notification->data['message'] ?? 'Notificação') }}',
                            icon: '{{ $notification->data['icon'] ?? 'mdi-bell-outline' }}',
                            color: '{{ $notification->data['color'] ?? 'text-amber-500' }}',
                            link: '{{ $notification->data['link'] ?? '#' }}',
                            time: '{{ $notification->created_at->diffForHumans() }}'
                        },
                    @endforeach
                ],
                init() {
                    setInterval(() => {
                        this.fetchNotifications();
                    }, 6000);
                },
                playWaiterBellSound() {
                    try {
                        const ctx = new (window.AudioContext || window.webkitAudioContext)();
                        const playStrike = (startTime, freq1, freq2) => {
                            const osc1 = ctx.createOscillator();
                            const osc2 = ctx.createOscillator();
                            const gain = ctx.createGain();
                            osc1.type = 'sine';
                            osc2.type = 'triangle';
                            osc1.frequency.setValueAtTime(freq1, startTime);
                            osc2.frequency.setValueAtTime(freq2, startTime);
                            gain.gain.setValueAtTime(0.35, startTime);
                            gain.gain.exponentialRampToValueAtTime(0.001, startTime + 0.55);
                            osc1.connect(gain);
                            osc2.connect(gain);
                            gain.connect(ctx.destination);
                            osc1.start(startTime);
                            osc2.start(startTime);
                            osc1.stop(startTime + 0.55);
                            osc2.stop(startTime + 0.55);
                        };
                        const now = ctx.currentTime;
                        // Classic 3-chime hotel/restaurant service bell (Ding-Dong-Ding!)
                        playStrike(now, 987.77, 1975.53);        // B5
                        playStrike(now + 0.28, 783.99, 1567.98); // G5
                        playStrike(now + 0.58, 1174.66, 2349.32);// D6
                    } catch (e) {
                        console.warn('Audio alert blocked until user interaction:', e);
                    }
                },
                playNewOrderSound() {
                    try {
                        const ctx = new (window.AudioContext || window.webkitAudioContext)();
                        const now = ctx.currentTime;
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(523.25, now); // C5
                        osc.frequency.setValueAtTime(659.25, now + 0.14); // E5
                        osc.frequency.setValueAtTime(783.99, now + 0.28); // G5
                        gain.gain.setValueAtTime(0.28, now);
                        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.7);
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        osc.start(now);
                        osc.stop(now + 0.7);
                    } catch (e) {}
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
                        if (data && Array.isArray(data.notifications)) {
                            const newItems = data.notifications.filter(n => !this.knownIds.includes(n.id));
                            if (newItems.length > 0) {
                                newItems.forEach(n => this.knownIds.push(n.id));
                                const waiterCall = newItems.find(n => n.type === 'waiter_called');
                                if (waiterCall) {
                                    this.activeWaiterAlert = waiterCall;
                                    this.playWaiterBellSound();
                                    if (window.showToast) {
                                        window.showToast(waiterCall.message, 'warning', { title: 'Chamado de Garçom!', duration: 8000 });
                                    }
                                } else {
                                    this.playNewOrderSound();
                                    if (window.showToast) {
                                        window.showToast(newItems[0].message, 'info', { title: 'Nova Notificação', duration: 6000 });
                                    }
                                }
                            }
                            this.unreadCount = data.unreadCount;
                            this.notifications = data.notifications;
                        }
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
                },
                acknowledgeWaiterAlert() {
                    if (!this.activeWaiterAlert) return;
                    const id = this.activeWaiterAlert.id;
                    this.activeWaiterAlert = null;
                    fetch('{{ url('/notifications') }}/' + id + '/read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        this.fetchNotifications();
                    });
                },
                markAsRead(id, link) {
                    fetch('{{ url('/notifications') }}/' + id + '/read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        if (link && link !== '#') {
                            window.location.href = link;
                        } else {
                            this.fetchNotifications();
                        }
                    });
                }
            }">
                <button @click="open = !open" @click.outside="open = false"
                    class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors relative">
                    <i class="mdi mdi-bell-outline text-xl" :class="unreadCount > 0 ? 'text-warning animate-pulse' : ''"></i>
                    <template x-if="unreadCount > 0">
                        <span class="absolute top-1.5 right-1.5 min-w-[16px] h-4 px-1 bg-rose-600 text-white text-[10px] font-black rounded-full flex items-center justify-center"
                              x-text="unreadCount"></span>
                    </template>
                </button>

                <!-- Alerta Sonoro & Visual Flutuante quando Cliente Chama Garçom -->
                <template x-if="activeWaiterAlert">
                    <div class="fixed inset-x-4 top-20 z-50 max-w-md mx-auto">
                        <div class="bg-gray-950 text-white rounded-2xl shadow-2xl border-2 border-amber-400 p-4 flex items-start gap-3.5 animate-bounce">
                            <div class="w-12 h-12 rounded-2xl bg-amber-500 text-gray-950 flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/30">
                                <i class="mdi mdi-bell-ring text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-2 py-0.5 rounded-md bg-amber-500/20 text-amber-300 text-[10px] font-black uppercase tracking-wider">
                                        Chamado de Atendimento
                                    </span>
                                    <span class="text-[11px] text-gray-400">Agora mesmo</span>
                                </div>
                                <h4 class="text-sm font-black text-white mt-1" x-text="activeWaiterAlert.table_name || 'Mesa / Balcão'"></h4>
                                <p class="text-xs text-gray-300 mt-0.5" x-text="activeWaiterAlert.message"></p>
                                <div class="mt-3 flex items-center gap-2">
                                    <button type="button"
                                            @click="acknowledgeWaiterAlert()"
                                            class="flex-1 py-2 px-3 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-extrabold transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                        <i class="mdi mdi-check-circle-outline"></i>
                                        <span>Atender Agora</span>
                                    </button>
                                    <button type="button"
                                            @click="playWaiterBellSound()"
                                            class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-amber-300 text-xs"
                                            title="Repetir Som">
                                        <i class="mdi mdi-volume-high"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="open" x-transition
                    class="absolute right-0 mt-2 w-80 bg-popover text-popover-foreground rounded-xl shadow-lg border border-border z-50 overflow-hidden"
                    style="display: none;">
                    <div
                        class="px-4 py-3 border-b border-border/70 flex justify-between items-center bg-muted/40">
                        <h6 class="text-xs font-semibold text-foreground uppercase tracking-wider">
                            {{ __('messages.notifications') }}
                        </h6>
                        <span x-text="unreadCount + ' {{ __('messages.new_plural') }}'"
                            class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-primary/10 text-primary"></span>
                    </div>

                    <div class="max-h-96 overflow-y-auto">
                        <template x-if="notifications.length > 0">
                            <div class="divide-y divide-border/50">
                                <template x-for="notification in notifications" :key="notification.id">
                                    <button @click="markAsRead(notification.id, notification.link)"
                                        class="w-full text-left px-4 py-3 hover:bg-accent/60 transition-colors flex gap-3 cursor-pointer">
                                        <div :class="notification.color + ' mt-1 flex-shrink-0'">
                                            <i :class="'mdi ' + notification.icon + ' text-xl'"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-foreground font-medium leading-snug"
                                                x-text="notification.message"></p>
                                            <p class="text-[11px] text-muted-foreground mt-1"
                                                x-text="notification.time"></p>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </template>

                        <template x-if="notifications.length === 0">
                            <div class="p-8 text-center text-muted-foreground">
                                <i class="mdi mdi-bell-off-outline text-3xl mb-2 block opacity-50"></i>
                                <p class="text-xs font-medium">{{ __('messages.no_new_notifications') }}</p>
                            </div>
                        </template>
                    </div>

                    <div
                        class="px-4 py-2 border-t border-border/70 text-center bg-muted/30">
                        <a href="{{ route('notifications.index') }}"
                            class="text-xs font-semibold text-primary hover:underline">
                            {{ __('messages.view_all_notifications') }}
                        </a>
                    </div>
                </div>
            </li>

            <!-- User Profile -->
            <li class="relative ml-2" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                    class="flex items-center gap-2.5 hover:bg-accent hover:text-accent-foreground p-1.5 rounded-lg transition-colors cursor-pointer border border-transparent hover:border-border/60">
                    @if (Auth::user()->profile_photo_path)
                        <img class="size-8 rounded-full object-cover border border-border"
                            src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Perfil">
                    @else
                        <div
                            class="size-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm border border-primary/20">
                            <i class="mdi mdi-account text-base"></i>
                        </div>
                    @endif
                    <div class="hidden md:block text-left leading-tight">
                        <p class="text-xs font-semibold text-foreground leading-none">
                            {{ $displayUserName }}
                        </p>
                        <p class="text-[10px] text-muted-foreground mt-0.5 capitalize">{{ Auth::user()->role ?? 'Usuário' }}
                        </p>
                    </div>
                    <i class="mdi mdi-chevron-down text-muted-foreground text-xs"></i>
                </button>

                <div x-show="open" x-transition
                    class="absolute right-0 mt-2 w-56 bg-popover text-popover-foreground rounded-xl shadow-lg border border-border p-1 z-50"
                    style="display: none;">
                    <div class="px-3 py-2.5 border-b border-border/70 mb-1">
                        <p class="text-xs font-semibold text-foreground leading-tight">{{ $displayUserName }}</p>
                        <p class="text-[11px] text-muted-foreground truncate mt-0.5">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        @if(session('is_support_mode'))
                            <form action="{{ route('admin.tenants.leave-impersonate') }}" method="POST" class="px-1 py-0.5">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2 px-2.5 py-1.5 rounded-md text-xs font-bold text-amber-900 bg-amber-100 hover:bg-amber-200 dark:bg-amber-950/70 dark:text-amber-200 transition-colors cursor-pointer">
                                    <i class="mdi mdi-exit-to-app text-sm"></i>
                                    <span>Sair do Modo Suporte</span>
                                </button>
                            </form>
                            <div class="h-px bg-border/60 my-1"></div>
                        @endif
                        @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('admin.tenants.index') }}"
                                class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-primary hover:bg-accent rounded-lg transition-colors">
                                <i class="mdi mdi-shield-crown-outline"></i> Painel do Dono (SaaS)
                            </a>
                        @endif
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg hover:bg-accent hover:text-accent-foreground transition-colors">
                            <i class="mdi mdi-account-edit-outline text-muted-foreground"></i> {{ __('messages.edit_profile') }}
                        </a>
                        @if(auth()->user()->role !== 'customer' && !$isOwnerConsole)
                            <a href="{{ route('settings.index') }}"
                                class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg hover:bg-accent hover:text-accent-foreground transition-colors">
                                <i class="mdi mdi-cog-outline text-muted-foreground"></i> {{ __('messages.system_settings') }}
                            </a>
                        @endif
                        <div class="h-px bg-border/60 my-1"></div>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-destructive hover:bg-destructive/10 rounded-lg transition-colors">
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