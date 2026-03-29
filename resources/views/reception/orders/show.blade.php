<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-3">
            <a href="{{ route('orders.index') }}" class="text-gray-500 hover:text-gray-700">← Volver</a>
            {{ __('Detalle de la Orden') }} #OT-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white px-6 py-6 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 mb-6 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold flex items-center gap-3">
                        <span class="text-gray-800">{{ $order->patient->name }}</span>
                    </h3>
                    <p class="text-gray-500 text-sm mt-1">
                        Trabajo: <span class="font-medium text-gray-700">{{ $order->type }} - {{ $order->material }} {{ $order->color ? '('.$order->color.')' : '' }}</span>
                    </p>
                </div>
                <div>
                    @php
                        $statusColors = [
                            'pendiente' => 'bg-gray-100 text-gray-800 border-gray-200',
                            'en_proceso' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'terminado' => 'bg-green-100 text-green-800 border-green-200',
                            'entregado' => 'bg-teal-100 text-teal-800 border-teal-200',
                        ];
                        $badgeClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-3 py-1.5 text-sm font-semibold rounded-lg border {{ $badgeClass }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white p-6 shadow-sm sm:rounded-2xl border border-gray-100">
                        <h4 class="text-lg font-bold text-gray-900 border-b pb-3 mb-4">Información Operativa</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Técnico Asignado</p>
                                <p class="text-sm font-medium text-gray-800 mt-1">{{ $order->technician ? $order->technician->name : 'No asignado' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Monto Cotizado</p>
                                <p class="text-sm font-medium text-gray-800 mt-1">S/ {{ number_format($order->amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Fecha de Emisión</p>
                                <p class="text-sm text-gray-800 mt-1">{{ $order->created_at->format('d/m/Y H:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Fecha Pactada de Entrega</p>
                                <p class="text-sm text-gray-800 mt-1 {{ $order->due_date && $order->due_date->isPast() && $order->status != 'entregado' ? 'text-red-600 font-bold' : '' }}">
                                    {{ $order->due_date ? $order->due_date->format('d/m/Y') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-indigo-50 p-6 shadow-sm sm:rounded-2xl border border-indigo-100">
                        <h4 class="text-lg font-bold text-indigo-900 mb-4">Conformidad de Entrega (Link)</h4>
                        
                        @if($order->status === 'entregado' && $order->signature_path)
                            <div class="text-center">
                                <div class="bg-white p-2 rounded-xl border border-indigo-100 mb-3 shadow-inner">
                                    <img src="{{ Storage::url($order->signature_path) }}" alt="Firma Registrada" class="max-h-24 mx-auto mix-blend-multiply opacity-80">
                                </div>
                                <p class="text-xs text-green-700 font-bold bg-green-100 py-1 px-3 rounded-full inline-block">FIRMADO OK</p>
                                <p class="text-[10px] text-gray-500 mt-2">Firmado el: {{ $order->signed_at ? \Carbon\Carbon::parse($order->signed_at)->format('d/m/Y H:i') : '-' }}</p>
                            </div>
                        @else
                            <p class="text-sm text-indigo-800 mb-4">Genera un enlace temporal de 7 días firmado criptográficamente para que el cliente dibuje su firma desde su celular o clínica.</p>
                            <div x-data="{
                                copyLink() {
                                    navigator.clipboard.writeText('{{ URL::signedRoute('public.delivery.show', ['order' => $order->id], now()->addDays(7)) }}');
                                    alert('¡Enlace de firma copiado al portapapeles!');
                                }
                            }">
                                <button @click="copyLink()" class="w-full bg-[#6B46C1] hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-xl shadow-sm text-sm transition-colors flex justify-center items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                    Copiar Enlace PWA
                                </button>
                                <a href="{{ URL::signedRoute('public.delivery.show', ['order' => $order->id], now()->addDays(7)) }}" target="_blank" class="block text-center text-xs text-indigo-600 mt-3 font-semibold hover:underline">Vista Previa</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
