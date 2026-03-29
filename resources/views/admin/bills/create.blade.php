<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($bill) ? 'Editar Compra/Gasto' : 'Registrar Nueva Compra/Gasto' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form 
                        action="{{ isset($bill) ? route('admin.bills.update', $bill) : route('admin.bills.store') }}" 
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($bill))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Left Column -->
                            <div>
                                <h3 class="font-bold text-gray-900 border-b pb-2 mb-4">Datos Principales</h3>
                                
                                <div class="mb-4">
                                    <x-input-label for="supplier_id" :value="__('Proveedor')" />
                                    <select id="supplier_id" name="supplier_id" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">Seleccione un Proveedor</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ (old('supplier_id', $bill->supplier_id ?? '') == $supplier->id) ? 'selected' : '' }}>
                                                {{ $supplier->business_name }} ({{ $supplier->ruc }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="general_category_id" :value="__('Categoría General')" />
                                    <select id="general_category_id" name="general_category_id" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Ninguna</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ (old('general_category_id', $bill->general_category_id ?? '') == $cat->id) ? 'selected' : '' }}>
                                                {{ $cat->type }} - {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('general_category_id')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="purchase_order_id" :value="__('Orden de Compra Relacionada (Opcional)')" />
                                    <select id="purchase_order_id" name="purchase_order_id" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Ninguna</option>
                                        @foreach($purchaseOrders as $po)
                                            <option value="{{ $po->id }}" {{ (old('purchase_order_id', $bill->purchase_order_id ?? '') == $po->id) ? 'selected' : '' }}>
                                                PO-{{ $po->id }} ({{ $po->expected_date ? $po->expected_date->format('d/m/Y') : '' }}) - Total: {{ $po->total }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-indigo-600 mt-1">Si selecciona una Orden Operativa, se ingresarán los productos a almacén automáticamente.</p>
                                    <x-input-error :messages="$errors->get('purchase_order_id')" class="mt-2" />
                                </div>
                                
                                <div class="mb-4">
                                    <x-input-label for="bill_number" :value="__('Número de Factura de Proveedor')" />
                                    <x-text-input id="bill_number" class="block mt-1 w-full" type="text" name="bill_number" :value="old('bill_number', $bill->bill_number ?? '')" required placeholder="F001-00123" />
                                    <x-input-error :messages="$errors->get('bill_number')" class="mt-2" />
                                </div>

                            </div>

                            <!-- Right Column -->
                            <div>
                                <h3 class="font-bold text-gray-900 border-b pb-2 mb-4">Montos y Fechas</h3>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="mb-4">
                                        <x-input-label for="issue_date" :value="__('Fecha Emisión')" />
                                        <x-text-input id="issue_date" class="block mt-1 w-full" type="date" name="issue_date" :value="old('issue_date', isset($bill) ? $bill->issue_date->format('Y-m-d') : date('Y-m-d'))" required />
                                        <x-input-error :messages="$errors->get('issue_date')" class="mt-2" />
                                    </div>

                                    <div class="mb-4">
                                        <x-input-label for="due_date" :value="__('F. Vencimiento')" />
                                        <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date', isset($bill) ? $bill->due_date->format('Y-m-d') : date('Y-m-d'))" required />
                                        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="subtotal" :value="__('Subtotal (Sin Igv)')" />
                                    <x-text-input id="subtotal" class="block mt-1 w-full font-mono font-bold" type="number" step="0.01" name="subtotal" :value="old('subtotal', $bill->subtotal ?? '0.00')" required />
                                    <x-input-error :messages="$errors->get('subtotal')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="tax_amount" :value="__('IGV/IVA')" />
                                    <x-text-input id="tax_amount" class="block mt-1 w-full font-mono font-bold" type="number" step="0.01" name="tax_amount" :value="old('tax_amount', $bill->tax_amount ?? '0.00')" required />
                                    <x-input-error :messages="$errors->get('tax_amount')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="total_amount" :value="__('Monto Total a Pagar')" />
                                    <x-text-input id="total_amount" class="block mt-1 w-full text-indigo-700 text-lg font-bold font-mono bg-indigo-50 border-indigo-200" type="number" step="0.01" name="total_amount" :value="old('total_amount', $bill->total_amount ?? '0.00')" required />
                                    <x-input-error :messages="$errors->get('total_amount')" class="mt-2" />
                                    <p class="text-xs text-gray-400 mt-1">Este monto quedará registrado como saldo pendiente si aún no lo ha pagado.</p>
                                </div>

                                <div class="mb-4 border-t pt-4">
                                    <x-input-label for="invoice_file" :value="__('Adjuntar Factura (Física/PDF/Foto)')" />
                                    <input id="invoice_file" class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" type="file" name="invoice_file" accept=".pdf,image/*" />
                                    @if(isset($bill) && $bill->invoice_file_path)
                                        <p class="text-xs text-gray-500 mt-2">Ya existe un archivo adjunto. Mantenlo vacío para no reemplazar.</p>
                                    @endif
                                    <x-input-error :messages="$errors->get('invoice_file')" class="mt-2" />
                                </div>

                            </div>
                        </div>

                        <div class="mb-6 w-full">
                            <x-input-label for="notes" :value="__('Notas y Observaciones')" />
                            <textarea id="notes" name="notes" rows="2" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $bill->notes ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4 border-t pt-4">
                            <a href="{{ route('admin.bills.index') }}" class="text-sm font-bold text-gray-600 hover:text-gray-900 border px-4 py-2 rounded-lg bg-gray-50">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Guardar y Procesar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
