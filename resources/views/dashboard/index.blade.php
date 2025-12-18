@extends('layouts.app')

@section('title', 'Dashboard - Zalala Beach Bar')
@section('icon', 'view-dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="mdi mdi-view-dashboard text-primary"></i> Dashboard
            </h1>
            <div class="flex gap-3">
                <a href="{{ route('pos.index') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-warning hover:bg-warning/90 text-white rounded-lg transition-colors font-medium">
                    <i class="mdi mdi-cash-register"></i> Ir para POS
                </a>
                <button
                    class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                    onclick="refreshDashboard()">
                    <i class="mdi mdi-refresh text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <!-- Vendas Hoje -->
            <div
                class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
                    <i class="mdi mdi-cash-multiple text-6xl"></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                            <i class="mdi mdi-cash-multiple text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Vendas Hoje</p>
                            <h3 class="text-2xl font-bold">{{ number_format($todaySales ?? 0, 2, ',', '.') }} MT</h3>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-blue-100">Vs ontem</span>
                        <span
                            class="flex items-center gap-1 {{ ($salesGrowth ?? 0) >= 0 ? 'text-green-300' : 'text-red-300' }} font-medium bg-white/10 px-2 py-0.5 rounded">
                            {{ number_format($salesGrowth ?? 0, 1) }}%
                            <i class="mdi mdi-arrow-{{ ($salesGrowth ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Pedidos Ativos -->
            <div
                class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
                    <i class="mdi mdi-cart text-6xl"></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                            <i class="mdi mdi-cart text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-amber-100 text-sm font-medium">Pedidos Ativos</p>
                            <h3 class="text-2xl font-bold">{{ $openOrders ?? 0 }}</h3>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-amber-100">Pendentes: {{ $pendingOrders ?? 0 }}</span>
                        <a href="{{ route('orders.index') }}"
                            class="text-white hover:text-amber-100 underline decoration-amber-300/50 hover:decoration-amber-100 transition-colors">
                            Ver Todos
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estoque Baixo -->
            <div
                class="bg-gradient-to-br from-red-500 to-rose-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
                    <i class="mdi mdi-alert-circle text-6xl"></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                            <i class="mdi mdi-alert-circle text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-red-100 text-sm font-medium">Estoque Baixo</p>
                            <h3 class="text-2xl font-bold">{{ $lowStockProducts->count() ?? 0 }}</h3>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-red-100">Total: {{ $totalProducts ?? 0 }}</span>
                        <a href="{{ route('products.index') }}"
                            class="px-2 py-1 bg-white/20 hover:bg-white/30 rounded text-xs transition-colors">
                            Gerenciar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Mesas -->
            <div
                class="bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
                    <i class="mdi mdi-table-furniture text-6xl"></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                            <i class="mdi mdi-table-furniture text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-teal-100 text-sm font-medium">Status das Mesas</p>
                            <h3 class="text-2xl font-bold">{{ $availableTables ?? 0 }}/{{ $tables->count() ?? 0 }}</h3>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-teal-100">{{ $occupiedTables ?? 0 }} ocupadas</span>
                        <a href="{{ route('tables.index') }}"
                            class="text-white hover:text-teal-100 underline decoration-teal-300/50 hover:decoration-teal-100 transition-colors">
                            Ver Mesas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Info -->
        <!-- Charts and Info -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Sales Chart -->
            <div class="xl:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 h-full">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h5 class="font-semibold text-gray-800 dark:text-dark flex items-center gap-2">
                            <i class="mdi mdi-chart-line text-primary"></i>
                            Desempenho de Vendas
                        </h5>
                        <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                            <button type="button"
                                class="px-3 py-1 text-sm font-medium rounded-md transition-colors active bg-white dark:bg-gray-600 shadow-sm text-gray-800 dark:text-white"
                                onclick="changeChartPeriod('daily')">
                                Diário
                            </button>
                            <button type="button"
                                class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                                onclick="changeChartPeriod('hourly')">
                                Por Hora
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="h-80 w-full">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Real-time Status -->
            <div class="xl:col-span-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-4">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg">
                            <i class="mdi mdi-cart-plus text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pedidos em Andamento</p>
                            <h4 class="text-xl font-bold text-gray-800 dark:text-white">{{ $openOrders ?? 0 }}</h4>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-4">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-lg">
                            <i class="mdi mdi-check-circle text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pedidos Hoje</p>
                            <h4 class="text-xl font-bold text-gray-800 dark:text-white">{{ $completedOrdersToday ?? 0 }}
                            </h4>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-4">
                        <div class="p-3 bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 rounded-lg">
                            <i class="mdi mdi-account-multiple text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Novos Clientes</p>
                            <h4 class="text-xl font-bold text-gray-800 dark:text-white">+{{ $newClientsToday ?? 0 }}</h4>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-4">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg">
                            <i class="mdi mdi-calendar-clock text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Vendas da Semana</p>
                            <h4 class="text-xl font-bold text-gray-800 dark:text-white">
                                {{ number_format($weekSales ?? 0, 2, ',', '.') }} MT</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Top Products and Recent Orders -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <!-- Top Products -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 h-full">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h5 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="mdi mdi-trophy text-warning"></i>
                        Produtos Mais Vendidos
                    </h5>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        @forelse($topProducts ?? [] as $index => $product)
                            <div
                                class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div
                                    class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-grow min-w-0">
                                    <h6 class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $product->name }}
                                    </h6>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ optional($product->category)->name ?? 'Sem categoria' }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span
                                        class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-medium rounded-lg">
                                        {{ $product->total_sold ?? 0 }} vendas
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 dark:text-gray-400 py-4">Nenhum produto vendido</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 h-full">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h5 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="mdi mdi-clock-fast text-info"></i>
                        Pedidos Recentes
                    </h5>
                    <a href="{{ route('orders.index') }}"
                        class="text-sm text-warning hover:text-warning/80 font-medium transition-colors">
                        Ver Todos
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead
                            class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase font-medium">
                            <tr>
                                <th class="px-4 py-3 rounded-tl-lg">ID</th>
                                <th class="px-4 py-3">Cliente</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 rounded-tr-lg">Hora</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @forelse($recentOrders ?? [] as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 font-medium">#{{ $order->id }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $order->client_name ?? 'Consumidor Final' }}</td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-800 dark:text-white">
                                        {{ number_format($order->total_amount, 2, ',', '.') }} MT</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-lg bg-{{ $order->status_color ?? 'success' }}-100 text-{{ $order->status_color ?? 'success' }}-600 dark:bg-{{ $order->status_color ?? 'success' }}-900/30 dark:text-{{ $order->status_color ?? 'success' }}-400">
                                            {{ $order->status_label ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->created_at->format('H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Nenhum pedido
                                        recente</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Tables and Low Stock -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    <!-- Tables Status -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 h-full">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h5 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                <i class="mdi mdi-table-furniture text-info"></i>
                                Status das Mesas
                            </h5>
                            <a href="{{ route('tables.index') }}" class="text-sm text-warning hover:text-warning/80 font-medium transition-colors">
                                Ver Todas
                            </a>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @forelse($tables->take(8) ?? [] as $table)
                                    <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow cursor-pointer flex flex-col items-center text-center h-full"
                                        role="button" tabindex="0"
                                        onclick="openTableInstructionsModal({{ $table->id }}, '{{ $table->number }}', '{{ $table->status }}')"
                                        data-status="{{ $table->status }}">
                                        <div class="mb-2">
                                            <i class="mdi mdi-table-furniture text-3xl {{ $table->status === 'occupied' ? 'text-red-500' : 'text-green-500' }}"></i>
                                        </div>
                                        <h6 class="font-medium text-gray-900 dark:text-white mb-1">Mesa {{ $table->number }}</h6>
                                        <small class="text-gray-500 dark:text-gray-400 mb-2 block">{{ $table->capacity }} lugares</small>

                                        @if (isset($table->current_order) && $table->current_order)
                                            <div class="w-full mb-3 p-2 rounded bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-900/30">
                                                <small class="block truncate text-amber-600 dark:text-amber-400 font-bold">
                                                    Pedido: #{{ $table->current_order->id ?? '—' }}
                                                </small>
                                                <small class="text-gray-500 dark:text-gray-400">
                                                    {{ number_format($table->current_order->total_amount ?? 0, 2, ',', '.') }} MT
                                                </small>
                                            </div>
                                        @endif

                                        <div class="mt-auto">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $table->status === 'occupied' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' }}">
                                                {{ $table->status === 'occupied' ? 'Ocupada' : 'Disponível' }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full">
                                        <p class="text-center text-gray-500 dark:text-gray-400 py-4">Nenhuma mesa cadastrada</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 h-full">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h5 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                <i class="mdi mdi-alert text-danger"></i>
                                Estoque Baixo
                            </h5>
                            <a href="{{ route('products.index') }}" class="text-sm text-warning hover:text-warning/80 font-medium transition-colors">
                                Ver Todos
                            </a>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @forelse($lowStockProducts->take(8) ?? [] as $product)
                                    <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-3 hover:shadow-md transition-shadow cursor-pointer h-full"
                                        role="button" tabindex="0"
                                        onclick="openProductInstructionsModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock_quantity }})">
                                        <div class="aspect-square mb-3 bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden flex items-center justify-center">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    alt="{{ $product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="mdi mdi-food text-3xl text-gray-400"></i>
                                            @endif
                                        </div>
                                        <h6 class="font-medium text-gray-900 dark:text-white text-sm truncate mb-1" title="{{ $product->name }}">{{ $product->name }}</h6>
                                        <small class="text-red-500 font-medium block">Estoque: {{ $product->stock_quantity }}</small>
                                    </div>
                                @empty
                                    <div class="col-span-full">
                                        <p class="text-center text-gray-500 dark:text-gray-400 py-4">Estoque adequado</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h5 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="mdi mdi-lightning-bolt text-warning"></i>
                            Ações Rápidas
                        </h5>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                            <a href="{{ route('pos.index') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 transition-all group">
                                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="mdi mdi-cash-register text-2xl"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400">Novo Pedido</span>
                            </a>

                            <a href="{{ route('tables.index') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-teal-50 dark:hover:bg-teal-900/20 hover:text-teal-600 dark:hover:text-teal-400 transition-all group">
                                <div class="w-12 h-12 rounded-full bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="mdi mdi-table-furniture text-2xl"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400">Mesas</span>
                            </a>

                            <a href="{{ route('products.create') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-all group">
                                <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="mdi mdi-plus-circle text-2xl"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-purple-600 dark:group-hover:text-purple-400">Add Produto</span>
                            </a>

                            <a href="{{ route('clients.index') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400 transition-all group">
                                <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="mdi mdi-account-plus text-2xl"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-orange-600 dark:group-hover:text-orange-400">Novo Cliente</span>
                            </a>

                            <a href="{{ route('reports.index') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all group">
                                <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="mdi mdi-chart-bar text-2xl"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Relatórios</span>
                            </a>

                            <a href="#" class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-pink-50 dark:hover:bg-pink-900/20 hover:text-pink-600 dark:hover:text-pink-400 transition-all group opacity-50 cursor-not-allowed">
                                <div class="w-12 h-12 rounded-full bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="mdi mdi-calendar-plus text-2xl"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-pink-600 dark:group-hover:text-pink-400">Reserva</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Instructions Modal -->
        <div x-data="{ 
                open: false, 
                table: { id: null, number: '', status: '' },
                get isOccupied() { return this.table.status === 'occupied'; },
                get statusColor() { return this.isOccupied ? 'text-red-500' : 'text-green-500'; },
                get statusText() { return this.isOccupied ? 'Ocupada' : 'Disponível'; },
                get statusBadge() { return this.isOccupied ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400'; },
                get actionUrl() { 
                    return this.isOccupied 
                        ? '{{ route('tables.index') }}' 
                        : '{{ route('tables.create-order', ':tableId') }}'.replace(':tableId', this.table.id);
                },
                get actionIcon() { return this.isOccupied ? 'mdi-table-furniture' : 'mdi-cart-plus'; },
                get actionText() { return this.isOccupied ? 'Ir para Mesas' : 'Iniciar Pedido'; }
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
                            <a :href="actionUrl" class="inline-flex w-full justify-center rounded-md bg-warning px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-warning/90 sm:col-start-2">
                                <i class="mdi me-2" :class="actionIcon"></i>
                                <span x-text="actionText"></span>
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