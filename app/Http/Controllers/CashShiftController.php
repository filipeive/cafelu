<?php

namespace App\Http\Controllers;

use App\Models\CashShift;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CashShiftController extends Controller
{
    /**
     * Listagem e auditoria de turnos de caixa.
     */
    public function index(Request $request): View
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());
        $userId = $request->input('user_id');
        $status = $request->input('status');

        $query = CashShift::with(['user'])
            ->withCount('sales')
            ->whereDate('opened_at', '>=', $dateFrom)
            ->whereDate('opened_at', '<=', $dateTo);

        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            $query->where('user_id', auth()->id());
        } elseif ($userId) {
            $query->where('user_id', $userId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $shifts = $query->latest('opened_at')->paginate(15)->withQueryString();

        // KPIs
        $kpiQuery = CashShift::whereDate('opened_at', '>=', $dateFrom)
            ->whereDate('opened_at', '<=', $dateTo);

        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            $kpiQuery->where('user_id', auth()->id());
        }

        $totalShifts = (clone $kpiQuery)->count();
        $openShifts = (clone $kpiQuery)->where('status', 'open')->count();
        $closedShifts = (clone $kpiQuery)->where('status', 'closed')->get();
        $totalDifferences = $closedShifts->sum('difference');
        $totalSales = (clone $kpiQuery)->sum('total_sales');

        $operators = User::whereIn('role', ['admin', 'manager', 'cashier', 'waiter'])
            ->orWhereHas('sales')
            ->orderBy('name')
            ->get();

        return view('cash-shifts.index', compact(
            'shifts',
            'totalShifts',
            'openShifts',
            'totalDifferences',
            'totalSales',
            'operators',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Detalhes de um turno de caixa.
     */
    public function show(CashShift $shift): View
    {
        $shift->load(['user', 'sales.saleItems.product']);

        return view('cash-shifts.show', compact('shift'));
    }

    /**
     * Retorna o estado do turno atual do operador (ou geral).
     */
    public function currentStatus(): JsonResponse
    {
        $user = auth()->user();

        // Procura turno aberto para este operador específico
        $shift = CashShift::where('user_id', $user->id)
            ->where('status', 'open')
            ->latest('opened_at')
            ->first();

        // Se o utilizador for admin ou gerente e não tiver turno próprio, verifica se há algum turno aberto no sistema
        if (!$shift && in_array($user->role, ['admin', 'manager'])) {
            $shift = CashShift::where('status', 'open')
                ->latest('opened_at')
                ->first();
        }

        if (!$shift) {
            return response()->json([
                'is_open' => false,
                'user_name' => $user->name,
                'user_role' => $user->role,
            ]);
        }

        // Recalcular totais para consistência
        $shift->recalculateTotals();

        return response()->json([
            'is_open' => true,
            'shift_id' => $shift->id,
            'operator_name' => $shift->user?->name ?? 'Operador',
            'opened_at' => $shift->opened_at->format('d/m/Y H:i'),
            'opening_balance' => (float) $shift->opening_balance,
            'cash_sales' => (float) $shift->cash_sales,
            'card_sales' => (float) $shift->card_sales,
            'mpesa_sales' => (float) $shift->mpesa_sales,
            'emola_sales' => (float) $shift->emola_sales,
            'total_sales' => (float) $shift->total_sales,
            'expected_cash' => $shift->expected_cash,
        ]);
    }

    /**
     * Abrir turno de caixa.
     */
    public function open(Request $request)
    {
        $validated = $request->validate([
            'opening_balance' => 'required|numeric|min:0',
            'user_id'         => 'nullable|exists:users,id',
            'notes'           => 'nullable|string|max:500',
        ]);

        $targetUserId = auth()->id();
        if (!empty($validated['user_id']) && in_array(auth()->user()->role, ['admin', 'manager'])) {
            $targetUserId = (int)$validated['user_id'];
        }

        // Verificar se já tem turno aberto
        $existing = CashShift::where('user_id', $targetUserId)
            ->where('status', 'open')
            ->first();

        if ($existing) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'O operador já possui um turno em aberto (# ' . $existing->id . ').'
                ], 422);
            }
            return back()->with('error', 'O operador já possui um turno aberto.');
        }

        $shift = CashShift::create([
            'user_id'         => $targetUserId,
            'opened_at'       => now(),
            'opening_balance' => $validated['opening_balance'],
            'status'          => 'open',
            'notes'           => $validated['notes'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Turno de caixa aberto com sucesso.',
                'shift_id' => $shift->id,
            ]);
        }

        return redirect()->route('pos.index')->with('success', 'Turno de caixa aberto com sucesso.');
    }

    /**
     * Fechar turno de caixa (Fecho Cego).
     */
    public function close(Request $request, CashShift $shift)
    {
        if ($shift->status === 'closed') {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este turno já se encontra fechado.'
                ], 422);
            }
            return back()->with('error', 'Este turno já está fechado.');
        }

        $validated = $request->validate([
            'closing_balance_actual' => 'required|numeric|min:0',
            'notes'                  => 'nullable|string|max:500',
        ]);

        // Recalcular totais das vendas associadas
        $shift->recalculateTotals();

        $expectedCash = $shift->expected_cash;
        $actualCash = (float) $validated['closing_balance_actual'];
        $diff = round($actualCash - $expectedCash, 2);

        $shift->update([
            'closed_at'              => now(),
            'closing_balance_system' => $expectedCash,
            'closing_balance_actual' => $actualCash,
            'difference'             => $diff,
            'status'                 => 'closed',
            'notes'                  => !empty($validated['notes'])
                ? ($shift->notes ? $shift->notes . " | Fecho: " . $validated['notes'] : $validated['notes'])
                : $shift->notes,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success'       => true,
                'message'       => 'Turno fechado com sucesso.',
                'shift_id'      => $shift->id,
                'expected_cash' => $expectedCash,
                'actual_cash'   => $actualCash,
                'difference'    => $diff,
                'receipt_url'   => route('shifts.receipt', $shift->id),
            ]);
        }

        return redirect()->route('shifts.receipt', $shift->id)
            ->with('success', 'Turno fechado com sucesso.');
    }

    /**
     * Troca rápida de utilizador/operador no restaurante (para equipa de garçons/caixas).
     */
    public function quickSwitchUser(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pin'     => 'nullable|string',
        ]);

        $currentUser = auth()->user();
        $targetUser = User::findOrFail($validated['user_id']);

        // Se não for admin/gerente, pode requerer validação simples
        Auth::login($targetUser);

        return response()->json([
            'success'   => true,
            'message'   => 'Operador alterado para ' . $targetUser->name,
            'user_id'   => $targetUser->id,
            'user_name' => $targetUser->name,
            'user_role' => $targetUser->role,
        ]);
    }

    /**
     * Impressão do relatório/talão de fecho Z do turno.
     */
    public function receipt(CashShift $shift): View
    {
        $shift->load(['user', 'sales']);
        return view('cash-shifts.receipt', compact('shift'));
    }
}
