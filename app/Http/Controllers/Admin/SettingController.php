<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.razon_social' => 'required|string|max:255',
            'settings.ruc' => 'required|string|max:20',
            'settings.direccion' => 'required|string|max:255',
            'settings.igv' => 'required|numeric|min:0|max:100',
            'settings.serie_factura' => 'required|string|max:10',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => 'general']
            );
        }

        return redirect()->route('admin.settings')->with('status', 'Configuración actualizada correctamente.');
    }
}
