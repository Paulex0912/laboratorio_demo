<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// Public Web Signature Forms
Route::get('/delivery/{order}/sign', [\App\Http\Controllers\Client\OrderDeliveryController::class , 'show'])->name('public.delivery.show')->middleware('signed');
Route::post('/delivery/{order}/sign', [\App\Http\Controllers\Client\OrderDeliveryController::class , 'sign'])->name('public.delivery.sign')->middleware('signed');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class , 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/api/dashboard-stats', [\App\Http\Controllers\DashboardController::class , 'stats'])->middleware(['auth'])->name('api.dashboard.stats');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

    // Admin
    Route::middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Admin')->group(function () {
            Route::get('/admin/users', function () {
                    return 'Usuarios';
                }
                )->name('admin.users');
                Route::get('/admin/settings', [\App\Http\Controllers\Admin\SettingController::class , 'index'])->name('admin.settings');
                Route::post('/admin/settings', [\App\Http\Controllers\Admin\SettingController::class , 'store'])->name('admin.settings.store');
                Route::get('/admin/audit', [\App\Http\Controllers\Admin\AuditController::class , 'index'])->name('admin.audit.index');
                Route::get('/admin/reports', [\App\Http\Controllers\Admin\ReportController::class , 'index'])->name('admin.reports.index');
                Route::post('/admin/reports/export', [\App\Http\Controllers\Admin\ReportController::class , 'export'])->name('admin.reports.export');
                Route::resource('admin/employees', \App\Http\Controllers\Admin\EmployeeController::class)->names('admin.employees');
                Route::resource('admin/suppliers', \App\Http\Controllers\Admin\SupplierController::class)->names('admin.suppliers');
                Route::resource('admin/purchases', \App\Http\Controllers\Admin\PurchaseOrderController::class)->names('admin.purchases');

                Route::get('admin/bills/calendar', [\App\Http\Controllers\Admin\BillController::class , 'calendar'])->name('admin.bills.calendar');
                Route::get('admin/api/bills-calendar', [\App\Http\Controllers\Admin\BillController::class , 'calendarData'])->name('api.bills.calendar');
                Route::resource('admin/bills', \App\Http\Controllers\Admin\BillController::class)->names('admin.bills');
                Route::resource('admin/attendances', \App\Http\Controllers\Admin\AttendanceController::class)->names('admin.attendances')->only(['index']);
                Route::resource('admin/payrolls', \App\Http\Controllers\Admin\PayrollController::class)->names('admin.payrolls')->only(['index', 'show']);
                Route::post('admin/work_types/import', [\App\Http\Controllers\WorkTypeController::class , 'import'])->name('work_types.import');
                Route::resource('admin/work_types', \App\Http\Controllers\WorkTypeController::class)->names('work_types')->except(['show']);

                // Mantenedor de Categorías Generales
                Route::resource('admin/general_categories', \App\Http\Controllers\Admin\GeneralCategoryController::class)->names('admin.general_categories')->except(['show']);
            }
            );

            // Recepción
            Route::middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Admin|Recepción')->group(function () {
            Route::get('patients/export', [\App\Http\Controllers\Reception\PatientController::class , 'export'])->name('patients.export');
            Route::post('patients/import', [\App\Http\Controllers\Reception\PatientController::class , 'import'])->name('patients.import');
            Route::resource('patients', \App\Http\Controllers\Reception\PatientController::class)->except(['show', 'destroy']);

            Route::resource('quotes', \App\Http\Controllers\Reception\QuoteController::class);
            Route::get('quotes/{quote}/pdf', [\App\Http\Controllers\Reception\QuoteController::class , 'pdf'])->name('quotes.pdf');
            Route::post('quotes/{quote}/approve', [\App\Http\Controllers\Reception\QuoteController::class , 'approve'])->name('quotes.approve');

            Route::post('orders/import', [\App\Http\Controllers\Reception\OrderController::class , 'import'])->name('orders.import');
            Route::resource('orders', \App\Http\Controllers\Reception\OrderController::class)->except(['destroy']);
            Route::patch('orders/{order}/cancel', [\App\Http\Controllers\Reception\OrderController::class , 'cancel'])->name('orders.cancel');
            Route::patch('orders/{order}/status', [\App\Http\Controllers\Reception\OrderController::class , 'changeStatus'])->name('orders.changeStatus');
            Route::post('orders/{order}/photos', [\App\Http\Controllers\Reception\OrderController::class , 'storePhoto'])->name('orders.photos.store');
            Route::delete('orders/photos/{photo}', [\App\Http\Controllers\Reception\OrderController::class , 'destroyPhoto'])->name('orders.photos.destroy');
            Route::get('orders/{order}/checkout', [\App\Http\Controllers\Reception\OrderController::class , 'checkout'])->name('orders.checkout');
            Route::post('orders/{order}/checkout', [\App\Http\Controllers\Reception\OrderController::class , 'processCheckout'])->name('orders.processCheckout');

            // Cobranzas y Tesorería
            Route::get('collections', [\App\Http\Controllers\Finance\CollectionController::class , 'index'])->name('collections.index');
            Route::get('collections/{patient}', [\App\Http\Controllers\Finance\CollectionController::class , 'show'])->name('collections.show');
            Route::post('collections/{patient}/apply', [\App\Http\Controllers\Finance\CollectionController::class , 'store'])->name('collections.store');
        }
        );

        // Tesorería / Admin
        Route::middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Admin|Tesorero')->group(function () {
            // Caja
            Route::get('/cash', [\App\Http\Controllers\Finance\CashController::class , 'index'])->name('cash.index');
            Route::post('/cash', [\App\Http\Controllers\Finance\CashController::class , 'store'])->name('cash.store');

            // Bancos
            Route::get('/banks', [\App\Http\Controllers\Finance\BankController::class , 'index'])->name('banks.index');
            Route::post('/banks', [\App\Http\Controllers\Finance\BankController::class , 'storeAccount'])->name('banks.storeAccount');
            Route::get('/banks/{bank}', [\App\Http\Controllers\Finance\BankController::class , 'show'])->name('banks.show');
            Route::post('/banks/{bank}/movements', [\App\Http\Controllers\Finance\BankController::class , 'storeMovement'])->name('banks.storeMovement');
            Route::post('/banks/{bank}/import', [\App\Http\Controllers\Finance\BankController::class , 'import'])->name('banks.import');
            Route::post('/banks/movements/{movement}/toggle-reconciled', [\App\Http\Controllers\Finance\BankController::class , 'toggleReconciled'])->name('banks.movements.toggleReconciled');

            // Conciliación
            Route::get('/reconciliation', [\App\Http\Controllers\Finance\ReconciliationController::class , 'index'])->name('reconciliation.index');

            // Facturación
            Route::get('/invoices/receivables', [\App\Http\Controllers\Finance\InvoiceController::class , 'receivables'])->name('invoices.receivables');
            Route::resource('invoices', \App\Http\Controllers\Finance\InvoiceController::class)->except(['destroy']);
            Route::post('/invoices/{invoice}/payments', [\App\Http\Controllers\Finance\InvoiceController::class , 'storePayment'])->name('invoices.storePayment');
            Route::patch('/invoices/{invoice}/status', [\App\Http\Controllers\Finance\InvoiceController::class , 'updateStatus'])->name('invoices.updateStatus');

            // Rendiciones de Gastos
            Route::resource('expenses', \App\Http\Controllers\Finance\ExpenseController::class)->only(['index', 'create', 'store', 'show']);
            Route::patch('/expenses/{expense}/approve', [\App\Http\Controllers\Finance\ExpenseController::class , 'approve'])->name('expenses.approve');
            Route::patch('/expenses/{expense}/reject', [\App\Http\Controllers\Finance\ExpenseController::class , 'reject'])->name('expenses.reject');
            Route::patch('/expenses/{expense}/liquidate', [\App\Http\Controllers\Finance\ExpenseController::class , 'liquidate'])->name('expenses.liquidate');

            // Dashboard Tesorería
            Route::get('/treasury/flow', [\App\Http\Controllers\Finance\TreasuryController::class , 'flow'])->name('treasury.flow');
        }
        );

        // Técnico
        Route::middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Técnico')->group(function () {
            Route::get('/technician/orders', [\App\Http\Controllers\Technician\OrderController::class , 'index'])->name('technician.orders');
            Route::patch('/technician/orders/{order}/status', [\App\Http\Controllers\Technician\OrderController::class , 'updateStatus'])->name('technician.orders.status');
            Route::post('/technician/orders/{order}/materials', [\App\Http\Controllers\Lab\WorkOrderMaterialController::class , 'store'])->name('technician.orders.materials.store');
        }
        );

        // Inventario (Admin / Almacenero)
        Route::middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Admin|Almacenero')->group(function () {
            Route::resource('inventory/products', \App\Http\Controllers\Inventory\ProductController::class)->names('inventory.products');
        }
        );
    });

