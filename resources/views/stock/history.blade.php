@extends('layouts.app')

@section('content')
    <div class="w-full">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="mdi mdi-history text-orange-500"></i>
                    Histórico de Movimentações
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Acompanhe todas as entradas e saídas de produtos do
                    estoque.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('stock.index') }}"
                    class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 transition-all flex items-center gap-2">
                    <i class="mdi mdi-arrow-left"></i>
                    Voltar para Estoque
                </a>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 mb-8">
            <form action="{{ route('stock.history') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Produto</label>
                    <select name="product_id"
                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                        <option value="">Todos os Produtos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Tipo</label>
                    <select name="type"
                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                        <option value="">Todos os Tipos</option>
                        <option value="entry" {{ request('type') == 'entry' ? 'selected' : '' }}>Entrada</option>
                        <option value="exit" {{ request('type') == 'exit' ? 'selected' : '' }}>Saída</option>
                        <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Ajuste</option>
                        <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Venda</option>
                        <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Devolução</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-500/20 text-sm">
                        Filtrar
                    </button>
                    <a href="{{ route('stock.history') }}"
                        class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-bold rounded-xl hover:bg-gray-200 transition-all text-sm">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <!-- Movements Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Data/Hora</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Produto</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tipo</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Quantidade</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Usuário</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Notas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($movements as $movement)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                                <div class="font-medium">{{ $movement->created_at->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $movement->created_at->format('H:i:s') }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $movement->product->name ?? 'Produto Removido' }}</div>
                                                <div class="text-xs text-gray-500">ID: #{{ $movement->product_id }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                            {{ $movement->type === 'entry' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
                            ($movement->type === 'exit' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' :
                                ($movement->type === 'adjustment' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' :
                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300')) }}">
                                                    {{ ucfirst($movement->type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="text-sm font-bold {{ $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $movement->user->name ?? 'Sistema' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                                {{ $movement->notes ?? '-' }}
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="mdi mdi-history text-6xl text-gray-200 dark:text-gray-700"></i>
                                        <p class="text-gray-500 dark:text-gray-400 mt-4">Nenhuma movimentação encontrada.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($movements->hasPages())
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                    {{ $movements->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection