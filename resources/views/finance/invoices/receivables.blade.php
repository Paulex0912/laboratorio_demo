<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cartera por Cobrar (Deuda Activa)') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ view: 'list' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- KPIs Globales de Riesgo -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs text-gray-500 font-bold tracking-widest uppercase mb-1">Deuda Absoluta Total</p>
                    <p class="text-3xl font-black text-gray-900 font-mono">S/ {{ number_format($metrics['total_debt'], 2) }}</p>
                </div>
                <div class="bg-red-50 rounded-2xl shadow-sm border border-red-100 p-5">
                    <p class="text-xs text-red-600 font-bold tracking-widest uppercase mb-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Deuda Vencida
                    </p>
                    <p class="text-3xl font-black text-red-700 font-mono">S/ {{ number_format($metrics['expired_debt'], 2) }}</p>
                </div>
                <div class="bg-amber-50 rounded-2xl shadow-sm border border-amber-100 p-5">
                    <p class="text-xs text-amber-700 font-bold tracking-widest uppercase mb-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Vence Pronto(&lt;3d)
                    </p>
                    <p class="text-3xl font-black text-amber-800 font-mono">S/ {{ number_format($metrics['soon_debt'], 2) }}</p>
                </div>
                <div class="bg-green-50 rounded-2xl shadow-sm border border-green-100 p-5">
                    <p class="text-xs text-green-700 font-bold tracking-widest uppercase mb-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Deuda Sana
                    </p>
                    <p class="text-3xl font-black text-green-800 font-mono">S/ {{ number_format($metrics['healthy_debt'], 2) }}</p>
                </div>
            </div>

            <!-- Filtros Superiores -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-wrap gap-4 justify-between items-center">
                <div class="flex gap-4 items-center flex-wrap">
                    <span class="text-sm font-semibold text-gray-700">Filtros Rápidos:</span>
                    <a href="{{ route('invoices.receivables') }}" class="px-4 py-2 text-sm rounded-lg border {{ !request('filter') ? 'bg-indigo-50 border-indigo-200 text-indigo-700 font-bold' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' }}">Toda la Cartera</a>
                    <a href="{{ route('invoices.receivables', ['filter' => 'expired']) }}" class="px-4 py-2 text-sm rounded-lg border {{ request('filter') === 'expired' ? 'bg-red-50 border-red-200 text-red-700 font-bold' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' }}">Solo Vencidas</a>
                    <a href="{{ route('invoices.receivables', ['filter' => 'soon']) }}" class="px-4 py-2 text-sm rounded-lg border {{ request('filter') === 'soon' ? 'bg-amber-50 border-amber-200 text-amber-700 font-bold' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' }}">Por Vencer (3 días)</a>
                </div>
                
                <div class="flex bg-gray-100 p-1 rounded-lg">
                    <button @click="view = 'list'" :class="view === 'list' ? 'bg-white shadow-sm font-bold text-indigo-700' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-1.5 text-sm rounded-md transition-all">📋 Lista</button>
                    <button @click="view = 'calendar'" :class="view === 'calendar' ? 'bg-white shadow-sm font-bold text-indigo-700' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-1.5 text-sm rounded-md transition-all">📅 Calendario</button>
                </div>
            </div>

            <!-- Tabla de Deudas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Paciente</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Factura</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Fechas Clave</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Monto Total</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Saldo Pendiente</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Riesgo / Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($receivables as $inv)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-4">
                                    <div class="text-sm font-bold text-indigo-900">{{ $inv->patient->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $inv->patient->phone ?? 'Sin Teléfono' }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-mono text-gray-900 border border-gray-200 rounded px-2 py-0.5 inline-block bg-white">{{ $inv->series }}-{{ $inv->number }}</div>
                                </td>
                                <td class="p-4 text-sm">
                                    <div class="text-gray-500"><span class="font-medium text-gray-400 text-xs uppercase">Emi:</span> {{ $inv->date_issued->format('d/m') }}</div>
                                    @php
                                        $isExpired = $inv->due_date < now()->startOfDay();
                                        $isSoon = $inv->due_date <= now()->addDays(3)->endOfDay() && !$isExpired;
                                    @endphp
                                    <div class="mt-0.5 font-bold {{ $isExpired ? 'text-red-600' : ($isSoon ? 'text-amber-600' : 'text-gray-900') }}">
                                        <span class="font-medium text-gray-400 text-xs uppercase">Ven:</span> {{ $inv->due_date->format('d/m/Y') }}
                                        @if($isExpired)
                                            <span class="text-[10px] ml-1 px-1.5 py-0.5 bg-red-100 text-red-700 rounded uppercase">Vencido</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4 text-sm text-gray-500 font-mono">
                                    S/ {{ number_format($inv->total, 2) }}
                                </td>
                                <td class="p-4">
                                    <div class="text-base font-black text-gray-900 font-mono">
                                        S/ {{ number_format($inv->current_balance, 2) }}
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('invoices.show', $inv) }}" class="text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors shadow-sm {{ $isExpired ? 'bg-red-600 hover:bg-red-700' : ($isSoon ? 'bg-amber-500 hover:bg-amber-600' : 'bg-indigo-600 hover:bg-indigo-700') }}">
                                        Cobrar
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-500 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900">Cartera Sana</h3>
                                    <p class="text-gray-500">No hay facturas pendientes o vencidas según el filtro actual.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Calendario de Vencimientos -->
            <div x-show="view === 'calendar'" x-cloak class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div id="calendar-receivables" class="w-full"></div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar-receivables');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    list: 'Lista'
                },
                events: @json($events ?? []),
                eventClick: function(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault(); // prevents browser from following link in current tab.
                    }
                }
            });
            
            // AlpineJS hook to render properly when unhidden
            document.addEventListener('alpine:init', () => {
                Alpine.effect(() => {
                    const alpineData = Alpine.$data(document.querySelector('[x-data]'));
                    if (alpineData && alpineData.view === 'calendar') {
                        setTimeout(() => { calendar.render(); }, 100);
                    }
                });
            });
            calendar.render();
        });
    </script>
    @endpush
</x-app-layout>
