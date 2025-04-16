<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use App\Models\SaleItem;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $products = $query->orderBy('name')->paginate(12);
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');

            if ($request->hasFile('image')) {
                $data['image_path'] = $request->file('image')->store('products', 'public');
            }

            Product::create($data);

            DB::commit();

            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => 'Item adicionado com sucesso!'])
                : redirect()->route('products.index')->with('success', 'Item adicionado com sucesso ao cardápio!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Erro: ' . $e->getMessage()], 500)
                : back()->with('error', 'Erro ao adicionar item: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        $product->load('category');
        return response()->json($product);
    }

    public function edit(Product $product)
    {
        $product->load('category');
        return response()->json($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');

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

            $product->update($data);

            DB::commit();

            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => 'Atualizado com sucesso!'])
                : redirect()->route('products.index')->with('success', 'Item atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Erro: ' . $e->getMessage()], 500)
                : back()->with('error', 'Erro ao atualizar item: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, Product $product)
    {
        try {
            DB::beginTransaction();

            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $product->delete();

            DB::commit();

            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => 'Removido com sucesso!'])
                : redirect()->route('products.index')->with('success', 'Item removido com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Erro: ' . $e->getMessage()], 500)
                : back()->with('error', 'Erro ao remover item: ' . $e->getMessage());
        }
    }

    public function updateStock(Request $request, Product $product)
    {
        try {
            $request->validate([
                'stock_quantity' => 'required|integer|min:0'
            ]);

            $product->update([
                'stock_quantity' => $request->stock_quantity
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estoque atualizado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar estoque: ' . $e->getMessage()
            ], 422);
        }
    }

    public function export(Request $request)
    {
        // Lógica de exportação pode ser inserida aqui
        return $request->expectsJson()
            ? response()->json(['message' => 'Funcionalidade de exportação será implementada em breve.'])
            : back()->with('success', 'Funcionalidade de exportação será implementada em breve.');
    }

    public function stockHistory(Product $product)
    {
        $history = StockMovement::where('product_id', $product->id)
            ->orderBy('created_at')
            ->get()
            ->map(function ($movement) {
                return [
                    'date' => $movement->created_at->format('d/m/Y'),
                    'quantity' => $movement->quantity
                ];
            });

        return response()->json([
            'dates' => $history->pluck('date'),
            'quantities' => $history->pluck('quantity')
        ]);
    }

    public function salesData(Product $product)
    {
        $sales = SaleItem::where('product_id', $product->id)
            ->with('sale')
            ->orderBy('created_at')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->sale->created_at->format('d/m/Y'),
                    'quantity' => $item->quantity
                ];
            });

        return response()->json([
            'dates' => $sales->pluck('date'),
            'quantities' => $sales->pluck('quantity')
        ]);
    }
}
