@extends('layouts.app')

@section('content')
    <div class="w-full">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('employees.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white transition-colors">
                                {{ __('Funcionários') }}
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Detalhes') }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ __('Perfil do Funcionário') }}</h1>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Editar') }}
                </a>
                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Voltar') }}
                </a>
            </div>
        </div>

        <!-- Employee Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="md:flex">
                <!-- Profile Sidebar -->
                <div class="md:w-1/3 bg-gray-50 dark:bg-gray-900/50 p-8 text-center border-b md:border-b-0 md:border-r border-gray-100 dark:border-gray-700">
                    <div class="relative inline-block">
                        <img src="{{ $employee->photo ? asset('storage/' . $employee->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($employee->name) }}" 
                             alt="{{ $employee->name }}"
                             class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-md mx-auto">
                        <span class="absolute bottom-1 right-1 block h-5 w-5 rounded-full ring-2 ring-white dark:ring-gray-800 bg-green-400"></span>
                    </div>
                    <h2 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ $employee->name }}</h2>
                    <p class="text-indigo-600 dark:text-indigo-400 font-medium">{{ $employee->role }}</p>
                    <div class="mt-6 space-y-2 text-sm text-gray-500 dark:text-gray-400">
                        <p class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ $employee->email }}
                        </p>
                        <p class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            {{ $employee->phone }}
                        </p>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="md:w-2/3 p-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 pb-2 border-b border-gray-100 dark:border-gray-700">
                        {{ __('Informações Gerais') }}
                    </h3>
                    
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Endereço') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $employee->address ?: __('Não informado') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Salário') }}</dt>
                            <dd class="mt-1 text-sm font-bold text-gray-900 dark:text-gray-200">
                               MZN {{ number_format($employee->salary, 2, ',', '.') }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Data de Pagamento') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                                {{ $employee->payment_date ? \Carbon\Carbon::parse($employee->payment_date)->format('d/m/Y') : '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Data de Registro') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                                {{ $employee->created_at}}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection