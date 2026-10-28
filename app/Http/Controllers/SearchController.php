<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\Sale;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        $category = $request->input('type', 'all');

        $results = [
            'products'   => collect(),
            'orders'     => collect(),
            'sales'      => collect(),
            'quotations' => collect(),
            'clients'    => collect(),
            'tables'     => collect(),
            'employees'  => collect(),
        ];

        if ($query !== '') {
            $results['products'] = Product::with('category')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->limit(12)
                ->get();

            $results['orders'] = Order::with('table')
                ->where(function ($q) use ($query) {
                    $q->where('id', 'like', "%{$query}%")
                      ->orWhere('customer_name', 'like', "%{$query}%")
                      ->orWhereHas('table', function ($tq) use ($query) {
                          $tq->where('number', 'like', "%{$query}%");
                      });
                })
                ->latest()
                ->limit(12)
                ->get();

            $results['sales'] = Sale::where(function ($q) use ($query) {
                    $q->where('id', 'like', "%{$query}%")
                      ->orWhere('invoice_number', 'like', "%{$query}%")
                      ->orWhere('customer_name', 'like', "%{$query}%")
                      ->orWhere('customer_nuit', 'like', "%{$query}%");
                })
                ->latest('sale_date')
                ->limit(12)
                ->get();

            if (Schema::hasTable('quotations')) {
                $results['quotations'] = Quotation::where(function ($q) use ($query) {
                        $q->where('quotation_number', 'like', "%{$query}%")
                          ->orWhere('customer_name', 'like', "%{$query}%")
                          ->orWhere('customer_nuit', 'like', "%{$query}%");
                    })
                    ->latest('date')
                    ->limit(10)
                    ->get();
            }

            $results['clients'] = Client::where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%")
                      ->orWhere('nuit', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get();

            $results['tables'] = Table::where('is_temporary', false)
                ->where('number', 'like', "%{$query}%")
                ->orderBy('number')
                ->limit(8)
                ->get();

            $results['employees'] = Employee::where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('role', 'like', "%{$query}%");
                })
                ->limit(8)
                ->get();
        }

        $counts = [
            'products'   => $results['products']->count(),
            'orders'     => $results['orders']->count(),
            'sales'      => $results['sales']->count(),
            'quotations' => $results['quotations']->count(),
            'clients'    => $results['clients']->count(),
            'tables'     => $results['tables']->count(),
            'employees'  => $results['employees']->count(),
        ];

        $totalResults = array_sum($counts);

        return view('search.results', compact('results', 'query', 'totalResults', 'counts', 'category'));
    }
}
