<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $filter = $request->query('filter', 'all');

        $clientsList = collect();

        // Fetch Offline Clients
        if ($filter === 'all' || $filter === 'offline') {
            $offline = \App\Models\Client::when($search, function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%");
            })->get()->map(function ($c) {
                $c->type = 'offline';
                return $c;
            });
            $clientsList = $clientsList->concat($offline);
        }

        // Fetch Online Customers (Users)
        if ($filter === 'all' || $filter === 'online') {
            $online = \App\Models\User::where('role', 'customer')
                ->when($search, function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%");
                })->get()->map(function ($u) {
                    $u->type = 'online';
                    // Ensure consistency for the view
                    $u->phone = $u->phone ?? 'N/A';
                    return $u;
                });
            $clientsList = $clientsList->concat($online);
        }

        // Manual Pagination
        $perPage = 10;
        $page = $request->query('page', 1);
        $clients = new \Illuminate\Pagination\LengthAwarePaginator(
            $clientsList->forPage($page, $perPage),
            $clientsList->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('client.index', compact('clients', 'search', 'filter'));
    }

    public function create()
    {
        return view('client.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:clients,email',
                'phone' => 'required|string|min:9|max:20',
                'address' => 'nullable|string|max:255',
            ]);

            Client::create($validated);

            return redirect()->route('clients.index')->with('success', 'Client created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('client.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:clients,email,' . $id,
                'phone' => 'required|string|min:9|max:20',
                'address' => 'nullable|string|max:255',
            ]);

            $client = Client::findOrFail($id);
            $client->update($validated);

            return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();

            return redirect()->route('client.index')->with('success', 'Client deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('client.index')->withErrors($e->getMessage());
        }
    }
    //show
    public function show($id)
    {
        $client = Client::findOrFail($id);
        return view('client.show', compact('client'));
    }
    //search
    public function search(Request $request)
    {
        $search = $request->input('search');
        $clients = Client::where('name', 'LIKE', "%$search%")->paginate(10);
        return view('client.index', compact('clients', 'search'));
    }
}
