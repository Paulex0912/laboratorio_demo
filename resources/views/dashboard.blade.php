<x-app-layout>
    <div x-data="dashboardAnalytics()" x-init="init()" class="space-y-6">
        
        <!-- Controles y Título -->
        <div class="flex flex-col md:flex-row justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-2xl font-extrabold text-[#6B46C1] tracking-tight">Panel Gerencial</h2>
                <p class="text-sm text-gray-500">Métricas clave en tiempo real</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-4 items-center">
                <!-- Filtros de Fecha -->
                <div class="flex items-center space-x-2 bg-gray-50 p-1.5 rounded-lg border border-gray-200">
                    <input type="date" x-model="startDate" @change="fetchData()" class="text-sm border-gray-300 rounded-md focus:ring-[#6B46C1] focus:border-[#6B46C1] py-1.5 px-3">
                    <span class="text-gray-400 text-sm">a</span>
                    <input type="date" x-model="endDate" @change="fetchData()" class="text-sm border-gray-300 rounded-md focus:ring-[#6B46C1] focus:border-[#6B46C1] py-1.5 px-3">
                </div>

                <span x-show="loading" class="text-sm text-gray-400 flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-[#6B46C1]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Actualizando...
                </span>
                <span x-show="!loading" class="text-xs text-green-600 bg-green-50 px-3 py-1.5 rounded-full font-medium border border-green-100 flex items-center gap-1.5">
                    <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div> Live
                </span>
                <button @click="fetchData()" class="p-2 bg-gray-50 text-gray-500 hover:text-[#6B46C1] hover:bg-purple-50 rounded-xl transition-colors border border-gray-100" title="Refrescar">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </button>
            </div>
        </div>

        <!-- Top Stats Row -->
        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 xl:grid-cols-4">
            <!-- Card 1 -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div class="text-sm font-bold text-gray-500 uppercase tracking-wider">Ventas (Mes)</div>
                    <div class="p-2 bg-green-100 text-green-700 rounded-xl">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline relative z-10">
                    <span class="text-lg font-bold text-gray-400 mr-1">S/</span>
                    <div class="text-3xl font-extrabold text-gray-900" x-text="kpis.ventas_mes">...</div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div class="text-sm font-bold text-gray-500 uppercase tracking-wider">Saldo de Caja</div>
                    <div class="p-2 bg-blue-100 text-blue-700 rounded-xl">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline relative z-10">
                    <span class="text-lg font-bold text-gray-400 mr-1">S/</span>
                    <div class="text-3xl font-extrabold text-gray-900" x-text="kpis.saldo_caja">...</div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-red-100 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div class="text-sm font-bold text-red-500 uppercase tracking-wider">Deuda Vencida</div>
                    <div class="p-2 bg-red-100 text-red-700 rounded-xl">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex flex-col relative z-10">
                    <div class="flex items-baseline text-red-600">
                        <span class="text-lg font-bold mr-1">S/</span>
                        <div class="text-3xl font-extrabold" x-text="kpis.facturas_vencidas_monto">...</div>
                    </div>
                    <div class="text-xs text-red-400 mt-1 font-medium"><span x-text="kpis.facturas_pendientes"></span> facturas por cobrar</div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div class="text-sm font-bold text-gray-500 uppercase tracking-wider">Órdenes (Lab)</div>
                    <div class="p-2 bg-purple-100 text-[#6B46C1] rounded-xl">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline relative z-10">
                    <div class="text-3xl font-extrabold text-gray-900" x-text="kpis.ordenes_activas">...</div>
                    <div class="ml-2 text-sm font-medium text-gray-500">en proceso</div>
                </div>
            </div>
        </div>
        
        <!-- Graficos Fila 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Ventas Historico -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 lg:col-span-2">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Ingresos Facturados (Últimos 7 días respecto al final del periodo)</h3>
                <div class="h-72 relative w-full">
                    <canvas id="ventasChart"></canvas>
                </div>
            </div>
            
            <!-- Estado Ordenes -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Estado de Trabajos del Periodo</h3>
                <div class="h-64 relative w-full flex justify-center">
                    <canvas id="ordenesChart"></canvas>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 text-center text-sm text-gray-500">
                    Proporción del volumen de órdenes actuales.
                </div>
            </div>
        </div>

        <!-- Graficos Fila 2 (Nuevos) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-8">
            <!-- Top Clientes -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold tracking-tight text-[#6B46C1] mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    Top 10 Clientes (Facturación)
                </h3>
                <div class="h-80 relative w-full mt-4">
                    <canvas id="topClientesChart"></canvas>
                </div>
            </div>
            
            <!-- Top Productos/Trabajos -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Tratamientos / Trabajos Más Solicitados</h3>
                <div class="h-80 relative w-full mt-4">
                    <canvas id="topProductosChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Cargar Chart.js mediante CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboardAnalytics', () => {
                // Fechas iniciales por defecto: Inicio y fin del mes actual.
                const today = new Date();
                const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

                const formatDate = (date) => {
                    return date.toISOString().split('T')[0];
                };

                return {
                    loading: true,
                    startDate: formatDate(startOfMonth),
                    endDate: formatDate(endOfMonth),
                    kpis: {
                        ventas_mes: '0.00',
                        saldo_caja: '0.00',
                        facturas_vencidas_monto: '0.00',
                        facturas_pendientes: 0,
                        ordenes_activas: 0
                    },
                    chartsInst: {
                        ventas: null,
                        ordenes: null,
                        clientes: null,
                        productos: null
                    },
                    
                    init() {
                        this.fetchData();
                        // Polling cada 5 minutos (300000 ms)
                        setInterval(() => {
                            this.fetchData();
                        }, 300000);
                    },

                    async fetchData() {
                        this.loading = true;
                        try {
                            const params = new URLSearchParams({
                                start_date: this.startDate,
                                end_date: this.endDate
                            });

                            const response = await fetch(`{{ route("api.dashboard.stats") }}?${params.toString()}`);
                            const data = await response.json();
                            
                            this.kpis = data.kpis;
                            this.updateCharts(data.charts);
                        } catch (error) {
                            console.error("Error obteniendo datos del dashboard:", error);
                        } finally {
                            this.loading = false;
                        }
                    },

                updateCharts(chartData) {
                    // Update Ventas Chart
                    const ctxVentas = document.getElementById('ventasChart');
                    if(ctxVentas) {
                        if(this.chartsInst.ventas) this.chartsInst.ventas.destroy();
                        
                        this.chartsInst.ventas = new Chart(ctxVentas, {
                            type: 'bar',
                            data: {
                                labels: chartData.ventas_dias.labels,
                                datasets: [{
                                    label: 'Facturación (S/)',
                                    data: chartData.ventas_dias.data,
                                    backgroundColor: '#9F7AEA', // purple-400
                                    hoverBackgroundColor: '#6B46C1', // purple-700
                                    borderRadius: 6,
                                    borderSkipped: false,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: '#f3f4f6', drawBorder: false }
                                    },
                                    x: {
                                        grid: { display: false, drawBorder: false }
                                    }
                                }
                            }
                        });
                    }

                        // Update Ordenes Doughnut
                        const ctxOrdenes = document.getElementById('ordenesChart');
                        if(ctxOrdenes) {
                            if(this.chartsInst.ordenes) this.chartsInst.ordenes.destroy();
                            
                            this.chartsInst.ordenes = new Chart(ctxOrdenes, {
                                type: 'doughnut',
                                data: {
                                    labels: chartData.ordenes_dona.labels,
                                    datasets: [{
                                        data: chartData.ordenes_dona.data,
                                        backgroundColor: ['#60A5FA', '#34D399', '#14B8A6'],
                                        borderWidth: 0,
                                        hoverOffset: 4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    cutout: '75%',
                                    plugins: {
                                        legend: { position: 'bottom' }
                                    }
                                }
                            });
                        }

                        // Update Top Clientes Chart
                        const ctxClientes = document.getElementById('topClientesChart');
                        if (ctxClientes && chartData.top_clientes) {
                            if(this.chartsInst.clientes) this.chartsInst.clientes.destroy();
                            
                            this.chartsInst.clientes = new Chart(ctxClientes, {
                                type: 'bar',
                                data: {
                                    labels: chartData.top_clientes.labels,
                                    datasets: [{
                                        label: 'Total Facturado (S/)',
                                        data: chartData.top_clientes.data,
                                        backgroundColor: '#F59E0B', // Amber
                                        hoverBackgroundColor: '#D97706',
                                        borderRadius: 4,
                                        borderSkipped: false,
                                    }]
                                },
                                options: {
                                    indexAxis: 'y', // Grafica de barras horizontal
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { display: false } },
                                    scales: {
                                        x: {
                                            beginAtZero: true,
                                            grid: { color: '#f3f4f6', drawBorder: false }
                                        },
                                        y: {
                                            grid: { display: false, drawBorder: false }
                                        }
                                    }
                                }
                            });
                        }

                        // Update Top Productos / Trabajos Chart
                        const ctxProductos = document.getElementById('topProductosChart');
                        if (ctxProductos && chartData.top_productos) {
                            if(this.chartsInst.productos) this.chartsInst.productos.destroy();
                            
                            // Generate Dynamic Colors for Doughnut/Pie
                            const bgColors = ['#10B981', '#3B82F6', '#8B5CF6', '#F43F5E', '#F59E0B', '#14B8A6', '#6366F1'];
                            
                            this.chartsInst.productos = new Chart(ctxProductos, {
                                type: 'pie', // Using pie to differentiate from doughnut
                                data: {
                                    labels: chartData.top_productos.labels,
                                    datasets: [{
                                        data: chartData.top_productos.data,
                                        backgroundColor: bgColors.slice(0, chartData.top_productos.labels.length),
                                        borderWidth: 1,
                                        borderColor: '#ffffff',
                                        hoverOffset: 6
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { position: 'right' }
                                    }
                                }
                            });
                        }
                    }
                };
            });
        });
    </script>
</x-app-layout>
