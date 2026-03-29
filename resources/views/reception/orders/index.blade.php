<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between items-center">
            {{ __('Órdenes de Trabajo') }}
            <div>
                <button @click="showImportModal = true" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-xl shadow-sm text-sm transition-colors mr-2">
                    Importar Excel
                </button>
                <a href="{{ route('orders.create') }}" class="bg-[#6B46C1] hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl shadow-sm text-sm transition-colors">
                    + Nueva Orden
                </a>
            </div>
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        showPhotoModal: false,
        showImportModal: false,
        currentOrderId: null,
        currentOrderPhotos: [],
        openPhotoModal(orderId, photos) {
            this.currentOrderId = orderId;
            this.currentOrderPhotos = photos;
            this.showPhotoModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filters -->
            <div class="bg-white p-4 shadow-sm sm:rounded-xl border border-gray-100 mb-6 flex items-center justify-between">
                <form action="{{ route('orders.index') }}" method="GET" class="flex gap-4 items-end">
                    <div>
                        <x-input-label for="status" :value="__('Filtrar por Estado')" />
                        <select name="status" id="status" class="mt-1 block w-48 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" onchange="this.form.submit()">
                            <option value="">Todos los Estados</option>
                            <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_proceso" {{ request('status') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                            <option value="terminado" {{ request('status') == 'terminado' ? 'selected' : '' }}>Terminado</option>
                            <option value="entregado" {{ request('status') == 'entregado' ? 'selected' : '' }}>Entregado/Facturado</option>
                            <option value="anulado" {{ request('status') == 'anulado' ? 'selected' : '' }}>Anulado</option>
                        </select>
                    </div>
                    @if(request()->has('status'))
                        <a href="{{ route('orders.index') }}" class="text-sm text-gray-500 hover:text-gray-700 underline mb-2">Limpiar filtro</a>
                    @endif
                </form>
            </div>
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
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Orden</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Paciente</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trabajo</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fotos</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Técnico</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha Entrega</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Facturación</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($orders as $order)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 text-sm font-medium text-gray-900">#OT-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="p-4 text-sm text-gray-600">{{ $order->patient->name }}</td>
                                    <td class="p-4">
                                        @if($order->items && $order->items->count() > 0)
                                            <ul class="list-disc list-outside ml-4 text-sm font-medium text-gray-900">
                                            @foreach($order->items as $item)
                                                <li>{{ $item->type_name }} <span class="text-xs font-normal text-gray-500">({{ $item->material ?? 'N/A' }})</span></li>
                                            @endforeach
                                            </ul>
                                        @else
                                            <span class="text-xs text-gray-400">Sin detalles</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-sm text-center">
                                        <button @click="openPhotoModal({{ $order->id }}, {{ $order->photos->toJson() }})" class="text-blue-500 hover:text-blue-700 flex flex-col items-center">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-xs">{{ $order->photos->count() }}</span>
                                        </button>
                                    </td>
                                    <td class="p-4 text-sm text-gray-600">{{ $order->technician ? $order->technician->name : 'No asignado' }}</td>
                                    <td class="p-4 text-sm text-gray-600">
                                        <span class="{{ $order->due_date && $order->due_date->isPast() && $order->status != 'entregado' ? 'text-red-600 font-semibold' : '' }}">
                                            {{ $order->due_date ? $order->due_date->format('d/m/Y') : '-' }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        @if(in_array($order->status, ['pendiente', 'en_proceso', 'terminado']))
                                            <form action="{{ route('orders.changeStatus', $order) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" class="text-xs font-medium rounded-md border-gray-300 py-1 pl-2 pr-6 {{
                                                    $order->status == 'pendiente' ? 'bg-gray-100 text-gray-800' :
                                                    ($order->status == 'en_proceso' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800')
                                                }}">
                                                    <option value="pendiente" {{ $order->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                    <option value="en_proceso" {{ $order->status == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                                    <option value="terminado" {{ $order->status == 'terminado' ? 'selected' : '' }}>Terminado</option>
                                                </select>
                                            </form>
                                        @else
                                            @php
                                                $statusColors = [
                                                    'entregado' => 'bg-teal-100 text-teal-800',
                                                    'anulado' => 'bg-red-100 text-red-800',
                                                ];
                                                $colorClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2.5 py-1 text-xs font-medium rounded-md {{ $colorClass }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-sm">
                                        @if($order->invoice)
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-700">{{ $order->invoice->invoice_type }} #{{ str_pad($order->invoice->id, 5, '0', STR_PAD_LEFT) }}</span>
                                                @if($order->invoice->accountReceivable)
                                                    @if($order->invoice->accountReceivable->status === 'pagado')
                                                        <span class="text-xs text-green-600 font-medium">Cobrado</span>
                                                    @else
                                                        <span class="text-xs text-orange-600 font-medium whitespace-nowrap">Pend. S/ {{ number_format($order->invoice->accountReceivable->balance, 2) }}</span>
                                                    @endif
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium transition-colors">Ver</a>
                                        @if(!in_array($order->status, ['entregado', 'anulado']))
                                            <a href="{{ route('orders.edit', $order) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium transition-colors ml-2">Editar</a>
                                            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas anular esta orden?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium transition-colors ml-2">Anular</button>
                                            </form>
                                        @endif
                                        @if($order->status === 'terminado')
                                            <a href="{{ route('orders.checkout', $order) }}" class="text-green-600 hover:text-green-900 text-sm font-bold transition-colors ml-2">Entregar y Cobrar</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="p-6 text-center text-gray-500">No hay órdenes de trabajo registradas.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
            
            <!-- Modal de Importación Masiva -->
            <div x-show="showImportModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50" style="display: none;">
                <div class="bg-white rounded-lg p-6 w-full max-w-md m-auto flex-col flex" @click.away="showImportModal = false">
                    <h2 class="text-xl font-bold mb-4">Importar Órdenes (Excel)</h2>
                    <p class="text-sm text-gray-600 mb-4">Sube un archivo .xlsx con las columnas: paciente_dni, tecnico_dni, fecha_entrega, tipo_trabajo, material, color, precio</p>
                    
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('orders.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-4"/>
                        <div class="flex justify-end pt-2">
                            <button type="button" @click="showImportModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 mr-2">Cerrar</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Importar</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Modal de Fotos -->
            <div x-show="showPhotoModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50" style="display: none;">
                <div class="bg-white rounded-lg p-6 w-full max-w-2xl m-auto flex-col flex" @click.away="showPhotoModal = false">
                    <h2 class="text-xl font-bold mb-4">Fotos de la Orden #OT-<span x-text="String(currentOrderId).padStart(4, '0')"></span></h2>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
                        <template x-for="photo in currentOrderPhotos" :key="photo.id">
                            <div class="relative group border rounded p-2">
                                <img :src="'/storage/' + photo.photo_path" class="w-full h-32 object-cover rounded cursor-pointer" @click="window.open('/storage/' + photo.photo_path, '_blank')">
                                <p class="text-xs text-gray-600 mt-2 line-clamp-2" x-text="photo.comment"></p>
                                
                                <form :action="'/orders/photos/' + photo.id" method="POST" class="absolute top-0 right-0 m-1" onsubmit="return confirm('¿Eliminar foto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </template>
                        <div x-show="currentOrderPhotos.length === 0" class="col-span-full py-4 text-center text-gray-500 text-sm">
                            No hay fotos adjuntas a esta orden.
                        </div>
                    </div>

                    <form :action="'/orders/' + currentOrderId + '/photos'" method="POST" enctype="multipart/form-data" class="border-t pt-4">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Subir Nueva Foto</label>
                            <input type="file" name="photo" accept="image/*" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 mb-2"/>
                            
                            <label class="block text-sm font-medium text-gray-700 mt-2">Comentario (opcional)</label>
                            <input type="text" name="comment" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Detalle de la foto">
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="button" @click="showPhotoModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 mr-2">Cerrar</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Subir Foto</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
