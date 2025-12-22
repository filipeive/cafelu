@extends('layouts.app')

@section('title', 'Comparativo de Períodos')

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
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Comparativo de Períodos</h1>
                    <p class="text-gray-500 dark:text-gray-400">Analise o crescimento comparando dois intervalos de tempo.
                    </p>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <form action="{{ route('reports.periodComparison') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 items-end">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase">Período Atual (De)</label>
                    <input type="date" name="current_date_from" value="{{ $currentDateFrom }}"
                        class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase">Período Atual (Até)</label>
                    <input type="date" name="current_date_to" value="{{ $currentDateTo }}"
                        class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase">Período Anterior (De)</label>
                    <input type="date" name="previous_date_from" value="{{ $previousDateFrom }}"
                        class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase">Período Anterior (Até)</label>
                    <input type="date" name="previous_date_to" value="{{ $previousDateTo }}"
                        class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-sm">
                </div>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all">
                    Comparar
                </button>
            </form>
        </div>

        <!-- Comparison Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($comparisons as $key => $data)
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">{{ $data['label'] }}</p>
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-2xl font-black text-gray-800 dark:text-white">
                                {{ is_numeric($data['current']) ? 'MT ' . number_format($data['current'], 2) : $data['current'] }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">Anterior:
                                {{ is_numeric($data['previous']) ? 'MT ' . number_format($data['previous'], 2) : $data['previous'] }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-bold {{ $data['variation_percent'] >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                <i class="mdi {{ $data['variation_percent'] >= 0 ? 'mdi-arrow-up' : 'mdi-arrow-down' }}"></i>
                                {{ abs(number_format($data['variation_percent'], 1)) }}%
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Detailed Analysis -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Products Current -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Top Produtos (Período Atual)</h3>
                <div class="space-y-4">
                    @foreach($currentTopProducts as $product)
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $product->name }}</span>
                            <span class="text-sm font-black text-gray-800 dark:text-white">MT
                                {{ number_format($product->total_revenue, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Products Previous -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Top Produtos (Período Anterior)</h3>
                <div class="space-y-4">
                    @foreach($previousTopProducts as $product)
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $product->name }}</span>
                            <span class="text-sm font-black text-gray-800 dark:text-white">MT
                                {{ number_format($product->total_revenue, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection