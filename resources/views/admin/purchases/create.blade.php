<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Crear Orden de Compra</h2>
                        <a href="{{ route('admin.purchases.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Volver a lista</a>
                    </div>
                    
                    <form action="{{ route('admin.purchases.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-gray-100">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Proveedor <span class="text-red-500">*</span></label>
                                <select name="supplier_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Seleccione un proveedor...</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->business_name }} - {{ $supplier->ruc }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha Estimada de Recepción</label>
                                <input type="date" name="expected_date" value="{{ old('expected_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Notas Adicionales</label>
                                <textarea name="notes" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-600">
                            <strong>Nota:</strong> Esta acción generará una OC en estado "Borrador". Posteriormente desde el detalle podrá añadir productos y confirmarla.
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="px-6 py-2 bg-[#6B46C1] text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                Continuar y Agregar Productos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
