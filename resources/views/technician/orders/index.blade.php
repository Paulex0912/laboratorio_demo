<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mis Órdenes de Trabajo') }}
            </h2>
            <div x-data="{ online: navigator.onLine }" @online.window="online = true" @offline.window="online = false">
                <span x-show="!online" style="display: none;" class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold animate-pulse">
                    MODO OFFLINE (Sin Conexión)
                </span>
                <span x-show="online" class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                    ONLINE
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="offlineOrders()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Trabajos Asignados</h3>
                    <button x-show="pendingQueue.length > 0" @click="syncData()" class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1 rounded border border-indigo-200 hover:bg-indigo-100 transition-colors">
                        Sincronizar <span x-text="pendingQueue.length"></span> pendientes
                    </button>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50/50">
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Orden</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Paciente</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trabajo</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha Entrega</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado Actual</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($orders as $order)
                                <tr class="hover:bg-slate-50 transition-colors" x-data="{ localStatus: '{{ $order->status }}', visuallyUpdated: false }" id="row-{{ $order->id }}">
                                    <td class="p-4 text-sm font-medium text-gray-900">
                                        #OT-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                        <span x-show="visuallyUpdated" style="display: none;" class="ml-2 text-[10px] bg-yellow-100 text-yellow-800 px-1 py-0.5 rounded">Pendiente Sync</span>
                                    </td>
                                    <td class="p-4 text-sm text-gray-600">{{ $order->patient->name }}</td>
                                    <td class="p-4">
                                        <p class="text-sm font-medium text-gray-900">{{ $order->type }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->material }} {{ $order->color ? ' - '.$order->color : '' }}</p>
                                    </td>
                                    <td class="p-4 text-sm text-gray-600">
                                        <span class="{{ $order->due_date && $order->due_date->isPast() && $order->status != 'entregado' ? 'text-red-600 font-semibold' : '' }}">
                                            {{ $order->due_date ? $order->due_date->format('d/m/Y') : '-' }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-md bg-gray-100 text-gray-800" x-text="localStatus.replace('_', ' ').toUpperCase()">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <form x-show="localStatus !== 'entregado'" @submit.prevent="submitForm($event, {{ $order->id }}, getNextStatus(localStatus), localStatus)" action="{{ route('technician.orders.status', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <input type="hidden" name="status" :value="getNextStatus(localStatus)">
                                            
                                            <button type="submit" 
                                                    class="text-white font-medium py-1.5 px-3 rounded-lg text-sm transition-colors cursor-pointer"
                                                    :class="{
                                                        'bg-blue-600 hover:bg-blue-700': localStatus === 'pendiente',
                                                        'bg-green-600 hover:bg-green-700': localStatus === 'en_proceso',
                                                        'bg-teal-600 hover:bg-teal-700': localStatus === 'terminado'
                                                    }"
                                                    x-text="localStatus === 'pendiente' ? 'Iniciar Trabajo' : (localStatus === 'en_proceso' ? 'Marcar Terminado' : 'Entregar')">
                                            </button>
                                        </form>
                                        <span x-show="localStatus === 'entregado'" style="display: none;" class="text-xs text-gray-400 border border-gray-200 px-2 py-1 rounded">Entregado</span>
                                        
                                        <button @click="$dispatch('open-modal', 'add-material-modal'); $dispatch('set-order', {{ $order->id }})"
                                            class="mt-2 text-xs text-indigo-600 hover:text-indigo-800 underline block text-right">
                                            + Material
                                        </button>
                                    </td>
                                </tr>
                                <!-- Materiales Expandible (Simple list) -->
                                @if($order->materials->count() > 0)
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <td colspan="6" class="p-2 pl-8 text-xs text-gray-600">
                                        <div class="flex flex-wrap gap-2">
                                            <span class="font-medium text-gray-700">Materiales Usados:</span>
                                            @foreach($order->materials as $mat)
                                                <span class="bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded border border-indigo-200">
                                                    {{ $mat->product->name }} ({{ floatval($mat->quantity) }}{{ $mat->product->unit_measure }})
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-gray-500">No tienes órdenes asignadas activas.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 p-4 border-t border-gray-50">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Material -->
    <x-modal name="add-material-modal" focusable>
        <form x-data="{ orderId: null }" @set-order.window="orderId = $event.detail" :action="`/technician/orders/${orderId}/materials`" method="POST" class="p-6">
            @csrf
            
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Registrar Consumo de Material') }}
            </h2>
            
            <p class="mt-1 text-sm text-gray-600">
                Selecciona el material utilizado e ingresa la cantidad exacta. Se descontará del inventario.
            </p>

            <div class="mt-4">
                <x-input-label for="product_id" :value="__('Material / Producto')" />
                <select id="product_id" name="product_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="">Seleccione un material...</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ floatval($product->stock_current) }} {{ $product->unit_measure }})</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="quantity" :value="__('Cantidad')" />
                <x-text-input id="quantity" name="quantity" type="number" step="0.01" min="0.01" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-primary-button class="ms-3 bg-indigo-600 hover:bg-indigo-700">
                    {{ __('Registrar Consumo') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- PWA Offline Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('offlineOrders', () => ({
                isOnline: navigator.onLine,
                pendingQueue: [],
                db: null,

                init() {
                    window.addEventListener('online', () => {
                        this.isOnline = true;
                        this.syncData();
                    });
                    window.addEventListener('offline', () => {
                        this.isOnline = false;
                    });
                    
                    this.initDB();
                },

                initDB() {
                    const request = indexedDB.open('JoelDentOfflineDB', 1);
                    request.onupgradeneeded = (event) => {
                        this.db = event.target.result;
                        if (!this.db.objectStoreNames.contains('orders_sync')) {
                            this.db.createObjectStore('orders_sync', { keyPath: 'id' }); // id is a timestamp
                        }
                    };
                    request.onsuccess = (event) => {
                        this.db = event.target.result;
                        this.loadQueue();
                    };
                },

                loadQueue() {
                    const transaction = this.db.transaction(['orders_sync'], 'readonly');
                    const store = transaction.objectStore('orders_sync');
                    const request = store.getAll();
                    request.onsuccess = () => {
                        this.pendingQueue = request.result;
                    };
                },

                getNextStatus(current) {
                    if(current === 'pendiente') return 'en_proceso';
                    if(current === 'en_proceso') return 'terminado';
                    if(current === 'terminado') return 'entregado';
                    return current;
                },

                async submitForm(event, orderId, newStatus, currentStatus) {
                    if (this.isOnline) {
                        event.target.submit(); // Standard native submission
                    } else {
                        // Offline capture
                        const item = {
                            id: Date.now(),
                            order_id: orderId,
                            status: newStatus,
                            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        };
                        
                        // Save to IndexedDB
                        const transaction = this.db.transaction(['orders_sync'], 'readwrite');
                        const store = transaction.objectStore('orders_sync');
                        store.add(item);
                        
                        transaction.oncomplete = () => {
                            this.loadQueue();
                            alert('No tienes conexión a Internet. El cambio se ha guardado localmente y se sincronizará automáticamente al volver a la red.');
                            
                            // Visual alpine feedback to table row component
                            let rowScope = event.target.closest('tr').__x;
                            if(rowScope && rowScope.$data) {
                                rowScope.$data.localStatus = newStatus;
                                rowScope.$data.visuallyUpdated = true;
                            }
                        };
                    }
                },

                async syncData() {
                    if (this.pendingQueue.length === 0) return;
                    
                    for (let item of this.pendingQueue) {
                        try {
                            const formData = new FormData();
                            formData.append('_token', item.csrf);
                            formData.append('_method', 'PATCH');
                            formData.append('status', item.status);
                            
                            // Native Fetch
                            let response = await fetch(`/technician/orders/${item.order_id}/status`, {
                                method: 'POST', // Form spoofing via hidden _method over POST
                                body: formData,
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });

                            if (response.ok || response.status === 422 || response.status === 403) {
                                // If processed (even if rejected by backend validations), clear from queue
                                const tx = this.db.transaction(['orders_sync'], 'readwrite');
                                tx.objectStore('orders_sync').delete(item.id);
                            }
                        } catch (error) {
                            console.error('Offline Sync Error:', error);
                            // Keep in queue if network fails again
                        }
                    }
                    
                    this.loadQueue(); // Refresh queue array
                    setTimeout(() => window.location.reload(), 1500); // Soft reload to see true backend state
                }
            }));
        });
    </script>
</x-app-layout>
