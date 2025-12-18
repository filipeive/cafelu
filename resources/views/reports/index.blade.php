@extends('layouts.app')

@section('title', 'Dashboard de Relatórios')

@section('content')
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <h4 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Relatórios do Sistema</h4>
            <p class="text-gray-600 dark:text-gray-400">Selecione o tipo de relatório que deseja visualizar</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Relatório de Vendas -->
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 text-center">
                    <i class="mdi mdi-cash-multiple text-blue-500 text-5xl mb-4 block"></i>
                    <h5 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Relatório de Vendas</h5>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Resumo geral das vendas realizadas</p>
                    <a href="{{ route('reports.sales') }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        Visualizar
                    </a>
                </div>
            </div>

            <!-- Relatório de Estoque -->
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 text-center">
                    <i class="mdi mdi-package-variant text-green-500 text-5xl mb-4 block"></i>
                    <h5 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Relatório de Estoque</h5>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Produtos com estoque baixo</p>
                    <a href="{{ route('reports.inventory') }}"
                        class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        Visualizar
                    </a>
                </div>
            </div>

            <!-- Vendas por Produto -->
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 text-center">
                    <i class="mdi mdi-food text-cyan-500 text-5xl mb-4 block"></i>
                    <h5 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Vendas por Produto</h5>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Análise de vendas por produto</p>
                    <a href="{{ route('reports.salesByProduct') }}"
                        class="inline-block bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        Visualizar
                    </a>
                </div>
            </div>

            <!-- Vendas por Categoria -->
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 text-center">
                    <i class="mdi mdi-tag-multiple text-red-500 text-5xl mb-4 block"></i>
                    <h5 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Vendas por Categoria</h5>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Análise de vendas por categoria</p>
                    <a href="{{ route('reports.salesByCategory') }}"
                        class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        Visualizar
                    </a>
                </div>
            </div>

            <!-- Vendas por Forma de Pagamento -->
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 text-center">
                    <i class="mdi mdi-credit-card-multiple text-yellow-500 text-5xl mb-4 block"></i>
                    <h5 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Vendas por Forma de Pagamento</h5>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Análise por método de pagamento</p>
                    <a href="{{ route('reports.salesByPaymentMethod') }}"
                        class="inline-block bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        Visualizar
                    </a>
                </div>
            </div>

            <!-- Vendas por Data -->
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 text-center">
                    <i class="mdi mdi-calendar-text text-gray-500 text-5xl mb-4 block"></i>
                    <h5 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Vendas por Data</h5>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Análise de vendas por período</p>
                    <a href="{{ route('reports.salesByDate') }}"
                        class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        Visualizar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection