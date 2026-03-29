<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendario de Cuentas por Pagar') }}
        </h2>
    </x-slot>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
        <style>
            .fc-event {
                cursor: pointer;
                transition: transform 0.1s ease-in-out;
            }
            .fc-event:hover {
                transform: scale(1.02);
            }
            .fc-toolbar-title {
                font-weight: 700 !important;
                color: #4a5568 !important;
            }
            .fc-button-primary {
                background-color: #6B46C1 !important;
                border-color: #6B46C1 !important;
            }
            .fc-button-primary:hover {
                background-color: #553C9A !important;
                border-color: #553C9A !important;
            }
        </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4 flex gap-4 text-sm items-center">
                        <span class="font-bold text-gray-700">Leyenda:</span>
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 rounded-full bg-red-500 inline-block"></span> Vencidas / Por Pagar Hoy
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 rounded-full bg-amber-500 inline-block"></span> Por Vencer Próximamente
                        </div>
                    </div>

                    <div id="calendar" class="min-h-[600px] bg-white p-4 rounded border border-gray-100"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'es',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                    events: '{{ route('api.bills.calendar') }}',
                    eventClick: function(info) {
                        info.jsEvent.preventDefault(); // don't let the browser navigate
                        if (info.event.url) {
                            window.open(info.event.url, '_blank');
                        }
                    },
                    eventDidMount: function(info) {
                        // Add tooltip if needed
                        info.el.title = info.event.title + " (Click para ver detalle)";
                    }
                });
                calendar.render();
            });
        </script>
    @endpush
</x-app-layout>