require __DIR__ . '/auth.php';

Route::get('/setup-db', function () {
    $path = base_path('database/data/reporte_2025.csv');
    
    if (!file_exists($path)) {
        return "ERROR: El archivo NO existe en la ruta: " . $path;
    }

    try {
        \Artisan::call('migrate:fresh --seed');
        return "¡ÉXITO! Archivo encontrado y base de datos cargada.";
    } catch (\Exception $e) {
        return "Error al procesar: " . $e->getMessage();
    }
});

Route::get('/setup-admin', function () {
    try {
        // Forzamos la creación del rol por si el seeder falló
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);

        // Buscamos o creamos al usuario administrador
        $user = \App\Models\User::firstOrCreate(
        ['email' => 'admin@laboratorio.com'],
        [
            'name' => 'Administrador Joel Dent',
            'password' => bcrypt('password'),
        ]
        );

        // Le asignamos el rol
        $user->assignRole($role);

        return "¡LOGRADO! Rol 'Admin' vinculado a admin@laboratorio.com. Ya puedes entrar al login.";
    }
    catch (\Exception $e) {
        return "Error en Admin: " . $e->getMessage();
    }
});
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Route::get('/cargar-historial-2025', function () {
    $file = base_path('database/data/reporte_2025.csv');
    if (!file_exists($file)) return "Error: Archivo no encontrado.";

    $lines = file($file);
    $count = 0;

    foreach ($lines as $index => $line) {
        if ($index == 0) continue; 

        $separator = str_contains($line, ';') ? ';' : ',';
        $data = str_getcsv($line, $separator);
        $data = array_map('trim', $data);

        // Según tu diagnóstico: ID está en índice 0
        if (isset($data[0]) && is_numeric($data[0])) {
            try {
                // 1. Lógica de Paciente (Si el índice 4 está vacío, usamos el Doctor del índice 11)
                $nombrePaciente = !empty($data[4]) ? $data[4] : (!empty($data[11]) ? "Pac. de Dr. " . $data[11] : "PACIENTE GENERICO");
                
                $patient = Patient::firstOrCreate(['name' => $nombrePaciente]);

                // 2. Limpiar el monto (quitar el 'S/' y espacios)
                $montoLimpio = 0;
                if (isset($data[6])) {
                    $montoLimpio = (float) filter_var($data[6], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                }

                // 3. Insertar con los índices correctos de tu imagen
                DB::table('work_orders')->updateOrInsert(
                    ['id' => (int)$data[0]], 
                    [
                        'patient_id'    => $patient->id,
                        'status'        => 'entregado',
                        'type'          => $data[9] ?? 'Trabajo Dental', // Índice 9: Perno Indirecto
                        'material'      => $data[10] ?? null,            // Índice 10: Perno Muñon
                        'amount'        => $montoLimpio,
                        'due_date'      => !empty($data[2]) ? Carbon::parse($data[2])->format('Y-m-d') : null,
                        'created_at'    => !empty($data[1]) ? Carbon::parse($data[1])->format('Y-m-d') : now(),
                        'updated_at'    => now(),
                    ]
                );
                $count++;
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    return "¡LOGRADO! Se cargaron " . $count . " registros. Ya puedes revisar tus Órdenes de Trabajo.";
});