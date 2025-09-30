<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    /**
     * Exibe a visualização das mesas do restaurante
     */
    public function index()
    {
        $tables = Table::with(['orders' => function($query) {
            $query->whereIn('status', ['active', 'completed'])
                ->latest();
        }])->orderBy('number')->get();

        return view('tables.index', compact('tables'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'number'   => 'required|string|max:50|unique:tables,number',
            'capacity' => 'required|integer|min:1',
        ]);

        Table::create([
            'number'   => $request->number,
            'capacity' => $request->capacity,
            'status'   => 'free',
        ]);

        return redirect()->route('tables.index')->with('success', 'Mesa criada com sucesso!');
    }


    /**
     * Alterar o status da mesa (livre/ocupada)
     */
    public function updateStatus(Request $request, Table $table)
    {
        $request->validate([
            'status' => 'required|in:free,occupied',
        ]);

        $table->status = $request->status;
        $table->save();

        return redirect()->route('tables.index')->with('success', 'Status da mesa atualizado com sucesso!');
    }

     /**
     * Iniciar um novo pedido para uma mesa
     */
    public function createOrder(Request $request, Table $table)
    {
        try {
            // Validar se a mesa está em grupo e não é a principal
            if ($table->group_id && !$table->is_main) {
                $mainTable = Table::where('group_id', $table->group_id)
                    ->where('is_main', true)
                    ->first();
                
                return redirect()->route('tables.index')
                    ->with('warning', "Esta mesa está agrupada. Use a Mesa {$mainTable->number} (principal) para criar pedidos.");
            }

            // Verificar se já existe pedido ativo
            if ($table->hasActiveOrder()) {
                $activeOrder = $table->activeOrder();
                return redirect()->route('orders.edit', $activeOrder->id)
                    ->with('info', 'Esta mesa já possui um pedido ativo.');
            }

            DB::beginTransaction();

            $order = Order::create([
                'table_id' => $table->id,
                'user_id' => auth()->id(),
                'status' => 'active',
                'total_amount' => 0
            ]);

            // Atualizar status da mesa
            $table->update(['status' => 'occupied']);

            // Criar notificação se o serviço existir
            if (class_exists('\App\Services\NotificationService')) {
                \App\Services\NotificationService::newOrderNotification($order);
            }

            DB::commit();

            return redirect()->route('orders.edit', $order->id)
                ->with('success', 'Novo pedido criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao criar pedido: ' . $e->getMessage());
            
            return redirect()->route('tables.index')
                ->with('error', 'Erro ao criar pedido: ' . $e->getMessage());
        }
    }
    /**
     * Unir mesas (criar um grupo)
     */
        public function mergeTables(Request $request)
    {
        try {
            $request->validate([
                'table_ids' => 'required|array|min:2',
                'table_ids.*' => 'exists:tables,id',
                'main_table_id' => 'required|exists:tables,id|in:' . implode(',', $request->table_ids),
            ]);
    
            DB::beginTransaction();
    
            // Buscar todas as mesas com lock para atualização
            $tables = Table::whereIn('id', $request->table_ids)->lockForUpdate()->get();
            $mainTable = $tables->firstWhere('id', $request->main_table_id);
    
            // Validações de negócio
            $invalidTables = $tables->filter(function ($table) {
                // Removida a verificação de status, apenas verifica se já está em grupo
                return $table->group_id !== null;
            });
    
            if ($invalidTables->isNotEmpty()) {
                throw new \Exception('Uma ou mais mesas já fazem parte de um grupo.');
            }
    
            // Gerar ID único para o grupo
            $groupId = 'group_' . Str::random(16);
            $totalCapacity = $tables->sum('capacity');
    
            // Atualizar todas as mesas do grupo
            foreach ($tables as $table) {
                $updates = [
                    'group_id' => $groupId,
                    'is_main' => $table->id === $mainTable->id,
                    'status' => 'occupied', // Força o status para ocupado
                    'merged_capacity' => $table->id === $mainTable->id ? $totalCapacity : null
                ];
                
                $table->fill($updates);
                $table->save(); // Usar save() ao invés de update() para garantir eventos do modelo
            }
    
            DB::commit();
    
            return redirect()->route('tables.index')
                ->with('success', 'Mesas unidas com sucesso! Todas as mesas do grupo estão ocupadas.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('tables.index')
                ->with('error', 'Erro ao unir mesas: ' . $e->getMessage());
        }
    }

    public function splitTables(Request $request)
    {
        try {
            $request->validate(['group_id' => 'required|string']);

            DB::beginTransaction();

            $tables = Table::where('group_id', $request->group_id)
                        ->lockForUpdate()
                        ->get();

            if ($tables->isEmpty()) {
                throw new \Exception('Grupo de mesas não encontrado.');
            }

            $mainTable = $tables->firstWhere('is_main', 1);
            
            if ($mainTable && $mainTable->hasActiveOrder()) {
                throw new \Exception('Não é possível separar mesas com pedido ativo.');
            }

            foreach ($tables as $table) {
                $table->update([
                    'group_id' => null,
                    'is_main' => false,
                    'merged_capacity' => null,
                    'status' => 'free'
                ]);
            }

            DB::commit();
            return redirect()->route('tables.index')->with('success', 'Mesas separadas com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('tables.index')->with('error', 'Erro ao separar mesas: ' . $e->getMessage());
        }
    }
}