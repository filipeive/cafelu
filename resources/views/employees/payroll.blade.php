@extends('layouts.app')

@section('content')
    <div class="w-full" x-data="{ 
        month: {{ $month }}, 
        year: {{ $year }},
        updateFilters() {
            window.location.href = `{{ route('employees.payroll') }}?month=${this.month}&year=${this.year}`;
        }
    }">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Folha de Pagamento</h1>
                <p class="text-gray-500 dark:text-gray-400">Gerencie os pagamentos mensais da sua equipe</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('employees.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <i class="mdi mdi-arrow-left mr-2"></i> Voltar
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 mb-8">
            <div class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Mês</label>
                    <select x-model="month" @change="updateFilters()"
                        class="w-48 px-4 py-2 rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Ano</label>
                    <select x-model="year" @change="updateFilters()"
                        class="w-32 px-4 py-2 rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                        @foreach(range(now()->year - 2, now()->year + 1) as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1"></div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Previsto</p>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($employees->sum('salary'), 2) }} MZN
                    </h3>
                </div>
            </div>
        </div>

        <!-- Payroll Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Funcionário</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Cargo</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Salário Base</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($employees as $employee)
                            @php $payment = $payments->get($employee->id); @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $employee->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($employee->role) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ number_format($employee->salary, 2) }} MZN
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($payment)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <i class="mdi mdi-check-circle mr-1"></i> Pago em {{ $payment->payment_date }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                            <i class="mdi mdi-clock-outline mr-1"></i> Pendente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if(!$payment)
                                        <form action="{{ route('employees.pay-salary', $employee) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="month" value="{{ $month }}">
                                            <input type="hidden" name="year" value="{{ $year }}">
                                            <button type="submit" 
                                                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-bold rounded-lg transition-colors shadow-sm"
                                                onclick="return confirm('Confirmar pagamento de salário para {{ date('F', mktime(0, 0, 0, $month, 1)) }}?')">
                                                Pagar Agora
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 text-sm font-bold rounded-lg cursor-not-allowed">
                                            Já Pago
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
