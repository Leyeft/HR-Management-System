<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /* =======================
     * INDEX
     * ======================= */
    public function index(Request $request)
{
    $employees = Employee::with(['user', 'department'])
    ->whereHas('user') // ensures join exists
    ->join('users', 'employees.user_id', '=', 'users.id')

    // SEARCH
    ->when($request->search, function ($query) use ($request) {
        $query->where(function ($q) use ($request) {
            $q->where('users.name', 'like', '%' . $request->search . '%')
              ->orWhere('users.email', 'like', '%' . $request->search . '%');
        });
    })

    // FILTER: department
    ->when($request->department, function ($query) use ($request) {
        $query->where('employees.department_id', $request->department);
    })

    // FILTER: rank
    ->when($request->rank, function ($query) use ($request) {
        $query->where('employees.rank', $request->rank);
    })

    // ✅ ALPHABETICAL ORDER
    ->orderBy('users.name', 'asc')

    // IMPORTANT: avoid column conflict
    ->select('employees.*')

    ->paginate(10)
    ->withQueryString();

    $departments = Department::all();

    return view('employees.index', compact('employees', 'departments'));
}

    /* =======================
     * CREATE
     * ======================= */
    public function create()
    {
        $departments = Department::all();
        return view('employees.create', compact('departments'));
    }

    /* =======================
     * STORE
     * ======================= */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'department_id' => 'required|exists:departments,id',
            'rank' => 'required|in:employee,head',
            'date_of_birth' => 'required|date',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employee',
        ]);

        Employee::create([
            'user_id' => $user->id,
            'department_id' => $request->department_id,
            'rank' => $request->rank,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee created successfully');
    }

    /* =======================
     * EDIT
     * ======================= */
    public function edit(Employee $employee)
    {
        $departments = Department::all();
        $employee->load('user');

        return view('employees.edit', compact('employee', 'departments'));
    }

    /* =======================
     * UPDATE
     * ======================= */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->user_id,
            'department_id' => 'required|exists:departments,id',
            'rank' => 'required|in:employee,head',
            'date_of_birth' => 'required|date',
        ]);

        // Update user info
        $employee->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update employee info
        $employee->update([
            'department_id' => $request->department_id,
            'rank' => $request->rank,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee updated successfully');
    }

    /* =======================
     * DESTROY
     * ======================= */
    public function destroy(Employee $employee)
    {
        // This will also delete employee due to cascade
        $employee->user->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee deleted successfully');
    }
}
