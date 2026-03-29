<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('banks.index') }}" class="p-2 bg-white rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $bank->bank_name }} <span class="text-sm font-normal text-gray-500 ml-2 font-mono">{{ $bank->account_number }}</span>
                </h2>
            </div>
            <div class="px-4 py-2 bg-white rounded-full border border-gray-200 shadow-sm font-bold text-lg {{ $bank->balance >= 0 ? 'text-indigo-700' : 'text-red-600' }}">
                {{ $bank->currency == 'USD' ? '$' : 'S/' }} {{ number_format($bank->balance, 2) }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left: List -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-fit">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Historial de Movimientos</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Detalle</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Referencia</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($movements as $mov)
                                <tr class="hover:bg-slate-50 transition-colors {{ !$mov->reconciled ? 'bg-amber-50/30' : '' }}">
                                    <td class="p-4 text-sm text-gray-600 whitespace-nowrap">
                                        {{ $mov->date->format('d/m/Y') }}
                                        @if(!$mov->reconciled)
                                            <span title="Pendiente de conciliación bancaria" class="inline-flex ml-2 h-2 w-2 rounded-full bg-amber-400"></span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-sm font-medium text-gray-900">
                                        {{ $mov->description }}
                                        <div class="text-xs text-gray-400 mt-0.5">{{ ucfirst($mov->type) }}</div>
                                    </td>
                                    <td class="p-4 text-sm text-gray-500 font-mono">{{ $mov->reference ?: '-' }}</td>
                                    <td class="p-4 text-sm font-bold text-right {{ $mov->type === 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $mov->type === 'ingreso' ? '+' : '-' }} {{ number_format($mov->amount, 2) }}
                                    </td>
                                    <td class="p-4 text-right">
                                        <form action="{{ route('banks.movements.toggleReconciled', $mov) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 text-xs rounded-full font-medium transition-colors border {{ $mov->reconciled ? 'border-green-200 text-green-700 bg-green-50 hover:bg-green-100' : 'border-gray-200 text-gray-600 hover:bg-gray-100' }}">
                                                {{ $mov->reconciled ? 'Conciliado ✔' : 'Marcar' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        No hay movimientos registrados en esta cuenta.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($movements->hasPages())
                        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                            {{ $movements->links() }}
                        </div>
                    @endif
                </div>

                <!-- Right: Forms -->
                <div class="space-y-6">
                    
                    <!-- Importar Excel -->
                    <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100 h-fit shadow-sm">
                        <h3 class="text-sm font-bold text-indigo-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Importar Extracto Bancario
                        </h3>
                        <p class="text-xs text-indigo-700 mb-4">Sube un archivo Excel (.xlsx, .csv) con las columnas: <br><span class="font-mono bg-white px-1 rounded">fecha, descripcion, referencia, tipo, monto</span></p>
                        <form action="{{ route('banks.import', $bank) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" class="block w-full text-xs text-indigo-700 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" required>
                            <button type="submit" class="w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition">
                                Subir Excel e Importar
                            </button>
                        </form>
                    </div>

                    <!-- Manual Form -->
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200 h-fit shadow-sm">
                        <h3 class="text-sm font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2 uppercase tracking-wide">Registro Manual</h3>
                    
                    <form action="{{ route('banks.storeMovement', $bank) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <x-input-label for="type" :value="__('Tipo de Operación *')" />
                            <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="ingreso">Depósito / Ingreso (+)</option>
                                <option value="egreso">Retiro / Egreso (-)</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('type')" />
                        </div>

                        <div>
                            <x-input-label for="amount" :value="__('Monto ('. $bank->currency .') *')" />
                            <x-text-input id="amount" name="amount" type="number" step="0.01" min="0.01" class="mt-1 block w-full text-right" placeholder="0.00" required />
                            <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Descripción / Concepto *')" />
                            <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" placeholder="Ej: Pago Factura F001-23" required />
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="date" :value="__('Fecha *')" />
                                <x-text-input id="date" name="date" type="date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('date')" />
                            </div>

                            <div>
                                <x-input-label for="reference" :value="__('N° Operación')" />
                                <x-text-input id="reference" name="reference" type="text" class="mt-1 block w-full font-mono text-xs" placeholder="Opcional" />
                                <x-input-error class="mt-2" :messages="$errors->get('reference')" />
                            </div>
                        </div>

                        <div class="pt-4 mt-2 border-t border-gray-200">
                            <x-primary-button class="w-full justify-center">
                                {{ __('Guardar Transacción') }}
                            </x-primary-button>
                        </div>
                    </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
