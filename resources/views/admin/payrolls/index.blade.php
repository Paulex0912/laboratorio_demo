<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Planillas de Remuneraciones</h2>
                            <p class="text-sm text-gray-500 mt-1">Gestión de nóminas mensuales para empleados.</p>
                        </div>
                        <div class="mt-4 md:mt-0 space-x-2 flex">
                            <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition shadow-sm font-medium">
                                Configurar AFP/ONP
                            </button>
                            <button class="px-4 py-2 bg-[#6B46C1] text-white flex items-center rounded-lg hover:bg-indigo-700 transition shadow-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Generar Planilla
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg outline outline-1 outline-gray-200">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-semibold">Periodo</th>
                                    <th scope="col" class="px-6 py-3 font-semibold text-center">Estado</th>
                                    <th scope="col" class="px-6 py-3 font-semibold text-right">Costo Total Salarios (Bruto)</th>
                                    <th scope="col" class="px-6 py-3 font-semibold text-right">Líquido a Pagar (Neto)</th>
                                    <th scope="col" class="px-6 py-3 font-semibold text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payrolls as $payroll)
                                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-bold text-gray-900">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ str_pad($payroll->period_month, 2, '0', STR_PAD_LEFT) }} - {{ $payroll->period_year }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($payroll->status == 'borrador')
                                                <span class="px-2.5 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">Borrador</span>
                                            @elseif($payroll->status == 'aprobado')
                                                <span class="px-2.5 py-1 text-xs font-semibold text-green-800 bg-green-100 border border-green-200 rounded-full">Aprobado</span>
                                            @elseif($payroll->status == 'pagado')
                                                <span class="px-2.5 py-1 text-xs font-semibold text-blue-800 bg-blue-100 border border-blue-200 rounded-full">Pagado</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right text-gray-600">
                                            S/ {{ number_format($payroll->total_gross, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-gray-900">
                                            S/ {{ number_format($payroll->total_net, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="#" class="text-[#6B46C1] hover:text-indigo-900 font-medium">Ver Detalle</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <p class="text-sm font-medium text-gray-900">No hay planillas generadas.</p>
                                                <p class="mt-1 text-sm text-gray-500">Inicia generando la primera planilla de remuneraciones para tus empleados.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 pt-2">
                        {{ $payrolls->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
