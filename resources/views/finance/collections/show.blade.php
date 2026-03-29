<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between items-center">
            {{ __('Estado de Cuenta: ') }} {{ $patient->name }}
            <a href="{{ route('collections.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-xl shadow-sm text-sm transition-colors">
                Volver a Deudores
            </a>
        </h2>
    </x-slot>

    <div class="py-12" x-data="collectionForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Columna Izquierda: Detalle de Deuda Activa -->
            <div class="lg:col-span-2 space-y-6">
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Facturas y Cuentas por Cobrar Pendientes
                    </h3>

                    @if($receivables->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                                    <th class="p-3">Referencia Documento</th>
                                    <th class="p-3">Facturado / Creado</th>
                                    <th class="p-3">Vencimiento / Atraso</th>
                                    <th class="p-3 text-right">Saldo Deudor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($receivables as $idx => $rec)
                                <tr class="{{ $idx === 0 ? 'bg-indigo-50/50' : 'hover:bg-gray-50' }} transition-colors">
                                    <td class="p-3">
                                        <div class="text-sm font-semibold text-gray-800">
                                            @if($rec->invoice_id)
                                                {{ $rec->invoice->invoice_type }} {{ $rec->invoice->invoice_number }}
                                            @else
                                                CxC-{{ str_pad($rec->id, 5, '0', STR_PAD_LEFT) }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $rec->invoice ? 'Orden OT-' . str_pad($rec->invoice->work_order_id, 4, '0', STR_PAD_LEFT) : 'Generado Manualmente' }}
                                        </div>
                                    </td>
                                    <td class="p-3 text-sm text-gray-600">
                                        {{ $rec->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="p-3 text-sm text-gray-600">
                                        <div class="flex items-center space-x-2">
                                            <span class="{{ $rec->due_date && $rec->due_date->isPast() ? 'text-red-600 font-bold' : '' }}">
                                                {{ $rec->due_date ? $rec->due_date->format('d/m/Y') : 'Sin Vencimiento' }}
                                            </span>
                                            @if($rec->due_date)
                                                @php
                                                    $daysDelayed = now()->startOfDay()->diffInDays($rec->due_date->startOfDay(), false);
                                                @endphp
                                                @if($daysDelayed < 0)
                                                    <span class="px-2 py-0.5 text-xs bg-red-100 text-red-800 rounded-full font-bold">
                                                        -{{ abs($daysDelayed) }} días
                                                    </span>
                                                @elseif($daysDelayed == 0)
                                                    <span class="px-2 py-0.5 text-xs bg-amber-100 text-amber-800 rounded-full font-bold">Hoy</span>
                                                @else
                                                    <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full">
                                                        +{{ $daysDelayed }} días
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-3 text-right">
                                        <div class="text-sm font-bold text-gray-900">S/ {{ number_format($rec->balance, 2) }}</div>
                                        @if($idx === 0)
                                            <span class="inline-block mt-1 px-2 py-0.5 text-xs bg-indigo-100 text-indigo-800 rounded-full font-medium">Próximo a cobrar</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50 border-t-2 border-gray-200">
                                    <td colspan="3" class="p-3 text-right text-sm font-bold text-gray-700 uppercase">Deuda Total Consolidada:</td>
                                    <td class="p-3 text-right text-lg font-extrabold text-red-600">S/ {{ number_format($totalDebt, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="py-8 text-center text-gray-500">
                        Este paciente ha cancelado todo su saldo deudor.
                    </div>
                    @endif
                </div>

                <!-- Historial Reciente de Pagos -->
                @if($paymentHistory->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-700 mb-4 border-b pb-2 uppercase tracking-wide">
                        Últimos Abonos Registrados
                    </h3>
                    <div class="space-y-3">
                        @foreach($paymentHistory as $hist)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div>
                                <span class="block text-sm font-semibold text-gray-800">{{ $hist->receipt_number }}</span>
                                <span class="block text-xs text-gray-500">{{ $hist->payment_date->format('d/m/Y H:i A') }} • Vía {{ $hist->payment_method }}</span>
                            </div>
                            <span class="text-sm font-bold text-green-600">+ S/ {{ number_format($hist->amount, 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Columna Derecha: Tarjeta de Aplicación de Abono -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl shadow-indigo-100/50 border border-indigo-50 p-6 sticky top-6">
                    <h3 class="text-lg font-extrabold text-indigo-900 mb-2">Aplicar Nuevo Abono</h3>
                    <p class="text-xs text-gray-500 mb-6">El monto se descontará automáticamente de la factura más antigua (Sistema FIFO).</p>

                    @if($totalDebt > 0)
                    <form action="{{ route('collections.store', $patient) }}" method="POST">
                        @csrf
                        
                        <div class="space-y-5">
                            <div>
                                <label for="amount_paid" class="block text-sm font-medium text-gray-700">Monto a Abonar (S/)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">S/</span>
                                    </div>
                                    <input type="number" id="amount_paid" name="amount_paid" x-model.number="amountPaid" step="0.01" min="0.01" max="{{ $totalDebt }}" class="pl-8 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md font-bold text-lg" required>
                                </div>
                                <div class="mt-2 text-xs flex justify-between" x-show="newBalance !== null" x-cloak>
                                    <span class="text-gray-500">Nuevo Saldo Proyectado:</span>
                                    <span class="font-bold" :class="newBalance <= 0 ? 'text-green-600' : 'text-amber-600'" x-text="'S/ ' + newBalance.toFixed(2)"></span>
                                </div>
                            </div>

                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Método de Ingreso</label>
                                <select id="payment_method" name="payment_method" x-model="method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Transferencia">Transferencia Bancaria</option>
                                    <option value="Tarjeta">Tarjeta (POS)</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                            </div>

                            <div x-show="method === 'Transferencia'" x-cloak class="bg-indigo-50 p-3 rounded-lg">
                                <label for="bank_account_id" class="block text-xs font-semibold text-indigo-800 mb-1">Cta. de Destino (Banco)</label>
                                <select id="bank_account_id" name="bank_account_id" :required="method === 'Transferencia'" class="block w-full rounded-md border-indigo-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                                    <option value="">Seleccione cuenta receptora...</option>
                                    @foreach($banks ?? [] as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->bank_name }} ({{ $bank->account_number }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="method !== 'Efectivo'" x-cloak>
                                <label for="reference_number" class="block text-sm font-medium text-gray-700">Voucher / Referencia</label>
                                <input type="text" id="reference_number" name="reference_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Opcional">
                            </div>

                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm leading-5 font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Registrar Abono
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="rounded-xl bg-green-50 p-4 border border-green-100 flex items-center justify-center">
                        <span class="text-sm font-bold text-green-700">✓ Al Día</span>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        function collectionForm() {
            return {
                totalDebt: {{ $totalDebt }},
                amountPaid: {{ $totalDebt }},
                method: 'Efectivo',
                
                get newBalance() {
                    let b = this.totalDebt - (this.amountPaid || 0);
                    return b < 0 ? 0 : b;
                }
            }
        }
    </script>
</x-app-layout>
