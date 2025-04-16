@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h4 class="mb-4">{{ isset($employee) ? 'Editar' : 'Novo' }} Funcionário</h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="alert alert-info">
                {{ isset($employee) ? 'Você está editando o funcionário: ' . $employee->name : 'Preencha os dados do novo funcionário' }}
            </div>
            <div class="alert alert-warning">
                {{ isset($employee) ? 'Atenção: Você está editando um funcionário existente.' : 'Atenção: Você está criando um novo funcionário.' }}
            </div>
            <div class="alert alert-secondary">
                {{ isset($employee) ? 'ID do Funcionário: ' . $employee->id : 'Novo Funcionário' }}
            </div>
            <form action="{{ isset($employee) ? route('employees.update', $employee) : route('employees.store') }}"
                method="POST">
                @csrf
                @if (isset($employee))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" name="name" class="form-control"
                        value="{{ old('name', $employee->name ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Cargo</label>
                    <select name="role" class="form-select" required>
                        <option value="chef" {{ old('role', $employee->role ?? '') == 'chef' ? 'selected' : '' }}>Chef
                        </option>
                        <option value="waiter" {{ old('role', $employee->role ?? '') == 'waiter' ? 'selected' : '' }}>
                            Garçom</option>
                        <option value="manager" {{ old('role', $employee->role ?? '') == 'manager' ? 'selected' : '' }}>
                            Gerente</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="hire_date" class="form-label">Data de Contratação</label>
                    <input type="date" name="hire_date" class="form-control"
                        value="{{ old('hire_date', $employee->hire_date ?? '') }}" required>
                </div>

                <button type="submit" class="btn btn-success">{{ isset($employee) ? 'Atualizar' : 'Salvar' }}</button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection
