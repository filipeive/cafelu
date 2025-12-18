@extends('layouts.app')

@extends('layouts.app')
@section('title', 'Detalhes do Produto')

@section('content')
    <div class="container-wrapper">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="mdi mdi-package-variant-closed text-orange-500"></i>
                    {{ $product->name }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Detalhes e estatísticas do produto</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                    <i class="mdi mdi-arrow-left mr-2"></i> Voltar
                </a>
                @if (Auth::user()->role == 'admin')
                    <button type="button" onclick="editProduct({{ $product->id }})"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                        <i class="mdi mdi-pencil mr-2"></i> Editar
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Details Card -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <!-- Image -->
                        <div class="mb-6 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-900 relative group">
                            @if($product->image_path)
                                <img src="{{ Storage::url($product->image_path) }}"
                                    class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-500"
                                    alt="{{ $product->name }}">
                            @else
                                <div class="h-64 flex flex-col items-center justify-center text-gray-400 dark:text-gray-600">
                                    <i class="mdi mdi-image-off text-6xl mb-2"></i>
                                    <span class="text-sm">Sem imagem</span>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="absolute top-4 right-4">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold shadow-sm backdrop-blur-sm
                                    {{ $product->is_active ? 'bg-green-100/90 text-green-700 dark:bg-green-900/90 dark:text-green-300' : 'bg-red-100/90 text-red-700 dark:bg-red-900/90 dark:text-red-300' }}">
                                    {{ $product->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                        </div>

                        <!-- Info List -->
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Categoria</label>
                                <div class="mt-1 flex items-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                                        <i class="mdi mdi-tag-outline mr-1"></i>
                                        {{ $product->category->name ?? 'Sem categoria' }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Preço
                                        de Venda</label>
                                    <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                                        MZN {{ number_format($product->selling_price, 2, ',', '.') }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Preço
                                        de Custo</label>
                                    <p class="mt-1 text-lg font-medium text-gray-600 dark:text-gray-300">
                                        MZN {{ number_format($product->purchase_price, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estoque
                                    Atual</label>
                                <div class="mt-1 flex items-center justify-between">
                                    <span
                                        class="text-2xl font-bold {{ $product->stock_quantity <= $product->min_stock_level ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                        {{ $product->stock_quantity }} <span
                                            class="text-sm font-normal text-gray-500">{{ $product->unit }}</span>
                                    </span>
                                    <span class="text-xs text-gray-500">Mínimo: {{ $product->min_stock_level }}</span>
                                </div>
                                <!-- Stock Progress Bar -->
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mt-2">
                                    <div class="h-2.5 rounded-full {{ $product->stock_quantity <= $product->min_stock_level ? 'bg-red-600' : 'bg-green-600' }}"
                                        style="width: {{ min(($product->stock_quantity / ($product->min_stock_level * 3)) * 100, 100) }}%">
                                    </div>
                                </div>
                            </div>

                            @if($product->description)
                                <div>
                                    <label
                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Descrição</label>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                        {{ $product->description }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Sales Chart -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <i class="mdi mdi-chart-line text-blue-500"></i>
                        Histórico de Vendas
                    </h4>
                    <div class="relative h-64 w-full">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Stock History Chart -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <i class="mdi mdi-chart-bar text-green-500"></i>
                        Histórico de Estoque
                    </h4>
                    <div class="relative h-64 w-full">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal (Reused from Index if needed, or redirect to edit page) -->
    <!-- Note: The edit button currently redirects to a route, but if we want to use the modal we need to include it or change logic. 
         For now, I'll keep the button logic consistent with the index page which uses a modal. 
         However, the index page has the modal code. The show page might not have it.
         The original code had a link to `products.edit`. If that route returns a view, I should refactor that view too.
         But the index page uses a modal. Let's check if `products.edit` route exists and what it returns.
         If it's a separate page, I should refactor it. If it's not used (since index uses modal), I might redirect to index with modal open?
         Actually, the original code had: <a href="{{ route('products.edit', $product) }}" ...>
         Let's check if there is an edit.blade.php.
    -->
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart Defaults
            Chart.defaults.color = document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563';
            Chart.defaults.borderColor = document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb';

            loadSalesData();
            loadStockData();
        });

        function loadSalesData() {
            fetch(`/products/${@json($product->id)}/sales-data`)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('salesChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.dates,
                            datasets: [{
                                label: 'Vendas',
                                data: data.quantities,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 3,
                                pointHoverRadius: 5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    borderColor: 'rgba(255, 255, 255, 0.1)',
                                    borderWidth: 1
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        borderDash: [2, 2]
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                });
        }

        function loadStockData() {
            fetch(`/products/${@json($product->id)}/stock-history`)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('stockChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.dates,
                            datasets: [{
                                label: 'Nível de Estoque',
                                data: data.quantities,
                                backgroundColor: '#10b981',
                                borderRadius: 4,
                                barThickness: 20
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        borderDash: [2, 2]
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                });
        }
    </script>
@endpush