@extends('layouts.app')

@section('title', 'Detalhes da Venda')

@section('content')
    <div class="w-full">
        <!-- Sale Header -->
        <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-sm p-6 mb-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h4 class="text-2xl font-bold text-orange-600 dark:text-orange-400 mb-2">
                        Venda #{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}
                    </h4>
                    <span class="{{ get_status_class_staradmins($sale->status) }} px-4 py-2 rounded-full text-sm font-medium inline-flex items-center">
                        <i class="mdi mdi-circle-medium"></i>
                        {{ ucfirst($sale->status) }}
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Data da Venda</p>
                    <h5 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}
                    </h5>
                    <div class="mt-3 flex items-center justify-end gap-2">
                        <span class="p-2 bg-green-50 dark:bg-green-900/20 rounded-full">
                            <i class="mdi {{ get_payment_icon_mdi($sale->payment_method) }} text-green-500"></i>
                        </span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ ucfirst($sale->payment_method) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sale Items -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h5 class="text-lg font-bold text-gray-900 dark:text-white">Itens da Venda</h5>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Produto</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase text-center">Quantidade</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase text-right">Preço Unit.</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($sale->saleItems as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($item->product->image_path)
                                            <img src="{{ Storage::url($item->product->image_path) }}"
                                                class="w-12 h-12 rounded-lg object-cover shadow-sm"
                                                alt="{{ $item->product->name }}">
                                        @endif
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-medium text-gray-900 dark:text-white">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right text-gray-700 dark:text-gray-300">MZN {{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">MZN {{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                        <tr>
                            <th colspan="3" class="px-6 py-4 text-right text-gray-700 dark:text-gray-300 font-semibold">Total:</th>
                            <th class="px-6 py-4 text-right text-xl font-bold text-orange-600 dark:text-orange-400">
                                MZN {{ number_format($sale->total_amount, 2) }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Payment Details (if mixed payment) -->
        @if ($sale->payment_method == 'mixed')
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6 max-w-lg mx-auto">
                <div class="p-6">
                    <h6 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Detalhes do Pagamento</h6>
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($sale->cash_amount > 0)
                                <tr>
                                    <td class="py-2 text-gray-700 dark:text-gray-300">
                                        <i class="mdi mdi-cash mr-2 text-green-500"></i>Dinheiro
                                    </td>
                                    <td class="py-2 text-right font-medium text-gray-900 dark:text-white">MZN {{ number_format($sale->cash_amount, 2) }}</td>
                                </tr>
                            @endif
                            @if ($sale->card_amount > 0)
                                <tr>
                                    <td class="py-2 text-gray-700 dark:text-gray-300">
                                        <i class="mdi mdi-credit-card mr-2 text-blue-500"></i>Cartão
                                    </td>
                                    <td class="py-2 text-right font-medium text-gray-900 dark:text-white">MZN {{ number_format($sale->card_amount, 2) }}</td>
                                </tr>
                            @endif
                            @if ($sale->mpesa_amount > 0)
                                <tr>
                                    <td class="py-2 text-gray-700 dark:text-gray-300">
                                        <i class="mdi mdi-phone mr-2 text-yellow-500"></i>M-Pesa
                                    </td>
                                    <td class="py-2 text-right font-medium text-gray-900 dark:text-white">MZN {{ number_format($sale->mpesa_amount, 2) }}</td>
                                </tr>
                            @endif
                            @if ($sale->emola_amount > 0)
                                <tr>
                                    <td class="py-2 text-gray-700 dark:text-gray-300">
                                        <i class="mdi mdi-wallet mr-2 text-cyan-500"></i>e-Mola
                                    </td>
                                    <td class="py-2 text-right font-medium text-gray-900 dark:text-white">MZN {{ number_format($sale->emola_amount, 2) }}</td>
                                </tr>
                            @endif
                            <tr class="border-t-2 border-gray-300 dark:border-gray-600">
                                <th class="py-2 text-gray-900 dark:text-white">Total Pago</th>
                                <th class="py-2 text-right text-green-600 dark:text-green-400">MZN {{ number_format($sale->getTotalPayments(), 2) }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('sales.index') }}" class="px-6 py-3 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-medium transition-colors flex items-center gap-2">
                <i class="mdi mdi-arrow-left"></i>
                Voltar para Vendas
            </a>
            <button type="button" class="px-6 py-3 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-medium shadow-md hover:shadow-lg transition-all flex items-center gap-2"
                onclick="window.printSaleRecibo({{ $sale->id }})">
                <i class="mdi mdi-printer"></i>
                Imprimir Recibo
            </button>
        </div>
    </div>
@endsection
