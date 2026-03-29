<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Registrar Proveedor</h2>
                        <a href="{{ route('admin.suppliers.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Volver a lista</a>
                    </div>
                    
                    <form action="{{ route('admin.suppliers.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">RUC <span class="text-red-500">*</span></label>
                                <input type="text" name="ruc" value="{{ old('ruc') }}" required maxlength="11"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('ruc') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Razón Social <span class="text-red-500">*</span></label>
                                <input type="text" name="business_name" value="{{ old('business_name') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('business_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Dirección Fiscal</label>
                                <input type="text" name="address" value="{{ old('address') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div class="pt-4 mt-2 border-t border-gray-100 md:col-span-2">
                                <h3 class="text-sm font-medium text-gray-900 mb-4">Información de Contacto o Ventas</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre del Contacto</label>
                                <input type="text" name="contact_name" value="{{ old('contact_name') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Teléfono / Celular</label>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Días de Término de Pago <span class="text-red-500">*</span></label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="payment_term_days" value="{{ old('payment_term_days', 0) }}" required min="0"
                                        class="block w-full rounded-md border-gray-300 pl-3 pr-12 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 sm:text-sm">días</span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Escriba 0 si es pago al contado.</p>
                            </div>
                        </div>

                        <div class="flex justify-end pt-5 border-t border-gray-200">
                            <button type="submit" class="px-6 py-2 bg-[#6B46C1] text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                Guardar Proveedor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
