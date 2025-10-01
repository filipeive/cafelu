@extends('layouts.app')

@section('page-title', isset($category) ? 'Editar Categoria' : 'Nova Categoria')
@section('title-icon', 'mdi-format-list-bulleted')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('categories.index') }}">
            <i class="mdi mdi-format-list-bulleted me-1"></i> Categorias
        </a>
    </li>
    <li class="breadcrumb-item active d-flex align-items-center">
        {{ isset($category) ? 'Editar' : 'Nova' }}
    </li>
@endsection

@push('styles')
    <style>
        .category-form-header {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0;
        }

        .category-form-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #0891b2;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .category-form-desc {
            color: #64748b;
            font-size: 1rem;
            margin-bottom: 0;
        }

        .category-form-card {
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background: #fff;
            padding: 2rem;
            margin-top: 0;
        }

        @media (max-width: 768px) {
            .category-form-header {
                padding: 1rem;
            }

            .category-form-card {
                padding: 1rem;
            }

            .category-form-title {
                font-size: 1.2rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container wrapper py-4">
        <div class="card justify-content-center">
            <div class="card-body col-lg-7">
                <div class="category-form-header mb-0">
                    <span class="category-form-title">
                        <i class="mdi mdi-format-list-bulleted"></i>
                        {{ isset($category) ? 'Editar Categoria' : 'Nova Categoria' }}
                    </span>
                    <span class="category-form-desc">
                        {{ isset($category) ? 'Altere o nome da categoria do cardápio.' : 'Crie uma nova categoria para organizar seus produtos.' }}
                    </span>
                </div>
                <div class="category-form-card">
                    <form method="POST"
                        action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}">
                        @csrf
                        @if (isset($category))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nome da Categoria <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control rounded-3 @error('name') is-invalid @enderror"
                                value="{{ old('name', $category->name ?? '') }}" required
                                placeholder="Ex: Bebidas, Entradas, Pratos Principais" autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Estado da Categoria</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                    {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    {{ old('is_active', $category->is_active ?? true) ? 'Ativo' : 'Desativado' }}
                                </label>
                            </div>
                            <div class="form-text">Categorias desativadas não aparecem no cardápio.</div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="mdi mdi-content-save me-2"></i> Salvar
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="mdi mdi-arrow-left me-2"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
