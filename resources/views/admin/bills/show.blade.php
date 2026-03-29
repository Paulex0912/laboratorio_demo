<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle de Compra: ') }} {{ $bill->bill_number }}
            </h2>
            <a href="{{ route('admin.bills.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Columna Izquierda: Detalles -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Información del Proveedor</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">Razón Social</p>
                                    <p class="text-gray-900 font-bold">{{ $bill->supplier->business_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">RUC</p>
                                    <p class="text-gray-900">{{ $bill->supplier->ruc }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">Categoría del Gasto/Compra</p>
                                    <p class="text-indigo-700 font-bold bg-indigo-50 px-2 py-1 inline-block rounded">{{ $bill->generalCategory->name ?? 'Sin Categoría' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">Orden de Compra Relacionada</p>
                                    <p class="text-gray-900">
                                        @if($bill->purchase_order_id)
                                            <a href="{{ route('admin.purchases.show', $bill->purchase_order_id) }}" class="text-indigo-600 hover:underline">PO-{{ $bill->purchase_order_id }}</a>
                                        @else
                                            Ninguna
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($bill->purchaseOrder && $bill->purchaseOrder->lines->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Productos Ingresados al Almacén</h3>
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="text-gray-500 border-b">
                                        <th class="py-2">Producto</th>
                                        <th class="py-2 text-center">Cantidad</th>
                                        <th class="py-2 text-right">Precio Unitario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bill->purchaseOrder->lines as $line)
                                        <tr class="border-b">
                                            <td class="py-2 font-medium">{{ $line->product->name }}</td>
                                            <td class="py-2 text-center">{{ $line->quantity }}</td>
                                            <td class="py-2 text-right">S/ {{ number_format($line->unit_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <p class="text-xs text-green-600 mt-4">* Estos productos aumentaron el stock automáticamente al registrar esta factura.</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($bill->notes)
                    <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg border border-yellow-200">
                        <div class="p-6">
                            <h3 class="text-sm font-bold text-yellow-800 mb-2">Notas:</h3>
                            <p class="text-yellow-900 text-sm whitespace-pre-line">{{ $bill->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Columna Derecha: Montos y Archivo -->
                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-[#6B46C1]">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Resumen Financiero</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Fecha Emisión:</span>
                                    <span class="font-semibold">{{ $bill->issue_date->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Fecha Vencimiento:</span>
                                    <span class="font-bold {{ $bill->due_date->isPast() && $bill->balance > 0 ? 'text-red-500' : 'text-gray-900' }}">{{ $bill->due_date->format('d/m/Y') }}</span>
                                </div>
                                <hr>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Subtotal:</span>
                                    <span>S/ {{ number_format($bill->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">IGV:</span>
                                    <span>S/ {{ number_format($bill->tax_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold text-indigo-900 pt-2 border-t">
                                    <span>Total:</span>
                                    <span>S/ {{ number_format($bill->total_amount, 2) }}</span>
                                </div>
                                
                                <div class="flex justify-between text-md font-bold pt-4 mt-4 border-t">
                                    <span class="text-gray-600">Saldo por Pagar:</span>
                                    <span class="text-red-600">S/ {{ number_format($bill->balance, 2) }}</span>
                                </div>
                            </div>

                            <div class="mt-6 text-center">
                                <span class="px-3 py-1 text-sm font-bold uppercase rounded-full 
                                    {{ $bill->status == 'pendiente' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $bill->status == 'parcial' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $bill->status == 'pagada' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $bill->status == 'anulada' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $bill->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center">
                            <h3 class="text-md font-bold text-gray-900 mb-4">Documento Físico</h3>
                            @if($bill->invoice_file_path)
                                @php
                                    $ext = pathinfo($bill->invoice_file_path, PATHINFO_EXTENSION);
                                @endphp
                                
                                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($bill->invoice_file_path) }}" target="_blank">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($bill->invoice_file_path) }}" alt="Factura" class="w-full h-auto rounded-lg border hover:opacity-90 transition">
                                    </a>
                                @else
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($bill->invoice_file_path) }}" target="_blank" class="inline-flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                        <svg class="w-12 h-12 text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span class="text-indigo-600 font-bold">Ver Documento PDF</span>
                                    </a>
                                @endif
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($bill->invoice_file_path) }}" download class="block mt-4 text-xs font-bold text-gray-500 hover:text-gray-900 underline">Descargar Archivo</a>
                            @else
                                <div class="text-gray-400 p-4 border rounded bg-gray-50">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    No se adjuntó ningún archivo.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
