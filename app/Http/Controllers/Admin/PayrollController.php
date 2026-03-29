<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::with('aprover')->latest('period_year')->latest('period_month')->paginate(10);
        return view('admin.payrolls.index', compact('payrolls'));
    }
}
