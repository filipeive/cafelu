@extends('layouts.app')

@section('title', 'Pesquisa Global')
@section('title-icon', 'mdi-magnify')
@section('page-title', 'Pesquisa Global')

@section('breadcrumbs')
    <li class="flex items-center">
        <a href="{{ route('dashboard') }}" class="hover:text-sky-600 transition-colors">Início</a>
        <i class="mdi mdi-chevron-right text-gray-400 mx-1"></i>
    </li>
    <li class="text-gray-800 dark:text-gray-200 font-semibold">Pesquisa</li>
@endsection

@section('content')
    <div class="space-y-6" x-data="{ activeTab: '{{ $category ?? 'all' }}' }">
        <!-- Search Box Hero Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
            <form action="{{ route('search') }}" method="GET" class="space-y-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <i class="mdi mdi-magnify text-xl text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                        <input type="text" name="q" value="{{ $query }}" autofocus
                            placeholder="Pesquisar produto, pedido, mesa, factura, cotação, cliente ou NUIT..."
                            class="w-full pl-12 pr-10 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm font-medium focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                        @if($query !== '')
                            <a href="{{ route('search') }}" title="Limpar pesquisa"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <i class="mdi mdi-close-circle text-lg"></i>
                            </a>
                        @endif
                    </div>
                    <button type="submit"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <i class="mdi mdi-magnify text-lg"></i>
                        <span>Pesquisar</span>
                    </button>
                </div>

                @if($query !== '')
                    <!-- Filter Tabs -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-gray-100 dark:border-gray-700/70">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <button type="button" @click="activeTab = 'all'"
                                :class="activeTab === 'all' ? 'bg-orange-500 text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200'"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                                <span>Todos</span>
                                <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-black/15">{{ $totalResults }}</span>
                            </button>

                            @if(($counts['products'] ?? 0) > 0)
                                <button type="button" @click="activeTab = 'products'"
                                    :class="activeTab === 'products' ? 'bg-orange-500 text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200'"
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                                    <i class="mdi mdi-food-fork-drink"></i>
                                    <span>Produtos ({{ $counts['products'] }})</span>
                                </button>
                            @endif

                            @if(($counts['orders'] ?? 0) > 0)
                                <button type="button" @click="activeTab = 'orders'"
                                    :class="activeTab === 'orders' ? 'bg-orange-500 text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200'"
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                                    <i class="mdi mdi-clipboard-text-clock-outline"></i>
                                    <span>Pedidos ({{ $counts['orders'] }})</span>
                                </button>
                            @endif

                            @if(($counts['sales'] ?? 0) > 0)
                                <button type="button" @click="activeTab = 'sales'"
                                    :class="activeTab === 'sales' ? 'bg-orange-500 text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200'"
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                                    <i class="mdi mdi-receipt-text-outline"></i>
                                    <span>Facturas & Recibos ({{ $counts['sales'] }})</span>
                                </button>
                            @endif

                            @if(($counts['quotations'] ?? 0) > 0)
                                <button type="button" @click="activeTab = 'quotations'"
                                    :class="activeTab === 'quotations' ? 'bg-orange-500 text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200'"
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                                    <i class="mdi mdi-file-document-edit-outline"></i>
                                    <span>Cotações ({{ $counts['quotations'] }})</span>
                                </button>
                            @endif

                            @if(($counts['clients'] ?? 0) > 0)
                                <button type="button" @click="activeTab = 'clients'"
                                    :class="activeTab === 'clients' ? 'bg-orange-500 text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200'"
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                                    <i class="mdi mdi-account-group-outline"></i>
                                    <span>Clientes ({{ $counts['clients'] }})</span>
                                </button>
                            @endif

                            @if(($counts['tables'] ?? 0) > 0)
                                <button type="button" @click="activeTab = 'tables'"
                                    :class="activeTab === 'tables' ? 'bg-orange-500 text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200'"
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                                    <i class="mdi mdi-table-furniture"></i>
                                    <span>Mesas ({{ $counts['tables'] }})</span>
                                </button>
                            @endif
                        </div>

                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            <strong>{{ $totalResults }}</strong> resultado(s) para <span class="text-orange-600 dark:text-orange-400 font-bold">"{{ $query }}"</span>
                        </span>
                    </div>
                @else
                    <div class="flex flex-wrap items-center gap-2 text-xs text-gray-400 pt-1">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Sugestões de pesquisa:</span>
                        <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300">Nome do Prato ou Bebida</span>
                        <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300">Nº do Pedido ou Mesa</span>
                        <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300">Nº Factura (FR / FT / RC)</span>
                        <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300">Nome, Telefone ou NUIT do Cliente</span>
                    </div>
                @endif
            </form>
        </div>

        @if($query === '')
            <!-- Quick Modules Grid when no query typed yet -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <a href="{{ route('products.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-orange-400 shadow-xs transition-all text-center group">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-950/40 text-orange-500 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <i class="mdi mdi-food-fork-drink text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200 block">Produtos</span>
                </a>
                <a href="{{ route('orders.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-sky-400 shadow-xs transition-all text-center group">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/40 text-sky-500 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <i class="mdi mdi-clipboard-text-clock-outline text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200 block">Pedidos</span>
                </a>
                <a href="{{ route('tables.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-emerald-400 shadow-xs transition-all text-center group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-500 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <i class="mdi mdi-qrcode-scan text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200 block">Mesas & QR</span>
                </a>
                <a href="{{ route('sales.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-purple-400 shadow-xs transition-all text-center group">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-500 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <i class="mdi mdi-receipt-text-outline text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200 block">Facturas & Recibos</span>
                </a>
                <a href="{{ route('quotations.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-amber-400 shadow-xs transition-all text-center group">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-500 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <i class="mdi mdi-file-document-edit-outline text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200 block">Cotações</span>
                </a>
                <a href="{{ route('clients.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-blue-400 shadow-xs transition-all text-center group">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-500 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <i class="mdi mdi-account-group-outline text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200 block">Clientes</span>
                </a>
            </div>
        @elseif($totalResults > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Produtos -->
                @if($results['products']->count() > 0)
                    <div x-show="activeTab === 'all' || activeTab === 'products'"
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/50 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="mdi mdi-food-fork-drink text-orange-500 text-lg"></i>
                                <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-wider">Produtos ({{ $results['products']->count() }})</h3>
                            </div>
                            <a href="{{ route('products.index', ['search' => $query]) }}" class="text-xs font-bold text-orange-600 hover:underline">Ver todos</a>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach($results['products'] as $product)
                                <a href="{{ route('products.show', $product->id) }}"
                                    class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $product->category->name ?? 'Sem categoria' }} &bull; Stock: {{ $product->stock_quantity }} un.
                                        </p>
                                    </div>
                                    <span class="text-sm font-black text-orange-600 dark:text-orange-400">
                                        {{ number_format($product->selling_price ?: $product->price, 2, ',', '.') }} MT
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Pedidos -->
                @if($results['orders']->count() > 0)
                    <div x-show="activeTab === 'all' || activeTab === 'orders'"
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/50 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="mdi mdi-clipboard-text-clock-outline text-sky-500 text-lg"></i>
                                <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-wider">Pedidos ({{ $results['orders']->count() }})</h3>
                            </div>
                            <a href="{{ route('orders.index', ['search' => $query]) }}" class="text-xs font-bold text-sky-600 hover:underline">Ver todos</a>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach($results['orders'] as $order)
                                <a href="{{ route('orders.show', $order->id) }}"
                                    class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                                            Pedido #{{ $order->id }} &bull; {{ $order->table ? 'Mesa ' . $order->table->number : ($order->customer_name ?: 'Balcão / Online') }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $order->created_at?->format('d/m/Y H:i') }} &bull; Estado: {{ ucfirst($order->status) }}
                                        </p>
                                    </div>
                                    <span class="text-sm font-black text-gray-900 dark:text-white">
                                        {{ number_format($order->total_amount, 2, ',', '.') }} MT
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Vendas, Facturas & Recibos -->
                @if($results['sales']->count() > 0)
                    <div x-show="activeTab === 'all' || activeTab === 'sales'"
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/50 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="mdi mdi-receipt-text-outline text-emerald-500 text-lg"></i>
                                <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-wider">Facturas & Recibos ({{ $results['sales']->count() }})</h3>
                            </div>
                            <a href="{{ route('sales.index') }}" class="text-xs font-bold text-emerald-600 hover:underline">Ver todas</a>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach($results['sales'] as $sale)
                                <a href="{{ route('sales.show', $sale->id) }}"
                                    class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $sale->invoice_number ?? '#' . str_pad($sale->id, 5, '0', STR_PAD_LEFT) }} — {{ $sale->customer_name ?: 'Consumidor Final' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $sale->official_invoice_title }} &bull; {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}
                                            @if($sale->customer_nuit) &bull; NUIT: {{ $sale->customer_nuit }} @endif
                                        </p>
                                    </div>
                                    <span class="text-sm font-black text-emerald-600 dark:text-emerald-400">
                                        {{ number_format($sale->total_amount, 2, ',', '.') }} MT
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Cotações -->
                @if($results['quotations']->count() > 0)
                    <div x-show="activeTab === 'all' || activeTab === 'quotations'"
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/50 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="mdi mdi-file-document-edit-outline text-amber-500 text-lg"></i>
                                <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-wider">Cotações ({{ $results['quotations']->count() }})</h3>
                            </div>
                            <a href="{{ route('quotations.index', ['search' => $query]) }}" class="text-xs font-bold text-amber-600 hover:underline">Ver todas</a>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach($results['quotations'] as $quotation)
                                <a href="{{ route('quotations.show', $quotation->id) }}"
                                    class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $quotation->quotation_number }} — {{ $quotation->customer_name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $quotation->date?->format('d/m/Y') }} &bull; {{ $quotation->status_label }}
                                        </p>
                                    </div>
                                    <span class="text-sm font-black text-gray-900 dark:text-white">
                                        {{ number_format($quotation->total_amount, 2, ',', '.') }} MT
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Clientes -->
                @if($results['clients']->count() > 0)
                    <div x-show="activeTab === 'all' || activeTab === 'clients'"
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/50 flex items-center gap-2">
                            <i class="mdi mdi-account-group-outline text-blue-500 text-lg"></i>
                            <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-wider">Clientes ({{ $results['clients']->count() }})</h3>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach($results['clients'] as $client)
                                <a href="{{ route('clients.index', ['search' => $client->name]) }}"
                                    class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $client->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $client->phone ?: 'Sem telefone' }} {{ $client->email ? '• ' . $client->email : '' }}
                                        </p>
                                    </div>
                                    <i class="mdi mdi-chevron-right text-gray-400"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Mesas -->
                @if($results['tables']->count() > 0)
                    <div x-show="activeTab === 'all' || activeTab === 'tables'"
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/50 flex items-center gap-2">
                            <i class="mdi mdi-table-furniture text-emerald-500 text-lg"></i>
                            <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-wider">Mesas ({{ $results['tables']->count() }})</h3>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach($results['tables'] as $table)
                                <a href="{{ route('tables.index') }}"
                                    class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">Mesa #{{ $table->number }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Capacidade: {{ $table->capacity }} lugares</p>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $table->status === 'free' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $table->status === 'free' ? 'Livre' : 'Ocupada' }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="mdi mdi-magnify-close text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Nenhum resultado encontrado para "{{ $query }}"</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-md mx-auto">
                    Experimente pesquisar pelo nome do produto, número do pedido, número da factura (`FR-`, `FT-`, `RC-`), cotação (`COT-`) ou nome/NUIT do cliente.
                </p>
            </div>
        @endif
    </div>
@endsection
