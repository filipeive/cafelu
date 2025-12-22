@extends('layouts.app')

@section('title', 'Meu Painel')

@section('content')
    <div class="w-full pb-12">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Olá, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Bem-vindo de volta ao seu portal exclusivo.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-xl text-orange-600 dark:text-orange-400">
                        <i class="mdi mdi-shopping text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total de Pedidos</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_orders'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl text-green-600 dark:text-green-400">
                        <i class="mdi mdi-cash-multiple text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Investido</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($stats['total_spent'], 2) }} MT
                        </h3>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl text-blue-600 dark:text-blue-400">
                        <i class="mdi mdi-clock-fast text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pedidos Ativos</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['active_orders'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Orders -->
            <div class="lg:col-span-2 space-y-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Pedidos Recentes</h2>
                        <a href="{{ route('customer.orders') }}"
                            class="text-sm font-semibold text-orange-500 hover:text-orange-600 transition-colors">
                            Ver Todos <i class="mdi mdi-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">
                                    <th class="px-6 py-4">ID</th>
                                    <th class="px-6 py-4">Data</th>
                                    <th class="px-6 py-4">Total</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Acompanhamento</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($orders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        <td class="px-6 py-4 font-mono text-orange-600 dark:text-orange-400 font-medium">
                                            #{{ $order->id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                            {{ number_format($order->total_amount, 2) }} MT
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusClasses = [
                                                    'active' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                                    'preparing' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                                    'ready' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                                    'completed' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
                                                    'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                                    'canceled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                                ];
                                                $statusLabels = [
                                                    'active' => 'Recebido',
                                                    'preparing' => 'Preparando',
                                                    'ready' => 'Pronto para Retirada',
                                                    'completed' => 'Entregue',
                                                    'paid' => 'Pago',
                                                    'canceled' => 'Cancelado',
                                                ];
                                            @endphp
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-bold {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $statusLabels[$order->status] ?? $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-2">
                                                @if(!in_array($order->status, ['paid', 'completed', 'canceled']))
                                                    <div class="flex items-center gap-1">
                                                        <div
                                                            class="flex-1 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden flex">
                                                            <div class="h-full bg-blue-500 {{ $order->status === 'active' ? 'animate-pulse' : '' }}"
                                                                style="width: 25%"></div>
                                                            <div class="h-full bg-yellow-500 {{ $order->status === 'preparing' ? 'animate-pulse' : '' }}"
                                                                style="width: {{ in_array($order->status, ['preparing', 'ready', 'completed']) ? '25%' : '0%' }}">
                                                            </div>
                                                            <div class="h-full bg-purple-500 {{ $order->status === 'ready' ? 'animate-pulse' : '' }}"
                                                                style="width: {{ in_array($order->status, ['ready', 'completed']) ? '25%' : '0%' }}">
                                                            </div>
                                                            <div class="h-full bg-indigo-500 {{ $order->status === 'completed' ? 'animate-pulse' : '' }}"
                                                                style="width: {{ $order->status === 'completed' ? '25%' : '0%' }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span
                                                        class="text-[10px] font-bold uppercase {{ $order->status === 'ready' ? 'text-purple-500 animate-bounce' : 'text-gray-500' }}">
                                                        @if($order->status === 'active') Aguardando Início @endif
                                                        @if($order->status === 'preparing') Na Cozinha @endif
                                                        @if($order->status === 'ready') Pronto! @endif
                                                        @if($order->status === 'completed') Entregue @endif
                                                    </span>

                                                    <button
                                                        onclick="window.openPaymentModal({{ $order->id }}, {{ $order->total_amount }})"
                                                        class="mt-2 w-full py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                                        Pagar Agora
                                                    </button>
                                                @else
                                                    <div class="flex items-center gap-1 text-green-500">
                                                        <i class="mdi mdi-check-circle text-sm"></i>
                                                        <span class="text-[10px] font-bold uppercase">
                                                            {{ $order->status === 'canceled' ? 'Cancelado' : 'Finalizado' }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center text-gray-400">
                                                <i class="mdi mdi-cart-outline text-5xl mb-4"></i>
                                                <p class="text-lg">Nenhum pedido realizado ainda.</p>
                                                <a href="{{ route('welcome') }}#menu"
                                                    class="mt-4 bg-orange-500 text-white px-6 py-2 rounded-xl font-bold hover:bg-orange-600 transition-colors shadow-lg shadow-orange-500/30">
                                                    Explorar Cardápio
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-8">
                <!-- Recommended Products -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <i class="mdi mdi-star text-yellow-500"></i> Sugestões para Você
                    </h2>
                    <div class="space-y-4">
                        @foreach($recommendedProducts as $product)
                            <div class="flex items-center gap-4 group cursor-pointer">
                                <div class="w-16 h-16 rounded-xl bg-gray-100 dark:bg-gray-700 overflow-hidden flex-shrink-0">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i class="mdi mdi-food text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4
                                        class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-orange-500 transition-colors">
                                        {{ $product->name }}
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($product->price, 2) }}
                                        MT</p>
                                </div>
                                <a href="{{ route('welcome') }}#menu"
                                    class="p-2 rounded-lg bg-orange-50 dark:bg-orange-900/20 text-orange-500 hover:bg-orange-500 hover:text-white transition-all">
                                    <i class="mdi mdi-plus"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Quick Profile Card -->
                <div
                    class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 opacity-10 -mr-8 -mt-8">
                        <i class="mdi mdi-account-circle text-[120px]"></i>
                    </div>
                    <div class="relative z-10">
                        <h2 class="text-lg font-bold mb-2">Seu Perfil</h2>
                        <p class="text-orange-100 text-sm mb-6">Mantenha seus dados atualizados para uma melhor experiência.
                        </p>
                        <a href="{{ route('customer.profile') }}"
                            class="inline-flex items-center gap-2 bg-white text-orange-600 px-4 py-2 rounded-xl font-bold text-sm hover:bg-orange-50 transition-colors shadow-md">
                            Editar Perfil <i class="mdi mdi-pencil text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('customer.partials.payment-modal')
@endsection