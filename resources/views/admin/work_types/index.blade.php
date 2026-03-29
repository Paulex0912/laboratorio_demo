<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tipos de Trabajo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ showImportModal: false }">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4 border-b pb-4 gap-4">
                        <h3 class="text-lg font-semibold">Listado de Tipos de Trabajo</h3>
                        <div class="flex gap-2">
                            <button @click="showImportModal = true" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Importar Excel
                            </button>
                            <a href="{{ route('work_types.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Añadir Tipo
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 text-green-600 bg-green-100 p-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 text-red-600 bg-red-100 p-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 text-red-600 bg-red-100 p-3 rounded">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b">
                                <th class="p-3">Nombre</th>
                                <th class="p-3">Descripción</th>
                                <th class="p-3">Precio Ref. (S/)</th>
                                <th class="p-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($workTypes as $workType)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-medium">{{ $workType->name }}</td>
                                <td class="p-3 text-sm text-gray-600">{{ Str::limit($workType->description, 50) ?: '-' }}</td>
                                <td class="p-3">{{ number_format($workType->default_price, 2) }}</td>
                                <td class="p-3">
                                    <a href="{{ route('work_types.edit', $workType) }}" class="text-blue-500 hover:text-blue-700 mr-2">Editar</a>
                                    <form action="{{ route('work_types.destroy', $workType) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar este tipo de trabajo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-3 text-center text-gray-500">No hay tipos de trabajo registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $workTypes->links() }}
                    </div>
                </div>
            </div>

            <!-- Modal Importar Trabajos -->
            <div x-show="showImportModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    <div x-show="showImportModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>

                    <div x-show="showImportModal" @click.away="showImportModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Importar Tipos de Trabajo</h3>
                            <button @click="showImportModal = false" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Cerrar</span>
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-4">
                                Seleccione un archivo Excel (.xlsx, .xls) con el formato correcto de columnas (Categoría, Descripción, Importe).
                            </p>
                            <form action="{{ route('work_types.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Archivo Excel</label>
                                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
                                </div>
                                <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                                    <button type="button" @click="showImportModal = false" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700">
                                        Importar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Modal -->

        </div>
    </div>
</x-app-layout>
