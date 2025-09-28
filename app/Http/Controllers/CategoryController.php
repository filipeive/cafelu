<?php

// ===================================================
// app/Http/Controllers/CategoryController.php
// ===================================================
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['products' => function($query) {
            $query->where('is_active', true)->whereNull('deleted_at');
        }])->orderBy('sort_order')->orderBy('name')->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function show(Category $category)
    {
        $category->load(['products' => function($query) {
            $query->where('is_active', true)->whereNull('deleted_at');
        }]);

        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Category $category)
    {
        // Verificar se há produtos vinculados
        $productsCount = Product::where('category_id', $category->id)
                               ->where('is_active', true)
                               ->whereNull('deleted_at')
                               ->count();

        if ($productsCount > 0) {
            return redirect()->route('categories.index')
                           ->with('error', 'Não é possível excluir categoria com produtos ativos vinculados.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoria excluída com sucesso!');
    }
}