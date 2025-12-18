@extends('layouts.app')

@section('content')
    <div class="w-full">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('audit_logs.index') }}"
                                class="text-sm text-gray-500 hover:text-orange-500 dark:text-gray-400 transition-colors">
                                <i class="mdi mdi-history mr-1"></i> Auditoria
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="mdi mdi-chevron-right text-gray-400"></i>
                                <span class="ml-1 text-sm font-medium text-gray-900 dark:text-white md:ml-2">Detalhes do
                                    Log</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalhes do Log #{{ $auditLog->id }}</h1>
            </div>
            <a href="{{ route('audit_logs.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                <i class="mdi mdi-arrow-left mr-2"></i> Voltar
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Summary Card -->
            <div class="lg:col-span-1 space-y-6">
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-6 uppercase tracking-wider">Resumo da Ação
                    </h3>

                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl flex items-center justify-center 
                                        {{ $auditLog->action === 'created' ? 'bg-green-100 text-green-600' : ($auditLog->action === 'updated' ? 'bg-blue-100 text-blue-600' : 'bg-red-100 text-red-600') }}">
                                <i
                                    class="mdi mdi-{{ $auditLog->action === 'created' ? 'plus' : ($auditLog->action === 'updated' ? 'pencil' : 'delete') }} text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Ação</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ ucfirst($auditLog->action) }}
                                </p>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold mb-1">Modelo Afetado</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                <span class="font-bold">{{ class_basename($auditLog->auditable_type) }}</span>
                                <span class="text-gray-500">#{{ $auditLog->auditable_id }}</span>
                            </p>
                        </div>

                        <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold mb-1">Realizado por</p>
                            <div class="flex items-center gap-2 mt-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-400 text-xs font-bold">
                                    {{ $auditLog->user ? strtoupper(substr($auditLog->user->name, 0, 2)) : 'S' }}
                                </div>
                                <span
                                    class="text-sm font-bold text-gray-900 dark:text-white">{{ $auditLog->user->name ?? 'Sistema' }}</span>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold mb-1">Data e Hora</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $auditLog->created_at->format('d/m/Y H:i:s') }}
                                <span
                                    class="text-xs text-gray-500 block">({{ $auditLog->created_at->diffForHumans() }})</span>
                            </p>
                        </div>

                        <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold mb-1">Origem</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white flex items-center gap-2">
                                <i class="mdi mdi-ip-network text-gray-400"></i>
                                {{ $auditLog->ip_address }}
                            </p>
                            <p class="text-[10px] text-gray-400 mt-1 break-all">{{ $auditLog->user_agent }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Values Comparison -->
            <div class="lg:col-span-2 space-y-6">
                @if($auditLog->action === 'updated')
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Comparação de
                                Valores</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Campo
                                        </th>
                                        <th
                                            class="px-6 py-3 text-xs font-bold text-red-500 uppercase bg-red-50/30 dark:bg-red-900/10">
                                            Valor Antigo</th>
                                        <th
                                            class="px-6 py-3 text-xs font-bold text-green-500 uppercase bg-green-50/30 dark:bg-green-900/10">
                                            Novo Valor</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($auditLog->new_values as $key => $newValue)
                                        @php $oldValue = $auditLog->old_values[$key] ?? null; @endphp
                                        <tr>
                                            <td class="px-6 py-4 text-sm font-bold text-gray-700 dark:text-gray-300">{{ $key }}</td>
                                            <td
                                                class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 bg-red-50/10 dark:bg-red-900/5 line-through decoration-red-300">
                                                {{ is_array($oldValue) ? json_encode($oldValue) : ($oldValue ?? 'null') }}
                                            </td>
                                            <td
                                                class="px-6 py-4 text-sm text-gray-900 dark:text-white bg-green-50/10 dark:bg-green-900/5 font-medium">
                                                {{ is_array($newValue) ? json_encode($newValue) : ($newValue ?? 'null') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                {{ $auditLog->action === 'created' ? 'Valores do Novo Registro' : 'Valores do Registro Removido' }}
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php $values = $auditLog->action === 'created' ? $auditLog->new_values : $auditLog->old_values; @endphp
                                @foreach($values as $key => $value)
                                    <div
                                        class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-gray-100 dark:border-gray-600">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">{{ $key }}</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white break-all">
                                            {{ is_array($value) ? json_encode($value) : ($value ?? 'null') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection