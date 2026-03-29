<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Financiero y Flujo Proyectado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Resumen Financiero -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Saldo Bancos -->
                <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                    <div class="absolute right-0 top-0 opacity-10">
                        <svg class="h-24 w-24" fill="currentColor" viewBox="0 0 24 24"><path d="M4 10h16v11H4v-11zm16-2H4V5h16v3zM12 12h-2v2h2v-2zm4 0h-2v2h2v-2zM8 12H6v2h2v-2zm4 4h-2v2h2v-2zm4 0h-2v2h2v-2zM8 16H6v2h2v-2z"></path></svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-indigo-100 font-medium text-sm tracking-wide">CAJA Y BANCOS ACTUAL</p>
                        <h3 class="text-3xl font-bold mt-2">S/ {{ number_format($totalBanks, 2) }}</h3>
                    </div>
                </div>



                <!-- Balance Proyectado -->
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-gray-400 font-medium text-sm tracking-wide">BALANCE PROYECTADO LÍQUIDO</p>
                        <h3 class="text-3xl font-bold mt-2 {{ $projectedBalance >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                            S/ {{ number_format($projectedBalance, 2) }}
                        </h3>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-700 text-xs text-gray-400">
                        Total Bancos y Efectivo
                    </div>
                </div>
            </div>

            <!-- Gráfico de Flujo de Caja -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Proyección de Flujo de Caja (Próximos 6 meses)</h3>
                
                <div class="relative h-80 w-full">
                    <canvas id="cashFlowChart"></canvas>
                </div>
            </div>

            <!-- Panel Informativo -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-4">
                <div class="bg-blue-100 p-2 rounded-full text-blue-600 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-blue-900">Sobre este Dashboard</h4>
                    <p class="text-sm text-blue-700 mt-1">
                        El gráfico muestra una proyección base para los meses venideros. En sprints posteriores (Fase de Facturación y Cuentas por Cobrar), 
                        esta gráfica se integrará automáticamente con las facturas de clientes y deudas de proveedores para generar previsiones hiperprecisas.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- Cargar Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('cashFlowChart').getContext('2d');
            
            // Datos inyectados desde el controlador
            const labels = @json($months);
            const ingresos = @json($cashFlowData['ingresos']);
            const egresos = @json($cashFlowData['egresos']);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Ingresos Proyectados',
                            data: ingresos,
                            backgroundColor: 'rgba(16, 185, 129, 0.8)', // Emerald 500
                            borderRadius: 6,
                            borderSkipped: false,
                        },
                        {
                            label: 'Egresos Proyectados',
                            data: egresos,
                            backgroundColor: 'rgba(239, 68, 68, 0.8)', // Red 500
                            borderRadius: 6,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'S/ ' + new Intl.NumberFormat('es-PE').format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6', // Gray 100
                                drawBorder: false,
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'S/ ' + value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false,
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
