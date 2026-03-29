<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Auditoría del Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Registro de Eventos Globales</h3>
                        <p class="text-sm text-gray-500">Historial completo de acciones y mutaciones de datos (Invoices, Cheques, Gastos, etc.)</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Fecha y Hora</th>
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Usuario (IP)</th>
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Acción</th>
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Entidad Afectada</th>
                                    <th class="p-4 font-semibold text-gray-600 text-sm text-center">Data Modificada</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="p-4 text-sm text-gray-600">
                                            <div class="font-medium">{{ $log->created_at->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s a') }}</div>
                                        </td>
                                        <td class="p-4 text-sm">
                                            <div class="font-medium text-gray-800">{{ $log->user->name ?? 'Sistema' }}</div>
                                            <div class="text-xs text-gray-400">{{ $log->ip_address }}</div>
                                        </td>
                                        <td class="p-4 text-sm font-medium">
                                            @php
                                                $actionColors = [
                                                    'created' => 'bg-green-100 text-green-700',
                                                    'updated' => 'bg-blue-100 text-blue-700',
                                                    'deleted' => 'bg-red-100 text-red-700',
                                                    'restored' => 'bg-purple-100 text-purple-700',
                                                ];
                                                $color = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-700';
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-bold rounded-md {{ $color }}">
                                                {{ strtoupper($log->action) }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-sm">
                                            <div class="font-medium text-gray-700">{{ class_basename($log->model_type) }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $log->model_id }}</div>
                                        </td>
                                        <td class="p-4 text-center">
                                            <div x-data="{ showData: false }" class="relative">
                                                <button @click="showData = true" class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold underline">
                                                    Ver Cambios JSON
                                                </button>
                                                
                                                <!-- Simple Modal -->
                                                <div x-show="showData" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto w-full">
                                                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                                                        <div x-show="showData" @click="showData = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                                                        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Detalle de Cambios</h3>
                                                                <div class="grid grid-cols-2 gap-4 text-left">
                                                                    <div class="bg-red-50 p-4 rounded-lg">
                                                                        <h4 class="font-bold text-red-800 text-xs uppercase mb-2">Valores Anteriores (Orignal)</h4>
                                                                        <pre class="text-xs text-red-600 whitespace-pre-wrap overflow-x-auto">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                                                                    </div>
                                                                    <div class="bg-green-50 p-4 rounded-lg">
                                                                        <h4 class="font-bold text-green-800 text-xs uppercase mb-2">Valores Nuevos (Dirty)</h4>
                                                                        <pre class="text-xs text-green-600 whitespace-pre-wrap overflow-x-auto">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                                <button type="button" @click="showData = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:w-auto sm:text-sm">
                                                                    Cerrar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-gray-500 font-medium">No hay eventos registrados en la auditoría.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $logs->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
