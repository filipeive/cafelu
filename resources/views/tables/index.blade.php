@extends('layouts.app')
@section('title', 'Mesas - ' . \App\Models\Setting::get('company_name', config('app.name', 'Restaurante Pro')))

@section('content')
@php
    $totalTablesCount = $tables->count();
    $freeTablesCount = $tables->filter(fn($t) => !$t->hasActiveOrder() && !$t->group_id)->count();
    $occupiedTablesCount = $tables->filter(fn($t) => $t->hasActiveOrder())->count();
    $groupedTablesCount = $tables->filter(fn($t) => $t->group_id !== null)->count();
    $tempTablesCount = $tables->filter(fn($t) => $t->is_temporary)->count();
    $totalCapacity = $tables->sum('capacity');
    $occupancyRate = $totalTablesCount > 0 ? round(($occupiedTablesCount / $totalTablesCount) * 100) : 0;
    $activeOrdersTotal = $tables->map(fn($t) => $t->activeOrder()?->total_amount ?? 0)->sum();
@endphp

<div x-data="tableManagement({{ $tables->toJson() }})" class="w-full space-y-6">

    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 border border-gray-100 dark:border-gray-700/80 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300 text-xs font-bold uppercase tracking-wider mb-2">
                <i class="mdi mdi-table-furniture text-sm"></i>
                Salão & Atendimento
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                Mapa de Mesas
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Controle em tempo real da ocupação do salão, pedidos ativos e agrupamentos
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button @click="openCreateModal()" type="button" 
                class="px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-orange-500/20 flex items-center gap-2 cursor-pointer">
                <i class="mdi mdi-plus-circle-outline text-lg"></i>
                <span>Nova Mesa</span>
            </button>

            <button @click="openMergeModal()" type="button" 
                class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold text-sm rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                <i class="mdi mdi-link-variant text-lg text-orange-500"></i>
                <span>Unir Mesas</span>
            </button>

            <a href="{{ route('orders.kitchen') }}" 
                class="px-4 py-2.5 bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-700/60 hover:bg-amber-100 dark:hover:bg-amber-900/40 text-amber-800 dark:text-amber-300 font-bold text-sm rounded-xl transition-all flex items-center gap-2">
                <i class="mdi mdi-chef-hat text-lg text-amber-500"></i>
                <span>Cozinha (KDS)</span>
            </a>

            <a href="{{ route('orders.index') }}" 
                class="px-4 py-2.5 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 text-gray-600 dark:text-gray-300 font-semibold text-sm rounded-xl transition-all flex items-center gap-2">
                <i class="mdi mdi-clipboard-text-clock-outline text-lg"></i>
                <span>Ver Pedidos</span>
            </a>
        </div>
    </div>

    <!-- Quick Metrics / KPIs Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total de Mesas -->
        <button type="button" @click="filterBy('all')"
            class="text-left bg-white dark:bg-gray-800 p-5 rounded-3xl border transition-all duration-200 shadow-xs hover:shadow-md cursor-pointer"
            :class="activeFilter === 'all' ? 'border-orange-500 ring-2 ring-orange-500/20' : 'border-gray-100 dark:border-gray-700/80'">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total de Mesas</span>
                <div class="w-10 h-10 rounded-2xl bg-orange-50 dark:bg-orange-950/40 text-orange-500 flex items-center justify-center">
                    <i class="mdi mdi-table-furniture text-xl"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white">{{ $totalTablesCount }}</span>
                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">({{ $totalCapacity }} assentos)</span>
            </div>
        </button>

        <!-- Mesas Livres -->
        <button type="button" @click="filterBy('free')"
            class="text-left bg-white dark:bg-gray-800 p-5 rounded-3xl border transition-all duration-200 shadow-xs hover:shadow-md cursor-pointer"
            :class="activeFilter === 'free' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-gray-100 dark:border-gray-700/80'">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Mesas Livres</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 flex items-center justify-center">
                    <i class="mdi mdi-check-circle-outline text-xl"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl md:text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ $freeTablesCount }}</span>
                <span class="text-xs text-emerald-600/80 dark:text-emerald-400/80 font-medium">disponíveis</span>
            </div>
        </button>

        <!-- Mesas Ocupadas -->
        <button type="button" @click="filterBy('occupied')"
            class="text-left bg-white dark:bg-gray-800 p-5 rounded-3xl border transition-all duration-200 shadow-xs hover:shadow-md cursor-pointer"
            :class="activeFilter === 'occupied' ? 'border-rose-500 ring-2 ring-rose-500/20' : 'border-gray-100 dark:border-gray-700/80'">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Mesas Ocupadas</span>
                <div class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 flex items-center justify-center">
                    <i class="mdi mdi-account-group-outline text-xl"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl md:text-3xl font-black text-rose-600 dark:text-rose-400">{{ $occupiedTablesCount }}</span>
                @if($activeOrdersTotal > 0)
                    <span class="text-xs text-rose-600/80 dark:text-rose-400/80 font-semibold truncate">MZN {{ number_format($activeOrdersTotal, 0, ',', '.') }}</span>
                @else
                    <span class="text-xs text-rose-600/80 dark:text-rose-400/80 font-medium">em consumo</span>
                @endif
            </div>
        </button>

        <!-- Ocupação Geral -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl border border-gray-100 dark:border-gray-700/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Ocupação do Salão</span>
                <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 flex items-center justify-center">
                    <i class="mdi mdi-chart-donut text-xl"></i>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                    <span class="text-gray-700 dark:text-gray-200">{{ $occupancyRate }}% Ocupado</span>
                    <span class="text-gray-400">{{ $groupedTablesCount }} em grupos</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-700 h-2.5 rounded-full overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-rose-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $occupancyRate }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Tabs Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-4 rounded-3xl border border-gray-100 dark:border-gray-700/80 shadow-xs">
        <!-- Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" @click="filterBy('all')"
                class="px-4 py-2 rounded-2xl text-xs font-bold transition-all cursor-pointer"
                :class="activeFilter === 'all' ? 'bg-orange-500 text-white shadow-sm shadow-orange-500/30' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'">
                Todas ({{ $totalTablesCount }})
            </button>
            <button type="button" @click="filterBy('free')"
                class="px-4 py-2 rounded-2xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                :class="activeFilter === 'free' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Livres ({{ $freeTablesCount }})
            </button>
            <button type="button" @click="filterBy('occupied')"
                class="px-4 py-2 rounded-2xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                :class="activeFilter === 'occupied' ? 'bg-rose-600 text-white shadow-sm shadow-rose-600/30' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'">
                <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                Ocupadas ({{ $occupiedTablesCount }})
            </button>
            @if($groupedTablesCount > 0)
                <button type="button" @click="filterBy('grouped')"
                    class="px-4 py-2 rounded-2xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                    :class="activeFilter === 'grouped' ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/30' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'">
                    <i class="mdi mdi-link-variant text-xs"></i>
                    Agrupadas ({{ $groupedTablesCount }})
                </button>
            @endif
            @if($tempTablesCount > 0)
                <button type="button" @click="filterBy('temporary')"
                    class="px-4 py-2 rounded-2xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                    :class="activeFilter === 'temporary' ? 'bg-amber-600 text-white shadow-sm shadow-amber-600/30' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'">
                    Temporárias ({{ $tempTablesCount }})
                </button>
            @endif
        </div>

        <!-- Search Input -->
        <div class="relative w-full lg:w-72">
            <i class="mdi mdi-magnify absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
            <input type="text" x-model="searchQuery" placeholder="Buscar mesa ou cliente..."
                class="w-full pl-10 pr-9 py-2 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            <button x-show="searchQuery" @click="searchQuery = ''" type="button" 
                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i class="mdi mdi-close-circle text-sm"></i>
            </button>
        </div>
    </div>

    <!-- Tables Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($tables as $table)
            @php
                $hasActiveOrder = $table->hasActiveOrder();
                $isGrouped = $table->group_id !== null;
                $isMain = (bool)$table->is_main;
                $activeOrder = $table->activeOrder();
                $customerName = $activeOrder?->customer_name ?? '';
            @endphp

            <div x-show="matchesFilter('{{ $table->id }}', '{{ $table->number }}', {{ $hasActiveOrder ? 'true' : 'false' }}, {{ $isGrouped ? 'true' : 'false' }}, {{ $table->is_temporary ? 'true' : 'false' }}, '{{ addslashes($customerName) }}')"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative bg-white dark:bg-gray-800 rounded-3xl border transition-all duration-200 hover:-translate-y-1 hover:shadow-xl flex flex-col justify-between overflow-hidden {{ $hasActiveOrder ? 'border-rose-200/90 dark:border-rose-900/40 shadow-xs' : ($isGrouped ? 'border-blue-200/90 dark:border-blue-900/40 shadow-xs' : 'border-gray-100 dark:border-gray-700/80 shadow-xs') }}">
                
                {{-- Top Status Border Accent --}}
                <div class="h-1.5 w-full {{ $hasActiveOrder ? 'bg-gradient-to-r from-rose-500 to-amber-500' : ($isGrouped ? 'bg-gradient-to-r from-blue-500 to-indigo-500' : 'bg-gradient-to-r from-emerald-400 to-emerald-600') }}"></div>

                <div class="p-5 flex-1 flex flex-col justify-between">
                    <!-- Top Info Line -->
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-xl transition-all {{ $hasActiveOrder ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400' : ($isGrouped ? 'bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400' : 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400') }}">
                                {{ $table->number }}
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-gray-900 dark:text-white leading-tight">
                                    Mesa {{ $table->number }}
                                </h3>
                                <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    <i class="mdi mdi-account-multiple-outline text-sm"></i>
                                    <span>{{ $table->merged_capacity ?? $table->capacity }} lugares</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Pill -->
                        <div class="flex flex-col items-end gap-1">
                            @if($hasActiveOrder)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200/60 shadow-2xs">
                                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                                    Ocupada
                                </span>
                            @elseif($isGrouped)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200/60 shadow-2xs">
                                    <i class="mdi mdi-link-variant text-xs"></i>
                                    Grupo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/60 shadow-2xs">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    Livre
                                </span>
                            @endif

                            @if($table->is_temporary)
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200/50">
                                    Temp
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    @if($hasActiveOrder)
                        <!-- Active Order Summary Box -->
                        <div class="bg-gray-50 dark:bg-gray-900/60 rounded-2xl p-3.5 border border-gray-100 dark:border-gray-700/50 mb-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-extrabold text-orange-600 dark:text-orange-400 flex items-center gap-1">
                                    <i class="mdi mdi-receipt text-sm"></i>
                                    Pedido #{{ $activeOrder->id }}
                                </span>
                                <span class="text-[11px] text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <i class="mdi mdi-clock-outline text-xs"></i>
                                    {{ $activeOrder->created_at->diffForHumans(null, true) }}
                                </span>
                            </div>

                            @if($activeOrder->customer_name)
                                <div class="flex items-center gap-1.5 text-xs text-gray-700 dark:text-gray-300 truncate">
                                    <i class="mdi mdi-account text-gray-400"></i>
                                    <span class="truncate font-semibold">{{ $activeOrder->customer_name }}</span>
                                </div>
                            @endif

                            <div class="flex items-baseline justify-between pt-1 border-t border-gray-200/60 dark:border-gray-700/40">
                                <span class="text-[11px] text-gray-500 dark:text-gray-400">
                                    {{ $activeOrder->items->count() }} {{ $activeOrder->items->count() == 1 ? 'item' : 'itens' }}
                                </span>
                                <span class="text-sm font-black text-gray-900 dark:text-white">
                                    MZN {{ number_format($activeOrder->total_amount, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @else
                        <!-- Free Table Calm State -->
                        <div class="py-5 text-center flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                            <div class="w-12 h-12 rounded-full bg-gray-50 dark:bg-gray-750 flex items-center justify-center mb-2 text-emerald-500/70">
                                <i class="mdi mdi-silverware-fork-knife text-xl"></i>
                            </div>
                            <span class="text-xs font-medium">Mesa disponível para atendimento</span>
                        </div>
                    @endif

                    @if($isGrouped)
                        <div class="mb-3 px-3 py-1.5 rounded-xl bg-blue-50/70 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/40 flex items-center justify-between text-xs text-blue-700 dark:text-blue-300">
                            <span class="font-medium flex items-center gap-1">
                                <i class="mdi mdi-link"></i>
                                {{ $isMain ? 'Mesa Principal' : 'Mesa Secundária' }}
                            </span>
                            @if($table->grouped_tables_numbers)
                                <span class="text-[11px] opacity-80">c/ {{ $table->grouped_tables_numbers }}</span>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Card Actions Bottom Bar -->
                <div class="p-3 bg-gray-50/80 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700/60 flex items-center gap-2">
                    @if(!$hasActiveOrder && (!$isGrouped || $isMain))
                        <form action="{{ route('tables.create-order', $table) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full py-2 px-3 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                <i class="mdi mdi-plus-circle text-base"></i>
                                <span>Abrir Pedido</span>
                            </button>
                        </form>
                    @elseif($hasActiveOrder && (!$isGrouped || $isMain))
                        <a href="{{ route('orders.edit', $activeOrder->id) }}"
                            class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5">
                            <i class="mdi mdi-clipboard-edit-outline text-base"></i>
                            <span>Ver Pedido</span>
                        </a>
                    @endif

                    @if($isGrouped && $isMain)
                        <form action="{{ route('tables.split') }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja separar este grupo de mesas?');">
                            @csrf
                            <input type="hidden" name="group_id" value="{{ $table->group_id }}">
                            <button type="submit" 
                                class="py-2 px-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-300 text-xs font-bold rounded-xl border border-rose-200/50 transition-colors flex items-center gap-1 cursor-pointer"
                                title="Separar Mesas">
                                <i class="mdi mdi-link-off text-base"></i>
                                <span>Separar</span>
                            </button>
                        </form>
                    @endif

                    @if($table->is_temporary && !$hasActiveOrder)
                        <form action="{{ route('tables.destroy', $table->id) }}" method="POST" class="inline" onsubmit="return confirm('Remover esta mesa temporária?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-colors cursor-pointer"
                                title="Remover mesa">
                                <i class="mdi mdi-trash-can-outline text-base"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Empty State -->
    <div x-show="isNoTablesVisible()" class="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border border-gray-100 dark:border-gray-700/80 shadow-xs" style="display: none;">
        <div class="w-16 h-16 rounded-full bg-orange-50 dark:bg-orange-950/40 text-orange-500 mx-auto flex items-center justify-center text-3xl mb-4">
            <i class="mdi mdi-table-furniture"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Nenhuma mesa encontrada</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
            Não encontramos nenhuma mesa correspondente aos filtros ou termo de busca informado.
        </p>
        <button @click="searchQuery = ''; activeFilter = 'all'" type="button" class="mt-4 px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-gray-700 dark:text-gray-200 rounded-xl text-xs font-bold transition-all cursor-pointer">
            Limpar Filtros
        </button>
    </div>

    <!-- Modal Criar Mesa -->
    <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="closeCreateModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-md w-full border border-gray-100 dark:border-gray-700">
                <form action="{{ route('tables.store') }}" method="POST">
                    @csrf
                    <div class="px-6 py-5 bg-gradient-to-r from-orange-500 to-amber-500 text-white flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <i class="mdi mdi-table-plus text-2xl"></i>
                            <div>
                                <h3 class="text-base font-bold leading-tight">Cadastrar Nova Mesa</h3>
                                <p class="text-xs text-orange-100">Adicione uma nova mesa física ou temporária</p>
                            </div>
                        </div>
                        <button type="button" @click="closeCreateModal()" class="text-white/80 hover:text-white rounded-lg p-1 cursor-pointer">
                            <i class="mdi mdi-close text-xl"></i>
                        </button>
                    </div>

                    <div class="p-6 space-y-5">
                        <div>
                            <label for="create_number" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                                Número da Mesa
                            </label>
                            <div class="relative">
                                <i class="mdi mdi-pound absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="number" name="number" id="create_number" :value="nextNumber" required min="1"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-bold focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                            </div>
                        </div>

                        <div>
                            <label for="create_capacity" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                                Capacidade de Lugares
                            </label>
                            <div class="relative">
                                <i class="mdi mdi-account-multiple absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="number" name="capacity" id="create_capacity" x-model="createCapacity" required min="1"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-bold focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                            </div>
                            <!-- Presets rápidos -->
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-[11px] text-gray-400">Atalhos:</span>
                                <template x-for="seats in [2, 4, 6, 8, 10]" :key="seats">
                                    <button type="button" @click="createCapacity = seats"
                                        class="px-2.5 py-1 text-xs font-bold rounded-lg transition-all cursor-pointer"
                                        :class="createCapacity == seats ? 'bg-orange-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200'">
                                        <span x-text="seats + ' lug.'"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div class="pt-2">
                            <label class="flex items-start gap-3 p-3.5 rounded-2xl bg-gray-50 dark:bg-gray-900/60 border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-100/50">
                                <input type="checkbox" name="is_temporary" value="1" class="mt-0.5 rounded text-orange-500 focus:ring-orange-500 h-4 w-4">
                                <div class="text-xs">
                                    <span class="font-bold text-gray-800 dark:text-gray-200 block">Mesa Temporária</span>
                                    <span class="text-gray-500 dark:text-gray-400 block mt-0.5">Ideal para eventos pontuais, mesas no deck ou salão externo</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
                        <button @click="closeCreateModal()" type="button"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-bold text-xs hover:bg-gray-100 dark:hover:bg-gray-800 transition-all cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl shadow-md shadow-orange-500/20 transition-all flex items-center gap-1.5 cursor-pointer">
                            <i class="mdi mdi-check"></i>
                            <span>Cadastrar Mesa</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Unir Mesas -->
    <div x-show="showMergeModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="closeMergeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-2xl w-full border border-gray-100 dark:border-gray-700">
                <form action="{{ route('tables.merge') }}" method="POST">
                    @csrf
                    <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <i class="mdi mdi-link-variant text-2xl"></i>
                            <div>
                                <h3 class="text-base font-bold leading-tight">Unir Mesas (Criar Grupo)</h3>
                                <p class="text-xs text-blue-100">Agrupe mesas livres para atender turmas maiores</p>
                            </div>
                        </div>
                        <button type="button" @click="closeMergeModal()" class="text-white/80 hover:text-white rounded-lg p-1 cursor-pointer">
                            <i class="mdi mdi-close text-xl"></i>
                        </button>
                    </div>

                    <div class="p-6 space-y-5">
                        <div class="p-3.5 bg-blue-50 dark:bg-blue-950/40 rounded-2xl border border-blue-200/60 dark:border-blue-800/50 flex items-start gap-3">
                            <i class="mdi mdi-information-outline text-blue-600 text-lg mt-0.5"></i>
                            <p class="text-xs text-blue-800 dark:text-blue-200 leading-relaxed">
                                Selecione <strong>duas ou mais mesas livres</strong> para criar a junção e defina qual será a <strong>mesa principal</strong> onde os pedidos e consumo serão lançados.
                            </p>
                        </div>

                        <!-- Grade de Mesas Livres -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                                Escolha as mesas livres para unir
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 max-h-56 overflow-y-auto p-1 scrollbar-thin">
                                @forelse ($tables->filter(fn($t) => !$t->hasActiveOrder() && !$t->group_id) as $freeTable)
                                    <div @click="toggleTableSelection({{ $freeTable->id }})"
                                        class="p-3 rounded-2xl border transition-all cursor-pointer flex items-center justify-between"
                                        :class="selectedTables.includes('{{ $freeTable->id }}') ? 'bg-blue-50 dark:bg-blue-950/60 border-blue-500 ring-2 ring-blue-500/20 text-blue-700 dark:text-blue-300' : 'bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100'">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-xl font-bold flex items-center justify-center text-xs"
                                                :class="selectedTables.includes('{{ $freeTable->id }}') ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
                                                {{ $freeTable->number }}
                                            </div>
                                            <div>
                                                <span class="text-xs font-bold block">Mesa {{ $freeTable->number }}</span>
                                                <span class="text-[11px] opacity-75 block">{{ $freeTable->capacity }} lug.</span>
                                            </div>
                                        </div>
                                        <template x-if="selectedTables.includes('{{ $freeTable->id }}')">
                                            <i class="mdi mdi-check-circle text-blue-600 text-lg"></i>
                                        </template>
                                    </div>
                                @empty
                                    <div class="col-span-full py-6 text-center text-xs text-gray-400">
                                        Nenhuma mesa livre disponível no momento para agrupamento.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Mesa Principal -->
                        <div x-show="selectedTables.length >= 2" x-transition>
                            <label for="merge_main_table" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                                Mesa Principal de Atendimento
                            </label>
                            <select id="merge_main_table" name="main_table_id" x-model="mainTable" required
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-bold text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="">Selecione qual será a mesa principal...</option>
                                <template x-for="t in getSelectedTableObjects()" :key="t.id">
                                    <option :value="t.id" x-text="`Mesa ${t.number} (${t.capacity} lugares)`"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Resumo da União -->
                        <div x-show="selectedTables.length > 0" x-transition class="p-3.5 bg-gray-50 dark:bg-gray-900/60 rounded-2xl border border-gray-200 dark:border-gray-700 flex items-center justify-between text-xs">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">
                                <strong class="text-gray-900 dark:text-white" x-text="selectedTables.length"></strong> mesas selecionadas
                            </span>
                            <span class="font-bold text-blue-600 dark:text-blue-400 flex items-center gap-1">
                                <i class="mdi mdi-account-multiple"></i>
                                <span x-text="selectedTotalCapacity + ' lugares no total'"></span>
                            </span>
                        </div>

                        <!-- Hidden Inputs para as mesas selecionadas -->
                        <template x-for="tblId in selectedTables" :key="tblId">
                            <input type="hidden" name="table_ids[]" :value="tblId">
                        </template>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
                        <button @click="closeMergeModal()" type="button"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-bold text-xs hover:bg-gray-100 dark:hover:bg-gray-800 transition-all cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="!canMerge"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-xs rounded-xl shadow-md shadow-blue-600/20 transition-all flex items-center gap-1.5 cursor-pointer">
                            <i class="mdi mdi-link-variant"></i>
                            <span>Unir Mesas</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function tableManagement(tablesData) {
        return {
            searchQuery: '',
            activeFilter: 'all',
            showMergeModal: false,
            showCreateModal: false,
            selectedTables: [],
            mainTable: '',
            createCapacity: 4,
            tables: tablesData || [],

            nextNumber: (function() {
                if (!tablesData || tablesData.length === 0) return 1;
                const nums = tablesData.map(t => parseInt(t.number) || 0);
                return Math.max(...nums) + 1;
            })(),

            filterBy(filter) {
                this.activeFilter = filter;
            },

            matchesFilter(id, number, hasOrder, isGrouped, isTemp, customerName) {
                // 1. Search Query
                if (this.searchQuery && this.searchQuery.trim() !== '') {
                    const q = this.searchQuery.toLowerCase().trim();
                    const numMatch = number.toString().includes(q);
                    const custMatch = customerName ? customerName.toLowerCase().includes(q) : false;
                    if (!numMatch && !custMatch) {
                        return false;
                    }
                }

                // 2. Tab Filter
                if (this.activeFilter === 'free') {
                    return !hasOrder && !isGrouped;
                }
                if (this.activeFilter === 'occupied') {
                    return hasOrder;
                }
                if (this.activeFilter === 'grouped') {
                    return isGrouped;
                }
                if (this.activeFilter === 'temporary') {
                    return isTemp;
                }

                return true;
            },

            isNoTablesVisible() {
                return !this.tables.some(t => {
                    const hasOrder = t.orders && t.orders.length > 0;
                    const isGrouped = t.group_id !== null;
                    const isTemp = !!t.is_temporary;
                    const custName = hasOrder && t.orders[0] ? (t.orders[0].customer_name || '') : '';
                    return this.matchesFilter(t.id, t.number, hasOrder, isGrouped, isTemp, custName);
                });
            },

            openMergeModal() {
                this.selectedTables = [];
                this.mainTable = '';
                this.showMergeModal = true;
            },

            closeMergeModal() {
                this.showMergeModal = false;
            },

            openCreateModal() {
                this.showCreateModal = true;
            },

            closeCreateModal() {
                this.showCreateModal = false;
            },

            toggleTableSelection(id) {
                const strId = id.toString();
                const idx = this.selectedTables.indexOf(strId);
                if (idx > -1) {
                    this.selectedTables.splice(idx, 1);
                    if (this.mainTable === strId) {
                        this.mainTable = this.selectedTables[0] || '';
                    }
                } else {
                    this.selectedTables.push(strId);
                    if (!this.mainTable) {
                        this.mainTable = strId;
                    }
                }
            },

            getSelectedTableObjects() {
                return this.tables.filter(t => this.selectedTables.includes(t.id.toString()));
            },

            get selectedTotalCapacity() {
                return this.getSelectedTableObjects().reduce((sum, t) => sum + (parseInt(t.capacity) || 0), 0);
            },

            get canMerge() {
                return this.selectedTables.length >= 2 && this.mainTable !== '';
            }
        }
    }
</script>
@endpush
@endsection
