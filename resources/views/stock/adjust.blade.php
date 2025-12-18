@extends('layouts.app')

@section('content')
    <div class="w-full">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="mdi mdi-plus-minus-box text-orange-500"></i>
                Ajustar Estoque
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Ajuste o nível de estoque para o produto: <span
                    class="font-bold text-gray-900 dark:text-white">{{ $product->name }}</span></p>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('stock.store-adjustment', $product->id) }}" method="POST" class="p-8">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    <div
                        class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div
                            class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 text-orange-600 rounded-xl flex items-center justify-center text-xl font-bold">
                            {{ $product->stock_quantity }}
                        </div>
                        <div>
                            <div class="text-xs font-bold text-gray-500 uppercase">Estoque Atual</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Quantidade disponível no momento</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tipo de Ajuste</label>
                        <div class="grid grid-cols-3 gap-4">
                            <label
                                class="relative flex flex-col p-4 border border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all has-[:checked]:border-orange-500 has-[:checked]:ring-2 has-[:checked]:ring-orange-500/20">
                                <input type="radio" name="type" value="entry" checked class="sr-only">
                                <i class="mdi mdi-arrow-down-bold text-green-500 text-xl mb-1"></i>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">Entrada</span>
                                <span class="text-xs text-gray-500">Adicionar itens</span>
                            </label>
                            <label
                                class="relative flex flex-col p-4 border border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all has-[:checked]:border-orange-500 has-[:checked]:ring-2 has-[:checked]:ring-orange-500/20">
                                <input type="radio" name="type" value="exit" class="sr-only">
                                <i class="mdi mdi-arrow-up-bold text-red-500 text-xl mb-1"></i>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">Saída</span>
                                <span class="text-xs text-gray-500">Remover itens</span>
                            </label>
                            <label
                                class="relative flex flex-col p-4 border border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all has-[:checked]:border-orange-500 has-[:checked]:ring-2 has-[:checked]:ring-orange-500/20">
                                <input type="radio" name="type" value="adjustment" class="sr-only">
                                <i class="mdi mdi-equal-box text-blue-500 text-xl mb-1"></i>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">Correção</span>
                                <span class="text-xs text-gray-500">Definir valor exato</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Quantidade</label>
                        <input type="number" name="quantity" step="0.01" required
                            class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-lg font-bold">
                        @error('quantity') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Observações
                            (Opcional)</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                            placeholder="Motivo do ajuste..."></textarea>
                        @error('notes') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3">
                    <a href="{{ route('stock.index') }}"
                        class="px-6 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-50 transition-all">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-8 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-500/20">
                        Confirmar Ajuste
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection