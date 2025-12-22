@extends('layouts.app')
@section('title', 'Mesas - Restaurante Pro')

@section('content')
    <div x-data="tableManagement({{ $tables->toJson() }})" class="w-full">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                        <i class="mdi mdi-table-furniture text-blue-600 mr-2"></i>
                        Gerenciamento de Mesas
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Visualize, organize e gerencie as mesas do restaurante</p>
                </div>
                <div class="mt-4 md:mt-0">
                <div class="mt-4 md:mt-0 flex gap-2">
                    <button @click="openCreateModal()" type="button" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center transition duration-150 ease-in-out">
                        <i class="mdi mdi-plus mr-2"></i> Criar Mesa
                    </button>
                    <button @click="openMergeModal()" type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center transition duration-150 ease-in-out">
                        <i class="mdi mdi-link mr-2"></i> Unir Mesas
                    </button>
                    <!-- ver pedidos -->
                    <a href="{{ route('orders.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center transition duration-150 ease-in-out">
                        <i class="mdi mdi-table-furniture mr-2"></i> Ver Pedidos
                    </a>
                </div>
                </div>
            </div>
        </div>

        <!-- Status Legend -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <div class="flex flex-wrap gap-6">
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded bg-gradient-to-br from-green-500 to-green-700 mr-2"></div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mesa Livre</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded bg-gradient-to-br from-red-500 to-red-700 mr-2"></div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mesa Ocupada</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded bg-gradient-to-br from-blue-500 to-blue-700 mr-2"></div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mesa Agrupada</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded bg-gradient-to-br from-yellow-500 to-yellow-700 mr-2"></div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mesa Temporária</span>
                </div>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900">
                <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="mdi mdi-view-grid mr-2"></i>
                    Mesas Disponíveis
                </h5>
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">Total: {{ count($tables) }}</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($tables as $table)
                        @php
                            $hasActiveOrder = $table->hasActiveOrder();
                            $isGrouped = $table->group_id !== null;
                            $isMain = $table->is_main;
                            $activeOrder = $table->activeOrder();
                            
                            // Determine gradient based on status
                            if ($hasActiveOrder) {
                                $gradientClass = 'bg-gradient-to-br from-red-500 to-red-700';
                            } elseif ($isGrouped) {
                                $gradientClass = 'bg-gradient-to-br from-blue-500 to-blue-700';
                            } elseif ($table->is_temporary) {
                                $gradientClass = 'bg-gradient-to-br from-yellow-500 to-yellow-700';
                            } else {
                                $gradientClass = 'bg-gradient-to-br from-green-500 to-green-700';
                            }
                        @endphp

                        <div class="relative overflow-hidden rounded-xl shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl flex flex-col min-h-[220px] text-white {{ $gradientClass }}">
                            <!-- Background Pattern -->
                            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white to-transparent pointer-events-none"></div>

                            @if ($isGrouped)
                                <div class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/20">
                                    <i class="mdi mdi-link-variant text-white"></i>
                                </div>
                            @endif

                            @if ($table->is_temporary)
                                <div class="absolute top-4 right-4 w-auto px-2 h-6 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/20 text-xs font-bold">
                                    TEMP
                                </div>
                                @if(!$hasActiveOrder)
                                    <form action="{{ route('tables.destroy', $table->id) }}" method="POST" class="absolute top-4 left-4 z-50" onsubmit="return confirm('Tem certeza que deseja remover esta mesa temporária?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-full bg-red-500/80 hover:bg-red-600 backdrop-blur-sm flex items-center justify-center border border-white/20 transition-colors">
                                            <i class="mdi mdi-delete text-white"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif

                            <div class="relative z-10 flex-grow flex flex-col p-6">
                                <h3 class="text-3xl font-bold mb-2 drop-shadow-md">{{ $table->number }}</h3>

                                <div class="mb-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide bg-white/20 backdrop-blur-sm border border-white/20 shadow-sm mb-2">
                                        <i class="mdi {{ $hasActiveOrder ? 'mdi-lock' : 'mdi-lock-open' }} mr-1"></i>
                                        {{ $hasActiveOrder ? 'Ocupada' : 'Livre' }}
                                    </span>

                                    <div class="mt-1 flex items-center opacity-90 text-sm font-medium">
                                        <i class="mdi mdi-account-multiple mr-1"></i>
                                        {{ $table->merged_capacity ?? $table->capacity }} lugares
                                    </div>
                                </div>

                                @if ($hasActiveOrder)
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-yellow-400 text-yellow-900 shadow-sm">
                                            <i class="mdi mdi-receipt mr-1"></i>
                                            Pedido #{{ $activeOrder->id }}
                                        </span>
                                        @if($activeOrder->customer_name)
                                            <div class="mt-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-blue-400 text-blue-900 shadow-sm">
                                                    <i class="mdi mdi-account mr-1"></i>
                                                    {{ $activeOrder->customer_name }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="relative z-20 p-4 mt-auto flex flex-wrap gap-2 justify-center bg-black/10 backdrop-blur-sm transition-opacity duration-300">
                                @if (!$hasActiveOrder && (!$isGrouped || $isMain))
                                    <form action="{{ route('tables.create-order', $table) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-full shadow-sm text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                            <i class="mdi mdi-plus-circle mr-1"></i>Novo Pedido
                                        </button>
                                    </form>
                                @elseif($hasActiveOrder && (!$isGrouped || $isMain))
                                    <a href="{{ route('orders.edit', $activeOrder->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-full shadow-sm text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <i class="mdi mdi-pencil mr-1"></i>Editar Pedido
                                    </a>
                                @endif

                                @if ($isGrouped && $isMain)
                                    <form action="{{ route('tables.split') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="group_id" value="{{ $table->group_id }}">
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-full shadow-sm text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            <i class="mdi mdi-link-off mr-1"></i>Separar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Merge Tables Modal -->
        <div x-show="showMergeModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showMergeModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75" @click="showMergeModal = false"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showMergeModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full">
                    <form action="{{ route('tables.merge') }}" method="POST">
                        @csrf
                        <div class="bg-blue-600 px-4 py-3 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-white flex items-center" id="modal-title">
                                <i class="mdi mdi-link mr-2"></i> Unir Mesas
                            </h3>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mb-4 bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="mdi mdi-information-outline text-blue-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700 dark:text-blue-200">
                                            Selecione pelo menos duas mesas livres para uni-las. Depois escolha qual será a mesa principal onde os pedidos serão registrados.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Selecione as mesas para unir</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach ($tables as $table)
                                        @if ($table->status === 'free' && !$table->group_id)
                                            <div class="relative">
                                                <input type="checkbox" id="table-{{ $table->id }}" value="{{ $table->id }}" x-model="selectedTables" class="peer sr-only">
                                                <label for="table-{{ $table->id }}" class="flex items-center p-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900 peer-checked:text-blue-600 dark:peer-checked:text-blue-300 transition-all">
                                                    <i class="mdi mdi-table-furniture text-lg mr-3 text-gray-400 peer-checked:text-blue-500"></i>
                                                    <span class="text-sm font-medium">Mesa {{ $table->number }} ({{ $table->capacity }} lug.)</span>
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="main_table_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Mesa Principal</label>
                                <select id="main_table_id" name="main_table_id" x-model="mainTable" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="">Selecione uma mesa principal</option>
                                    <template x-for="table in getSelectedTableObjects()" :key="table.id">
                                        <option :value="table.id" x-text="`Mesa ${table.number} (${table.capacity} lugares)`"></option>
                                    </template>
                                </select>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    <i class="mdi mdi-information-outline mr-1 text-blue-500"></i>
                                    A mesa principal será onde os pedidos serão registrados
                                </p>
                            </div>
                            
                            <!-- Hidden inputs for selected tables -->
                            <template x-for="tableId in selectedTables" :key="tableId">
                                <input type="hidden" name="table_ids[]" :value="tableId">
                            </template>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" :disabled="!canMerge" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                Unir Mesas
                            </button>
                            <button @click="showMergeModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    
    <!-- Create Table Modal -->
    <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="closeCreateModal()">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('tables.store') }}" method="POST">
                    @csrf
                    <div class="bg-green-600 px-4 py-3 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-white flex items-center">
                            <i class="mdi mdi-plus-circle mr-2"></i> Criar Nova Mesa
                        </h3>
                    </div>
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <label for="number" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Número da Mesa</label>
                            <input type="number" name="number" id="number" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="capacity" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Capacidade (Lugares)</label>
                            <input type="number" name="capacity" id="capacity" required min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_temporary" value="1" class="form-checkbox h-5 w-5 text-green-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Mesa Temporária?</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Mesas temporárias podem ser removidas facilmente.</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Criar
                        </button>
                        <button @click="closeCreateModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Pedidos -->
    </div>
@push('scripts')
    <script>
        function tableManagement(tablesData) {
            return {
                showMergeModal: false,
                showCreateModal: false,
                selectedTables: [],
                mainTable: '',
                tables: tablesData,

                init() {
                    console.log('Table Management Initialized', this.tables);
                },

                openMergeModal() {
                    console.log('Opening Merge Modal');
                    this.selectedTables = [];
                    this.mainTable = '';
                    this.showMergeModal = true;
                },

                openCreateModal() {
                    console.log('Opening Create Modal');
                    this.showCreateModal = true;
                },

                closeCreateModal() {
                    this.showCreateModal = false;
                },

                closeMergeModal() {
                    this.showMergeModal = false;
                },

                getSelectedTableObjects() {
                    return this.tables.filter(t => this.selectedTables.includes(t.id.toString()));
                },

                get canMerge() {
                    return this.selectedTables.length >= 2 && this.mainTable !== '';
                }
            }
        }
    </script>
@endpush
@endsection