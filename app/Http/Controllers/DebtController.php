<?php

namespace App\Http\Controllers;

use App\Models\CustomerDebt;
use App\Models\DebtPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebtController extends Controller
{
    public function index()
    {
        $debts = CustomerDebt::with(['user', 'sale'])
            ->orderBy('status', 'asc')
            ->orderBy('due_date', 'asc')
            ->get();

        $totalOutstanding = CustomerDebt::where('status', '!=', 'paid')->sum('remaining_amount');
        $totalPaid = DebtPayment::sum('amount');

        return view('debts.index', compact('debts', 'totalOutstanding', 'totalPaid'));
    }

    public function show(CustomerDebt $debt)
    {
        $debt->load(['user', 'sale.saleItems.product', 'payments']);
        return view('debts.show', compact('debt'));
    }

    public function pay(Request $request, CustomerDebt $debt)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $debt->remaining_amount,
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $amount = $request->amount;

            DebtPayment::create([
                'customer_debt_id' => $debt->id,
                'amount' => $amount,
                'payment_method' => $request->payment_method,
                'payment_date' => now(),
                'notes' => $request->notes,
            ]);

            $debt->remaining_amount = (float) $debt->remaining_amount - (float) $amount;

            if ($debt->remaining_amount <= 0) {
                $debt->status = 'paid';
                $debt->remaining_amount = 0.00;

                // Create Sale when debt is fully paid
                if ($debt->items && count($debt->items) > 0) {
                    $sale = \App\Models\Sale::create([
                        'user_id' => auth()->id(),
                        'customer_name' => $debt->customer_name,
                        'sale_date' => now(),
                        'total_amount' => $debt->total_amount,
                        'payment_method' => $request->payment_method,
                        'status' => 'completed',
                        'cash_amount' => $request->payment_method === 'cash' ? $amount : 0,
                        'card_amount' => $request->payment_method === 'card' ? $amount : 0,
                        'mpesa_amount' => $request->payment_method === 'mpesa' ? $amount : 0,
                        'emola_amount' => $request->payment_method === 'emola' ? $amount : 0,
                    ]);

                    // Create SaleItems and update stock
                    foreach ($debt->items as $item) {
                        \App\Models\SaleItem::create([
                            'sale_id' => $sale->id,
                            'product_id' => $item['id'],
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['price'],
                        ]);

                        // Update product stock
                        $product = \App\Models\Product::find($item['id']);
                        if ($product) {
                            $product->stock_quantity -= $item['quantity'];
                            $product->save();
                        }
                    }

                    // Link sale to debt
                    $debt->sale_id = $sale->id;
                }
            } else {
                $debt->status = 'partial';
            }

            $debt->save();

            DB::commit();

            return redirect()->back()->with('success', 'Pagamento de dívida registrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao registrar pagamento: ' . $e->getMessage());
        }
    }

    public function registerDebt(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:products,id',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'customer_name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Role check
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            return response()->json([
                'success' => false,
                'message' => 'Apenas administradores e gerentes podem registrar dívidas.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Create debt record only (no sale)
            $debt = CustomerDebt::create([
                'user_id' => auth()->id(),
                'customer_name' => $validated['customer_name'],
                'total_amount' => $validated['total_amount'],
                'remaining_amount' => $validated['total_amount'],
                'status' => 'pending',
                'items' => $validated['items'],
                'notes' => $validated['notes'] ?? 'Dívida registrada via POS. Itens: ' . collect($validated['items'])->pluck('name')->join(', ')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dívida registrada com sucesso!',
                'debt_id' => $debt->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao registrar dívida: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(CustomerDebt $debt)
    {
        $debt->delete();
        return redirect()->route('debts.index')->with('success', 'Registro de dívida removido com sucesso.');
    }
}
