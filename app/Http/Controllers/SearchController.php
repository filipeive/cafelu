<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Client;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return redirect()->back();
        }

        $results = [
            'products' => Product::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->limit(10)->get(),

            'sales' => Sale::where('id', 'like', "%{$query}%")
                ->orWhere('customer_name', 'like', "%{$query}%")
                ->limit(10)->get(),

            'orders' => Order::where('id', 'like', "%{$query}%")
                ->orWhereHas('table', function ($q) use ($query) {
                    $q->where('number', 'like', "%{$query}%");
                })
                ->limit(10)->get(),

            'clients' => Client::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%")
                ->limit(10)->get(),

            'employees' => Employee::where('name', 'like', "%{$query}%")
                ->orWhere('role', 'like', "%{$query}%")
                ->limit(10)->get(),

            'users' => User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->limit(10)->get(),
        ];

        $totalResults = collect($results)->flatten()->count();

        return view('search.results', compact('results', 'query', 'totalResults'));
    }
}
