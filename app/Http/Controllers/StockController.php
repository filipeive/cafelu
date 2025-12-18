<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('low_stock')) {
            $query->where('stock_quantity', '<=', 10);
        }

        $products = $query->paginate(15);
        $categories = \App\Models\Category::all();

        return view('stock.index', compact('products', 'categories'));
    }

    public function history(Request $request)
    {
        $query = StockMovement::with(['product', 'user'])->latest();

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $movements = $query->paginate(20);
        $products = Product::orderBy('name')->get();

        return view('stock.history', compact('movements', 'products'));
    }

    public function adjust(Product $product)
    {
        return view('stock.adjust', compact('product'));
    }

    public function storeAdjustment(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|numeric',
            'type' => 'required|in:entry,exit,adjustment',
            'notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $quantity = $validated['quantity'];
            $oldStock = $product->stock_quantity;

            if ($validated['type'] === 'entry') {
                $product->increment('stock_quantity', $quantity);
            } elseif ($validated['type'] === 'exit') {
                $product->decrement('stock_quantity', $quantity);
                $quantity = -$quantity; // Store as negative for exit
            } else {
                // Adjustment: quantity is the NEW absolute stock
                $diff = $quantity - $oldStock;
                $product->stock_quantity = $quantity;
                $product->save();
                $quantity = $diff; // Movement is the difference
            }

            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'quantity' => $quantity,
                'type' => $validated['type'],
                'notes' => $validated['notes'],
            ]);

            DB::commit();
            return redirect()->route('stock.index')->with('success', 'Estoque atualizado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao atualizar estoque: ' . $e->getMessage());
        }
    }
}
