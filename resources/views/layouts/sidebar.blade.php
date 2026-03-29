<!-- Overlay para móviles -->
<div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>

<!-- Contenedor del Sidebar -->
<div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-white border-r border-gray-100 lg:translate-x-0 lg:static lg:inset-0 shadow-sm flex flex-col justify-between">
    
    <div>
        <!-- Logo Area -->
        <div class="flex items-center justify-center h-20 text-center shadow-sm">
            <!-- Icono inspirado en diseño1.png (cuadro morado con la letra D) -->
            <div class="flex items-center justify-center w-10 h-10 bg-[#6B46C1] rounded-lg shadow-lg">
                <span class="text-xl font-bold text-white">D</span>
            </div>
            <span class="mx-2 text-xl font-bold text-gray-800">Laboratorio Dental <span class="text-[#6B46C1]">JoelDent</span></span>
        </div>

        <!-- Navigation Links -->
        <nav class="mt-6 px-4 space-y-2">
            
            <!-- Dashboard General -->
            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('dashboard') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="mx-4">Dashboard</span>
            </a>

            <!-- Menú Recepción/Admin -->
            @hasanyrole('Admin|Recepción')
            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('patients.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('patients.index') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="mx-4">Doctores/Clínicas</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('quotes.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('quotes.index') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="mx-4">Cotizaciones</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('orders.*') && !request()->routeIs('technician.orders') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('orders.index') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span class="mx-4">Órdenes Trabajo</span>
            </a>
            @endhasanyrole

            <!-- Menú Técnico -->
            @role('Técnico')
            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('technician.orders') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('technician.orders') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="mx-4">Mis Órdenes</span>
            </a>
            @endrole

            <!-- Menú Tesorería/Admin -->
            @hasanyrole('Admin|Tesorero')
            <div class="mt-6 mb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Finanzas</p>
            </div>
            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('cash.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('cash.index') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="mx-4">Caja Diaria</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('banks.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('banks.index') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg>
                <span class="mx-4">Bancos</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('reconciliation.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('reconciliation.index') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                <span class="mx-4">Conciliación Bancaria</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('treasury.flow') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('treasury.flow') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                </svg>
                <span class="mx-4">Flujo de Caja</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('expenses.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('expenses.index') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                <span class="mx-4">Rendiciones</span>
            </a>

            <div class="mt-8 mb-4">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Facturación</h3>
            </div>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('invoices.*') && !request()->routeIs('invoices.receivables') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('invoices.index') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="mx-4">Comprobantes</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('collections.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('collections.index') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="mx-4">Cuentas por Cobrar / Abonos</span>
            </a>
            @endhasanyrole

            <!-- Menú Inventarios y Compras -->
            @hasanyrole('Admin|Almacenero|Logística')
            <div class="mt-6 mb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Logística e Inventario</p>
            </div>
            
            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('admin.purchases.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('admin.purchases.index') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="mx-4">Órdenes de Compra</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('admin.bills.*') && !request()->routeIs('admin.bills.calendar') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('admin.bills.index') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <span class="mx-4">Compras (Bills)</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('admin.bills.calendar') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('admin.bills.calendar') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="mx-4">Calendario de Pagos</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('admin.suppliers.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('admin.suppliers.index') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="mx-4">Proveedores</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('inventory.products.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('inventory.products.index') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="mx-4">Productos y Stock</span>
            </a>
            @endhasanyrole

            <!-- Menú Recursos Humanos -->
            @hasanyrole('Admin|RRHH')
            <div class="mt-6 mb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Recursos Humanos</p>
            </div>
            
            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('admin.employees.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('admin.employees.index') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="mx-4">Personal</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('admin.attendances.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('admin.attendances.index') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="mx-4">Asistencias</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('admin.payrolls.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('admin.payrolls.index') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="mx-4">Planillas</span>
            </a>
            @endhasanyrole

            <!-- Menú Admin -->
            @role('Admin')
            <div class="mt-6 mb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Administración</p>
            </div>
            
            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('admin.reports.index') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('admin.reports.index') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="mx-4">Descargar Reportes</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('admin.audit.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('admin.audit.index') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span class="mx-4">App Auditoría (Logs)</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('work_types.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('work_types.index') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="mx-4">Tipos de Trabajo</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('admin.users') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('admin.users') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="mx-4">Usuarios</span>
            </a>

            <a class="flex items-center px-4 py-3 mt-2 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('admin.settings') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('admin.settings') }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="mx-4">Configuración</span>
            </a>
            @endrole

            <!-- Mantenimiento Opciones Generales -->
            @hasanyrole('Admin|RRHH|Logística|Recepción')
            <div class="mt-6 mb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Mantenimiento</p>
            </div>
            <a class="flex items-center px-4 py-3 mt-2 mb-4 text-gray-500 rounded-xl transition-colors hover:bg-gray-50 hover:text-gray-900 {{ request()->routeIs('admin.general_categories.*') ? 'bg-indigo-50 text-[#6B46C1] font-medium' : '' }}" href="{{ route('admin.general_categories.index') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                <span class="mx-4">Categorías Generales</span>
            </a>
            @endhasanyrole

        </nav>
    </div>
</div>
