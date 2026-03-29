<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Caja Chica - Tesorería') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filtro de Fecha -->
            <div class="mb-6 flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Resumen del Día</h3>
                <form action="{{ route('cash.index') }}" method="GET" class="flex gap-2">
                    <input type="date" name="date" value="{{ $date }}" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-md text-sm transition-colors">
                        Filtrar
                    </button>
                    @if($date !== date('Y-m-d'))
                        <a href="{{ route('cash.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm flex items-center px-2">Hoy</a>
                    @endif
                </form>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Ingresos -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 border-l-4 border-l-green-500 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1">
                    <div class="text-sm font-medium text-gray-500">Total Ingresos</div>
                    <div class="mt-4 text-3xl font-bold text-gray-900">S/ {{ number_format($totalIncome, 2) }}</div>
                </div>

                <!-- Egresos -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 border-l-4 border-l-red-500 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1">
                    <div class="text-sm font-medium text-gray-500">Total Egresos</div>
                    <div class="mt-4 text-3xl font-bold text-gray-900">S/ {{ number_format($totalExpense, 2) }}</div>
                </div>

                <!-- Balance -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 border-l-4 border-l-[#6B46C1] flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1">
                    <div class="text-sm font-medium text-gray-500">Balance / Saldo</div>
                    <div class="mt-4 text-3xl font-bold {{ $balance >= 0 ? 'text-gray-900' : 'text-red-600' }}">S/ {{ number_format($balance, 2) }}</div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left: List -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Movimientos de {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Monto</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Doc/Ref</th>
                                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nota</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($movements as $mov)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4">
                                        @if($mov->type === 'ingreso')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="-ml-0.5 mr-1.5 h-3 w-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                Ingreso
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="-ml-0.5 mr-1.5 h-3 w-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-3.707-5.293l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 12.586V9a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 00-1.414 1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                Egreso
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-sm font-medium text-gray-900">{{ $mov->category ? $mov->category->name : 'General' }}</td>
                                    <td class="p-4 text-sm font-bold {{ $mov->type === 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $mov->type === 'ingreso' ? '+' : '-' }} S/ {{ number_format($mov->amount, 2) }}
                                    </td>
                                    <td class="p-4 text-sm text-gray-500">{{ $mov->ref_doc ?: '-' }}</td>
                                    <td class="p-4 text-sm text-gray-500">
                                        <div class="flex flex-col">
                                            <span>{{ $mov->notes }}</span>
                                            @if($mov->receipt_path)
                                                <a href="{{ Storage::url($mov->receipt_path) }}" target="_blank" class="text-xs text-[#6B46C1] hover:underline mt-1 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                    Ver Adjunto
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-gray-500">No hay movimientos registrados en esta fecha.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right: Form -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Registrar Movimiento</h3>
                    
                    <form action="{{ route('cash.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="hidden" name="date" value="{{ date('Y-m-d') }}">

                        <div>
                            <x-input-label for="type" :value="__('Tipo *')" />
                            <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="ingreso">Ingreso (+)</option>
                                <option value="egreso">Egreso (-)</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('type')" />
                        </div>

                        <div>
                            <x-input-label for="amount" :value="__('Monto (S/) *')" />
                            <x-text-input id="amount" name="amount" type="number" step="0.01" min="0.01" class="mt-1 block w-full" required placeholder="0.00" />
                            <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Categoría')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Seleccione...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }} ({{ ucfirst($cat->type) }})</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-input-label for="ref_doc" :value="__('Doc. / Referencia')" />
                            <x-text-input id="ref_doc" name="ref_doc" type="text" class="mt-1 block w-full" placeholder="Ej: Factura #123, OT-001" />
                        </div>

                        <div>
                            <x-input-label for="notes" :value="__('Notas')" />
                            <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                        </div>

                        <div>
                            <x-input-label for="receipt" :value="__('Adjunto (Opcional)')" />
                            <input id="receipt" name="receipt" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            <x-input-error class="mt-2" :messages="$errors->get('receipt')" />
                        </div>

                        <div class="pt-4">
                            <x-primary-button class="w-full justify-center">
                                {{ __('Guardar Movimiento') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
