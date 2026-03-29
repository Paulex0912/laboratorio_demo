<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Compras y Gastos (Bills)') }}
            </h2>
            <a href="{{ route('admin.bills.create') }}" class="inline-flex items-center px-4 py-2 bg-[#6B46C1] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                Nueva Compra
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="p-4">N° Factura</th>
                                    <th class="p-4">Proveedor / Concepto</th>
                                    <th class="p-4">F. Emisión - F. Vencimiento</th>
                                    <th class="p-4 text-right">Total</th>
                                    <th class="p-4 text-center">Estado</th>
                                    <th class="p-4 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($bills as $bill)
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-4">
                                            <span class="font-bold text-gray-900">{{ $bill->bill_number }}</span>
                                            @if($bill->invoice_file_path)
                                            <a href="{{ \Storage::url($bill->invoice_file_path) }}" target="_blank" class="block text-xs text-indigo-500 hover:text-indigo-700 mt-1" title="Ver archivo adjunto">
                                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg> Doc
                                            </a>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            <div class="font-semibold text-gray-800">{{ $bill->supplier->business_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $bill->generalCategory->name ?? 'Sin categoría' }}</div>
                                        </td>
                                        <td class="p-4 text-sm text-gray-600">
                                            <div>{{ $bill->issue_date->format('d/m/Y') }}</div>
                                            <div class="font-bold {{ $bill->due_date->isPast() && $bill->balance > 0 ? 'text-red-500' : 'text-gray-500' }}">Vence: {{ $bill->due_date->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="p-4 text-right font-bold text-gray-900">
                                            S/ {{ number_format($bill->total_amount, 2) }}
                                            <div class="text-xs font-normal text-gray-500">Saldo: S/ {{ number_format($bill->balance, 2) }}</div>
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $bill->status == 'pendiente' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $bill->status == 'parcial' ? 'bg-amber-100 text-amber-800' : '' }}
                                                {{ $bill->status == 'pagada' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $bill->status == 'anulada' ? 'bg-gray-100 text-gray-800' : '' }}">
                                                {{ ucfirst($bill->status) }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-center space-x-2">
                                            <a href="{{ route('admin.bills.show', $bill) }}" class="text-indigo-600 hover:text-indigo-900" title="Ver detalle">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.bills.destroy', $bill) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar compra?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" {{ $bill->status !== 'pendiente' ? 'disabled' : '' }}>
                                                    <svg class="w-5 h-5 inline {{ $bill->status !== 'pendiente' ? 'opacity-50' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-gray-500">No hay compras registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $bills->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
