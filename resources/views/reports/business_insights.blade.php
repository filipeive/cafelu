@extends('layouts.app')

@section('title', 'Insights e Recomendações')

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
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Insights do Negócio</h1>
                    <p class="text-gray-500 dark:text-gray-400">Análise automática e recomendações baseadas em dados.</p>
                </div>
            </div>

            <form action="{{ route('reports.businessInsights') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div
                    class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700/50 p-2 rounded-xl border border-gray-200 dark:border-gray-600">
                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                        class="bg-transparent border-none text-sm focus:ring-0 text-gray-700 dark:text-gray-200">
                    <span class="text-gray-400">até</span>
                    <input type="date" name="date_to" value="{{ $dateTo }}"
                        class="bg-transparent border-none text-sm focus:ring-0 text-gray-700 dark:text-gray-200">
                </div>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all">
                    Atualizar Análise
                </button>
            </form>
        </div>

        @if(empty($alerts) && empty($insights) && empty($recommendations))
            <div class="bg-white dark:bg-gray-800 p-12 rounded-2xl text-center border border-gray-100 dark:border-gray-700">
                <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="mdi mdi-chart-timeline-variant text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">Dados insuficientes</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Não há dados suficientes no período selecionado para gerar
                    insights automáticos.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Alerts Column -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="mdi mdi-alert-circle text-red-500"></i> Alertas Críticos
                    </h3>
                    @forelse($alerts as $alert)
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border-l-4 border-red-500 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-gray-800 dark:text-white">{{ $alert['title'] }}</h4>
                                <span
                                    class="text-xs font-black text-red-600 bg-red-50 px-2 py-1 rounded">{{ $alert['value'] }}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $alert['message'] }}</p>
                        </div>
                    @empty
                        <div
                            class="bg-green-50 dark:bg-green-900/20 p-6 rounded-2xl border border-green-100 dark:border-green-800 text-center">
                            <i class="mdi mdi-check-decagram text-3xl text-green-600 mb-2 block"></i>
                            <p class="text-sm font-medium text-green-800 dark:text-green-300">Nenhum alerta crítico detectado.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Insights Column -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="mdi mdi-lightbulb-on text-yellow-500"></i> Insights Positivos
                    </h3>
                    @forelse($insights as $insight)
                        <div
                            class="bg-white dark:bg-gray-800 p-6 rounded-2xl border-l-4 border-{{ $insight['type'] == 'success' ? 'green' : 'blue' }}-500 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-gray-800 dark:text-white">{{ $insight['title'] }}</h4>
                                <span
                                    class="text-xs font-black text-{{ $insight['type'] == 'success' ? 'green' : 'blue' }}-600 bg-{{ $insight['type'] == 'success' ? 'green' : 'blue' }}-50 px-2 py-1 rounded">{{ $insight['value'] }}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $insight['message'] }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-12">Nenhum insight relevante no momento.</p>
                    @endforelse
                </div>

                <!-- Recommendations Column -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="mdi mdi-rocket-launch text-purple-500"></i> Recomendações
                    </h3>
                    @forelse($recommendations as $rec)
                        <div
                            class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm group hover:border-purple-500 transition-colors">
                            <div class="flex items-center gap-3 mb-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600">
                                    <i class="mdi mdi-star-outline"></i>
                                </div>
                                <h4 class="font-bold text-gray-800 dark:text-white">{{ $rec['title'] }}</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $rec['description'] }}</p>
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded {{ $rec['priority'] == 'high' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                    Prioridade {{ $rec['priority'] == 'high' ? 'Alta' : 'Média' }}
                                </span>
                                <span class="text-xs font-medium text-purple-600">{{ $rec['action'] }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-12">Continue monitorando para novas recomendações.</p>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
@endsection