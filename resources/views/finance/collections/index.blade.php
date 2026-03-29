<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cobranzas y Tesorería') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Barra de Herramientas -->
            <div class="mb-6 flex justify-between items-center">
                <div class="w-full">
                    <form action="{{ route('collections.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center w-full">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Buscar paciente por Nombre o DNI...">
                        </div>
                        <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full md:w-auto rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Desde">
                            <span class="hidden md:inline text-gray-500">-</span>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full md:w-auto rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Hasta">
                        </div>
                        <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 w-full md:w-auto">
                            Buscar / Filtrar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Listado de Deudores -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50/50">
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Paciente / DNI</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Última Deuda</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Atraso (Días)</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Deuda Total Consolidada</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($patients as $patient)
                                <tr class="hover:bg-amber-50/30 transition-colors">
                                    <td class="p-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $patient->name }}</div>
                                        <div class="text-xs text-gray-500">DNI: {{ $patient->dni }}</div>
                                    </td>
                                    <td class="p-4 text-sm text-gray-600">
                                        @php
                                            $oldestDebt = $patient->accountReceivables->sortBy('due_date')->first();
                                        @endphp
                                        <span class="{{ $oldestDebt && $oldestDebt->due_date->isPast() ? 'text-red-500 font-bold' : '' }}">
                                            {{ $oldestDebt ? $oldestDebt->due_date->format('d/m/Y') : '-' }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($oldestDebt)
                                            @php
                                                $daysDelayed = now()->startOfDay()->diffInDays($oldestDebt->due_date->startOfDay(), false);
                                            @endphp
                                            @if($daysDelayed < 0)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                                    {{ abs($daysDelayed) }} días
                                                </span>
                                            @elseif($daysDelayed == 0)
                                                 <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                    Vence hoy
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    {{ $daysDelayed }} días
                                                </span>
                                            @endif
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-extrabold bg-red-100 text-red-800">
                                            S/ {{ number_format($patient->total_debt, 2) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('collections.show', $patient) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-colors">
                                            Cobrar Abono
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-12 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center text-green-500">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <p class="text-gray-500 font-medium">¡Excelente! No hay cuentas por cobrar pendientes.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $patients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
