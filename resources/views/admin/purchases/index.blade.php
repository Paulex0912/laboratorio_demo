<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Órdenes de Compra</h2>
                        <a href="{{ route('admin.purchases.create') }}" class="px-4 py-2 bg-[#6B46C1] text-white rounded-lg hover:bg-indigo-700 transition">
                            + Nueva Orden
                        </a>
                    </div>
                    
                    @if(session('success'))
                        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Código</th>
                                    <th scope="col" class="px-6 py-3">Proveedor</th>
                                    <th scope="col" class="px-6 py-3">Fecha Esperada</th>
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                    <th scope="col" class="px-6 py-3 text-right">Total</th>
                                    <th scope="col" class="px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900">OC-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                        <td class="px-6 py-4">{{ $order->supplier->business_name }}</td>
                                        <td class="px-6 py-4">{{ $order->expected_date ? $order->expected_date->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4">
                                            @if($order->status == 'borrador')
                                                <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">Borrador</span>
                                            @elseif($order->status == 'enviado')
                                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Enviado</span>
                                            @elseif($order->status == 'recibido')
                                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Recibido</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right font-medium">S/ {{ number_format($order->total, 2) }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-2">Ver</a>
                                            @if($order->status == 'borrador' || $order->status == 'enviado')
                                                <a href="#" class="text-green-600 hover:text-green-900">Recibir</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No se han emitido órdenes de compra.
                                        </td>
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
        </div>
    </div>
</x-app-layout>
