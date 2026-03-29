<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('invoices.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Emitir Nueva Factura') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <form action="{{ route('invoices.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <!-- Fila 1: Paciente y Fechas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="patient_id" :value="__('Paciente *')" />
                            <select id="patient_id" name="patient_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Seleccione un paciente</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->name }} - {{ $patient->dni ?? 'Sin DNI' }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('patient_id')" />
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="date_issued" :value="__('Fecha Emisión *')" />
                                <x-text-input id="date_issued" name="date_issued" type="date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full text-sm" required />
                                <x-input-error class="mt-2" :messages="$errors->get('date_issued')" />
                            </div>
                            <div>
                                <x-input-label for="due_date" :value="__('Vencimiento *')" />
                                <x-text-input id="due_date" name="due_date" type="date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full text-sm" required />
                                <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
                            </div>
                        </div>
                    </div>

                    <!-- Fila 2: Correlativo Físico -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div>
                            <x-input-label for="series" :value="__('Serie Comprobante *')" />
                            <select id="series" name="series" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono" required>
                                <option value="F001" {{ old('series', 'F001') == 'F001' ? 'selected' : '' }}>Factura Electrónica (F001)</option>
                                <option value="B001" {{ old('series') == 'B001' ? 'selected' : '' }}>Boleta de Venta (B001)</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('series')" />
                        </div>
                        <div>
                            <x-input-label for="number" :value="__('Número Correlativo *')" />
                            <x-text-input id="number" name="number" type="text" value="{{ old('number', $nextNumber) }}" class="mt-1 block w-full font-mono text-gray-500 bg-gray-100" readonly required />
                            <x-input-error class="mt-2" :messages="$errors->get('number')" />
                        </div>
                    </div>

                    <!-- Fila 3: Importes Matemáticos (Alpine.js) -->
                    <div x-data="invoiceCalculator({{ old('subtotal', 0) }})" x-init="calculateTotal()" class="mt-8 border-t border-gray-100 pt-8">
                        <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-widest mb-6 border-b pb-2">Desglose Financiero (PEN)</h3>
                        
                        <div class="flex flex-col items-end space-y-4">
                            <div class="w-full md:w-1/2 flex items-center justify-between">
                                <span class="text-gray-600 font-medium tracking-wide">SUBTOTAL (S/)</span>
                                <div class="w-48">
                                    <x-text-input id="subtotal" name="subtotal" type="number" step="0.01" min="0.01" x-model.number="subtotal" @input="calculateTotal()" class="block w-full text-right font-mono" placeholder="0.00" required />
                                </div>
                            </div>
                            <x-input-error class="mt-2 w-full md:w-1/2 text-right" :messages="$errors->get('subtotal')" />
                            
                            <div class="w-full md:w-1/2 flex items-center justify-between">
                                <span class="text-gray-400 font-medium tracking-wide">IGV (18%)</span>
                                <div class="w-48 px-3 py-2 text-right font-mono text-gray-500 bg-gray-50 rounded-md border border-transparent" x-text="'S/ ' + igv.toFixed(2)"></div>
                            </div>
                            
                            <div class="w-full md:w-1/2 flex items-center justify-between border-t border-dashed border-gray-300 pt-4 mt-2">
                                <span class="text-gray-900 font-bold text-lg tracking-wide">TOTAL A FACTURAR</span>
                                <div class="w-48 px-3 py-2 text-right font-mono font-bold text-xl text-indigo-700 bg-indigo-50 rounded-md border border-indigo-100" x-text="'S/ ' + total.toFixed(2)"></div>
                            </div>
                        </div>

                    </div>

                    <div class="pt-6 mt-6 border-t border-gray-100 flex justify-end">
                        <x-primary-button class="py-3 px-8 shadow-md">
                            {{ __('Emitir y Guardar Factura') }}
                        </x-primary-button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <script>
        function invoiceCalculator(initialSubtotal) {
            return {
                subtotal: initialSubtotal || 0.00,
                igv: 0.00,
                total: 0.00,
                calculateTotal() {
                    const sub = parseFloat(this.subtotal) || 0;
                    this.igv = sub * 0.18; // 18% para Perú SUNAT
                    this.total = sub + this.igv;
                }
            }
        }
    </script>
</x-app-layout>
