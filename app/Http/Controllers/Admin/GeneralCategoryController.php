<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralCategory;
use Illuminate\Http\Request;

class GeneralCategoryController extends Controller
{
    public function index()
    {
        $categories = GeneralCategory::orderBy('type')->orderBy('name')->get();
        return view('admin.general_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.general_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:general_categories,name',
            'type' => 'required|string|in:Gasto,Compra,Servicio,Otro',
            'description' => 'nullable|string|max:255',
        ]);

        GeneralCategory::create($validated);

        return redirect()->route('admin.general_categories.index')->with('success', 'Categoría creada correctamente.');
    }

    public function edit(GeneralCategory $generalCategory)
    {
        return view('admin.general_categories.edit', compact('generalCategory'));
    }

    public function update(Request $request, GeneralCategory $generalCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:general_categories,name,' . $generalCategory->id,
            'type' => 'required|string|in:Gasto,Compra,Servicio,Otro',
            'description' => 'nullable|string|max:255',
        ]);

        $generalCategory->update($validated);

        return redirect()->route('admin.general_categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(GeneralCategory $generalCategory)
    {
        try {
            $generalCategory->delete();
            return redirect()->route('admin.general_categories.index')->with('success', 'Categoría eliminada.');
        } catch (\Exception $e) {
            return redirect()->route('admin.general_categories.index')->with('error', 'No se puede eliminar la categoría porque está en uso.');
        }
    }
}
