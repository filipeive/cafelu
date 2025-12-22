@extends('layouts.app')

@section('title', 'Produtos com Baixo Estoque')

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
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Alertas de Estoque</h1>
                    <p class="text-gray-500 dark:text-gray-400">Produtos que atingiram ou estão abaixo do nível mínimo.</p>
                </div>
            </div>

            <button onclick="window.print()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all flex items-center gap-2">
                <i class="mdi mdi-printer"></i> Imprimir Lista de Compras
            </button>
        </div>

        @if($products->isEmpty())
            <div
                class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 p-12 rounded-2xl text-center">
                <div
                    class="w-20 h-20 bg-green-100 dark:bg-green-800/50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="mdi mdi-check-circle text-4xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-green-800 dark:text-green-300">Tudo em ordem!</h3>
                <p class="text-green-600 dark:text-green-400 mt-2">Não há produtos com estoque baixo no momento.</p>
            </div>
        @else
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">
                                <th class="py-4 px-6">Produto</th>
                                <th class="py-4 px-6">Estoque Atual</th>
                                <th class="py-4 px-6">Nível Mínimo</th>
                                <th class="py-4 px-6">Status</th>
                                <th class="py-4 px-6 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @foreach($products as $product)
                                <tr class="text-sm hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-800 dark:text-white">{{ $product->name }}</span>
                                            <span class="text-xs text-gray-400">{{ $product->category->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="font-black text-red-600 text-lg">{{ $product->stock_quantity }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-gray-500">{{ $product->min_stock_level }}</td>
                                    <td class="py-4 px-6">
                                        @if($product->stock_quantity <= 0)
                                            <span
                                                class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-[10px] font-bold uppercase">Esgotado</span>
                                        @else
                                            <span
                                                class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-[10px] font-bold uppercase">Crítico</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="text-blue-600 hover:underline font-medium">Repor</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection