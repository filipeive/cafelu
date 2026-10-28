@extends('layouts.app')

@section('title', 'Ordem de Cozinha (KDS)')

@section('content')
<div class="space-y-6" x-data="kitchenDisplay()">

    <!-- Top KDS Controls -->
    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <!-- Left Info -->
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-orange-500/10 border border-orange-500/20 text-orange-500 flex items-center justify-center text-3xl">
                    <i class="mdi mdi-chef-hat"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                            Ecrã de Cozinha (KDS)
                        </h1>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-300/60 animate-pulse">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            LIVE
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Gestão e monitorização de pedidos em tempo real para a cozinha e bar.
                    </p>
                </div>
            </div>

            <!-- Right Controls & Stats -->
            <div class="flex flex-wrap items-center gap-3">
                
                <!-- Quick Stats -->
                <div class="flex items-center gap-2 bg-gray-50 dark:bg-gray-900/80 border border-gray-200 dark:border-gray-700 rounded-2xl px-4 py-2 text-xs">
                    <span class="text-amber-600 dark:text-amber-400 font-bold flex items-center gap-1">
                        <i class="mdi mdi-clock-outline"></i> Novos: <strong class="text-gray-900 dark:text-white ml-0.5" x-text="stats.pending">{{ $stats['pending'] }}</strong>
                    </span>
                    <span class="text-gray-300 dark:text-gray-700">|</span>
                    <span class="text-sky-600 dark:text-sky-400 font-bold flex items-center gap-1">
                        <i class="mdi mdi-fire"></i> Preparando: <strong class="text-gray-900 dark:text-white ml-0.5" x-text="stats.preparing">{{ $stats['preparing'] }}</strong>
                    </span>
                    <span class="text-gray-300 dark:text-gray-700">|</span>
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1">
                        <i class="mdi mdi-check-circle-outline"></i> Prontos: <strong class="text-gray-900 dark:text-white ml-0.5" x-text="stats.ready">{{ $stats['ready'] }}</strong>
                    </span>
                </div>

                <!-- Auto-refresh button -->
                <button @click="autoRefresh = !autoRefresh"
                    :class="autoRefresh ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border-emerald-300 dark:border-emerald-700' : 'bg-gray-100 text-gray-500 border-gray-200'"
                    class="px-4 py-2.5 rounded-2xl border text-xs font-bold transition-all flex items-center gap-2 cursor-pointer">
                    <i class="mdi mdi-sync" :class="autoRefresh ? 'animate-spin' : ''"></i>
                    <span x-text="autoRefresh ? 'Auto (15s)' : 'Manual'"></span>
                </button>

                <!-- Audio alert toggle -->
                <button @click="soundEnabled = !soundEnabled"
                    :class="soundEnabled ? 'bg-orange-50 text-orange-600 border-orange-200 dark:bg-orange-950/30' : 'bg-gray-100 text-gray-400 border-gray-200'"
                    class="p-2.5 rounded-2xl border text-base transition-all cursor-pointer"
                    :title="soundEnabled ? 'Som ativado para novos pedidos' : 'Som desativado'">
                    <i class="mdi" :class="soundEnabled ? 'mdi-volume-high' : 'mdi-volume-off'"></i>
                </button>

                <!-- Refresh now -->
                <button @click="fetchData()" class="p-2.5 rounded-2xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 text-base transition-all cursor-pointer" title="Atualizar Agora">
                    <i class="mdi mdi-reload"></i>
                </button>

                <!-- Link to Tables / POS -->
                <a href="{{ route('tables.index') }}" class="px-4 py-2.5 rounded-2xl bg-gray-900 hover:bg-black dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-bold text-xs transition-all flex items-center gap-2">
                    <i class="mdi mdi-table-chair text-orange-400"></i>
                    <span>Mesas</span>
                </a>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 mt-5 pt-4 border-t border-gray-100 dark:border-gray-700/60 overflow-x-auto pb-1">
            <button @click="filter = 'all'"
                :class="filter === 'all' ? 'bg-orange-500 text-white shadow-sm shadow-orange-500/20' : 'bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-200'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap">
                Todos (<span x-text="stats.total">{{ $stats['total'] }}</span>)
            </button>
            <button @click="filter = 'active'"
                :class="filter === 'active' ? 'bg-amber-500 text-white shadow-sm shadow-amber-500/20' : 'bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-200'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-1.5">
                <i class="mdi mdi-clock-outline"></i>
                Novos (<span x-text="stats.pending">{{ $stats['pending'] }}</span>)
            </button>
            <button @click="filter = 'preparing'"
                :class="filter === 'preparing' ? 'bg-sky-500 text-white shadow-sm shadow-sky-500/20' : 'bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-200'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-1.5">
                <i class="mdi mdi-fire"></i>
                Em Preparo (<span x-text="stats.preparing">{{ $stats['preparing'] }}</span>)
            </button>
            <button @click="filter = 'ready'"
                :class="filter === 'ready' ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20' : 'bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-200'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-1.5">
                <i class="mdi mdi-check-circle-outline"></i>
                Prontos (<span x-text="stats.ready">{{ $stats['ready'] }}</span>)
            </button>
        </div>
    </div>

    <!-- Kitchen Orders Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        
        <!-- Foreach Orders from server, with fallback / interactive Alpine rendering -->
        <template x-for="order in filteredOrders" :key="order.id">
            <div class="rounded-3xl p-5 border-2 shadow-sm flex flex-col justify-between transition-all duration-300 relative overflow-hidden group"
                 :class="{
                     'border-amber-400/80 bg-white dark:bg-gray-800 shadow-amber-500/5': order.status === 'active',
                     'border-sky-400/80 bg-white dark:bg-gray-800 shadow-sky-500/5': order.status === 'preparing',
                     'border-emerald-400/80 bg-white dark:bg-gray-800 shadow-emerald-500/5': order.status === 'ready'
                 }">

                <!-- Card Header -->
                <div>
                    <div class="flex items-start justify-between border-b border-gray-100 dark:border-gray-700/80 pb-3 mb-3">
                        <div class="flex items-center gap-3">
                            <span class="w-12 h-12 rounded-2xl bg-gray-900 dark:bg-gray-700 text-white font-black text-sm flex items-center justify-center shadow-xs">
                                #<span x-text="order.id"></span>
                            </span>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-base font-black text-gray-900 dark:text-white" x-text="order.table_name || 'Balcão'"></h3>
                                    <template x-if="order.is_self_service">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200 dark:border-purple-800" title="Pedido feito pelo Cardápio Digital">
                                            <i class="mdi mdi-cellphone"></i>
                                            <span>Digital</span>
                                        </span>
                                    </template>
                                    <template x-if="order.has_additions">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-black bg-amber-100 text-amber-800 dark:bg-amber-950/70 dark:text-amber-200 border border-amber-300 dark:border-amber-700 animate-pulse" title="Itens adicionados à comanda">
                                            <i class="mdi mdi-bell-ring text-xs"></i>
                                            <span>+Itens Novos</span>
                                        </span>
                                    </template>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-0.5">
                                    <i class="mdi mdi-account-outline"></i>
                                    <span x-text="order.customer_name"></span>
                                </p>
                            </div>
                        </div>

                        <!-- Elapsed Badge & Time -->
                        <div class="text-right">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-bold"
                                  :class="order.elapsed_min > 20 ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 animate-pulse' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
                                <i class="mdi mdi-timer-outline"></i>
                                <span x-text="order.elapsed_min + ' min'"></span>
                            </span>
                            <div class="text-[10px] text-gray-400 mt-1 font-mono" x-text="order.created_at_fmt"></div>
                        </div>
                    </div>

                    <!-- Notes alert if any -->
                    <template x-if="order.notes">
                        <div class="mb-3 p-3 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-200 text-xs flex items-start gap-2">
                            <i class="mdi mdi-comment-alert text-base flex-shrink-0 text-amber-500 mt-0.5"></i>
                            <span class="font-medium" x-text="order.notes"></span>
                        </div>
                    </template>

                    <!-- Items List -->
                    <div class="space-y-2.5 my-3 max-h-72 overflow-y-auto pr-1">
                        <template x-for="item in order.items" :key="item.id">
                            <div class="border rounded-2xl p-3 flex items-start justify-between gap-3 transition-colors"
                                 :class="item.is_new ? 'bg-amber-50/80 dark:bg-amber-950/30 border-amber-300 dark:border-amber-700/80' : 'bg-gray-50 dark:bg-gray-900/60 border-gray-100 dark:border-gray-700/80'">
                                <div class="flex items-start gap-3">
                                    <span class="w-8 h-8 rounded-xl text-xs font-black flex items-center justify-center flex-shrink-0 mt-0.5"
                                          :class="item.is_new ? 'bg-amber-500 text-white shadow-xs' : 'bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-500/20'"
                                          x-text="item.quantity + 'x'">
                                    </span>
                                    <div>
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <h4 class="text-xs font-bold text-gray-900 dark:text-white" x-text="item.name"></h4>
                                            <template x-if="item.is_new">
                                                <span class="px-1.5 py-0.5 rounded-md bg-amber-500 text-white text-[9px] font-black uppercase tracking-wider">
                                                    Adicionado
                                                </span>
                                            </template>
                                        </div>
                                        <template x-if="item.notes">
                                            <p class="text-[11px] text-amber-600 dark:text-amber-400 italic mt-0.5 flex items-center gap-1 font-medium">
                                                <i class="mdi mdi-note-text-outline"></i>
                                                <span x-text="item.notes"></span>
                                            </p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Card Actions -->
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700/80 space-y-2">
                    
                    <!-- When Active: Iniciar Preparo -->
                    <template x-if="order.status === 'active'">
                        <button @click="updateStatus(order.id, 'preparing')"
                            class="w-full py-3.5 rounded-2xl bg-sky-500 hover:bg-sky-600 active:bg-sky-700 text-white font-black text-xs transition-all flex items-center justify-center gap-2 shadow-md shadow-sky-500/20 cursor-pointer">
                            <i class="mdi mdi-fire text-base"></i>
                            <span>INICIAR PREPARO</span>
                        </button>
                    </template>

                    <!-- When Preparing: Marcar como Pronto -->
                    <template x-if="order.status === 'preparing'">
                        <button @click="updateStatus(order.id, 'ready')"
                            class="w-full py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 text-white font-black text-xs transition-all flex items-center justify-center gap-2 shadow-md shadow-emerald-500/20 cursor-pointer">
                            <i class="mdi mdi-check-circle text-base"></i>
                            <span>MARCAR COMO PRONTO</span>
                        </button>
                    </template>

                    <!-- When Ready: Entregar ao Cliente/Mesa -->
                    <template x-if="order.status === 'ready'">
                        <button @click="updateStatus(order.id, 'delivered')"
                            class="w-full py-3.5 rounded-2xl bg-purple-600 hover:bg-purple-700 active:bg-purple-800 text-white font-black text-xs transition-all flex items-center justify-center gap-2 shadow-md shadow-purple-600/20 cursor-pointer">
                            <i class="mdi mdi-bell-ring text-base"></i>
                            <span>ENTREGUE AO CLIENTE / MESA</span>
                        </button>
                    </template>

                    <!-- Secondary Link: Ver Comanda / Detalhes -->
                    <div class="flex items-center justify-between text-[11px] pt-1 px-1">
                        <a :href="'{{ url('/orders') }}/' + order.id" class="text-gray-400 hover:text-orange-500 font-semibold flex items-center gap-1 transition-colors">
                            <i class="mdi mdi-clipboard-text-outline"></i>
                            <span>Ver Comanda</span>
                        </a>
                        <a :href="'{{ url('/orders') }}/' + order.id + '/print-receipt'" target="_blank" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 font-semibold flex items-center gap-1 transition-colors">
                            <i class="mdi mdi-printer"></i>
                            <span>Imprimir Ticket</span>
                        </a>
                    </div>
                </div>

            </div>
        </template>

        <!-- Empty State -->
        <div x-show="filteredOrders.length === 0" class="col-span-full py-20 bg-white dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-3xl text-center space-y-4">
            <div class="w-20 h-20 rounded-full bg-orange-50 dark:bg-orange-950/40 text-orange-500 mx-auto flex items-center justify-center text-4xl">
                <i class="mdi mdi-chef-hat"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Sem pedidos pendentes na cozinha</h3>
                <p class="text-xs text-gray-400 max-w-sm mx-auto mt-1">
                    Todos os pedidos estão em dia ou foram concluídos. Novos pedidos do POS ou Self-Service aparecerão automaticamente aqui.
                </p>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
