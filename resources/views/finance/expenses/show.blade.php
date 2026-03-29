<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('expenses.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Rendición: {{ $report->title }}
                </h2>
                @php
                    $badgeClasses = [
                        'borrador' => 'bg-gray-100 text-gray-700 border-gray-200',
                        'enviada' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'aprobada' => 'bg-green-100 text-green-700 border-green-200',
                        'rechazada' => 'bg-red-100 text-red-700 border-red-200',
                        'liquidada' => 'bg-purple-100 text-purple-700 border-purple-200',
                    ];
                    $class = $badgeClasses[$report->status] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $class }}">
                    {{ strtoupper($report->status) }}
                </span>
            </div>
            
            <div class="text-right">
                <div class="text-sm font-medium text-gray-500">Monto Total Solicitado</div>
                <div class="text-2xl font-black text-gray-900">S/ {{ number_format($report->total, 2) }}</div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Resumen y Metadatos -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Empleado Emisor</div>
                        <div class="text-sm font-medium text-gray-900">{{ $report->user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $report->user->email }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha de Creación</div>
                        <div class="text-sm font-medium text-gray-900">{{ $report->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $report->created_at->format('H:i:s a') }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Items Adjuntos</div>
                        <div class="text-sm font-medium text-gray-900">{{ $report->lines->count() }} comprobantes</div>
                    </div>
                    @if($report->status !== 'borrador' && $report->status !== 'enviada')
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Estado Revisión</div>
                        <div class="text-sm font-medium text-gray-900">Por: {{ $report->approver->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $report->approved_at ? $report->approved_at->format('d/m/Y H:i a') : 'Fecha no registrada' }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Detalle de Líneas de Gasto -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800">Desglose de Gastos</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="p-4 font-semibold text-gray-600 text-sm w-16">N°</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm">Categoría</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm">Descripción del Ítem</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm text-right">Monto</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm text-center">Comprobante (Archivo)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($report->lines as $index => $line)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-4 text-sm font-medium text-gray-500 text-center">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-md">
                                            {{ $line->category->name }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-sm text-gray-800 font-medium">
                                        {{ $line->description }}
                                    </td>
                                    <td class="p-4 text-sm font-bold text-gray-900 text-right">
                                        S/ {{ number_format($line->amount, 2) }}
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($line->receipt_path)
                                            <a href="{{ Storage::url($line->receipt_path) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-xs font-semibold rounded transition-colors">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                Ver Ticket
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Sin adjunto</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <!-- Fila de Total -->
                            <tr class="bg-gray-50 border-t-2 border-gray-200">
                                <td colspan="3" class="p-4 text-right font-bold text-gray-700">Total a Reembolsar / Justificar:</td>
                                <td class="p-4 text-right font-black text-indigo-700 text-lg">S/ {{ number_format($report->total, 2) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Acciones de Aprobación (Solo Admin o Tesorero) -->
            @hasanyrole('Admin|Tesorero')
                @if($report->status === 'enviada')
                <div class="mt-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-100" x-data="{ showRejectModal: false }">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Revisión de Gastos</h3>
                    <p class="text-sm text-gray-500 mb-6">Como revisor financiero, verifica que los comprobantes adjuntos justifiquen el monto total de la solicitud antes de aprobar o rechazar la rendición de {{ $report->user->name }}.</p>
                    
                    <div class="flex flex-wrap gap-4">
                        <!-- Aprobar Form -->
                        <form action="{{ route('expenses.approve', $report) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('¿Estás seguro de APROBAR esta rendición? Se notificará al usuario.');" class="inline-flex items-center px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow-sm transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Aprobar Rendición
                            </button>
                        </form>

                        <!-- Botón para mostrar rechazo -->
                        <button type="button" @click="showRejectModal = true" class="inline-flex items-center px-6 py-2.5 bg-white border-2 border-red-200 text-red-600 hover:bg-red-50 font-semibold rounded-xl transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Rechazar Rendición...
                        </button>
                    </div>

                    <!-- Modal de Rechazo Modal -->
                    <div x-show="showRejectModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="showRejectModal" @click="showRejectModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            
                            <div x-show="showRejectModal" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <form action="{{ route('expenses.reject', $report) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            </div>
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Motivo de Rechazo</h3>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500 mb-3">Escribe el motivo por el cual se rechaza esta rendición. El empleado recibirá un correo detallando la razón para que pueda subsanarla.</p>
                                                    <textarea name="rejection_reason" required rows="3" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 text-sm p-3" placeholder="Ej: El comprobante N° 2 es ilegible / Monto no coincide con política de viáticos."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Confirmar Rechazo
                                        </button>
                                        <button type="button" @click="showRejectModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                @if($report->status === 'aprobada')
                <div class="mt-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-100" x-data="{ showLiquidateModal: false }">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Liquidación de Fondos</h3>
                            <p class="text-sm text-gray-500">Esta rendición ya fue aprobada. Selecciona la cuenta bancaria o caja general desde la cual saldrá el dinero hacia {{ $report->user->name }}.</p>
                        </div>
                        <button type="button" @click="showLiquidateModal = true" class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-sm transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Liquidar S/ {{ number_format($report->total, 2) }}
                        </button>
                    </div>

                    <!-- Modal de Liquidación -->
                    <div x-show="showLiquidateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="showLiquidateModal" @click="showLiquidateModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                            
                            <div x-show="showLiquidateModal" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                                <form action="{{ route('expenses.liquidate', $report) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="text-center sm:text-left">
                                            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">Origen de los Fondos</h3>
                                            
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Salida</label>
                                                    <select name="payment_method" required class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm">
                                                        <option value="caja">Caja General (Efectivo)</option>
                                                        <option value="banco">Transferencia Bancaria</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="bg-purple-50 p-4 border border-purple-100 rounded-lg">
                                                    <div class="flex justify-between items-center text-sm">
                                                        <span class="font-semibold text-purple-900">Monto a Descontar:</span>
                                                        <span class="font-black text-purple-700 text-lg">S/ {{ number_format($report->total, 2) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Confirmar Liquidación
                                        </button>
                                        <button type="button" @click="showLiquidateModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endhasanyrole

        </div>
    </div>
</x-app-layout>
