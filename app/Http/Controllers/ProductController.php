<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        // Construir query com filtros
        $query = Product::with('category');

        // Aplicar filtros se fornecidos
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Ordenar e paginar
        $products = $query->orderBy('name')->paginate(12)->withQueryString();

        // Buscar categorias para filtros
        $categories = Category::where('is_active', '1')->orderBy('name')->get();

        // Calcular estatísticas básicas
        $allProducts = Product::all();
        $lowStockCount = Product::where('type', 'product')
                                ->whereRaw('stock_quantity <= min_stock_level')
                                ->where('is_active', true)
                                ->count();

        return view('products.index', compact('products', 'categories', 'allProducts', 'lowStockCount'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(Request $request)
    {
        $categories = Category::where('is_active', '1')->orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        try {
            $validationRules = [
                'name' => 'required|string|max:150',
                'category_id' => 'required|exists:categories,id',
                'type' => 'required|in:product,service',
                'selling_price' => 'required|numeric|min:0',
                'purchase_price' => 'nullable|numeric|min:0',
                'unit' => 'nullable|string|max:20',
                'description' => 'nullable|string|max:500',
                'is_active' => 'boolean'
            ];

            // Validações adicionais se for produto
            if ($request->type === 'product') {
                $validationRules['stock_quantity'] = 'required|integer|min:0';
                $validationRules['min_stock_level'] = 'required|integer|min:0';
            }

            $validated = $request->validate($validationRules, [
                'name.required' => 'O nome do produto é obrigatório.',
                'name.max' => 'O nome não pode ter mais de 150 caracteres.',
                'category_id.required' => 'A categoria é obrigatória.',
                'category_id.exists' => 'A categoria selecionada não existe.',
                'type.required' => 'O tipo é obrigatório.',
                'type.in' => 'O tipo deve ser produto ou serviço.',
                'selling_price.required' => 'O preço de venda é obrigatório.',
                'selling_price.numeric' => 'O preço deve ser um número.',
                'selling_price.min' => 'O preço deve ser maior ou igual a zero.',
                'purchase_price.numeric' => 'O preço de compra deve ser um número.',
                'purchase_price.min' => 'O preço de compra deve ser maior ou igual a zero.',
                'stock_quantity.required' => 'A quantidade em estoque é obrigatória para produtos.',
                'stock_quantity.integer' => 'A quantidade deve ser um número inteiro.',
                'stock_quantity.min' => 'A quantidade deve ser maior ou igual a zero.',
                'min_stock_level.required' => 'O estoque mínimo é obrigatório para produtos.',
                'min_stock_level.integer' => 'O estoque mínimo deve ser um número inteiro.',
                'min_stock_level.min' => 'O estoque mínimo deve ser maior ou igual a zero.',
            ]);

            DB::beginTransaction();

            $data = collect($validated)->only([
                'name', 'category_id', 'type', 'selling_price',
                'purchase_price', 'unit', 'description'
            ])->toArray();

            $data['is_active'] = $request->boolean('is_active', true);

            if ($request->type === 'product') {
                $data['stock_quantity'] = (int) $request->input('stock_quantity', 0);
                $data['min_stock_level'] = (int) $request->input('min_stock_level', 0);
            } else {
                $data['stock_quantity'] = 0;
                $data['min_stock_level'] = 0;
                $data['unit'] = null;
            }

            $product = Product::create($data);

            // Criar movimento inicial de estoque se necessário
            if ($product->type === 'product' && $product->stock_quantity > 0) {
                StockMovement::create([
                    'product_id'     => $product->id,
                    'user_id'        => auth()->id(),
                    'movement_type'  => 'in',
                    'quantity'       => $product->stock_quantity,
                    'reason'         => 'Estoque inicial do produto',
                    'movement_date'  => now(),
                ]);
            }

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produto criado com sucesso!',
                    'data' => $product->load('category')
                ]);
            }

            return redirect()->route('products.index')
                ->with('success', 'Produto criado com sucesso!');

        } catch (ValidationException $e) {
            DB::rollBack();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação.',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar produto: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro interno do servidor.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erro ao criar produto: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'stockMovements.user']);
        return view('products.show', compact('product'));
    }

    /**
     * Retornar dados do produto para AJAX
     */
    public function showData(Product $product)
    {
        $product->load(['category', 'stockMovements.user']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category->name ?? 'N/A',
                'type' => $product->type,
                'type_label' => $product->type === 'product' ? 'Produto' : 'Serviço',
                'selling_price' => $product->selling_price,
                'formatted_selling_price' => 'MZN ' . number_format($product->selling_price, 2, ',', '.'),
                'purchase_price' => $product->purchase_price,
                'formatted_purchase_price' => $product->purchase_price ? 'MZN ' . number_format($product->purchase_price, 2, ',', '.') : 'N/A',
                'stock_quantity' => $product->stock_quantity,
                'min_stock_level' => $product->min_stock_level,
                'unit' => $product->unit ?? 'N/A',
                'description' => $product->description ?? 'Nenhuma descrição disponível.',
                'is_active' => $product->is_active,
                'status_label' => $product->is_active ? 'Ativo' : 'Inativo',
                'is_low_stock' => $product->isLowStock(),
                'created_at' => $product->created_at->format('d/m/Y H:i'),
                'updated_at' => $product->updated_at->format('d/m/Y H:i'),
                'stock_movements_count' => $product->stockMovements->count(),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', '1')->orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            if ($request->has('toggle_status')) {
                $product->update([
                    'is_active' => $request->boolean('is_active')
                ]);

                $status = $request->boolean('is_active') ? 'ativado' : 'desativado';
                
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => "Produto '{$product->name}' {$status} com sucesso!",
                        'data' => $product->fresh()->load('category')
                    ]);
                }

                return redirect()->route('products.index')
                    ->with('success', "Produto '{$product->name}' {$status} com sucesso!");
            }

            // Validação normal para edição completa
            $validationRules = [
                'name' => 'required|string|max:150',
                'category_id' => 'required|exists:categories,id',
                'selling_price' => 'required|numeric|min:0',
                'purchase_price' => 'nullable|numeric|min:0',
                'unit' => 'nullable|string|max:20',
                'description' => 'nullable|string|max:500',
                'is_active' => 'boolean'
            ];

            if ($product->type === 'product') {
                $validationRules['min_stock_level'] = 'required|integer|min:0';
            }

            $validated = $request->validate($validationRules, [
                'name.required' => 'O nome do produto é obrigatório.',
                'name.max' => 'O nome não pode ter mais de 150 caracteres.',
                'category_id.required' => 'A categoria é obrigatória.',
                'category_id.exists' => 'A categoria selecionada não existe.',
                'selling_price.required' => 'O preço de venda é obrigatório.',
                'selling_price.numeric' => 'O preço deve ser um número.',
                'selling_price.min' => 'O preço deve ser maior ou igual a zero.',
                'min_stock_level.required' => 'O estoque mínimo é obrigatório para produtos.',
                'min_stock_level.integer' => 'O estoque mínimo deve ser um número inteiro.',
                'min_stock_level.min' => 'O estoque mínimo deve ser maior ou igual a zero.',
            ]);

            $data = collect($validated)->only([
                'name', 'category_id', 'selling_price',
                'purchase_price', 'unit', 'description'
            ])->toArray();

            $data['is_active'] = $request->boolean('is_active', true);

            if ($product->type === 'product') {
                $data['min_stock_level'] = (int) $request->input('min_stock_level', 0);
            }

            $product->update($data);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produto atualizado com sucesso!',
                    'data' => $product->fresh()->load('category')
                ]);
            }

            return redirect()->route('products.show', $product)
                ->with('success', 'Produto atualizado com sucesso!');

        } catch (ValidationException $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação.',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar produto: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro interno do servidor.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erro ao atualizar produto: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product, Request $request)
    {
        try {
            // Verificar se já está excluído
            if ($product->is_deleted) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Este produto já foi excluído do sistema.'
                    ], 400);
                }
                return redirect()->route('products.index')
                    ->with('error', 'Este produto já foi excluído do sistema.');
            }

            DB::beginTransaction();

            // Se for produto e tiver estoque, registrar movimento de saída final
            if ($product->type === 'product' && $product->stock_quantity > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'movement_type' => 'out',
                    'quantity' => $product->stock_quantity,
                    'reason' => "PRODUTO EXCLUÍDO: {$product->name} (ID: {$product->id}) — Estoque zerado por exclusão do sistema.",
                    'movement_date' => now(),
                ]);

                // Zerar o estoque
                $product->stock_quantity = 0;
            }

            // Marcar como excluído
            $product->is_deleted = true;
            $product->is_active = false;
            $product->deleted_at = now();
            $product->save();

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Produto '{$product->name}' foi excluído com sucesso! Seu histórico e movimentações foram preservados."
                ]);
            }

            return redirect()->route('products.index')
                ->with('success', "Produto '{$product->name}' foi excluído com sucesso! Seu histórico e movimentações foram preservados.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir produto: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao processar a exclusão do produto.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->route('products.index')
                ->with('error', 'Erro ao processar a exclusão do produto: ' . $e->getMessage());
        }
    }

    /**
     * Adjust stock for a product.
     */
    public function adjustStock(Request $request, Product $product)
    {
        try {
            if ($product->type !== 'product') {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Apenas produtos podem ter estoque ajustado.'
                    ], 400);
                }
                return redirect()->route('products.show', $product)
                    ->with('error', 'Apenas produtos podem ter estoque ajustado.');
            }

            $validated = $request->validate([
                'adjustment_type' => 'required|in:increase,decrease',
                'quantity' => 'required|integer|min:1',
                'reason' => 'required|string|max:200',
            ], [
                'adjustment_type.required' => 'O tipo de ajuste é obrigatório.',
                'adjustment_type.in' => 'Tipo de ajuste inválido.',
                'quantity.required' => 'A quantidade é obrigatória.',
                'quantity.integer' => 'A quantidade deve ser um número inteiro.',
                'quantity.min' => 'A quantidade deve ser maior que zero.',
                'reason.required' => 'O motivo é obrigatório.',
                'reason.max' => 'O motivo não pode ter mais de 200 caracteres.',
            ]);

            DB::beginTransaction();

            $movementType = $request->adjustment_type === 'increase' ? 'in' : 'out';
            $quantity = $request->integer('quantity');

            if ($request->adjustment_type === 'decrease') {
                if ($product->stock_quantity < $quantity) {
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => "Estoque insuficiente. Disponível: {$product->stock_quantity} {$product->unit}."
                        ], 400);
                    }
                    return redirect()->route('products.show', $product)
                        ->with('error', "Estoque insuficiente. Disponível: {$product->stock_quantity} {$product->unit}.");
                }
            }

            if ($request->adjustment_type === 'increase') {
                $product->stock_quantity += $quantity;
            } else {
                $product->stock_quantity -= $quantity;
            }

            $product->save();

            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'movement_type' => $movementType,
                'quantity' => $quantity,
                'reason' => $validated['reason'],
                'movement_date' => now(),
            ]);

            DB::commit();

            $action = $request->adjustment_type === 'increase' ? 'Entrada' : 'Saída';
            $message = "{$action} de {$quantity} {$product->unit} registrada com sucesso! Novo estoque: {$product->stock_quantity}";

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'new_stock' => $product->stock_quantity,
                        'unit' => $product->unit
                    ]
                ]);
            }
            
            return redirect()->route('products.show', $product)
                ->with('success', $message);

        } catch (ValidationException $e) {
            DB::rollBack();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação.',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors());

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao ajustar estoque: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao ajustar estoque.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->route('products.show', $product)
                ->with('error', 'Erro ao ajustar estoque: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate a product.
     */
    public function duplicate(Product $product, Request $request)
    {
        try {
            DB::beginTransaction();

            $newProduct = $product->replicate();
            $newProduct->name = $product->name . ' (Cópia)';
            $newProduct->stock_quantity = 0;
            $newProduct->is_active = false;
            $newProduct->save();

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produto duplicado com sucesso! Ajuste os dados conforme necessário.',
                    'data' => $newProduct->load('category')
                ]);
            }

            return redirect()->route('products.edit', $newProduct)
                ->with('info', 'Produto duplicado com sucesso! Ajuste os dados conforme necessário.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao duplicar produto: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao duplicar produto.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->route('products.index')
                ->with('error', 'Erro ao duplicar produto: ' . $e->getMessage());
        }
    }
}