<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Comprobantes y Facturación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Bandeja de Facturas</h3>
                    <p class="text-sm text-gray-500">Historial de comprobantes emitidos y estado de pagos.</p>
                </div>
                <div>
                    <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Emitir Factura
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Comprobante</th>
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente / Paciente</th>
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fechas</th>
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Importe</th>
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($invoices as $invoice)
                            <tr class="hover:bg-slate-50 transition-colors {{ $invoice->status === 'anulada' ? 'opacity-60' : '' }}">
                                <td class="p-4">
                                    <div class="text-sm font-bold text-gray-900 font-mono flex items-center gap-2">
                                        {{ $invoice->invoice_type }} {{ $invoice->series_number }}
                                        @if($invoice->status === 'anulada')
                                            <span class="text-xs text-red-600 line-through">Anulada</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $invoice->patient->name ?? 'N/A' }}</div>
                                </td>
                                <td class="p-4 text-xs text-gray-600">
                                    <div><span class="text-gray-400">Emisión:</span> {{ $invoice->issue_date ? $invoice->issue_date->format('d/m/Y') : 'N/A' }}</div>
                                    @if($invoice->accountReceivable)
                                    <div class="mt-1 font-medium {{ $invoice->accountReceivable->due_date < now() && !in_array($invoice->status, ['pagada', 'anulada']) ? 'text-red-500' : '' }}">
                                        <span class="text-gray-400">Vence:</span> {{ $invoice->accountReceivable->due_date ? $invoice->accountReceivable->due_date->format('d/m/Y') : 'N/A' }}
                                    </div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-bold text-gray-900">S/ {{ number_format($invoice->total, 2) }}</div>
                                    <div class="text-xs text-gray-500 mt-1">S/ {{ number_format($invoice->subtotal, 2) }} subtotal</div>
                                </td>
                                <td class="p-4">
                                    @php
                                        $stateColors = [
                                            'pendiente' => 'bg-amber-100 text-amber-800',
                                            'parcial' => 'bg-blue-100 text-blue-800',
                                            'pagada' => 'bg-green-100 text-green-800',
                                            'anulada' => 'bg-gray-100 text-gray-800',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $stateColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ strtoupper($invoice->status) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium inline-block py-1 px-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">Ver Detalles &rarr;</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-gray-500">
                                    No hay facturas registradas en el sistema. <a href="{{ route('invoices.create') }}" class="text-indigo-600 font-semibold hover:underline">Crear primera factura</a>.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($invoices->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>
            
        </div>
    </div>
</x-app-layout>
