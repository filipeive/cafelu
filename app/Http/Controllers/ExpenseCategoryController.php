<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ExpenseCategoryController extends Controller
{
    /**
     * Armazenar nova categoria de despesa
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:expense_categories,name',
            ], [
                'name.required' => 'O nome da categoria é obrigatório.',
                'name.max' => 'O nome da categoria não pode ter mais de 100 caracteres.',
                'name.unique' => 'Já existe uma categoria com este nome.',
            ]);

            $category = ExpenseCategory::create([
                'name' => $validated['name'],
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria criada com sucesso!',
                    'data' => $category
                ]);
            }

            return redirect()->route('expenses.index')
                ->with('success', 'Categoria criada com sucesso!');

        } catch (ValidationException $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação.',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro interno do servidor.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erro ao criar categoria: ' . $e->getMessage());
        }
    }
}