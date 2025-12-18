@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('expenses.index') }}"
                class="p-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-orange-500 transition-colors shadow-sm">
                <i class="mdi mdi-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('messages.edit_expense') }}</h1>
                <p class="text-gray-500 dark:text-gray-400">{{ __('messages.update_expense_desc') }}</p>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('expenses.update', $expense) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.description') }}
                            *</label>
                        <div class="relative">
                            <i class="mdi mdi-text-short absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="description" id="description"
                                value="{{ old('description', $expense->description) }}" required
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                placeholder="Ex: Pagamento de Energia">
                        </div>
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.amount') }}
                            (MZN) *</label>
                        <div class="relative">
                            <i class="mdi mdi-cash absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="number" name="amount" id="amount" value="{{ old('amount', $expense->amount) }}"
                                step="0.01" min="0" required
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                placeholder="0.00">
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.category') }}
                            *</label>
                        <div class="relative">
                            <i class="mdi mdi-tag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <select name="category" id="category" required
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all appearance-none">
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('category', $expense->category) == $category ? 'selected' : '' }}>
                                        {{ __('messages.' . strtolower($category)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="expense_date"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.date') }}
                            *</label>
                        <div class="relative">
                            <i class="mdi mdi-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="date" name="expense_date" id="expense_date"
                                value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.notes') }}
                            ({{ __('messages.optional') }})</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                            placeholder="{{ __('messages.additional_obs') }}">{{ old('notes', $expense->notes) }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('expenses.index') }}"
                        class="px-6 py-3 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 font-semibold hover:bg-gray-50 dark:hover:bg-gray-900 transition-all">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="submit"
                        class="px-10 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-500/20 transition-all transform hover:-translate-y-0.5">
                        {{ __('messages.update_expense') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection