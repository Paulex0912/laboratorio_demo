<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Area;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('area', 'user')->paginate(10);
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $areas = Area::all();
        return view('admin.employees.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dni' => 'required|string|size:8|unique:employees',
            'birthdate' => 'nullable|date',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'position' => 'required|string|max:255',
            'area_id' => 'nullable|exists:areas,id',
            'start_date' => 'required|date',
        ]);

        Employee::create($validated);

        return redirect()->route('admin.employees.index')->with('success', 'Empleado registrado correctamente.');
    }
}
