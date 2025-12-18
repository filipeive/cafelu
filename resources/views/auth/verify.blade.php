@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-[60vh]">
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Verify Your Email Address') }}</h3>
                </div>

                <div class="p-6">
                    @if (session('resent'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                            <p>{{ __('A fresh verification link has been sent to your email address.') }}</p>
                        </div>
                    @endif

                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                    </p>

                    <p class="text-gray-700 dark:text-gray-300">
                        {{ __('If you did not receive the email') }},
                    </p>

                    <form class="inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit"
                            class="text-blue-600 dark:text-blue-400 hover:underline hover:text-blue-800 dark:hover:text-blue-300 font-medium p-0 m-0 align-baseline transition duration-150 ease-in-out">
                            {{ __('click here to request another') }}
                        </button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection