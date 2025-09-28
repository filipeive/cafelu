<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ExpenseController extends Controller
{
    /**
     * Exibir lista de despesas com filtros
     */
    public function index(Request $request)
    {
        $query = Expense::with(['user', 'category'])
            ->where('user_id', auth()->id());

        // Aplicar filtros de pesquisa
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        // Ordenar por data mais recente
        $expenses = $query->latest('expense_date')->paginate(10);

        // Estatísticas baseadas nos filtros aplicados
        $totalExpenses = (clone $query)->sum('amount') ?? 0;
        $averageExpense = (clone $query)->avg('amount') ?? 0;
        $highestExpense = (clone $query)->max('amount') ?? 0;
        $lowestExpense = (clone $query)->min('amount') ?? 0;

        // Carregar categorias
        $categories = ExpenseCategory::orderBy('name')->get();

        return view('expenses.index', compact(
            'expenses', 
            'totalExpenses', 
            'averageExpense', 
            'highestExpense', 
            'lowestExpense', 
            'categories'
        ));
    }

    /**
     * Armazenar nova despesa
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'expense_category_id' => 'required|exists:expense_categories,id',
                'description' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0.01',
                'expense_date' => 'required|date|before_or_equal:today',
                'receipt_number' => 'nullable|string|max:50',
                'notes' => 'nullable|string|max:500',
                'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240',
            ]);

            $expenseData = [
                'user_id' => auth()->id(),
                'expense_category_id' => $validated['expense_category_id'],
                'description' => $validated['description'],
                'amount' => $validated['amount'],
                'expense_date' => $validated['expense_date'],
                'receipt_number' => $validated['receipt_number'],
                'notes' => $validated['notes'],
            ];

            // Processar upload do comprovativo
            if ($request->hasFile('receipt_file') && $request->file('receipt_file')->isValid()) {
                $file = $request->file('receipt_file');
                
                // Garantir que o diretório existe
                Storage::makeDirectory('public/receipts');
                
                // Gerar nome único para o arquivo
                $fileName = 'receipt_' . uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Salvar o arquivo
                $path = $file->storeAs('public/receipts', $fileName);
                
                // Salvar informações do arquivo
                $expenseData['receipt_file'] = 'receipts/' . $fileName;
                $expenseData['receipt_original_name'] = $file->getClientOriginalName();
                $expenseData['receipt_mime_type'] = $file->getMimeType();
                $expenseData['receipt_file_size'] = $file->getSize();
            }

            $expense = Expense::create($expenseData);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Despesa registrada com sucesso!',
                    'data' => $expense->load(['user', 'category'])
                ]);
            }

            return redirect()->route('expenses.index')
                ->with('success', 'Despesa registrada com sucesso!');

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
                ->with('error', 'Erro ao registrar despesa: ' . $e->getMessage());
        }
    }

    /**
     * Retornar dados da despesa para AJAX
     */
    public function showData(Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado.'
            ], 403);
        }

        $expense->load(['user', 'category']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $expense->id,
                'category' => $expense->category->name ?? 'N/A',
                'description' => $expense->description,
                'amount' => $expense->amount,
                'formatted_amount' => number_format($expense->amount, 2, ',', '.') . ' MT',
                'expense_date' => $expense->expense_date->format('d/m/Y'),
                'receipt_number' => $expense->receipt_number ?? 'N/A',
                'has_receipt' => $expense->hasReceipt(),
                'receipt_file' => $expense->receipt_file,
                'receipt_original_name' => $expense->receipt_original_name,
                'receipt_url' => $expense->receipt_url,
                'receipt_icon' => $expense->receipt_icon,
                'receipt_file_size' => $expense->receipt_file_size_formatted,
                'notes' => $expense->notes ?? 'Nenhuma observação registrada.',
                'user' => $expense->user->name ?? 'Sistema',
                'created_at' => $expense->created_at->format('d/m/Y H:i'),
                'updated_at' => $expense->updated_at->format('d/m/Y H:i'),
            ]
        ]);
    }

    /**
     * Exibir formulário de edição
     */
    public function edit(Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403, 'Acesso negado.');
        }

        $categories = ExpenseCategory::orderBy('name')->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Atualizar despesa
     */
    public function update(Request $request, Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado.'
                ], 403);
            }
            abort(403, 'Acesso negado.');
        }

        try {
            // Regras de validação condicionais
            $validationRules = [
                'expense_category_id' => 'required|exists:expense_categories,id',
                'description' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0.01',
                'expense_date' => 'required|date|before_or_equal:today',
                'receipt_number' => 'nullable|string|max:50',
                'notes' => 'nullable|string|max:500',
                'remove_receipt' => 'nullable|boolean',
            ];

            // Apenas validar o arquivo se ele foi enviado
            if ($request->hasFile('receipt_file')) {
                $validationRules['receipt_file'] = 'file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240';
            }

            $validated = $request->validate($validationRules, [
                'expense_category_id.required' => 'A categoria é obrigatória.',
                'expense_category_id.exists' => 'A categoria selecionada não existe.',
                'description.required' => 'A descrição é obrigatória.',
                'description.max' => 'A descrição não pode ter mais de 255 caracteres.',
                'amount.required' => 'O valor é obrigatório.',
                'amount.numeric' => 'O valor deve ser um número.',
                'amount.min' => 'O valor deve ser maior que zero.',
                'expense_date.required' => 'A data é obrigatória.',
                'expense_date.date' => 'A data deve ser válida.',
                'expense_date.before_or_equal' => 'A data não pode ser futura.',
                'receipt_number.max' => 'O número do recibo não pode ter mais de 50 caracteres.',
                'notes.max' => 'As observações não podem ter mais de 500 caracteres.',
                'receipt_file.file' => 'O comprovativo deve ser um arquivo válido.',
                'receipt_file.mimes' => 'O comprovativo deve ser do tipo: jpg, jpeg, png, pdf, doc, docx, xls, xlsx.',
                'receipt_file.max' => 'O comprovativo não pode ter mais de 10MB.',
            ]);

            $updateData = [
                'expense_category_id' => $validated['expense_category_id'],
                'description' => $validated['description'],
                'amount' => $validated['amount'],
                'expense_date' => $validated['expense_date'],
                'receipt_number' => $validated['receipt_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ];

            // Remover comprovativo
            if ($request->has('remove_receipt') && $request->remove_receipt == '1') {
                if ($expense->receipt_file && Storage::exists('public/' . $expense->receipt_file)) {
                    Storage::delete('public/' . $expense->receipt_file);
                }
                $updateData['receipt_file'] = null;
                $updateData['receipt_original_name'] = null;
                $updateData['receipt_mime_type'] = null;
                $updateData['receipt_file_size'] = null;
            }

            // Upload novo comprovativo
            if ($request->hasFile('receipt_file') && $request->file('receipt_file')->isValid()) {
                if ($expense->receipt_file && Storage::exists('public/' . $expense->receipt_file)) {
                    Storage::delete('public/' . $expense->receipt_file);
                }

                $file = $request->file('receipt_file');
                Storage::makeDirectory('public/receipts');
                $fileName = 'receipt_' . uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/receipts', $fileName);

                $updateData['receipt_file'] = 'receipts/' . $fileName;
                $updateData['receipt_original_name'] = $file->getClientOriginalName();
                $updateData['receipt_mime_type'] = $file->getMimeType();
                $updateData['receipt_file_size'] = $file->getSize();
            }

            $expense->update($updateData);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Despesa atualizada com sucesso!',
                    'data' => $expense->fresh()->load(['user', 'category'])
                ]);
            }

            return redirect()->route('expenses.index')
                ->with('success', 'Despesa atualizada com sucesso!');

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
                ->with('error', 'Erro ao atualizar despesa: ' . $e->getMessage());
        }
    }
    /**
     * Download do comprovativo
     */
    public function downloadReceipt(Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403, 'Acesso negado.');
        }

        if (!$expense->hasReceipt()) {
            abort(404, 'Comprovativo não encontrado.');
        }

        return Storage::download('public/' . $expense->receipt_file, $expense->receipt_original_name);
    }

    /**
     * Remover despesa
     */
    public function destroy(Expense $expense, Request $request)
    {
        if ($expense->user_id !== auth()->id()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado.'
                ], 403);
            }
            abort(403, 'Acesso negado.');
        }

        try {
            $expense->delete();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Despesa excluída com sucesso!'
                ]);
            }

            return redirect()->route('expenses.index')
                ->with('success', 'Despesa excluída com sucesso!');

        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao excluir despesa.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erro ao excluir despesa: ' . $e->getMessage());
        }
    }
}