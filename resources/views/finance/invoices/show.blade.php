<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('invoices.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Comprobante {{ $invoice->series }}-{{ $invoice->number }}
            </h2>
            @php
                $stateColors = [
                    'pendiente' => 'bg-amber-100 text-amber-800',
                    'parcial' => 'bg-blue-100 text-blue-800',
                    'pagada' => 'bg-green-100 text-green-800',
                    'anulada' => 'bg-gray-100 text-gray-800',
                ];
            @endphp
            <span class="px-3 py-1 text-sm font-bold rounded-full {{ $stateColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                {{ strtoupper($invoice->status) }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($invoice->status === 'anulada')
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">Factura Anulada</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p><strong>Motivo:</strong> {{ $invoice->cancellation_reason }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Columna Izquierda: Datos del Comprobante -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-4 mb-4">Información General</h3>
                        <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                            <div>
                                <span class="text-gray-500 block mb-1">Paciente</span>
                                <span class="font-medium text-gray-900">{{ $invoice->patient->name }} (DNI: {{ $invoice->patient->dni ?? 'N/A' }})</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block mb-1">Orden de Trabajo (Relacionada)</span>
                                <span class="font-medium text-gray-900">{{ $invoice->workOrder ? 'OT-' . str_pad($invoice->workOrder->id, 5, '0', STR_PAD_LEFT) : 'Venta Libre' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block mb-1">Fecha Emisión</span>
                                <span class="font-medium text-gray-900">{{ $invoice->date_issued->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block mb-1">Fecha Vencimiento</span>
                                <span class="font-medium {{ $invoice->due_date < now() && !in_array($invoice->status, ['pagada', 'anulada']) ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                    {{ $invoice->due_date->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-8 border-t border-gray-100 pt-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Desglose (PEN)</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Subtotal Operaciones Gravadas</span>
                                    <span class="font-mono text-gray-700">S/ {{ number_format($invoice->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">IGV (18%)</span>
                                    <span class="font-mono text-gray-700">S/ {{ number_format($invoice->igv, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center border-t border-dashed pt-3 mt-3">
                                    <span class="text-base font-bold text-gray-900">Importe Total</span>
                                    <span class="text-lg font-bold text-indigo-700 font-mono">S/ {{ number_format($invoice->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción Múltiple -->
                    @if($invoice->status !== 'anulada')
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex gap-4">
                            <button onclick="window.print()" class="flex-1 bg-white border border-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-xl shadow-sm hover:bg-gray-50 transition-colors inline-flex justify-center items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Imprimir Comprobante
                            </button>
                            
                            <!-- Botón Peligro: Anular (US-16) -->
                            <div x-data="{ openAnular: false }" class="flex-1">
                                <button @click="openAnular = true" class="w-full bg-red-50 border border-red-200 text-red-600 font-semibold py-2 px-4 rounded-xl hover:bg-red-100 transition-colors inline-flex justify-center items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Anular Factura
                                </button>

                                <!-- Modal de Anulación -->
                                <div x-show="openAnular" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="openAnular" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        <div x-show="openAnular" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                            <form action="{{ route('invoices.updateStatus', $invoice) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="anulada">
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <div class="sm:flex sm:items-start">
                                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                            </svg>
                                                        </div>
                                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Confirmar Anulación</h3>
                                                            <div class="mt-4">
                                                                <label class="block text-sm font-medium text-gray-700 mb-2">Motivo Físico o Justificación *</label>
                                                                <textarea name="cancellation_reason" required rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" placeholder="Ej: Error de digitación en RUC/DNI, paciente desistió..."></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                        Sí, Anular Definitivamente
                                                    </button>
                                                    <button @click="openAnular = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                        Cancelar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Columna Derecha: Control de Pagos (Cobranza) -->
                <div class="space-y-6">
                    <div class="bg-gradient-to-b from-indigo-900 to-indigo-800 rounded-2xl shadow-lg p-6 text-white text-center border-t-4 border-indigo-400">
                        <h4 class="text-indigo-200 text-xs font-semibold uppercase tracking-widest mb-1">Saldo Pendiente</h4>
                        <div class="text-4xl font-black font-mono tracking-tight text-white mb-2">
                            S/ {{ number_format($balance, 2) }}
                        </div>
                        <div class="text-sm text-indigo-200">
                            Abonado: S/ {{ number_format($totalPaid, 2) }}
                        </div>
                    </div>

                    @if($balance > 0 && $invoice->status !== 'anulada')
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h4 class="font-bold text-gray-900 border-b pb-3 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Registrar Cobro (Abono)
                            </h4>
                            <form action="{{ route('invoices.storePayment', $invoice) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Monto a abonar (Max S/ {{ $balance }})</label>
                                    <input type="number" name="amount" step="0.01" max="{{ $balance }}" value="{{ $balance }}" class="w-full text-sm font-mono border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Medio Pago</label>
                                        <select name="payment_method" class="w-full text-sm border-gray-300 rounded-md" required>
                                            <option value="Efectivo">Efectivo (Caja)</option>
                                            <option value="Yape/Plin">Yape/Plin</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Tarjeta">Tarjeta POS</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Fecha</label>
                                        <input type="date" name="paid_at" value="{{ date('Y-m-d') }}" class="w-full text-sm border-gray-300 rounded-md" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Operación / Ref. (Opcional)</label>
                                    <input type="text" name="reference" class="w-full text-sm border-gray-300 rounded-md placeholder-gray-300" placeholder="Ej: OP 1928374">
                                </div>
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl transition-colors shadow-sm text-sm">
                                    Registrar Abono
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-hidden">
                        <h4 class="font-bold text-gray-900 mb-4 text-sm uppercase tracking-wide">Historial de Pagos</h4>
                        @if($invoice->payments->count() > 0)
                            <div class="space-y-3 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent">
                                @foreach($invoice->payments as $payment)
                                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-green-100 text-green-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-gray-900 text-sm">S/ {{ number_format($payment->amount, 2) }}</span>
                                                <span class="text-xs text-gray-500 font-medium">{{ $payment->payment_method }}</span>
                                                <span class="text-xs text-gray-400 mt-1">{{ $payment->paid_at->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-4 bg-gray-50 rounded-lg">No hay cobros registrados.</p>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
