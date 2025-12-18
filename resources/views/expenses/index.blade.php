@extends('layouts.app')

@section('content')
    <div class="w-full" x-data="{ searchQuery: '' }">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('messages.expenses_management') }}</h1>
                <p class="text-gray-500 dark:text-gray-400">{{ __('messages.expenses_desc') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('expenses.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <i class="mdi mdi-plus mr-2"></i> {{ __('messages.new_expense') }}
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg text-red-600 dark:text-red-400">
                        <i class="mdi mdi-trending-down text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.expenses_month') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($expenses->where('expense_date', '>=', now()->startOfMonth())->sum('amount'), 2) }}
                            MZN</h3>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-lg text-orange-600 dark:text-orange-400">
                        <i class="mdi mdi-cash-multiple text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.daily_average') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($expenses->where('expense_date', '>=', now()->startOfMonth())->sum('amount') / max(now()->day, 1), 2) }}
                            MZN</h3>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                        <i class="mdi mdi-file-document-outline text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.total_records') }}</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $expenses->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 mb-6">
            <div class="relative">
                <i class="mdi mdi-magnify absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                <input type="text" x-model="searchQuery" placeholder="{{ __('messages.search_expenses_placeholder') }}"
                    class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            </div>
        </div>

        <!-- Expenses Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.date') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.description') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.category') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.amount') }}</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">
                                {{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($expenses as $expense)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                                x-show="searchQuery === '' || '{{ strtolower($expense->description) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($expense->category) }}'.includes(searchQuery.toLowerCase())">
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $expense->description }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.by') }}:
                                        {{ $expense->user->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ $expense->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-red-600 dark:text-red-400">
                                        {{ number_format($expense->amount, 2) }} MZN</div>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('expenses.edit', $expense) }}"
                                        class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors inline-block"
                                        title="{{ __('messages.edit') }}">
                                        <i class="mdi mdi-pencil text-xl"></i>
                                    </a>
                                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                            title="{{ __('messages.delete') }}" onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                            <i class="mdi mdi-delete text-xl"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('messages.no_expenses_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($expenses->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection