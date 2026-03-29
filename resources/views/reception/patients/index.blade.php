<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Doctores/Clínicas') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4 border-b pb-4">
                        <h3 class="text-lg font-semibold">Listado de Doctores/Clínicas</h3>
                        <div class="flex space-x-2">
                            <button @click="showModal = true" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Importar Excel</button>
                            <a href="{{ route('patients.export') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Exportar Excel</a>
                            <a href="{{ route('patients.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Registrar Doctor/Clínica</a>
                        </div>
                    </div>

                    <!-- Modal de Importación -->
                    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50" style="display: none;">
                        <div class="bg-white rounded-lg p-6 w-full max-w-md m-auto flex-col flex" @click.away="showModal = false">
                            <h2 class="text-xl font-bold mb-4">Importar Doctores/Clínicas (Excel)</h2>
                            <p class="text-sm text-gray-600 mb-4">Sube un archivo .xlsx con las columnas: nombre, dni, ruc, telefono, email, notas_dentales, observaciones</p>
                            
                            @if($errors->any())
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('patients.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-4"/>
                                <div class="flex justify-end pt-2">
                                    <button type="button" @click="showModal = false" class="px-4 bg-transparent p-3 rounded-lg text-indigo-500 hover:bg-gray-100 hover:text-indigo-400 mr-2">Cerrar</button>
                                    <button type="submit" class="modal-close px-4 bg-indigo-500 p-3 rounded-lg text-white hover:bg-indigo-400">Importar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 text-green-600 bg-green-100 p-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b">
                                <th class="p-3">Nombre</th>
                                <th class="p-3">DNI / RUC</th>
                                <th class="p-3">Teléfono</th>
                                <th class="p-3">Email</th>
                                <th class="p-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patients as $patient)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $patient->name }}</td>
                                <td class="p-3">
                                    {{ $patient->dni ?: '-' }}
                                    @if($patient->ruc)
                                        <br><span class="text-xs text-gray-500">RUC: {{ $patient->ruc }}</span>
                                    @endif
                                </td>
                                <td class="p-3">{{ $patient->phone }}</td>
                                <td class="p-3">{{ $patient->email }}</td>
                                <td class="p-3">
                                    <a href="{{ route('patients.edit', $patient) }}" class="text-blue-500 hover:text-blue-700">Editar</a>
                                    <!-- Aquí iría botón de eliminar (form) -->
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-3 text-center text-gray-500">No hay doctores/clínicas registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $patients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
