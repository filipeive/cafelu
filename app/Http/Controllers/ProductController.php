<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::with('category');

            // Filtro de busca
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filtro de categoria
            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            // Filtro de status
            if ($request->filled('status')) {
                $query->where('is_active', $request->status);
            }

            $products = $query->orderBy('name')->paginate(8);
            $categories = Category::orderBy('name')->get();

            return view('products.index', compact('products', 'categories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar produtos: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
{
    try {
        // Validação - REMOVA 'price' da validação
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // 'price' => 'required|numeric|min:0', // REMOVER
            'purchase_price' => 'required|numeric|min:0', // Adicionar se necessário
            'selling_price' => 'required|numeric|min:0', // Adicionar se necessário
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'type' => 'required|in:product,service',
            'unit' => 'nullable|string|max:20',
            'min_stock_level' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        // Dados para criação - NÃO incluir 'price'
        $data = $request->only([
            'name', 
            'description', 
            'purchase_price', // Incluir
            'selling_price', // Incluir
            'stock_quantity', 
            'category_id',
            'type',
            'unit',
            'min_stock_level'
        ]);
        
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Upload de imagem
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Produto adicionado com sucesso!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Erro ao adicionar produto: ' . $e->getMessage()
        ], 500);
    }
}

    public function show($id)
    {
        try {
            $product = Product::with('category')->findOrFail($id);
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Produto não encontrado'
            ], 404);
        }
    }

    public function update(Request $request, $id)
{
    try {
        $product = Product::findOrFail($id);

        // Validação - REMOVA 'price' da validação
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // 'price' => 'required|numeric|min:0', // REMOVER
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'type' => 'required|in:product,service',
            'unit' => 'nullable|string|max:20',
            'min_stock_level' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        // Dados para atualização - NÃO incluir 'price'
        $data = $request->only([
            'name', 
            'description', 
            'purchase_price', // Incluir
            'selling_price', // Incluir
            'stock_quantity', 
            'category_id',
            'type',
            'unit',
            'min_stock_level'
        ]);
        
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Upload de nova imagem
        if ($request->hasFile('image')) {
            // Deletar imagem antiga
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Produto atualizado com sucesso!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Erro ao atualizar produto: ' . $e->getMessage()
        ], 500);
    }
}

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            DB::beginTransaction();

            // Deletar imagem se existir
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $product->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produto removido com sucesso!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover produto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStock(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'stock_quantity' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantidade inválida'
                ], 422);
            }

            $product->update([
                'stock_quantity' => $request->stock_quantity
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estoque atualizado com sucesso!',
                'new_stock' => $product->stock_quantity
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar estoque: ' . $e->getMessage()
            ], 500);
        }
    }

    public function stockHistory($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            $history = StockMovement::where('product_id', $product->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($movement) {
                    return [
                        'date' => $movement->created_at->format('d/m/Y H:i'),
                        'quantity' => $movement->quantity,
                        'type' => $movement->type ?? 'movement'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $history
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar histórico'
            ], 500);
        }
    }

    public function salesData($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            $sales = SaleItem::where('product_id', $product->id)
                ->with('sale')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->sale->created_at->format('d/m/Y'),
                        'quantity' => $item->quantity,
                        'total' => $item->quantity * $item->price
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $sales
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar dados de vendas'
            ], 500);
        }
    }

    public function export(Request $request)
    {
        // Implementação futura de exportação
        return response()->json([
            'success' => true,
            'message' => 'Funcionalidade de exportação será implementada em breve.'
        ]);
    }
}