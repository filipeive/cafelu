@extends('layouts.app')

@section('title', 'Turnos de Caixa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2.5">
                <i class="mdi mdi-cash-register text-orange-500"></i>
                <span>Turnos de Caixa & Auditoria</span>
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Controle de abertura, fecho cego, conferência de gaveta e histórico de caixas do restaurante.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('pos.index') }}" 
               class="px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition-all flex items-center gap-2 shadow-sm shadow-orange-500/25">
                <i class="mdi mdi-point-of-sale text-base"></i>
                <span>Ir para o POS</span>
            </a>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total de Turnos</p>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $totalShifts }}</h3>
                <span class="text-[11px] text-gray-500">No período selecionado</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl">
                <i class="mdi mdi-history"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Turnos Abertos</p>
                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $openShifts }}</h3>
                <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">Caixas em operação</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
                <i class="mdi mdi-lock-open-variant-outline"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Diferença / Quebras</p>
                <h3 class="text-2xl font-black {{ $totalDifferences < 0 ? 'text-rose-600 dark:text-rose-400' : ($totalDifferences > 0 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-white') }} mt-1">
                    MZN {{ number_format($totalDifferences, 2, ',', '.') }}
                </h3>
                <span class="text-[11px] text-gray-500">
                    {{ $totalDifferences < 0 ? 'Quebra total' : ($totalDifferences > 0 ? 'Sobra total' : 'Caixa exato') }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl">
                <i class="mdi mdi-scale-balance"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Vendas nos Turnos</p>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-1">
                    MZN {{ number_format($totalSales, 2, ',', '.') }}
                </h3>
                <span class="text-[11px] text-gray-500">Volume faturado</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl">
                <i class="mdi mdi-cash-multiple"></i>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs">
        <form method="GET" action="{{ route('shifts.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1">Data Inicial</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}"
                       class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/60 text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-2 focus:ring-orange-500">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1">Data Final</label>
                <input type="date" name="date_to" value="{{ $dateTo }}"
                       class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/60 text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-2 focus:ring-orange-500">
            </div>

            @if(in_array(auth()->user()->role, ['admin', 'manager']))
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1">Operador</label>
                <select name="user_id" class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/60 text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-2 focus:ring-orange-500">
                    <option value="">Todos os Operadores</option>
                    @foreach($operators as $op)
                        <option value="{{ $op->id }}" {{ request('user_id') == $op->id ? 'selected' : '' }}>
                            {{ $op->name }} ({{ $op->role }})
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1">Estado</label>
                <select name="status" class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/60 text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-2 focus:ring-orange-500">
                    <option value="">Todos os Estados</option>
                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Aberto</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Fechado</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" 
                        class="flex-1 py-2 px-3 rounded-xl bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 text-white text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="mdi mdi-filter-variant"></i>
                    <span>Filtrar</span>
                </button>
                <a href="{{ route('shifts.index') }}" 
                   class="p-2 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                   title="Limpar filtros">
                    <i class="mdi mdi-refresh text-base"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Shifts Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50/80 dark:bg-gray-900/40 border-b border-gray-100 dark:border-gray-700 text-[11px] uppercase tracking-wider text-gray-400 font-bold">
                    <tr>
                        <th class="py-3 px-4">Turno</th>
                        <th class="py-3 px-4">Operador</th>
                        <th class="py-3 px-4">Abertura</th>
                        <th class="py-3 px-4">Fecho</th>
                        <th class="py-3 px-4 text-right">Fundo Inicial</th>
                        <th class="py-3 px-4 text-right">Vendas Dinheiro</th>
                        <th class="py-3 px-4 text-right">Vendas Digitais</th>
                        <th class="py-3 px-4 text-right">Total Turno</th>
                        <th class="py-3 px-4 text-right">Diferença</th>
                        <th class="py-3 px-4 text-center">Estado</th>
                        <th class="py-3 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 font-medium">
                    @forelse($shifts as $shift)
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-750/50 transition-colors">
                        <td class="py-3.5 px-4 font-black text-gray-900 dark:text-white">
                            #{{ $shift->id }}
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-xl bg-orange-50 dark:bg-orange-950/40 text-orange-600 dark:text-orange-400 flex items-center justify-center font-bold text-xs">
                                    {{ substr($shift->user?->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="font-bold text-gray-800 dark:text-gray-200">
                                    {{ $shift->user?->name ?? 'Desconhecido' }}
                                </span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="text-gray-800 dark:text-gray-200 font-semibold block">
                                {{ $shift->opened_at->format('d/m/Y') }}
                            </span>
                            <span class="text-[10px] text-gray-400">
                                {{ $shift->opened_at->format('H:i') }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            @if($shift->closed_at)
                                <span class="text-gray-800 dark:text-gray-200 font-semibold block">
                                    {{ $shift->closed_at->format('d/m/Y') }}
                                </span>
                                <span class="text-[10px] text-gray-400">
                                    {{ $shift->closed_at->format('H:i') }}
                                </span>
                            @else
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold text-[11px] flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Em curso
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right font-semibold">
                            MZN {{ number_format($shift->opening_balance, 2, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 text-right font-semibold text-gray-900 dark:text-white">
                            MZN {{ number_format($shift->cash_sales, 2, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 text-right text-gray-500">
                            MZN {{ number_format($shift->card_sales + $shift->mpesa_sales + $shift->emola_sales, 2, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 text-right font-black text-orange-600 dark:text-orange-400">
                            MZN {{ number_format($shift->total_sales, 2, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                            @if($shift->status === 'closed')
                                @if($shift->difference < 0)
                                    <span class="text-rose-600 dark:text-rose-400 font-black">
                                        MZN {{ number_format($shift->difference, 2, ',', '.') }}
                                    </span>
                                @elseif($shift->difference > 0)
                                    <span class="text-blue-600 dark:text-blue-400 font-black">
                                        +MZN {{ number_format($shift->difference, 2, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">
                                        Exato
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400">---</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($shift->status === 'open')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/60">
                                    Aberto
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                    Fechado
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('shifts.show', $shift->id) }}" 
                                   class="p-1.5 rounded-lg text-gray-400 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-950/40 transition-colors"
                                   title="Ver detalhes do turno">
                                    <i class="mdi mdi-eye-outline text-base"></i>
                                </a>
                                @if($shift->status === 'closed')
                                <a href="{{ route('shifts.receipt', $shift->id) }}" target="_blank"
                                   class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/40 transition-colors"
                                   title="Imprimir Talão de Fecho (Fecho Z)">
                                    <i class="mdi mdi-printer text-base"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="py-12 text-center text-gray-400">
                            <i class="mdi mdi-cash-register text-4xl block mb-2 opacity-40"></i>
                            <p class="text-sm font-semibold">Nenhum turno de caixa encontrado</p>
                            <p class="text-[11px] mt-1">Ajuste os filtros ou abra um novo turno a partir do POS.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($shifts->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-700/60 bg-gray-50/50 dark:bg-gray-900/20">
            {{ $shifts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
