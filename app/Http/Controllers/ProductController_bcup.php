<?php

// ===================================================
// app/Http/Controllers/ProductController.php
// ===================================================
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductControllerbsa extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::with('category')->whereNull('deleted_at');

            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($request->filled('category') && $request->category !== 'all') {
                $query->where('category_id', $request->category);
            }

            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('is_active', $request->status === '1');
            }

            $products = $query->orderBy('name')->paginate(12);
            $categories = Category::where('is_active', true)->orderBy('name')->get();

            return view('products.index', compact('products', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Products index error: ' . $e->getMessage());
            return view('products.index', [
                'products' => collect()->paginate(),
                'categories' => collect()
            ])->with('error', 'Erro ao carregar produtos');
        }
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'type' => 'required|in:product,service',
                'purchase_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'min_stock_level' => 'required|integer|min:0',
                'unit' => 'nullable|string|max:20',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'boolean'
            ]);

            DB::beginTransaction();

            $data = $request->all();
            $data['is_active'] = $request->has('is_active');

            if ($request->hasFile('image')) {
                $data['image_path'] = $request->file('image')->store('products', 'public');
            }

            $product = Product::create($data);

            // Registrar movimento inicial de estoque
            if ($product->stock_quantity > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'movement_type' => 'in',
                    'quantity' => $product->stock_quantity,
                    'reason' => 'Estoque inicial',
                    'movement_date' => now()->toDateString()
                ]);
            }

            DB::commit();

            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => 'Produto criado com sucesso!'])
                : redirect()->route('products.index')->with('success', 'Produto criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product store error: ' . $e->getMessage());
            
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Erro: ' . $e->getMessage()], 500)
                : back()->with('error', 'Erro ao criar produto: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        try {
            $product->load('category');
            
            // Histórico de vendas
            $salesHistory = SaleItem::where('product_id', $product->id)
                ->with(['sale'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            // Movimentos de estoque
            $stockMovements = StockMovement::where('product_id', $product->id)
                ->with(['user'])
                ->orderBy('movement_date', 'desc')
                ->take(10)
                ->get();

            return view('products.show', compact('product', 'salesHistory', 'stockMovements'));
        } catch (\Exception $e) {
            \Log::error('Product show error: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'Produto não encontrado');
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'type' => 'required|in:product,service',
                'purchase_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'min_stock_level' => 'required|integer|min:0',
                'unit' => 'nullable|string|max:20',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'boolean'
            ]);

            DB::beginTransaction();

            $data = $request->all();
            $data['is_active'] = $request->has('is_active');

            // Gerenciar imagem
            if ($request->hasFile('image')) {
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }
                $data['image_path'] = $request->file('image')->store('products', 'public');
            }

            if ($request->has('remove_image') && $product->image_path) {
                Storage::disk('public')->delete($product->image_path);
                $data['image_path'] = null;
            }

            // Verificar mudança no estoque
            $oldStock = $product->stock_quantity;
            $newStock = $request->stock_quantity;

            $product->update($data);

            // Registrar movimento se o estoque mudou
            if ($oldStock != $newStock) {
                $difference = $newStock - $oldStock;
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'movement_type' => $difference > 0 ? 'in' : 'out',
                    'quantity' => abs($difference),
                    'reason' => 'Ajuste manual de estoque',
                    'movement_date' => now()->toDateString()
                ]);
            }

            DB::commit();

            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => 'Produto atualizado com sucesso!'])
                : redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product update error: ' . $e->getMessage());
            
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Erro: ' . $e->getMessage()], 500)
                : back()->with('error', 'Erro ao atualizar produto: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, Product $product)
    {
        try {
            DB::beginTransaction();

            // Soft delete
            $product->update(['is_active' => false]);
            $product->delete();

            DB::commit();

            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => 'Produto removido com sucesso!'])
                : redirect()->route('products.index')->with('success', 'Produto removido com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product destroy error: ' . $e->getMessage());

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Erro: ' . $e->getMessage()], 500)
                : back()->with('error', 'Erro ao remover produto: ' . $e->getMessage());
        }
    }

    public function updateStock(Request $request, Product $product)
    {
        try {
            $request->validate([
                'stock_quantity' => 'required|integer|min:0',
                'reason' => 'nullable|string|max:200'
            ]);

            $oldStock = $product->stock_quantity;
            $newStock = $request->stock_quantity;
            $difference = $newStock - $oldStock;

            DB::beginTransaction();

            $product->update(['stock_quantity' => $newStock]);

            // Registrar movimento
            if ($difference != 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'movement_type' => 'adjustment',
                    'quantity' => abs($difference),
                    'reason' => $request->reason ?: 'Ajuste de estoque',
                    'movement_date' => now()->toDateString()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Estoque atualizado com sucesso!',
                'new_stock' => $newStock
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update stock error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar estoque: ' . $e->getMessage()
            ], 422);
        }
    }

    public function stockHistory(Product $product)
    {
        $history = StockMovement::where('product_id', $product->id)
            ->orderBy('movement_date', 'asc')
            ->get()
            ->map(function ($movement) {
                return [
                    'date' => $movement->movement_date->format('Y-m-d'),
                    'quantity' => $movement->quantity,
                    'type' => $movement->movement_type
                ];
            });

        return response()->json([
            'dates' => $history->pluck('date'),
            'quantities' => $history->pluck('quantity'),
            'types' => $history->pluck('type')
        ]);
    }

    public function salesData(Product $product)
    {
        $sales = SaleItem::where('product_id', $product->id)
            ->with('sale')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->sale->sale_date->format('Y-m-d'),
                    'quantity' => $item->quantity,
                    'revenue' => $item->total_price
                ];
            });

        return response()->json([
            'dates' => $sales->pluck('date'),
            'quantities' => $sales->pluck('quantity'),
            'revenues' => $sales->pluck('revenue')
        ]);
    }
}
