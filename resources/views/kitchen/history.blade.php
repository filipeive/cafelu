@extends('layouts.app')

@section('title', 'Histórico')
@section('title-icon', 'mdi-history')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('kitchen.dashboard') }}" class="d-flex align-items-center">
            <i class="mdi mdi-chef-hat me-1"></i> Cozinha
        </a>
    </li>
    <li class="breadcrumb-item active d-flex align-items-center">
        <i class="mdi mdi-history me-1"></i> Histórico
    </li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">
                    <i class="mdi mdi-history me-2"></i>
                    Histórico da Cozinha
                </h2>
                <p class="text-muted mb-0">Pedidos finalizados nas últimas 24h</p>
            </div>
            <a href="{{ route('kitchen.dashboard') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-2"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('kitchen.history') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Data Inicial</label>
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Data Final</label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="table_id" class="form-label">Mesa</label>
                            <select class="form-select" name="table_id">
                                <option value="">Todas as mesas</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table->id }}" {{ request('table_id') == $table->id ? 'selected' : '' }}>
                                        Mesa {{ $table->number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="mdi mdi-magnify me-2"></i> Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- History Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Pedido</th>
                                <th>Mesa</th>
                                <th>Itens</th>
                                <th>Total</th>
                                <th>Finalizado em</th>
                                <th>Tempo Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>
                                    <strong>#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                    @if($order->customer_name)
                                        <br><small>{{ $order->customer_name }}</small>
                                    @endif
                                </td>
                                <td>Mesa {{ $order->table?->number ?? 'Balcão' }}</td>
                                <td>{{ $order->items->count() }} itens</td>
                                <td>MZN {{ number_format($order->total_amount, 2, ',', '.') }}</td>
                                <td>{{ $order->completed_at?->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($order->completed_at && $order->created_at)
                                        {{ $order->created_at->diffInMinutes($order->completed_at) }} min
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="mdi mdi-history" style="font-size: 3rem; color: #6b7280;"></i>
                                    <h5 class="mt-3">Nenhum pedido finalizado</h5>
                                    <p class="text-muted">Nenhum pedido foi finalizado no período selecionado.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection