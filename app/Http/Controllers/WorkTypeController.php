<?php

namespace App\Http\Controllers;

use App\Models\WorkType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\WorkTypesImport;

class WorkTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workTypes = WorkType::latest()->paginate(10);
        return view('admin.work_types.index', compact('workTypes'));
    }

    public function create()
    {
        return view('admin.work_types.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_price' => 'nullable|numeric|min:0',
        ]);

        WorkType::create($validated);

        return redirect()->route('work_types.index')->with('success', 'Tipo de trabajo creado correctamente.');
    }

    public function show(WorkType $workType)
    {
    // No necesario
    }

    public function edit(WorkType $workType)
    {
        return view('admin.work_types.form', compact('workType'));
    }

    public function update(Request $request, WorkType $workType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_price' => 'nullable|numeric|min:0',
        ]);

        $workType->update($validated);

        return redirect()->route('work_types.index')->with('success', 'Tipo de trabajo actualizado correctamente.');
    }

    public function destroy(WorkType $workType)
    {
        $workType->delete();
        return redirect()->route('work_types.index')->with('success', 'Tipo de trabajo eliminado.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new WorkTypesImport, $request->file('file'));
            return redirect()->route('work_types.index')->with('success', 'Catálogo de trabajos importado correctamente.');
        }
        catch (\Exception $e) {
            return redirect()->route('work_types.index')->with('error', 'Hubo un error al importar: ' . $e->getMessage());
        }
    }
}
