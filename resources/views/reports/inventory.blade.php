@extends('layouts.app')

@section('title', 'Relatório de Estoque')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <a href="{{ route('reports.index') }}"
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-500 hover:text-blue-600 transition-colors">
                    <i class="mdi mdi-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Relatório de Estoque</h1>
                    <p class="text-gray-500 dark:text-gray-400">Controle de níveis de estoque e valor total investido.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="window.print()"
                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-6 py-2.5 rounded-xl font-medium transition-all flex items-center gap-2">
                    <i class="mdi mdi-printer"></i> Imprimir
                </button>
                <a href="{{ route('reports.export', ['report_type' => 'products', 'format' => 'excel']) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all flex items-center gap-2">
                    <i class="mdi mdi-file-excel"></i> Exportar
                </a>
            </div>
        </div>

        <!-- Inventory Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Itens</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $products->count() }}</h3>
                <p class="text-xs text-gray-400 mt-4">{{ $products->sum('stock_quantity') }} unidades em estoque</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor em Estoque (Custo)</p>
                <h3 class="text-2xl font-bold text-blue-600 mt-1">MT
                    {{ number_format($products->sum(fn($p) => $p->stock_quantity * $p->purchase_price), 2) }}</h3>
                <p class="text-xs text-gray-400 mt-4">Baseado no preço de compra</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Alertas de Reposição</p>
                <h3 class="text-2xl font-bold text-red-600 mt-1">
                    {{ $products->where('stock_quantity', '<=', 'min_stock_level')->count() }}</h3>
                <p class="text-xs text-gray-400 mt-4">Itens abaixo do nível mínimo</p>
            </div>
        </div>

        <!-- Detailed Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Posição de Estoque</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                            <th class="py-4 px-6">Produto</th>
                            <th class="py-4 px-6">Categoria</th>
                            <th class="py-4 px-6">Preço Custo</th>
                            <th class="py-4 px-6">Preço Venda</th>
                            <th class="py-4 px-6">Estoque</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6">Valor Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @foreach($products as $product)
                            <tr class="text-sm hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="py-4 px-6">
                                    <span class="font-bold text-gray-800 dark:text-white">{{ $product->name }}</span>
                                </td>
                                <td class="py-4 px-6 text-gray-500 dark:text-gray-400">
                                    {{ $product->category->name ?? 'Sem Categoria' }}</td>
                                <td class="py-4 px-6 text-gray-600 dark:text-gray-400">MT
                                    {{ number_format($product->purchase_price, 2) }}</td>
                                <td class="py-4 px-6 text-gray-600 dark:text-gray-400">MT
                                    {{ number_format($product->selling_price, 2) }}</td>
                                <td class="py-4 px-6">
                                    <span
                                        class="font-bold {{ $product->stock_quantity <= $product->min_stock_level ? 'text-red-600' : 'text-gray-800 dark:text-white' }}">
                                        {{ $product->stock_quantity }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 ml-1">/ {{ $product->min_stock_level }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($product->stock_quantity <= 0)
                                        <span
                                            class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-[10px] font-bold uppercase">Esgotado</span>
                                    @elseif($product->stock_quantity <= $product->min_stock_level)
                                        <span
                                            class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-[10px] font-bold uppercase">Baixo</span>
                                    @else
                                        <span
                                            class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-[10px] font-bold uppercase">Normal</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 font-bold text-gray-800 dark:text-white">
                                    MT {{ number_format($product->stock_quantity * $product->purchase_price, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection