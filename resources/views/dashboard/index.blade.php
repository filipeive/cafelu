@extends('layouts.app')

@section('title', 'Dashboard - ' . \App\Models\Setting::get('company_name', 'POS'))
@section('icon', 'view-dashboard')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-1">
            <div class="flex flex-col gap-1">
                <h1 class="text-2xl font-bold tracking-tight text-foreground flex items-center gap-2.5">
                    <i class="mdi mdi-view-dashboard text-primary"></i> Dashboard
                </h1>
                <p class="text-xs sm:text-sm text-muted-foreground">
                    Acompanhamento operacional, desempenho de vendas e status em tempo real.
                </p>
            </div>
            <div class="flex items-center gap-2.5">
                <x-ui.button variant="outline" size="sm" onclick="refreshDashboard()" title="Atualizar dados">
                    <i class="mdi mdi-refresh text-base"></i>
                    <span>Atualizar</span>
                </x-ui.button>
                <x-ui.button href="{{ route('pos.index') }}" variant="default" size="sm">
                    <i class="mdi mdi-cash-register text-base"></i>
                    <span>Ir para POS</span>
                </x-ui.button>
            </div>
        </div>

        <!-- Stats Cards (shadcn KPI style) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <!-- Vendas Hoje -->
            <x-ui.card class="hover:border-border transition-colors">
                <x-ui.card-header class="flex-row items-center justify-between pb-2 space-y-0">
                    <x-ui.card-title class="text-xs font-medium text-muted-foreground">Vendas Hoje</x-ui.card-title>
                    <div class="size-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                        <i class="mdi mdi-cash-multiple text-lg"></i>
                    </div>
                </x-ui.card-header>
                <x-ui.card-content class="flex flex-col gap-1.5">
                    <div class="text-2xl font-bold tracking-tight text-foreground">{{ number_format($todaySales ?? 0, 2, ',', '.') }} MT</div>
                    <div class="flex items-center gap-1.5 text-xs text-muted-foreground">
                        <span class="inline-flex items-center gap-0.5 font-semibold {{ ($salesGrowth ?? 0) >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-destructive' }}">
                            <i class="mdi mdi-arrow-{{ ($salesGrowth ?? 0) >= 0 ? 'up' : 'down' }}-bold"></i>
                            {{ number_format(abs($salesGrowth ?? 0), 1) }}%
                        </span>
                        <span>vs. dia anterior</span>
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            <!-- Pedidos Ativos -->
            <x-ui.card class="hover:border-border transition-colors">
                <x-ui.card-header class="flex-row items-center justify-between pb-2 space-y-0">
                    <x-ui.card-title class="text-xs font-medium text-muted-foreground">Pedidos Ativos</x-ui.card-title>
                    <div class="size-8 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <i class="mdi mdi-cart-outline text-lg"></i>
                    </div>
                </x-ui.card-header>
                <x-ui.card-content class="flex flex-col gap-1.5">
                    <div class="text-2xl font-bold tracking-tight text-foreground">{{ $openOrders ?? 0 }}</div>
                    <div class="flex items-center justify-between text-xs text-muted-foreground">
                        <span>Pendentes: {{ $pendingOrders ?? 0 }}</span>
                        <a href="{{ route('orders.index') }}" class="text-primary hover:underline font-medium inline-flex items-center gap-0.5">
                            <span>Ver Todos</span>
                            <i class="mdi mdi-arrow-right text-xs"></i>
                        </a>
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            <!-- Estoque Baixo -->
            <x-ui.card class="hover:border-border transition-colors">
                <x-ui.card-header class="flex-row items-center justify-between pb-2 space-y-0">
                    <x-ui.card-title class="text-xs font-medium text-muted-foreground">Estoque Baixo</x-ui.card-title>
                    <div class="size-8 rounded-lg bg-destructive/10 text-destructive flex items-center justify-center">
                        <i class="mdi mdi-alert-circle-outline text-lg"></i>
                    </div>
                </x-ui.card-header>
                <x-ui.card-content class="flex flex-col gap-1.5">
                    <div class="text-2xl font-bold tracking-tight text-foreground">{{ $lowStockProducts->count() ?? 0 }}</div>
                    <div class="flex items-center justify-between text-xs text-muted-foreground">
                        <span>Total: {{ $totalProducts ?? 0 }}</span>
                        <a href="{{ route('products.index') }}" class="text-primary hover:underline font-medium inline-flex items-center gap-0.5">
                            <span>Gerenciar</span>
                            <i class="mdi mdi-arrow-right text-xs"></i>
                        </a>
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            <!-- Status Mesas -->
            <x-ui.card class="hover:border-border transition-colors">
                <x-ui.card-header class="flex-row items-center justify-between pb-2 space-y-0">
                    <x-ui.card-title class="text-xs font-medium text-muted-foreground">Status das Mesas</x-ui.card-title>
                    <div class="size-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <i class="mdi mdi-table-furniture text-lg"></i>
                    </div>
                </x-ui.card-header>
                <x-ui.card-content class="flex flex-col gap-1.5">
                    <div class="text-2xl font-bold tracking-tight text-foreground">{{ $availableTables ?? 0 }} <span class="text-sm font-normal text-muted-foreground">/ {{ $tables->count() ?? 0 }} livres</span></div>
                    <div class="flex items-center justify-between text-xs text-muted-foreground">
                        <span class="text-amber-600 dark:text-amber-400 font-medium">{{ $occupiedTables ?? 0 }} ocupadas</span>
                        <a href="{{ route('tables.index') }}" class="text-primary hover:underline font-medium inline-flex items-center gap-0.5">
                            <span>Ver Mesas</span>
                            <i class="mdi mdi-arrow-right text-xs"></i>
                        </a>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        </div>

        <!-- Charts and Info -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Sales Chart -->
            <div class="xl:col-span-2">
                <x-ui.card class="h-full">
                    <x-ui.card-header class="flex-row items-center justify-between border-b border-border/60 pb-4">
                        <div class="flex flex-col gap-0.5">
                            <x-ui.card-title class="flex items-center gap-2 text-base">
                                <i class="mdi mdi-chart-line text-primary"></i>
                                Desempenho de Vendas
                            </x-ui.card-title>
                            <x-ui.card-description>Análise comparativa das vendas do restaurante</x-ui.card-description>
                        </div>
                        <div class="inline-flex items-center rounded-lg bg-muted p-1 text-muted-foreground">
                            <button type="button"
                                class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1 text-xs font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 active bg-background text-foreground shadow-xs"
                                onclick="changeChartPeriod('daily')">
                                Diário
                            </button>
                            <button type="button"
                                class="inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1 text-xs font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 hover:text-foreground"
                                onclick="changeChartPeriod('hourly')">
                                Por Hora
                            </button>
                        </div>
                    </x-ui.card-header>
                    <x-ui.card-content class="pt-6">
                        <div class="h-80 w-full">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </x-ui.card-content>
                </x-ui.card>
            </div>

            <!-- Real-time Status -->
            <div class="xl:col-span-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-3.5">
                    <x-ui.card class="p-4 flex items-center gap-4">
                        <div class="size-11 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                            <i class="mdi mdi-cart-plus text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-muted-foreground">Pedidos em Andamento</p>
                            <h4 class="text-xl font-bold tracking-tight text-foreground">{{ $openOrders ?? 0 }}</h4>
                        </div>
                    </x-ui.card>

                    <x-ui.card class="p-4 flex items-center gap-4">
                        <div class="size-11 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                            <i class="mdi mdi-check-circle text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-muted-foreground">Pedidos Hoje</p>
                            <h4 class="text-xl font-bold tracking-tight text-foreground">{{ $completedOrdersToday ?? 0 }}</h4>
                        </div>
                    </x-ui.card>

                    <x-ui.card class="p-4 flex items-center gap-4">
                        <div class="size-11 rounded-xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center flex-shrink-0">
                            <i class="mdi mdi-account-multiple text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-muted-foreground">Novos Clientes</p>
                            <h4 class="text-xl font-bold tracking-tight text-foreground">+{{ $newClientsToday ?? 0 }}</h4>
                        </div>
                    </x-ui.card>

                    <x-ui.card class="p-4 flex items-center gap-4">
                        <div class="size-11 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
                            <i class="mdi mdi-calendar-clock text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-muted-foreground">Vendas da Semana</p>
                            <h4 class="text-xl font-bold tracking-tight text-foreground">
                                {{ number_format($weekSales ?? 0, 2, ',', '.') }} MT</h4>
                        </div>
                    </x-ui.card>
                </div>
            </div>
        </div>

        <!-- Top Products and Recent Orders -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <!-- Top Products -->
            <x-ui.card class="h-full">
                <x-ui.card-header class="border-b border-border/60 pb-3">
                    <x-ui.card-title class="flex items-center gap-2 text-base">
                        <i class="mdi mdi-trophy text-amber-500"></i>
                        Produtos Mais Vendidos
                    </x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content class="pt-4">
                    <div class="flex flex-col gap-2.5">
                        @forelse($topProducts ?? [] as $index => $product)
                            <div class="flex items-center gap-3.5 p-2.5 rounded-lg hover:bg-muted/60 transition-colors">
                                <div class="size-7 flex items-center justify-center rounded-full bg-muted text-muted-foreground font-bold text-xs flex-shrink-0">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-grow min-w-0">
                                    <h6 class="text-sm font-medium text-foreground truncate">{{ $product->name }}</h6>
                                    <p class="text-xs text-muted-foreground truncate">
                                        {{ optional($product->category)->name ?? 'Sem categoria' }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <x-ui.badge variant="secondary">
                                        {{ $product->total_sold ?? 0 }} vendas
                                    </x-ui.badge>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted-foreground text-sm py-6">Nenhum produto vendido</p>
                        @endforelse
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            <!-- Recent Orders -->
            <x-ui.card class="h-full overflow-hidden">
                <x-ui.card-header class="flex-row items-center justify-between border-b border-border/60 pb-3 space-y-0">
                    <x-ui.card-title class="flex items-center gap-2 text-base">
                        <i class="mdi mdi-clock-fast text-primary"></i>
                        Pedidos Recentes
                    </x-ui.card-title>
                    <a href="{{ route('orders.index') }}"
                        class="text-xs text-primary hover:underline font-medium transition-colors inline-flex items-center gap-1">
                        <span>Ver Todos</span>
                        <i class="mdi mdi-arrow-right text-xs"></i>
                    </a>
                </x-ui.card-header>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead class="bg-muted/50 text-muted-foreground text-xs uppercase font-medium border-b border-border/60">
                            <tr>
                                <th class="px-4 py-2.5">ID</th>
                                <th class="px-4 py-2.5">Cliente</th>
                                <th class="px-4 py-2.5">Total</th>
                                <th class="px-4 py-2.5">Status</th>
                                <th class="px-4 py-2.5">Hora</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/50">
                            @forelse($recentOrders ?? [] as $order)
                                <tr class="hover:bg-muted/40 transition-colors">
                                    <td class="px-4 py-2.5 font-medium text-foreground">#{{ $order->id }}</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">{{ $order->client_name ?? 'Consumidor Final' }}</td>
                                    <td class="px-4 py-2.5 font-semibold text-foreground">{{ number_format($order->total_amount, 2, ',', '.') }} MT</td>
                                    <td class="px-4 py-2.5">
                                        <x-ui.badge variant="{{ ($order->status ?? '') === 'completed' ? 'success' : (($order->status ?? '') === 'cancelled' ? 'destructive' : 'secondary') }}">
                                            {{ $order->status_label ?? 'N/A' }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-4 py-2.5 text-xs text-muted-foreground">{{ $order->created_at->format('H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-muted-foreground text-sm">Nenhum pedido recente</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </div>

        <!-- Tables and Low Stock -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <!-- Tables Status -->
            <x-ui.card class="h-full">
                <x-ui.card-header class="flex-row items-center justify-between border-b border-border/60 pb-3 space-y-0">
                    <x-ui.card-title class="flex items-center gap-2 text-base">
                        <i class="mdi mdi-table-furniture text-primary"></i>
                        Status das Mesas
                    </x-ui.card-title>
                    <a href="{{ route('tables.index') }}" class="text-xs text-primary hover:underline font-medium transition-colors inline-flex items-center gap-1">
                        <span>Ver Todas</span>
                        <i class="mdi mdi-arrow-right text-xs"></i>
                    </a>
                </x-ui.card-header>
                <x-ui.card-content class="pt-4">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @forelse($tables->take(8) ?? [] as $table)
                            <div class="rounded-xl border border-border/70 bg-card p-3 hover:border-border hover:shadow-xs transition-all cursor-pointer flex flex-col items-center text-center h-full select-none"
                                role="button" tabindex="0"
                                onclick="openTableInstructionsModal({{ $table->id }}, '{{ $table->number }}', '{{ $table->status }}')"
                                data-status="{{ $table->status }}">
                                <div class="mb-1.5 size-10 rounded-full flex items-center justify-center {{ $table->status === 'occupied' ? 'bg-destructive/10 text-destructive' : 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' }}">
                                    <i class="mdi mdi-table-furniture text-xl"></i>
                                </div>
                                <h6 class="font-semibold text-foreground text-sm">Mesa {{ $table->number }}</h6>
                                <span class="text-xs text-muted-foreground mb-2">{{ $table->capacity }} lugares</span>

                                @if (isset($table->current_order) && $table->current_order)
                                    <div class="w-full mb-2 p-1.5 rounded-lg bg-amber-500/10 border border-amber-500/20 text-left">
                                        <span class="block truncate text-amber-600 dark:text-amber-400 text-[11px] font-bold">
                                            #{{ $table->current_order->id ?? '—' }}
                                        </span>
                                        <span class="text-[11px] text-muted-foreground font-semibold">
                                            {{ number_format($table->current_order->total_amount ?? 0, 2, ',', '.') }} MT
                                        </span>
                                    </div>
                                @endif

                                <div class="mt-auto pt-1">
                                    <x-ui.badge variant="{{ $table->status === 'occupied' ? 'destructive' : 'success' }}" class="text-[10px] px-2 py-0">
                                        {{ $table->status === 'occupied' ? 'Ocupada' : 'Disponível' }}
                                    </x-ui.badge>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full">
                                <p class="text-center text-muted-foreground text-sm py-4">Nenhuma mesa cadastrada</p>
                            </div>
                        @endforelse
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            <!-- Low Stock -->
            <x-ui.card class="h-full">
                <x-ui.card-header class="flex-row items-center justify-between border-b border-border/60 pb-3 space-y-0">
                    <x-ui.card-title class="flex items-center gap-2 text-base">
                        <i class="mdi mdi-alert text-destructive"></i>
                        Estoque Baixo
                    </x-ui.card-title>
                    <a href="{{ route('products.index') }}" class="text-xs text-primary hover:underline font-medium transition-colors inline-flex items-center gap-1">
                        <span>Ver Todos</span>
                        <i class="mdi mdi-arrow-right text-xs"></i>
                    </a>
                </x-ui.card-header>
                <x-ui.card-content class="pt-4">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @forelse($lowStockProducts->take(8) ?? [] as $product)
                            <div class="rounded-xl border border-border/70 bg-card p-2.5 hover:border-border hover:shadow-xs transition-all cursor-pointer h-full flex flex-col justify-between"
                                role="button" tabindex="0"
                                onclick="openProductInstructionsModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock_quantity }})">
                                <div class="aspect-square mb-2 bg-muted rounded-lg overflow-hidden flex items-center justify-center">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="mdi mdi-food text-2xl text-muted-foreground"></i>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="font-medium text-foreground text-xs truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                                    <span class="text-destructive font-semibold text-[11px] block mt-0.5">Qtd: {{ $product->stock_quantity }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full">
                                <p class="text-center text-muted-foreground text-sm py-4">Estoque adequado</p>
                            </div>
                        @endforelse
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        </div>

        <!-- Quick Actions -->
        <x-ui.card>
            <x-ui.card-header class="border-b border-border/60 pb-3">
                <x-ui.card-title class="flex items-center gap-2 text-base">
                    <i class="mdi mdi-lightning-bolt text-amber-500"></i>
                    Ações Rápidas
                </x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="pt-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    <a href="{{ route('pos.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-border/70 bg-card hover:bg-accent hover:text-accent-foreground transition-all group shadow-2xs">
                        <div class="size-10 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                            <i class="mdi mdi-cash-register text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-foreground group-hover:text-primary">Novo Pedido</span>
                    </a>

                    <a href="{{ route('tables.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-border/70 bg-card hover:bg-accent hover:text-accent-foreground transition-all group shadow-2xs">
                        <div class="size-10 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                            <i class="mdi mdi-table-furniture text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-foreground group-hover:text-primary">Mesas</span>
                    </a>

                    <a href="{{ route('products.create') }}" class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-border/70 bg-card hover:bg-accent hover:text-accent-foreground transition-all group shadow-2xs">
                        <div class="size-10 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                            <i class="mdi mdi-plus-circle text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-foreground group-hover:text-primary">Add Produto</span>
                    </a>

                    <a href="{{ route('clients.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-border/70 bg-card hover:bg-accent hover:text-accent-foreground transition-all group shadow-2xs">
                        <div class="size-10 rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                            <i class="mdi mdi-account-plus text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-foreground group-hover:text-primary">Novo Cliente</span>
                    </a>

                    <a href="{{ route('reports.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-border/70 bg-card hover:bg-accent hover:text-accent-foreground transition-all group shadow-2xs">
                        <div class="size-10 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                            <i class="mdi mdi-chart-bar text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-foreground group-hover:text-primary">Relatórios</span>
                    </a>

                    <a href="#" class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-border/70 bg-card opacity-50 cursor-not-allowed">
                        <div class="size-10 rounded-lg bg-pink-500/10 text-pink-600 dark:text-pink-400 flex items-center justify-center mb-2">
                            <i class="mdi mdi-calendar-plus text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-muted-foreground">Reserva</span>
                    </a>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>

            <!-- Table Instructions Modal -->
        <div x-data="{ 
                open: false, 
                table: { id: null, number: '', status: '' },
                get isOccupied() { return this.table.status === 'occupied'; },
                get statusColor() { return this.isOccupied ? 'text-red-500' : 'text-green-500'; },
                get statusText() { return this.isOccupied ? 'Ocupada' : 'Disponível'; },
                get statusBadge() { return this.isOccupied ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400'; }
             }"
             @open-table-modal.window="open = true; table = $event.detail"
             x-show="open"
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            
            <!-- Backdrop -->
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 transition-opacity" 
                 @click="open = false"></div>

            <!-- Panel -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="open" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="absolute top-0 right-0 pt-4 pr-4">
                            <button @click="open = false" type="button" class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="sr-only">Fechar</span>
                                <i class="mdi mdi-close text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="text-center mb-6">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                <i class="mdi mdi-table-furniture text-3xl" :class="statusColor"></i>
                            </div>
                            <h3 class="text-xl font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title" x-text="'Mesa ' + table.number"></h3>
                            <div class="mt-2">
                                <span class="px-2 py-1 text-xs font-medium rounded-full" :class="statusBadge" x-text="statusText"></span>
                            </div>
                        </div>

                        <div class="mb-6 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900/30">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="mdi mdi-information-outline text-blue-400"></i>
                                </div>
                                <div class="ml-3 flex-1 md:flex md:justify-between">
                                    <p class="text-sm text-blue-700 dark:text-blue-300" x-show="isOccupied">
                                        <strong>Esta mesa está ocupada.</strong> Você pode visualizar o pedido atual ou fechar a conta na página de gerenciamento de mesas.
                                    </p>
                                    <p class="text-sm text-blue-700 dark:text-blue-300" x-show="!isOccupied">
                                        <strong>Esta mesa está livre.</strong> Clique abaixo para iniciar um novo pedido.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                            <!-- Iniciar Pedido (POST) -->
                            <form x-show="!isOccupied" :action="'{{ route('tables.create-order', ':tableId') }}'.replace(':tableId', table.id)" method="POST" class="sm:col-start-2">
                                @csrf
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-warning px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-warning/90">
                                    <i class="mdi mdi-cart-plus me-2"></i>
                                    Iniciar Pedido
                                </button>
                            </form>

                            <!-- Ir para Mesas (GET) -->
                            <a x-show="isOccupied" href="{{ route('tables.index') }}" class="inline-flex w-full justify-center rounded-md bg-warning px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-warning/90 sm:col-start-2">
                                <i class="mdi mdi-table-furniture me-2"></i>
                                Ir para Mesas
                            </a>

                            <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:col-start-1 sm:mt-0" @click="open = false">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Instructions Modal -->
        <div x-data="{ 
                open: false, 
                product: { id: null, name: '', stock: 0 }
             }"
             @open-product-modal.window="open = true; product = $event.detail"
             x-show="open"
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            
            <!-- Backdrop -->
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 transition-opacity" 
                 @click="open = false"></div>

            <!-- Panel -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="open" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="absolute top-0 right-0 pt-4 pr-4">
                            <button @click="open = false" type="button" class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="sr-only">Fechar</span>
                                <i class="mdi mdi-close text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="text-center mb-6">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                                <i class="mdi mdi-alert-circle text-3xl text-red-600 dark:text-red-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title" x-text="product.name"></h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Estoque Atual: <strong x-text="product.stock"></strong> unidades
                            </p>
                        </div>

                        <div class="mb-6 p-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-900/30">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="mdi mdi-alert text-amber-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-amber-700 dark:text-amber-300">
                                        Este produto está com estoque baixo. Acesse a página de produtos para realizar o reabastecimento.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                            <a href="{{ route('products.index') }}" class="inline-flex w-full justify-center rounded-md bg-warning px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-warning/90 sm:col-start-2">
                                <i class="mdi mdi-package-variant me-2"></i>
                                Ir para Produtos
                            </a>
                            <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:col-start-1 sm:mt-0" @click="open = false">
                                Fechar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Toast Container -->
            <div id="toastContainer" class="toast-container"></div>

            <!-- Loading Overlay -->
            <div id="loadingOverlay" class="loading-overlay">
                <div class="loading-spinner"></div>
            </div>
@endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                initializeChart();
            });

            let salesChart = null;
            const dailySalesData = @json($dailySales ?? []);
            const hourlySalesData = @json($hourlySales ?? []);

            function initializeChart(type = 'daily') {
                const ctx = document.getElementById('salesChart');
                if (!ctx) return;

                const data = type === 'daily' ? dailySalesData : hourlySalesData;
                const labels = data.map(item => type === 'daily' ? item.day : item.hour);
                const values = data.map(item => parseFloat(item.sales) || 0);

                if (salesChart) salesChart.destroy();

                salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Vendas (MT)',
                            data: values,
                            borderColor: '#FFA500',
                            backgroundColor: 'rgba(255, 165, 0, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#FFA500',
                            pointBorderColor: '#1A1A2E',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 15, 30, 0.9)',
                                titleColor: '#FFA500',
                                bodyColor: '#e06500ff',
                                borderColor: 'rgba(255, 165, 0, 0.3)',
                                borderWidth: 1,
                                padding: 12,
                                callbacks: {
                                    label: ctx => `${ctx.parsed.y.toFixed(2)} MT`
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.05)'
                                },
                                ticks: {
                                    color: '#ff5e00ff',
                                    callback: value => value.toFixed(0) + ' MT'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#000000ff'
                                }
                            }
                        }
                    }
                });
            }

            function changeChartPeriod(type) {
                // Remove active classes from all buttons
                const buttons = event.target.closest('div').querySelectorAll('button');
                buttons.forEach(btn => {
                    btn.classList.remove('bg-white', 'dark:bg-gray-600', 'shadow-sm', 'text-gray-800', 'dark:text-white');
                    btn.classList.add('text-gray-500', 'dark:text-gray-400', 'hover:text-gray-700', 'dark:hover:text-gray-200');
                });

                // Add active classes to clicked button
                const activeBtn = event.target;
                activeBtn.classList.remove('text-gray-500', 'dark:text-gray-400', 'hover:text-gray-700', 'dark:hover:text-gray-200');
                activeBtn.classList.add('bg-white', 'dark:bg-gray-600', 'shadow-sm', 'text-gray-800', 'dark:text-white');

                initializeChart(type);
            }

            function refreshDashboard() {
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i>';

                fetch('{{ route('dashboard.stats') }}', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                    .then(r => r.json())
                    .then(data => {
                        showToast('Dashboard atualizado com sucesso!', 'success');
                        setTimeout(() => location.reload(), 500);
                    })
                    .catch(err => {
                        console.error('Erro:', err);
                        showToast('Erro ao atualizar dashboard', 'error');
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                    });
            }

            function openTableInstructionsModal(tableId, tableNumber, status) {
                window.dispatchEvent(new CustomEvent('open-table-modal', {
                    detail: {
                        id: tableId,
                        number: tableNumber,
                        status: status
                    }
                }));
            }

            function openProductInstructionsModal(productId, productName, stockQuantity) {
                window.dispatchEvent(new CustomEvent('open-product-modal', {
                    detail: {
                        id: productId,
                        name: productName,
                        stock: stockQuantity
                    }
                }));
            }
        </script>
    @endpush