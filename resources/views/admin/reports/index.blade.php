<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between items-center">
            {{ __('Generador de Reportes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-8 text-gray-900">
                    <div class="max-w-3xl mx-auto">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 text-indigo-600 mb-4">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Exportar Base de Datos</h3>
                            <p class="text-gray-500 mt-2">Deselecciona el rango de fechas si deseas extraer la totalidad histórica del módulo.</p>
                        </div>

                        <form action="{{ route('admin.reports.export') }}" method="POST" class="space-y-6 bg-gray-50 p-6 rounded-2xl border border-gray-200">
                            @csrf
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Módulo de Información</label>
                                <select name="type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="invoices">Módulo de Facturación y Cobranzas</option>
                                    <option value="cash_movements">Movimientos Históricos de Caja</option>
                                    <option value="bank_movements">Movimientos Bancarios (Ingresos y Egresos)</option>
                                    <option value="work_orders">Total de Órdenes Históricas</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha de Inicio (Opcional)</label>
                                    <input type="date" name="start_date" value="{{ now()->subDays(30)->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha de Fin (Opcional)</label>
                                    <input type="date" name="end_date" value="{{ now()->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Formato de Archivo</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300">
                                        <input type="radio" name="format" value="xlsx" class="sr-only" checked>
                                        <span class="flex flex-1">
                                          <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-gray-900">Documento Excel</span>
                                            <span class="mt-1 flex items-center text-sm text-gray-500">Formato .XLSX</span>
                                          </span>
                                        </span>
                                        <svg class="h-5 w-5 text-indigo-600 ml-3" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                        </svg>
                                    </label>
                                    
                                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300 opacity-60">
                                        <input type="radio" name="format" value="csv" class="sr-only">
                                        <span class="flex flex-1">
                                          <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-gray-900">Texto Plano Ligero</span>
                                            <span class="mt-1 flex items-center text-sm text-gray-500">Formato .CSV</span>
                                          </span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="w-full mt-6 bg-[#6B46C1] hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-sm text-lg transition-colors flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Generar Reporte
                            </button>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
