<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50']);
        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', __('messages.category_created'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:50']);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', __('messages.category_updated'));
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', __('messages.category_deleted'));
    }
}
