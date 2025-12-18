@extends('layouts.app')

@section('content')
    <div class="w-full">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="mdi mdi-history text-orange-500"></i>
                    {{ __('messages.audit_logs') }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ __('messages.audit_logs_desc') }}</p>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 mb-8">
            <form action="{{ route('audit_logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">{{ __('messages.user') }}</label>
                    <select name="user_id"
                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                        <option value="">{{ __('messages.all_users') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">{{ __('messages.action') }}</label>
                    <select name="action"
                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                        <option value="">{{ __('messages.all_actions') }}</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>{{ __('messages.creation') }}</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>{{ __('messages.update') }}</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>{{ __('messages.deletion') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">{{ __('messages.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_placeholder') }}"
                        class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-500/20 text-sm">
                        {{ __('messages.filter') }}
                    </button>
                    <a href="{{ route('audit_logs.index') }}"
                        class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-bold rounded-xl hover:bg-gray-200 transition-all text-sm">
                        {{ __('messages.clear') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.date_time') }}</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.user') }}</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.action') }}</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.model') }}</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.details') }}</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.ip') }}</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">
                                {{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($logs as $log)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                                <div class="font-medium">{{ $log->created_at->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-400 text-xs font-bold">
                                                        {{ $log->user ? strtoupper(substr($log->user->name, 0, 2)) : 'S' }}
                                                    </div>
                                                    <span
                                                        class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->user->name ?? __('messages.system_user') }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                                                                        {{ $log->action === 'created' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
                            ($log->action === 'updated' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' :
                                'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300') }}">
                                                    {{ __('messages.' . ($log->action === 'created' ? 'creation' : ($log->action === 'updated' ? 'update' : 'deletion'))) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                                <span class="font-bold">{{ class_basename($log->auditable_type) }}</span>
                                                <span class="text-gray-500">#{{ $log->auditable_id }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($log->action === 'updated' && $log->new_values)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                                        {{ __('messages.changed') }}: {{ implode(', ', array_keys($log->new_values)) }}
                                                    </div>
                                                @elseif($log->action === 'created')
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.new_record_created') }}</div>
                                                @elseif($log->action === 'deleted')
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.record_removed') }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                                                {{ $log->ip_address }}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('audit_logs.show', $log->id) }}"
                                                    class="p-2 bg-gray-50 dark:bg-gray-700 text-gray-400 hover:text-orange-500 rounded-lg transition-colors"
                                                    title="{{ __('messages.view_details') }}">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i
                                            class="mdi mdi-text-box-search-outline text-6xl text-gray-200 dark:text-gray-700"></i>
                                        <p class="text-gray-500 dark:text-gray-400 mt-4">{{ __('messages.no_logs_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection