@extends('layouts.app')

@section('content')
    <div class="w-full">
        <!-- Welcome Section -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden mb-12">
            <div class="relative h-48 bg-gradient-to-r from-orange-500 to-orange-600 flex items-center justify-center">
                <div class="absolute inset-0 opacity-20"
                    style="background-image: url('{{ asset('assets/images/pattern.png') }}'); background-size: cover;">
                </div>
                <div class="relative z-10 text-center">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">
                        {{ __('Bem-vindo ao') }} {{ \App\Models\Setting::get('company_name', 'Café Lufamina') }}
                    </h1>
                    <p class="text-orange-100 text-lg">
                        {{ __('Lu & Yosh Catering - Gestão Profissional') }}
                    </p>
                </div>
            </div>

            <div class="p-8 text-center">
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-2xl mx-auto mb-10">
                    {{ __('Explore nosso sistema para gerenciar pedidos, cardápios, funcionários e muito mais com eficiência e estilo.') }}
                </p>

                <!-- Quick Actions Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- dashboard -->
                    <a href="{{ route('dashboard') }}"
                        class="group p-6 bg-orange-50 dark:bg-orange-900/20 rounded-2xl border border-orange-100 dark:border-orange-800/50 hover:bg-orange-500 transition-all duration-300 transform hover:-translate-y-1">
                        <div
                            class="w-14 h-14 bg-orange-500 text-white rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white group-hover:text-orange-500 transition-colors shadow-lg shadow-orange-500/20">
                            <i class="mdi mdi-view-dashboard text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-white transition-colors">
                            Dashboard
                        </h3>
                        <p
                            class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-orange-100 transition-colors mt-1">
                            Dashboard</p>
                    </a>
                    <a href="{{ route('pos.index') }}"
                        class="group p-6 bg-orange-50 dark:bg-orange-900/20 rounded-2xl border border-orange-100 dark:border-orange-800/50 hover:bg-orange-500 transition-all duration-300 transform hover:-translate-y-1">
                        <div
                            class="w-14 h-14 bg-orange-500 text-white rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white group-hover:text-orange-500 transition-colors shadow-lg shadow-orange-500/20">
                            <i class="mdi mdi-cash-register text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-white transition-colors">POS
                        </h3>
                        <p
                            class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-orange-100 transition-colors mt-1">
                            Vendas Rápidas</p>
                    </a>

                    <a href="{{ route('tables.index') }}"
                        class="group p-6 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-800/50 hover:bg-blue-500 transition-all duration-300 transform hover:-translate-y-1">
                        <div
                            class="w-14 h-14 bg-blue-500 text-white rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white group-hover:text-blue-500 transition-colors shadow-lg shadow-blue-500/20">
                            <i class="mdi mdi-table-chair text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-white transition-colors">Mesas
                        </h3>
                        <p
                            class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-blue-100 transition-colors mt-1">
                            Gerenciar Salão</p>
                    </a>

                    <a href="{{ route('orders.index') }}"
                        class="group p-6 bg-green-50 dark:bg-green-900/20 rounded-2xl border border-green-100 dark:border-green-800/50 hover:bg-green-500 transition-all duration-300 transform hover:-translate-y-1">
                        <div
                            class="w-14 h-14 bg-green-500 text-white rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white group-hover:text-green-500 transition-colors shadow-lg shadow-green-500/20">
                            <i class="mdi mdi-clipboard-list text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-white transition-colors">Pedidos
                        </h3>
                        <p
                            class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-green-100 transition-colors mt-1">
                            Ver Histórico</p>
                    </a>
                    <!-- historico de vendas sales -->
                    <a href="{{ route('sales.index') }}"
                        class="group p-6 bg-green-50 dark:bg-green-900/20 rounded-2xl border border-green-100 dark:border-green-800/50 hover:bg-green-500 transition-all duration-300 transform hover:-translate-y-1">
                        <div
                            class="w-14 h-14 bg-green-500 text-white rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white group-hover:text-green-500 transition-colors shadow-lg shadow-green-500/20">
                            <i class="mdi mdi-clipboard-list text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-white transition-colors">Vendas
                        </h3>
                        <p
                            class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-green-100 transition-colors mt-1">
                            Ver Histórico</p>
                    </a>
                    <!-- Despesas -->
                    <a href="{{ route('expenses.index') }}"
                        class="group p-6 bg-green-50 dark:bg-green-900/20 rounded-2xl border border-green-100 dark:border-green-800/50 hover:bg-green-500 transition-all duration-300 transform hover:-translate-y-1">
                        <div
                            class="w-14 h-14 bg-green-500 text-white rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white group-hover:text-green-500 transition-colors shadow-lg shadow-green-500/20">
                            <i class="mdi mdi-cash text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-white transition-colors">
                            Despesas
                        </h3>
                        <p
                            class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-green-100 transition-colors mt-1">
                            Ver Histórico</p>
                    </a>
                    <!-- Estoque -->
                    <a href="{{ route('stock.index') }}"
                        class="group p-6 bg-green-50 dark:bg-green-900/20 rounded-2xl border border-green-100 dark:border-green-800/50 hover:bg-green-500 transition-all duration-300 transform hover:-translate-y-1">
                        <div
                            class="w-14 h-14 bg-green-500 text-white rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white group-hover:text-green-500 transition-colors shadow-lg shadow-green-500/20">
                            <i class="mdi mdi-box-variant text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-white transition-colors">
                            Estoque
                        </h3>
                        <p
                            class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-green-100 transition-colors mt-1">
                            Gerenciar Estoque</p>
                    </a>
                    <!-- relatorios -->
                    <a href="{{ route('reports.index') }}"
                        class="group p-6 bg-purple-50 dark:bg-purple-900/20 rounded-2xl border border-purple-100 dark:border-purple-800/50 hover:bg-purple-500 transition-all duration-300 transform hover:-translate-y-1">
                        <div
                            class="w-14 h-14 bg-purple-500 text-white rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white group-hover:text-purple-500 transition-colors shadow-lg shadow-purple-500/20">
                            <i class="mdi mdi-chart-bar text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-white transition-colors">
                            Relatórios</h3>
                        <p
                            class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-purple-100 transition-colors mt-1">
                            Análise de Dados</p>
                    </a>
                    <!-- Configurações -->
                    <a href="{{ route('settings.index') }}"
                        class="group p-6 bg-gray-50 dark:bg-gray-900/20 rounded-2xl border border-gray-100 dark:border-gray-800/50 hover:bg-gray-500 transition-all duration-300 transform hover:-translate-y-1">
                        <div
                            class="w-14 h-14 bg-gray-500 text-white rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white group-hover:text-gray-500 transition-colors shadow-lg shadow-gray-500/20">
                            <i class="mdi mdi-cog text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-white transition-colors">
                            Configurações
                        </h3>
                        <p
                            class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-gray-100 transition-colors mt-1">
                            Ajustes do Sistema</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Logo Section -->
        <div class="text-center opacity-50 hover:opacity-100 transition-opacity duration-500">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo do Restaurante"
                class="mx-auto max-h-32 grayscale hover:grayscale-0 transition-all duration-500">
        </div>
    </div>
@endsection