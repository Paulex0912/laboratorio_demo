<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasRole('Admin')) {
            abort(403, 'Acceso Denegado. Solo Administradores pueden ver los registros de auditoría.');
        }

        $logs = AuditLog::with('user')->latest()->paginate(50);

        return view('admin.audit.index', compact('logs'));
    }
}
