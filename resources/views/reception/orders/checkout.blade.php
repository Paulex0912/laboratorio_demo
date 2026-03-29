<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between items-center">
            {{ __('Procesar Entrega y Cobro: Orden #OT-') }}{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
            <a href="{{ route('orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-xl shadow-sm text-sm transition-colors">
                Volver
            </a>
        </h2>
    </x-slot>

    <div class="py-12" x-data="checkoutForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
            
            <!-- Resumen de la Orden (Izquierda) -->
            <div class="w-full md:w-1/3 space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Resumen Clínico</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase">Paciente</span>
                            <span class="block text-sm font-medium text-gray-900">{{ $order->patient->name }}</span>
                            <span class="block text-xs text-gray-500">DNI: {{ $order->patient->dni }}</span>
                        </div>
                        
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase mt-4 mb-2">Trabajos Realizados</span>
                            <ul class="text-sm space-y-2">
                                @foreach($order->items as $item)
                                    <li class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                        <div>
                                            <span class="font-medium text-gray-800">{{ $item->type_name }}</span>
                                            <span class="block text-xs text-gray-500">{{ $item->material ?? 'Estándar' }}</span>
                                        </div>
                                        <span class="font-semibold text-gray-700">S/ {{ number_format($item->price, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Otras Órdenes -->
                @if($otherPendingOrders->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6">
                        <h3 class="text-md font-bold text-gray-900 mb-4 border-b pb-2">Otras Órdenes del Paciente</h3>
                        <p class="text-xs text-gray-500 mb-3">Selecciona para agrupar en la misma factura:</p>
                        <div class="space-y-3">
                            <template x-for="(o, index) in otherOrders" :key="o.id">
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors" :class="o.checked ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200'">
                                    <input type="checkbox" x-model="o.checked" :name="'included_orders[]'" :value="o.id" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded shadow-sm">
                                    <div class="ml-3 flex-1 flex justify-between items-center">
                                        <span class="block text-sm font-medium text-gray-900" x-text="'Orden #OT-' + String(o.id).padStart(4, '0')"></span>
                                        <span class="block text-sm font-bold text-gray-700">S/ <span x-text="o.amount.toFixed(2)"></span></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                @endif

                <!-- Tarjeta de Total (Impacto Visual) -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white text-center">
                    <div class="flex justify-between text-indigo-100 text-sm mb-2" x-show="subtotal !== totalFixed" x-cloak>
                        <span>Subtotal:</span>
                        <span>S/ <span x-text="subtotal.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between text-red-200 text-sm mb-2" x-show="discountPercentage > 0" x-cloak>
                        <span>Descuento (<span x-text="discountPercentage"></span>%):</span>
                        <span>-S/ <span x-text="discountAmount.toFixed(2)"></span></span>
                    </div>
                    <span class="block text-indigo-100 text-sm font-medium mb-1 mt-4">Total a Facturar</span>
                    <span class="block text-4xl font-extrabold tracking-tight">S/ <span x-text="totalFixed.toFixed(2)"></span></span>
                </div>
            </div>

            <!-- Pasarela de Pagos Formulario (Derecha) -->
            <div class="w-full md:w-2/3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Datos de Facturación y Cobro
                    </h3>

                    <form action="{{ route('orders.processCheckout', $order) }}" method="POST">
                        @csrf
                        
                        <!-- Bloque 1: Comprobante -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 border-b border-gray-100 pb-8">
                            <div>
                                <label for="invoice_type" class="block text-sm font-medium text-gray-700">Comprobante *</label>
                                <select id="invoice_type" name="invoice_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50" required>
                                    <option value="Boleta">Boleta</option>
                                    <option value="Factura">Factura</option>
                                    <option value="Recibo">Recibo</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="discount_percentage" class="block text-sm font-medium text-gray-700">Descuento (%)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" id="discount_percentage" name="discount_percentage" x-model.number="discountPercentage" step="0.1" min="0" max="100" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="amount_paid" class="block text-sm font-medium text-gray-700">Abono Hoy (S/) *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">S/</span>
                                    </div>
                                    <input type="number" id="amount_paid" name="amount_paid" x-model.number="amountPaid" step="0.01" min="0" :max="totalFixed" class="pl-8 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md font-bold text-lg text-indigo-700" required>
                                </div>
                                
                                <!-- Calculador de Deuda Dinámico -->
                                <div class="mt-2 text-sm" x-show="balance > 0" x-cloak>
                                    <span class="text-amber-600 font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                        Saldo Deudor: S/ <span x-text="balance.toFixed(2)"></span>
                                    </span>
                                    <p class="text-xs text-gray-500 ml-5 mt-1">Se generará cuenta por cobrar.</p>
                                </div>
                                <div class="mt-2 text-sm" x-show="balance === 0" x-cloak>
                                    <span class="text-green-600 font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Cancelación Total
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Bloque 2: Medio de Pago (Aparece sólo si paga algo) -->
                        <div x-show="amountPaid > 0" x-transition.opacity class="space-y-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Método de Pago</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" x-model="method" name="payment_method" value="Efectivo" class="peer sr-only">
                                        <div class="rounded-xl border border-gray-200 bg-white p-4 text-center hover:bg-gray-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:ring-1 peer-checked:ring-indigo-500 transition-all">
                                            <svg class="mx-auto mb-2 h-6 w-6 text-gray-400 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="text-xs font-semibold text-gray-700">Efectivo</span>
                                        </div>
                                    </label>
                                    
                                    <label class="cursor-pointer">
                                        <input type="radio" x-model="method" name="payment_method" value="Transferencia" class="peer sr-only">
                                        <div class="rounded-xl border border-gray-200 bg-white p-4 text-center hover:bg-gray-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:ring-1 peer-checked:ring-indigo-500 transition-all">
                                            <svg class="mx-auto mb-2 h-6 w-6 text-gray-400 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                                            <span class="text-xs font-semibold text-gray-700">Transferencia</span>
                                        </div>
                                    </label>

                                    <label class="cursor-pointer">
                                        <input type="radio" x-model="method" name="payment_method" value="Tarjeta" class="peer sr-only">
                                        <div class="rounded-xl border border-gray-200 bg-white p-4 text-center hover:bg-gray-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:ring-1 peer-checked:ring-indigo-500 transition-all">
                                            <svg class="mx-auto mb-2 h-6 w-6 text-gray-400 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                            <span class="text-xs font-semibold text-gray-700">Tarjeta (POS)</span>
                                        </div>
                                    </label>
                                    
                                    <label class="cursor-pointer">
                                        <input type="radio" x-model="method" name="payment_method" value="Cheque" class="peer sr-only">
                                        <div class="rounded-xl border border-gray-200 bg-white p-4 text-center hover:bg-gray-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:ring-1 peer-checked:ring-indigo-500 transition-all">
                                            <svg class="mx-auto mb-2 h-6 w-6 text-gray-400 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"></path></svg>
                                            <span class="text-xs font-semibold text-gray-700">Cheque</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Opciones Bancarias Dinámicas -->
                            <div x-show="method === 'Transferencia'" x-cloak class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <label for="bank_account_id" class="block text-sm font-medium text-blue-800 mb-1">Cuenta de Destino (Para conciliación) *</label>
                                <select id="bank_account_id" name="bank_account_id" :required="method === 'Transferencia'" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Seleccione una cuenta del sistema...</option>
                                    @foreach($banks ?? [] as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->bank_name }} - {{ $bank->account_number }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="method !== 'Efectivo'" x-cloak>
                                <label for="reference_number" class="block text-sm font-medium text-gray-700">Nro. de Referencia / Operación</label>
                                <input type="text" id="reference_number" name="reference_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Ej: 99482741">
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="inline-flex justify-center items-center py-3 px-6 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Confirmar Entrega y Facturar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function checkoutForm() {
            return {
                mainOrderAmount: {{ $order->amount }},
                otherOrders: [
                    @foreach($otherPendingOrders as $o)
                    { id: {{ $o->id }}, amount: {{ $o->amount }}, checked: false },
                    @endforeach
                ],
                discountPercentage: 0,
                amountPaid: {{ $order->amount }},
                method: 'Efectivo',
                
                init() {
                    // Si el total cambia, ajustamos el abono sugerido
                    this.$watch('totalFixed', value => {
                        this.amountPaid = value;
                    });
                },

                get subtotal() {
                    let calc = this.mainOrderAmount;
                    this.otherOrders.forEach(o => {
                        if (o.checked) calc += o.amount;
                    });
                    return calc;
                },

                get discountAmount() {
                    return this.subtotal * ((this.discountPercentage || 0) / 100);
                },

                get totalFixed() {
                    let calc = this.subtotal - this.discountAmount;
                    return calc > 0 ? calc : 0;
                },

                get balance() {
                    let calc = this.totalFixed - this.amountPaid;
                    return (calc > 0) ? calc : 0;
                }
            }
        }
    </script>
</x-app-layout>
