@extends('layouts.app')

@section('title', __('messages.notifications'))

@section('content')
    <div class="w-full">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('messages.notifications') }}</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">{{ __('messages.manage_your_notifications') }}</p>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-warning">
                        <i class="mdi mdi-check-all mr-2"></i> {{ __('messages.mark_all_as_read') }}
                    </button>
                </form>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            @if($notifications->count() > 0)
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($notifications as $notification)
                        <div
                            class="p-4 flex gap-4 {{ $notification->read_at ? 'opacity-60' : 'bg-orange-50/30 dark:bg-orange-900/10' }}">
                            <div class="{{ $notification->data['color'] }} mt-1">
                                <i class="mdi {{ $notification->data['icon'] }} text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $notification->data['message'] }}
                                    </p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $notification->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <div class="mt-2 flex gap-4 items-center">
                                    <a href="{{ $notification->data['link'] }}"
                                        class="text-xs font-semibold text-warning hover:text-orange-600">
                                        {{ __('messages.view_details') }}
                                    </a>
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="text-xs font-semibold text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                                {{ __('messages.mark_as_read') }}
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST"
                                        onsubmit="return confirm('{{ __('messages.confirm_delete_notification') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700">
                                            {{ __('messages.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div
                        class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="mdi mdi-bell-off-outline text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('messages.no_notifications') }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">{{ __('messages.you_are_all_caught_up') }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection