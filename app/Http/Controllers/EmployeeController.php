<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SalaryPayment;
use App\Models\Expense;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::latest()->get();
        return view('employees.index', compact('employees'));
    }

    public function payroll(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $employees = Employee::all();
        $payments = SalaryPayment::where('month', $month)
            ->where('year', $year)
            ->get()
            ->keyBy('employee_id');

        return view('employees.payroll', compact('employees', 'payments', 'month', 'year'));
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
            'salary' => 'nullable|numeric|min:0',
            'phone' => 'nullable|string|max:20',
            'hire_date' => 'required|date',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')->with('success', __('messages.employee_created'));
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
            'salary' => 'nullable|numeric|min:0',
            'phone' => 'nullable|string|max:20',
            'hire_date' => 'required|date',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', __('messages.employee_updated'));
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', __('messages.employee_deleted'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $employees = Employee::where('name', 'like', "%{$query}%")->get();

        return view('employees.index', compact('employees'));
    }

    public function paySalary(Request $request, Employee $employee)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
        ]);

        $month = $request->month;
        $year = $request->year;

        if (!$employee->salary || $employee->salary <= 0) {
            return redirect()->back()->with('error', __('messages.no_salary_defined'));
        }

        // Check if already paid
        $exists = SalaryPayment::where('employee_id', $employee->id)
            ->where('month', $month)
            ->where('year', $year)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', __('messages.salary_already_paid'));
        }

        try {
            $expense = Expense::create([
                'description' => __('messages.salary_payment_desc', ['name' => $employee->name, 'month' => $month, 'year' => $year]),
                'amount' => $employee->salary,
                'category' => 'Salaries',
                'expense_date' => now(),
                'notes' => __('messages.auto_payroll_note'),
                'user_id' => auth()->id(),
            ]);

            SalaryPayment::create([
                'employee_id' => $employee->id,
                'amount' => $employee->salary,
                'month' => $month,
                'year' => $year,
                'payment_date' => now(),
                'expense_id' => $expense->id,
            ]);

            return redirect()->back()->with('success', __('messages.salary_paid'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.error_registering_payment') . ': ' . $e->getMessage());
        }
    }
}
