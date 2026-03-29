<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Rendiciones de Gastos
            </h2>
            <a href="{{ route('expenses.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nueva Rendición
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                @if(session('success'))
                    <div class="p-4 mx-6 mt-6 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="p-4 mx-6 mt-6 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="p-4 font-semibold text-gray-600 text-sm">Fecha</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm">Título</th>
                                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Tesorero'))
                                    <th class="p-4 font-semibold text-gray-600 text-sm">Empleado</th>
                                @endif
                                <th class="p-4 font-semibold text-gray-600 text-sm">Total</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm">Estado</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($reports as $report)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $report->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $report->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="p-4 text-sm text-gray-800 font-medium">
                                        {{ $report->title }}
                                    </td>
                                    @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Tesorero'))
                                        <td class="p-4 text-sm text-gray-600">
                                            {{ $report->user->name }}
                                        </td>
                                    @endif
                                    <td class="p-4 text-sm font-bold text-gray-900">
                                        S/ {{ number_format($report->total, 2) }}
                                    </td>
                                    <td class="p-4">
                                        @php
                                            $badgeClasses = [
                                                'borrador' => 'bg-gray-100 text-gray-700',
                                                'enviada' => 'bg-blue-100 text-blue-700',
                                                'aprobada' => 'bg-green-100 text-green-700',
                                                'rechazada' => 'bg-red-100 text-red-700',
                                                'liquidada' => 'bg-purple-100 text-purple-700',
                                            ];
                                            $class = $badgeClasses[$report->status] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $class }}">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('expenses.show', $report) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium inline-block py-1 px-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">Ver Detalles &rarr;</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-500">
                                        No hay rendiciones de gastos registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($reports->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
