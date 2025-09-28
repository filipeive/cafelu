<?php
namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        if ($request->filled('product')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->product . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->where('movement_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('movement_date', '<=', $request->date_to);
        }

        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        $movements = $query->latest('movement_date')->paginate(20);

        return view('stock_movements.index', compact('movements'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)
                          ->whereNull('deleted_at')
                          ->orderBy('name')
                          ->get();
        
        return view('stock_movements.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'movement_type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
            'movement_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Criar movimento
            StockMovement::create([
                'product_id' => $request->product_id,
                'user_id' => auth()->id(),
                'movement_type' => $request->movement_type,
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'movement_date' => $request->movement_date,
            ]);

            // Atualizar estoque do produto
            $product = Product::find($request->product_id);
            if ($request->movement_type === 'in' || $request->movement_type === 'adjustment') {
                $product->increment('stock_quantity', $request->quantity);
            } else {
                $product->decrement('stock_quantity', $request->quantity);
            }

            DB::commit();

            return redirect()->route('stock-movements.index')
                           ->with('success', 'Movimento registrado com sucesso.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao registrar movimento: ' . $e->getMessage());
        }
    }
    
    public function edit(StockMovement $stockMovement)
    {
        $products = Product::all();
        return view('stock_movements.edit', compact('stockMovement', 'products'));
    }
    public function update(Request $request, StockMovement $stockMovement)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'movement_type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
            'movement_date' => 'required|date',
        ]);

        $stockMovement->update([
            'product_id' => $request->product_id,
            'user_id' => auth()->id(),
            'movement_type' => $request->movement_type,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'movement_date' => $request->movement_date,
        ]);

        return redirect()->route('stock_movements.index')
            ->with('success', 'Movimento atualizado com sucesso.');
    }
    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load(['product', 'user']);
        return view('stock_movements.show', compact('stockMovement'));
    }

    public function destroy(StockMovement $stockMovement)
    {
        try {
            DB::beginTransaction();

            // Reverter movimento no estoque
            $product = $stockMovement->product;
            if ($stockMovement->movement_type === 'in' || $stockMovement->movement_type === 'adjustment') {
                $product->decrement('stock_quantity', $stockMovement->quantity);
            } else {
                $product->increment('stock_quantity', $stockMovement->quantity);
            }

            $stockMovement->delete();

            DB::commit();

            return redirect()->route('stock-movements.index')
                           ->with('success', 'Movimento excluído com sucesso.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao excluir movimento: ' . $e->getMessage());
        }
    }
    public static function recordMovement($product_id, $quantity, $type, $reason = null, $reference_id = null)
    {
        return StockMovement::create([
            'product_id'   => $product_id,
            'user_id'      => auth()->id(),
            'movement_type'=> $type, // 'in' ou 'out'
            'quantity'     => $quantity,
            'reason'       => $reason,
            'reference_id' => $reference_id,
            'movement_date'=> now(),
        ]);
    }

}