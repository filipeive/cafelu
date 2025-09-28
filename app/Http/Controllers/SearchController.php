<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Table;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');
        
        if (empty($query)) {
            return view('search.results', [
                'query' => $query,
                'results' => collect(),
                'totalResults' => 0
            ]);
        }

        $results = $this->performSearch($query, $type);
        
        return view('search.results', [
            'query' => $query,
            'type' => $type,
            'results' => $results,
            'totalResults' => $results->sum(fn($group) => $group->count())
        ]);
    }

    public function api(Request $request)
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 8);
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'query' => $query,
                'results' => [],
                'total' => 0
            ]);
        }

        $results = $this->performQuickSearch($query, $limit);
        
        return response()->json([
            'query' => $query,
            'results' => $results,
            'total' => collect($results)->sum(fn($group) => count($group['items']))
        ]);
    }

    private function performSearch($query, $type = 'all')
    {
        $results = collect();

        // Buscar Produtos
        if ($type === 'all' || $type === 'products') {
            $products = Product::where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                /* ->orWhere('barcode', 'LIKE', "%{$query}%") */
                ->with('category')
                ->where('is_active', true)
                ->limit(20)
                ->get()
                ->map(function ($product) {
                    return [
                        'type' => 'product',
                        'id' => $product->id,
                        'title' => $product->name,
                        'subtitle' => $product->category->name ?? 'Sem categoria',
                        'description' => $product->description ?? 'Sem descrição',
                        'price' => number_format($product->selling_price, 2) . ' MZN',
                        'stock' => $product->stock_quantity ?? 0,
                        'url' => route('products.show', $product->id),
                        'icon' => 'mdi-food',
                        'badge' => $product->stock_quantity > 0 ? 'Em estoque' : 'Fora de estoque',
                        'badge_class' => $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger'
                    ];
                });
            
            if ($products->isNotEmpty()) {
                $results['products'] = $products;
            }
        }

        // Buscar Vendas
        if (($type === 'all' || $type === 'sales') && $this->userCan('view_sales')) {
            $sales = Sale::where(function($q) use ($query) {
                    $q->where('customer_name', 'LIKE', "%{$query}%")
                      ->orWhere('customer_phone', 'LIKE', "%{$query}%")
                      ->orWhere('id', 'LIKE', "%{$query}%");
                      /* ->orWhere('invoice_number', 'LIKE', "%{$query}%") */
                })
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($sale) {
                    return [
                        'type' => 'sale',
                        'id' => $sale->id,
                        'title' => "Venda #{$sale->id}",
                        'subtitle' => $sale->customer_name ?: 'Cliente não informado',
                        'description' => "Total: " . number_format($sale->total_amount, 2) . " MZN - " . $sale->sale_date?->format('d/m/Y'),
                        'date' => $sale->created_at->format('d/m/Y H:i'),
                        'url' => route('sales.show', $sale->id),
                        'icon' => 'mdi-cash-register',
                        'badge' => ucfirst($sale->payment_method ?? 'N/A'),
                        'badge_class' => match($sale->payment_method) {
                            'cash' => 'bg-success',
                            'card' => 'bg-info',
                            'transfer' => 'bg-warning',
                            'credit' => 'bg-danger',
                            default => 'bg-secondary'
                        }
                    ];
                });

            if ($sales->isNotEmpty()) {
                $results['sales'] = $sales;
            }
        }

        // Buscar Pedidos
        if (($type === 'all' || $type === 'orders') && $this->userCan('view_orders')) {
            $orders = Order::where(function($q) use ($query) {
                    $q->where('customer_name', 'LIKE', "%{$query}%")
                      ->orWhere('customer_phone', 'LIKE', "%{$query}%")
                      ->orWhere('customer_email', 'LIKE', "%{$query}%")
                      ->orWhere('id', 'LIKE', "%{$query}%");
                })
                ->with('user', 'table')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($order) {
                    return [
                        'type' => 'order',
                        'id' => $order->id,
                        'title' => "Pedido #{$order->id}",
                        'subtitle' => $order->customer_name ?? 'Cliente não informado',
                        'description' => "Mesa: " . ($order->table->number ?? 'N/A') . " - Total: " . number_format($order->total_amount ?? 0, 2) . " MZN",
                        'date' => $order->created_at->format('d/m/Y H:i'),
                        'url' => route('orders.show', $order->id),
                        'icon' => 'mdi-receipt',
                        'badge' => $this->getOrderStatusLabel($order->status),
                        'badge_class' => $this->getOrderStatusClass($order->status)
                    ];
                });

            if ($orders->isNotEmpty()) {
                $results['orders'] = $orders;
            }
        }

        // Buscar Clientes
        if ($type === 'all' || $type === 'clients') {
            $clients = Client::where('name', 'LIKE', "%{$query}%")
                ->orWhere('phone', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                /* ->orWhere('document', 'LIKE', "%{$query}%") */
                ->limit(15)
                ->get()
                ->map(function ($client) {
                    return [
                        'type' => 'client',
                        'id' => $client->id,
                        'title' => $client->name,
                        'subtitle' => $client->phone ?? 'Sem telefone',
                        'description' => $client->email ?? 'Cliente do restaurante',
                        'url' => route('client.show', $client->id),
                        'icon' => 'mdi-account-heart',
                        'badge' => $client->is_active ? 'Ativo' : 'Inativo',
                        'badge_class' => $client->is_active ? 'bg-success' : 'bg-secondary'
                    ];
                });

            if ($clients->isNotEmpty()) {
                $results['clients'] = $clients;
            }
        }

        // Buscar Mesas
        if ($type === 'all' || $type === 'tables') {
            $tables = Table::where('number', 'LIKE', "%{$query}%")
                /* ->orWhere('name', 'LIKE', "%{$query}%") */
                ->orWhere('capacity', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get()
                ->map(function ($table) {
                    return [
                        'type' => 'table',
                        'id' => $table->id,
                        'title' => "Mesa {$table->number}",
                        //'subtitle' => $table->name ?? "Capacidade: {$table->capacity} pessoas",
                        'capacity' => $table->description ?? 'Mesa do restaurante',
                        'url' => route('tables.show', $table->id),
                        'icon' => 'mdi-table-furniture',
                        'badge' => $this->getTableStatusLabel($table->status),
                        'badge_class' => $this->getTableStatusClass($table->status)
                    ];
                });

            if ($tables->isNotEmpty()) {
                $results['tables'] = $tables;
            }
        }

        // Buscar Funcionários (apenas para admins/gerentes)
        if (($type === 'all' || $type === 'employees')) {
            $employees = Employee::where('name', 'LIKE', "%{$query}%")
                ->orWhere('role', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get()
                ->map(function ($employee) {
                    return [
                        'type' => 'employee',
                        'id' => $employee->id,
                        'title' => $employee->name,
                        'subtitle' => $employee->position ?? 'Funcionário',
                        'description' => $employee->role ?? $employee->email ?? 'Funcionário do restaurante',
                        'url' => route('employees.show', $employee->id),
                        'icon' => 'mdi-account-tie',
                        'badge' => $employee->is_active ? 'Ativo' : 'Inativo',
                        'badge_class' => $employee->is_active ? 'bg-success' : 'bg-secondary'
                    ];
                });

            if ($employees->isNotEmpty()) {
                $results['employees'] = $employees;
            }
        }

        // Buscar Categorias
        if (($type === 'all' || $type === 'categories') && $this->userCan('manage_categories')) {
            $categories = Category::where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->where('is_active', true)
                ->limit(10)
                ->get()
                ->map(function ($category) {
                    return [
                        'type' => 'category',
                        'id' => $category->id,
                        'title' => $category->name,
                        'subtitle' => 'Categoria de produtos',
                        'description' => $category->description ?: 'Sem descrição',
                        'url' => route('categories.show', $category->id),
                        'icon' => 'mdi-format-list-bulleted',
                        'badge' => 'Categoria',
                        'badge_class' => 'bg-primary'
                    ];
                });

            if ($categories->isNotEmpty()) {
                $results['categories'] = $categories;
            }
        }

        // Buscar Despesas (apenas para admins/gerentes)
        if (($type === 'all' || $type === 'expenses') && $this->userCan('view_expenses')) {
            $expenses = Expense::where('description', 'LIKE', "%{$query}%")
               /*  ->orWhere('supplier', 'LIKE', "%{$query}%") */
                ->orWhere('receipt_number', 'LIKE', "%{$query}%")
                ->with('category')
                ->orderBy('expense_date', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($expense) {
                    return [
                        'type' => 'expense',
                        'id' => $expense->id,
                        'title' => $expense->description,
                        'subtitle' => $expense->supplier ?? 'Sem fornecedor',
                        'description' => "Valor: " . number_format($expense->amount, 2) . " MZN - " . $expense->date?->format('d/m/Y'),
                        'expense_date' => $expense->expense_date?->format('d/m/Y'),
                        'url' => route('expenses.show', $expense->id),
                        'icon' => 'mdi-cash-remove',
                        'badge' => $expense->category->name ?? 'Sem categoria',
                        'badge_class' => 'bg-danger'
                    ];
                });

            if ($expenses->isNotEmpty()) {
                $results['expenses'] = $expenses;
            }
        }
        
        return $results;
    }

    private function performQuickSearch($query, $limit)
    {
        $results = [];

        // Busca rápida de produtos
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->where('is_active', true)
            ->limit($limit)
            ->get(['id', 'name', 'selling_price', 'stock_quantity']);

        if ($products->isNotEmpty()) {
            $results['products'] = [
                'title' => 'Produtos',
                'icon' => 'mdi-food',
                'items' => $products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'text' => $product->name,
                        'subtitle' => number_format($product->selling_price, 2) . ' MZN',
                        'url' => route('products.show', $product->id),
                        'stock' => $product->stock_quantity ?? 0
                    ];
                })->toArray()
            ];
        }

        // Busca rápida de clientes
        $clients = Client::where('name', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'phone']);

        if ($clients->isNotEmpty()) {
            $results['clients'] = [
                'title' => 'Clientes',
                'icon' => 'mdi-account-group',
                'items' => $clients->map(function ($client) {
                    return [
                        'id' => $client->id,
                        'text' => $client->name,
                        'subtitle' => $client->phone ?? 'Sem telefone',
                        'url' => route('clients.show', $client->id)
                    ];
                })->toArray()
            ];
        }

        // Busca rápida de pedidos pendentes
        if ($this->userCan('view_orders')) {
            $pendingOrders = Order::where(function($q) use ($query) {
                    $q->where('customer_name', 'LIKE', "%{$query}%")
                      ->orWhere('id', 'LIKE', "%{$query}%");
                })
                ->whereIn('status', ['pending', 'preparing', 'ready'])
                ->limit(3)
                ->get(['id', 'customer_name', 'status', 'total_amount']);

            if ($pendingOrders->isNotEmpty()) {
                $results['orders'] = [
                    'title' => 'Pedidos Ativos',
                    'icon' => 'mdi-receipt',
                    'items' => $pendingOrders->map(function ($order) {
                        return [
                            'id' => $order->id,
                            'text' => "Pedido #{$order->id}",
                            'subtitle' => ($order->customer_name ?? 'Cliente') . ' - ' . $this->getOrderStatusLabel($order->status),
                            'url' => route('orders.show', $order->id)
                        ];
                    })->toArray()
                ];
            }
        }

        // Busca rápida de mesas
        $tables = Table::where('number', 'LIKE', "%{$query}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->limit(4)
            ->get(['id', 'number', 'name', 'status', 'capacity']);

        if ($tables->isNotEmpty()) {
            $results['tables'] = [
                'title' => 'Mesas',
                'icon' => 'mdi-table-furniture',
                'items' => $tables->map(function ($table) {
                    return [
                        'id' => $table->id,
                        'text' => "Mesa {$table->number}",
                        'subtitle' => ($table->name ?? '') . " - " . $this->getTableStatusLabel($table->status),
                        'url' => route('tables.show', $table->id)
                    ];
                })->toArray()
            ];
        }

        return $results;
    }

    private function getOrderStatusLabel($status)
    {
        return match($status) {
            'pending' => 'Pendente',
            'preparing' => 'Preparando',
            'ready' => 'Pronto',
            'served' => 'Servido',
            'completed' => 'Finalizado',
            'cancelled' => 'Cancelado',
            default => 'Desconhecido'
        };
    }

    private function getOrderStatusClass($status)
    {
        return match($status) {
            'pending' => 'bg-warning',
            'preparing' => 'bg-info',
            'ready' => 'bg-primary',
            'served' => 'bg-success',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    private function getTableStatusLabel($status)
    {
        return match($status) {
            'available' => 'Disponível',
            'occupied' => 'Ocupada',
            'reserved' => 'Reservada',
            'cleaning' => 'Limpeza',
            'maintenance' => 'Manutenção',
            default => 'Desconhecido'
        };
    }

    private function getTableStatusClass($status)
    {
        return match($status) {
            'available' => 'bg-success',
            'occupied' => 'bg-danger',
            'reserved' => 'bg-warning',
            'cleaning' => 'bg-info',
            'maintenance' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    /**
     * Helper para verificar permissões do usuário
     * Adapte conforme seu sistema de permissões
     */
    private function userCan($permission)
    {
        $user = auth()->user();
        
        if (!$user) return false;
        
        // Se for admin, pode tudo
        if ($user->role === 'admin') return true;
        
        // Permissões básicas para gerentes
        if ($user->role === 'manager') {
            return in_array($permission, [
                'view_sales', 'view_orders', 'view_employees', 
                'manage_categories', 'view_expenses'
            ]);
        }
        
        // Permissões básicas para funcionários
        if ($user->role === 'employee') {
            return in_array($permission, ['view_orders']);
        }
        
        return false;
    }
}