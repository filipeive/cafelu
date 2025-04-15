<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TableController extends Controller
{
    /**
     * Exibe a visualização das mesas do restaurante
     */
    public function index()
    {
        $tables = Table::orderBy('number')->get();
        return view('tables.index', compact('tables'));
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
    public function createOrder(Table $table)
    {
        // Verificar se a mesa já tem um pedido ativo
        $activeOrder = Order::where('table_id', $table->id)
            ->whereIn('status', ['active', 'completed'])
            ->first();

        if ($activeOrder) {
            return redirect()->route('orders.edit', $activeOrder->id);
        }

        // Criar novo pedido
        $order = new Order();
        $order->table_id = $table->id;
        $order->user_id = auth()->id();
        $order->status = 'active';
        $order->save();

        // Atualizar status da mesa
        $table->status = 'occupied';
        $table->save();

        return redirect()->route('orders.edit', $order->id);
    }

    /**
     * Unir mesas (criar um grupo)
     */
    public function mergeTables(Request $request)
    {
        $request->validate([
            'table_ids' => 'required|array|min:2',
            'table_ids.*' => 'exists:tables,id',
            'main_table_id' => 'required|exists:tables,id',
        ]);

        $tableIds = $request->table_ids;
        $mainTableId = $request->main_table_id;
        
        // Verificar se alguma das mesas já está em um grupo ou tem pedidos ativos
        $tables = Table::whereIn('id', $tableIds)->get();
        
        foreach ($tables as $table) {
            if ($table->group_id && $table->id != $mainTableId) {
                return redirect()->route('tables.index')
                    ->with('error', 'A mesa ' . $table->number . ' já está em um grupo.');
            }
            
            $hasActiveOrder = Order::where('table_id', $table->id)
                ->whereIn('status', ['active', 'completed'])
                ->exists();
                
            if ($hasActiveOrder && $table->id != $mainTableId) {
                return redirect()->route('tables.index')
                    ->with('error', 'A mesa ' . $table->number . ' já tem um pedido ativo.');
            }
        }
        
        // Gerar ID de grupo único
        $groupId = 'group_' . Str::random(16);
        
        // Calcular capacidade total
        $totalCapacity = $tables->sum('capacity');
        
        // Atualizar tabelas
        foreach ($tables as $table) {
            $table->group_id = $groupId;
            $table->is_main = ($table->id == $mainTableId) ? 1 : 0;
            $table->status = 'occupied';
            $table->save();
        }
        
        // Atualizar mesa principal com a capacidade total
        $mainTable = Table::find($mainTableId);
        $mainTable->merged_capacity = $totalCapacity;
        $mainTable->save();
        
        return redirect()->route('tables.index')
            ->with('success', 'Mesas unidas com sucesso!');
    }

    /**
     * Separar mesas (desfazer grupo)
     */
    public function splitTables(Request $request)
    {
        $request->validate([
            'group_id' => 'required|string',
        ]);
        
        $groupId = $request->group_id;
        
        // Verificar se há algum pedido ativo no grupo
        $mainTable = Table::where('group_id', $groupId)
            ->where('is_main', 1)
            ->first();
            
        if (!$mainTable) {
            return redirect()->route('tables.index')
                ->with('error', 'Grupo de mesas não encontrado.');
        }
        
        $hasActiveOrder = Order::where('table_id', $mainTable->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();
            
        if ($hasActiveOrder) {
            return redirect()->route('tables.index')
                ->with('error', 'Não é possível separar mesas com pedido ativo.');
        }
        
        // Separar todas as mesas do grupo
        $tables = Table::where('group_id', $groupId)->get();
        
        foreach ($tables as $table) {
            $table->group_id = null;
            $table->is_main = 0;
            $table->merged_capacity = null;
            $table->status = 'free';
            $table->save();
        }
        
        return redirect()->route('tables.index')
            ->with('success', 'Mesas separadas com sucesso!');
    }
}