@extends('layouts.app')

@section('content')
    <div class="w-full">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="mdi mdi-cog text-orange-500"></i>
                {{ __('messages.system_settings_title') }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ __('messages.system_settings_desc') }}</p>
        </div>

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-8">
                @foreach($settings as $group => $groupSettings)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-8 py-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                {{ __('messages.' . $group) }}
                            </h2>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($groupSettings as $setting)
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('messages.' . $setting->key) }}
                                    </label>
                                    @if($setting->type === 'text')
                                        <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                            class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                    @elseif($setting->type === 'number')
                                        <input type="number" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                            class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                    @elseif($setting->type === 'textarea')
                                        <textarea name="{{ $setting->key }}" rows="3"
                                            class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">{{ $setting->value }}</textarea>
                                    @elseif($setting->type === 'file')
                                        <div class="flex items-center gap-4">
                                            @if($setting->value)
                                                <img src="{{ asset('storage/' . $setting->value) }}"
                                                    class="w-16 h-16 rounded-lg object-contain bg-gray-100 dark:bg-gray-900 p-2">
                                            @endif
                                            <input type="file" name="{{ $setting->key }}"
                                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                                        </div>
                                    @elseif($setting->type === 'color')
                                        <div class="flex items-center gap-3">
                                            <input type="color" name="{{ $setting->key }}" value="{{ $setting->value }}"
                                                class="w-12 h-12 border-0 rounded-xl cursor-pointer bg-transparent">
                                            <input type="text" value="{{ $setting->value }}" readonly
                                                class="flex-1 px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">
                                        </div>
                                    @elseif($setting->type === 'select')
                                        <select name="{{ $setting->key }}"
                                            class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                            @if($setting->key === 'system_language')
                                                <option value="en" {{ $setting->value === 'en' ? 'selected' : '' }}>
                                                    {{ __('messages.english') }}</option>
                                                <option value="pt" {{ $setting->value === 'pt' ? 'selected' : '' }}>
                                                    {{ __('messages.portuguese') }}</option>
                                            @elseif($setting->key === 'system_timezone')
                                                @foreach(\DateTimeZone::listIdentifiers() as $timezone)
                                                    <option value="{{ $timezone }}" {{ $setting->value === $timezone ? 'selected' : '' }}>
                                                        {{ $timezone }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="flex items-center justify-end gap-3">
                    <button type="submit"
                        class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-orange-500/20 flex items-center gap-2">
                        <i class="mdi mdi-content-save"></i>
                        {{ __('messages.save_settings') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection