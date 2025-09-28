<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::latest()->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'role' => 'required|in:chef,waiter,manager',
            'hire_date' => 'required|date',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')->with('success', 'Funcionário criado com sucesso.');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'role' => 'required|in:chef,waiter,manager',
            'hire_date' => 'required|date',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', 'Funcionário atualizado com sucesso.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Funcionário removido com sucesso.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $employees = Employee::where('name', 'like', "%{$query}%")->get();

        return view('employees.index', compact('employees'));
    }
}
