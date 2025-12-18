@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumbs & Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('users.index') }}" class="text-sm text-gray-500 hover:text-orange-500 dark:text-gray-400 transition-colors">
                                <i class="mdi mdi-account-group mr-1"></i> Usuários
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="mdi mdi-chevron-right text-gray-400"></i>
                                <span class="ml-1 text-sm font-medium text-gray-900 dark:text-white md:ml-2">Detalhes</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Perfil do Usuário</h1>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('users.index') }}" 
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                    <i class="mdi mdi-arrow-left mr-2"></i> Voltar
                </a>
                <a href="{{ route('users.edit', $user->id) }}" 
                    class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-orange-500/20">
                    <i class="mdi mdi-pencil mr-2"></i> Editar
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- User Info Card -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="h-24 bg-gradient-to-r from-orange-400 to-orange-600"></div>
                    <div class="px-6 pb-8">
                        <div class="relative flex justify-center -mt-12 mb-4">
                            <div class="w-24 h-24 rounded-full bg-white dark:bg-gray-800 p-1 shadow-lg">
                                <div class="w-full h-full rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400 text-3xl font-bold">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            <div class="mt-4 flex justify-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-{{ get_role_class($user->role) }}-100 text-{{ get_role_class($user->role) }}-800 dark:bg-{{ get_role_class($user->role) }}-900/30 dark:text-{{ get_role_class($user->role) }}-300">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-8 space-y-4 border-t border-gray-100 dark:border-gray-700 pt-6">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Username</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $user->username }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Status</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    Ativo
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Último Acesso</span>
                                <span class="text-gray-900 dark:text-white font-medium">
                                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wider">Informações do Sistema</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg text-gray-400">
                                <i class="mdi mdi-calendar-plus"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Criado em</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg text-gray-400">
                                <i class="mdi mdi-update"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Última Atualização</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Log Section -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="mdi mdi-history text-orange-500"></i>
                            Atividade Recente
                        </h3>
                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold rounded-full">
                            {{ $user->auditLogs()->count() }} registros
                        </span>
                    </div>
                    
                    <div class="p-6">
                        @php
                            $logs = $user->auditLogs()->latest()->take(10)->get();
                        @endphp

                        @if($logs->count() > 0)
                            <div class="flow-root">
                                <ul role="list" class="-mb-8">
                                    @foreach($logs as $log)
                                        <li>
                                            <div class="relative pb-8">
                                                @if(!$loop->last)
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-100 dark:bg-gray-700" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-800 
                                                            {{ $log->action === 'created' ? 'bg-green-100 text-green-600' : ($log->action === 'updated' ? 'bg-blue-100 text-blue-600' : 'bg-red-100 text-red-600') }}">
                                                            <i class="mdi mdi-{{ $log->action === 'created' ? 'plus' : ($log->action === 'updated' ? 'pencil' : 'delete') }} text-sm"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                        <div>
                                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                {{ ucfirst($log->action) }} 
                                                                <span class="font-bold text-gray-900 dark:text-white">
                                                                    {{ class_basename($log->auditable_type) }}
                                                                </span>
                                                                @if($log->action === 'updated' && $log->new_values)
                                                                    <span class="text-xs italic">
                                                                        ({{ implode(', ', array_keys($log->new_values)) }})
                                                                    </span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div class="whitespace-nowrap text-right text-xs text-gray-500 dark:text-gray-400">
                                                            <time datetime="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="mt-6 text-center">
                                <a href="#" class="text-sm font-bold text-orange-500 hover:text-orange-600 transition-colors">Ver todo o histórico</a>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl inline-block mb-4">
                                    <i class="mdi mdi-text-box-search-outline text-4xl text-gray-300 dark:text-gray-600"></i>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">Nenhuma atividade registrada ainda.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection