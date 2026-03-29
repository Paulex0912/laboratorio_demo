<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Directorio de Personal</h2>
                        <a href="{{ route('admin.employees.create') }}" class="px-4 py-2 bg-[#6B46C1] text-white rounded-lg hover:bg-indigo-700 transition">
                            + Registrar Empleado
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
                                    <th scope="col" class="px-6 py-3">Empleado</th>
                                    <th scope="col" class="px-6 py-3">Documento (DNI)</th>
                                    <th scope="col" class="px-6 py-3">Cargo</th>
                                    <th scope="col" class="px-6 py-3">Área</th>
                                    <th scope="col" class="px-6 py-3">Ingreso</th>
                                    <th scope="col" class="px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold mr-3">
                                                    {{ substr($employee->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $employee->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $employee->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">{{ $employee->dni }}</td>
                                        <td class="px-6 py-4">{{ $employee->position }}</td>
                                        <td class="px-6 py-4">
                                            @if($employee->area)
                                                <span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">{{ $employee->area->name }}</span>
                                            @else
                                                <span class="text-gray-400 italic">No asignada</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $employee->start_date ? $employee->start_date->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Ficha</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No hay empleados registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