function kitchenDisplay() {
    return {
        autoRefresh: true,
        soundEnabled: true,
        filter: 'all',
        stats: {
            pending: {{ $stats['pending'] }},
            preparing: {{ $stats['preparing'] }},
            ready: {{ $stats['ready'] }},
            total: {{ $stats['total'] }}
        },
        orders: [
            @foreach($orders as $order)
            {
                id: {{ $order->id }},
                status: '{{ $order->status }}',
                customer_name: '{{ addslashes($order->customer_name ?: ($order->user?->name ?? "Consumo Local")) }}',
                table_name: '{{ addslashes($order->table ? $order->table->name : "Balcão / Viagem") }}',
                notes: '{{ addslashes($order->notes ?? "") }}',
                is_self_service: {{ (str_contains($order->notes ?? '', '[Digital') || ($order->user && $order->user->role === 'customer')) ? 'true' : 'false' }},
                has_additions: {{ (str_contains($order->notes ?? '', '[Item Adicional]') || $order->items->contains(fn($i) => str_contains($i->notes ?? '', '[Novo'))) ? 'true' : 'false' }},
                elapsed_min: {{ $order->created_at ? $order->created_at->diffInMinutes(now()) : 0 }},
                created_at_fmt: '{{ $order->created_at ? $order->created_at->format("H:i") : "--:--" }}',
                items: [
                    @foreach($order->items as $item)
                    {
                        id: {{ $item->id }},
                        name: '{{ addslashes($item->product ? $item->product->name : ($item->notes ?? "Item")) }}',
                        quantity: {{ $item->quantity }},
                        notes: '{{ addslashes($item->notes ?? "") }}',
                        is_new: {{ str_contains($item->notes ?? '', '[Novo') ? 'true' : 'false' }},
                    },
                    @endforeach
                ]
            },
            @endforeach
        ],

        get filteredOrders() {
            if (this.filter === 'all') return this.orders;
            return this.orders.filter(o => o.status === this.filter);
        },

        init() {
            // Setup polling timer every 15 seconds
            setInterval(() => {
                if (this.autoRefresh) {
                    this.fetchData();
                }
            }, 15000);
        },

        fetchData() {
            fetch('{{ route("orders.kitchen.live") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const prevCount = this.stats.pending;
                    this.stats = data.stats;
                    this.orders = data.orders;

                    // Play sound if new pending orders arrived
                    if (this.soundEnabled && data.stats.pending > prevCount) {
                        this.playNotificationSound();
                    }
                }
            })
            .catch(err => console.error('KDS Fetch Error:', err));
        },

        updateStatus(orderId, newStatus) {
            fetch(`{{ url('/orders') }}/${orderId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.fetchData();
                } else {
                    window.location.reload();
                }
            })
            .catch(() => {
                // Fallback to page reload
                window.location.reload();
            });
        },

        playNotificationSound() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
                osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.15); // A5
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.4);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.4);
            } catch (e) {
                console.log('AudioContext not allowed or not supported yet');
            }
        }
    }
}
</script>
@endpush
@endsection

