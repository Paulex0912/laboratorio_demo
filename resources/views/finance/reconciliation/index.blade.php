<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Conciliación Bancaria') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ tab: '{{ $currentTab ?? 'movements' }}' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Movimientos Bancarios Sin Conciliar</p>
                        <p class="text-3xl font-bold text-amber-600 mt-1">{{ $stats['unreconciled_count'] }}</p>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-full text-amber-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Facturas Pendientes de Cobro</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $stats['pending_invoices_count'] }}</p>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-full text-indigo-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 flex overflow-x-auto gap-2">
                <button @click="tab = 'movements'" :class="tab === 'movements' ? 'bg-[#6B46C1] text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'" class="px-6 py-2.5 rounded-xl font-medium text-sm transition-all whitespace-nowrap">
                    Movimientos Huerfanos
                </button>
                <button @click="tab = 'invoices'" :class="tab === 'invoices' ? 'bg-[#6B46C1] text-white shadow-md' : 'text-gray-600 hover:bg-gray-100'" class="px-6 py-2.5 rounded-xl font-medium text-sm transition-all whitespace-nowrap">
                    Facturas Pendientes
                </button>
                <button disabled title="Próximamente" class="px-6 py-2.5 rounded-xl font-medium text-sm text-gray-300 cursor-not-allowed whitespace-nowrap">
                    Compras/Egresos Pendientes
                </button>
            </div>

            <!-- Tab 1: Bank Movements -->
            <div x-show="tab === 'movements'" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-cloak>
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Ingresos y Egresos Bancarios Sin Conciliar</h3>
                    <p class="text-sm text-gray-500 mt-1">Estos movimientos importados o creados no se han asociado a ninguna deuda y figuran como huérfanos.</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cuenta</th>
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Detalle Original</th>
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Monto</th>
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Acción Sugerida</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($unreconciledMovements as $mov)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-4 text-sm text-gray-600">{{ $mov->date->format('d/m/Y') }}</td>
                                <td class="p-4 text-sm font-semibold text-gray-800">{{ $mov->account->bank_name ?? 'N/A' }}</td>
                                <td class="p-4 text-sm text-gray-900">
                                    {{ $mov->description }}
                                    <div class="text-xs text-gray-400 mt-0.5">Ref: {{ $mov->reference ?: 'Sin Ref' }}</div>
                                </td>
                                <td class="p-4 text-sm font-bold text-right {{ $mov->type === 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $mov->type === 'ingreso' ? '+' : '-' }} S/ {{ number_format($mov->amount, 2) }}
                                </td>
                                <td class="p-4 text-center">
                                    @if($mov->type === 'ingreso')
                                    <a href="{{ route('collections.index', ['search' => $mov->reference]) }}" class="inline-flex items-center px-3 py-1.5 border border-indigo-200 text-xs font-bold rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                        Buscar Deudor
                                    </a>
                                    @else
                                    <button disabled class="inline-flex items-center px-3 py-1.5 border border-gray-200 text-xs font-bold rounded-lg text-gray-400 bg-gray-50 cursor-not-allowed">
                                        Asignar Compra
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-500">No hay movimientos bancarios sin conciliar. Todo parece estar al día.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100">
                    {{ $unreconciledMovements->appends(['tab' => 'movements', 'invoices_page' => $pendingInvoices->currentPage()])->links() }}
                </div>
            </div>

            <!-- Tab 2: Pending Invoices -->
            <div x-show="tab === 'invoices'" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-cloak>
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-indigo-900">Facturas y Boletas Emitidas por Cobrar</h3>
                    <p class="text-sm text-gray-500 mt-1">Selecciona una factura de esta lista para buscar qué movimiento bancario (depósito) le corresponde.</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Documento</th>
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Paciente / Doctor</th>
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Vencimiento</th>
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Saldo Deudor</th>
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Enlazar con Banco</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pendingInvoices as $inv)
                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                <td class="p-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $inv->invoice_type }} {{ $inv->series_number ?? $inv->invoice_number }}</div>
                                    <div class="text-xs text-gray-500">{{ $inv->status }}</div>
                                </td>
                                <td class="p-4 text-sm font-semibold text-gray-800">
                                    {{ $inv->patient->name ?? 'Desconocido' }}
                                </td>
                                <td class="p-4 text-sm text-gray-600">
                                    <span class="{{ $inv->accountReceivable && $inv->accountReceivable->due_date && $inv->accountReceivable->due_date->isPast() ? 'text-red-600 font-bold' : '' }}">
                                        {{ $inv->accountReceivable && $inv->accountReceivable->due_date ? $inv->accountReceivable->due_date->format('d/m/Y') : 'N/A' }}
                                    </span>
                                </td>
                                <td class="p-4 text-sm text-right font-extrabold text-indigo-700">
                                    S/ {{ number_format($inv->patient->accountReceivables()->where('invoice_id', $inv->id)->sum('balance'), 2) }}
                                </td>
                                <td class="p-4 text-center">
                                    <a href="{{ route('collections.show', $inv->patient_id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition">
                                        Registrar Abono
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-500">No hay facturas pendientes de cobro en el sistema.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100">
                    {{ $pendingInvoices->appends(['tab' => 'invoices', 'movements_page' => $unreconciledMovements->currentPage()])->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
