<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Proveedores</h2>
                        <a href="{{ route('admin.suppliers.create') }}" class="px-4 py-2 bg-[#6B46C1] text-white rounded-lg hover:bg-indigo-700 transition">
                            + Nuevo Proveedor
                        </a>
                    </div>
                    
                    @if(session('success'))
                        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">RUC</th>
                                    <th scope="col" class="px-6 py-3">Razón Social</th>
                                    <th scope="col" class="px-6 py-3">Contacto</th>
                                    <th scope="col" class="px-6 py-3">Días Crédito</th>
                                    <th scope="col" class="px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $supplier)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $supplier->ruc }}</td>
                                        <td class="px-6 py-4">{{ $supplier->business_name }}</td>
                                        <td class="px-6 py-4">
                                            {{ $supplier->contact_name }}<br>
                                            <span class="text-xs text-gray-400">{{ $supplier->phone }}</span>
                                        </td>
                                        <td class="px-6 py-4">{{ $supplier->payment_term_days }} días</td>
                                        <td class="px-6 py-4 text-right">
                                            <!-- Additional actions can be added here -->
                                            <span class="text-gray-400 italic text-xs">Editar (Próx)</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No se han registrado proveedores en el sistema.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
