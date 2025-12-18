@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('employees.index') }}"
                class="p-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-orange-500 transition-colors shadow-sm">
                <i class="mdi mdi-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Novo Funcionário</h1>
                <p class="text-gray-500 dark:text-gray-400">Adicione um novo membro à sua equipe</p>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('employees.store') }}" method="POST" class="p-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nome
                            Completo *</label>
                        <div class="relative">
                            <i class="mdi mdi-account absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                placeholder="Ex: João Silva">
                        </div>
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Cargo
                            *</label>
                        <div class="relative">
                            <i class="mdi mdi-briefcase absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <select name="role" id="role" required
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all appearance-none">
                                <option value="waiter" {{ old('role') == 'waiter' ? 'selected' : '' }}>Garçom</option>
                                <option value="chef" {{ old('role') == 'chef' ? 'selected' : '' }}>Chef</option>
                                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Gerente</option>
                            </select>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Telefone
                            de Contato</label>
                        <div class="relative">
                            <i class="mdi mdi-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                placeholder="Ex: +258 84 000 0000">
                        </div>
                    </div>

                    <!-- Salary -->
                    <div>
                        <label for="salary"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Salário
                            Mensal (MZN)</label>
                        <div class="relative">
                            <i class="mdi mdi-cash absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="number" name="salary" id="salary" value="{{ old('salary') }}" step="0.01" min="0"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                placeholder="0.00">
                        </div>
                    </div>

                    <!-- Hire Date -->
                    <div>
                        <label for="hire_date"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Data
                            de Contratação *</label>
                        <div class="relative">
                            <i class="mdi mdi-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}"
                                required
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('employees.index') }}"
                        class="px-6 py-3 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 font-semibold hover:bg-gray-50 dark:hover:bg-gray-900 transition-all">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-10 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all transform hover:-translate-y-0.5">
                        Salvar Funcionário
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection