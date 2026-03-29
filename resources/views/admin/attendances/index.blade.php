<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Control de Asistencia</h2>
                            <p class="text-sm text-gray-500 mt-1">Registro diario de entradas y salidas del personal.</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <!-- Temporary button until PWA / Terminal is in place -->
                            <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition shadow-sm font-medium">
                                Exportar Reporte
                            </button>
                        </div>
                    </div>
                    
                    <!-- Filters section -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-100 flex gap-4">
                        <div class="w-1/3">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Fecha</label>
                            <input type="date" value="{{ date('Y-m-d') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div class="w-1/3">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Empleado</label>
                            <input type="text" placeholder="Buscar por nombre..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div class="w-1/3 flex items-end">
                            <button class="w-full px-4 py-2 bg-[#6B46C1] text-white rounded-md hover:bg-indigo-700 transition shadow-sm pb-2">
                                Filtrar
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Fecha</th>
                                    <th scope="col" class="px-6 py-3">Empleado</th>
                                    <th scope="col" class="px-6 py-3 text-center">Hora Ingreso</th>
                                    <th scope="col" class="px-6 py-3 text-center">Hora Salida</th>
                                    <th scope="col" class="px-6 py-3 text-center">Tardanza</th>
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $attendance->date ? $attendance->date->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 font-medium text-indigo-900">{{ $attendance->employee->name }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 py-1 bg-green-50 text-green-700 rounded font-medium border border-green-200">
                                                {{ $attendance->check_in ? $attendance->check_in->format('H:i') : '--:--' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($attendance->check_out)
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded font-medium border border-gray-200">
                                                    {{ $attendance->check_out->format('H:i') }}
                                                </span>
                                            @else
                                                <span class="text-orange-500 text-xs font-semibold">En turno</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($attendance->tardiness_minutes > 0)
                                                <span class="text-red-500 font-semibold">{{ $attendance->tardiness_minutes }} min</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 flex items-center">
                                            @if($attendance->type == 'normal')
                                                <div class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></div> Normal
                                            @elseif($attendance->type == 'falta')
                                                <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div> Faltó
                                            @else
                                                <div class="h-2.5 w-2.5 rounded-full bg-yellow-500 mr-2"></div> Justificado
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-500">No hay registros de asistencia para mostrar.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
