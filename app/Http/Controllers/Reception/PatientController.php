<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PatientsExport;
use App\Imports\PatientsImport;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::oldest()->paginate(10);
        return view('reception.patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reception.patients.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dni' => 'nullable|string|max:20|unique:patients,dni',
            'ruc' => 'nullable|string|max:20|unique:patients,ruc',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'dental_notes' => 'nullable|string',
            'observations' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        Patient::create($validated);

        return redirect()->route('patients.index')->with('success', 'Paciente registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
    //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        return view('reception.patients.form', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dni' => 'nullable|string|max:20|unique:patients,dni,' . $patient->id,
            'ruc' => 'nullable|string|max:20|unique:patients,ruc,' . $patient->id,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'dental_notes' => 'nullable|string',
            'observations' => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'Paciente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
    //
    }

    /**
     * Export users to Excel.
     */
    public function export()
    {
        return Excel::download(new PatientsExport, 'pacientes.xlsx');
    }

    /**
     * Import users from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048',
        ]);

        Excel::import(new PatientsImport, $request->file('file'));

        return redirect()->route('patients.index')->with('success', 'Pacientes importados masivamente con éxito.');
    }
}
