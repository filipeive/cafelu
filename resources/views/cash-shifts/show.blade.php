@extends('layouts.app')

@section('title', 'Detalhes do Turno #' . $shift->id)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('shifts.index') }}" 
               class="p-2 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 transition-colors">
                <i class="mdi mdi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                    <span>Turno de Caixa #{{ $shift->id }}</span>
                    @if($shift->status === 'open')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/60">
                            Aberto
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                            Fechado
                        </span>
                    @endif
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Operador: <strong class="text-gray-700 dark:text-gray-300">{{ $shift->user?->name ?? 'Operador' }}</strong> | Aberto em {{ $shift->opened_at->format('d/m/Y H:i') }}
                    @if($shift->closed_at)
                        | Fechado em {{ $shift->closed_at->format('d/m/Y H:i') }}
                    @endif
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @if($shift->status === 'closed')
                <a href="{{ route('shifts.receipt', $shift->id) }}" target="_blank"
                   class="px-4 py-2 rounded-xl bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 text-white text-xs font-bold transition-all flex items-center gap-2 shadow-xs">
                    <i class="mdi mdi-printer text-base"></i>
                    <span>Imprimir Fecho Z</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Cards de Balanço de Caixa -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Balanço Físico (Dinheiro em Gaveta) -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-wider text-gray-400">Gaveta & Dinheiro Físico</span>
                <i class="mdi mdi-cash text-xl text-emerald-500"></i>
            </div>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700/60">
                    <span class="text-gray-500">Fundo Inicial (Abertura):</span>
                    <span class="font-bold text-gray-900 dark:text-white">MZN {{ number_format($shift->opening_balance, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700/60">
                    <span class="text-gray-500">+ Vendas em Dinheiro:</span>
                    <span class="font-bold text-gray-900 dark:text-white">MZN {{ number_format($shift->cash_sales, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700/60 bg-gray-50/70 dark:bg-gray-900/40 px-2 rounded-lg">
                    <span class="font-bold text-gray-700 dark:text-gray-300">= Esperado em Caixa:</span>
                    <span class="font-black text-gray-900 dark:text-white">MZN {{ number_format($shift->expected_cash, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700/60">
                    <span class="text-gray-500">Declarado no Fecho (Contagem):</span>
                    <span class="font-bold text-gray-900 dark:text-white">
                        @if($shift->closing_balance_actual !== null)
                            MZN {{ number_format($shift->closing_balance_actual, 2, ',', '.') }}
                        @else
                            <span class="text-amber-500 font-semibold">Pendente de fecho</span>
                        @endif
                    </span>
                </div>
                <div class="flex justify-between py-2 px-2 rounded-xl {{ $shift->difference < 0 ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300' : ($shift->difference > 0 ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300') }}">
                    <span class="font-black text-xs uppercase">Diferença / Saldo:</span>
                    <span class="font-black text-sm">
                        @if($shift->status === 'closed')
                            MZN {{ number_format($shift->difference, 2, ',', '.') }}
                            ({{ $shift->difference < 0 ? 'Quebra' : ($shift->difference > 0 ? 'Sobra' : 'Exato') }})
                        @else
                            ---
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Vendas Digitais & Outros Métodos -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-wider text-gray-400">Pagamentos Digitais</span>
                <i class="mdi mdi-credit-card-outline text-xl text-blue-500"></i>
            </div>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700/60">
                    <span class="text-gray-500 flex items-center gap-1.5">
                        <i class="mdi mdi-credit-card text-gray-400"></i> Cartão POS (TPA):
                    </span>
                    <span class="font-bold text-gray-900 dark:text-white">MZN {{ number_format($shift->card_sales, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700/60">
                    <span class="text-gray-500 flex items-center gap-1.5">
                        <i class="mdi mdi-cellphone text-rose-500"></i> M-Pesa:
                    </span>
                    <span class="font-bold text-gray-900 dark:text-white">MZN {{ number_format($shift->mpesa_sales, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700/60">
                    <span class="text-gray-500 flex items-center gap-1.5">
                        <i class="mdi mdi-cellphone-wireless text-amber-500"></i> e-Mola:
                    </span>
                    <span class="font-bold text-gray-900 dark:text-white">MZN {{ number_format($shift->emola_sales, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 px-2 rounded-xl bg-gray-50 dark:bg-gray-900/50 mt-4">
                    <span class="font-black text-xs text-gray-700 dark:text-gray-300">Total Vendas Digitais:</span>
                    <span class="font-black text-sm text-gray-900 dark:text-white">
                        MZN {{ number_format($shift->card_sales + $shift->mpesa_sales + $shift->emola_sales, 2, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Resumo Consolidado do Turno -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-wider text-gray-400">Totalização Geral</span>
                <i class="mdi mdi-chart-pie text-xl text-orange-500"></i>
            </div>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700/60">
                    <span class="text-gray-500">Total de Transações:</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $shift->sales->count() }} vendas</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700/60">
                    <span class="text-gray-500">Duração do Turno:</span>
                    <span class="font-bold text-gray-900 dark:text-white">
                        @if($shift->closed_at)
                            {{ $shift->opened_at->diffForHumans($shift->closed_at, true) }}
                        @else
                            Em andamento ({{ $shift->opened_at->diffForHumans() }})
                        @endif
                    </span>
                </div>
                <div class="pt-3">
                    <span class="block text-[11px] font-bold uppercase text-gray-400">Total Faturado no Turno</span>
                    <p class="text-2xl font-black text-orange-600 dark:text-orange-400 mt-0.5">
                        MZN {{ number_format($shift->total_sales, 2, ',', '.') }}
                    </p>
                </div>
                @if($shift->notes)
                <div class="pt-2 text-[11px] text-gray-500 bg-gray-50 dark:bg-gray-900/40 p-2.5 rounded-xl border border-gray-100 dark:border-gray-700/50">
                    <strong class="text-gray-700 dark:text-gray-300 block mb-0.5">Observações:</strong>
                    {{ $shift->notes }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Lista de Vendas do Turno -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
            <h3 class="text-sm font-black text-gray-900 dark:text-white flex items-center gap-2">
                <i class="mdi mdi-receipt text-orange-500"></i>
                <span>Vendas Registadas neste Turno ({{ $shift->sales->count() }})</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50/80 dark:bg-gray-900/40 border-b border-gray-100 dark:border-gray-700 text-[11px] uppercase tracking-wider text-gray-400 font-bold">
                    <tr>
                        <th class="py-3 px-4">Venda #</th>
                        <th class="py-3 px-4">Hora</th>
                        <th class="py-3 px-4">Cliente</th>
                        <th class="py-3 px-4">Método</th>
                        <th class="py-3 px-4">Itens</th>
                        <th class="py-3 px-4 text-right">Total</th>
                        <th class="py-3 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 font-medium">
                    @forelse($shift->sales as $sale)
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-750/50 transition-colors">
                        <td class="py-3 px-4 font-bold text-gray-900 dark:text-white">
                            #{{ $sale->id }}
                        </td>
                        <td class="py-3 px-4 text-gray-500">
                            {{ $sale->created_at->format('H:i:s') }}
                        </td>
                        <td class="py-3 px-4 font-medium text-gray-800 dark:text-gray-200">
                            {{ $sale->customer_name ?: 'Cliente Geral' }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                {{ $sale->payment_method }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-500">
                            {{ $sale->saleItems->sum('quantity') }} itens
                        </td>
                        <td class="py-3 px-4 text-right font-black text-gray-900 dark:text-white">
                            MZN {{ number_format($sale->total_amount, 2, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('sales.receipt', $sale->id) }}" target="_blank"
                               class="p-1 rounded-lg text-gray-400 hover:text-blue-600 transition-colors"
                               title="Ver Recibo">
                                <i class="mdi mdi-printer text-base"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400 text-xs">
                            Nenhuma venda associada a este turno até ao momento.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

