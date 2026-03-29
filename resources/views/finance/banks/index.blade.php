<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cuentas Bancarias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Ticker Saldo Global -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-6 text-white flex justify-between items-center h-fit w-full">
                <div>
                    <h3 class="text-blue-100 font-medium text-sm tracking-wide">SALDO CONSOLIDADO BANCOS</h3>
                    <p class="text-4xl font-bold mt-1">S/ {{ number_format($totalBalance, 2) }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left: Account Portfolio -->
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($accounts as $account)
                    <a href="{{ route('banks.show', $account) }}" class="block group">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:border-indigo-300 hover:shadow-md transition-all h-full flex flex-col justify-between relative overflow-hidden">
                            <!-- Decorator line -->
                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-indigo-500"></div>
                            
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $account->bank_name }}</h4>
                                    <p class="text-sm font-mono text-gray-500 mt-1">{{ $account->account_number }}</p>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded-md">{{ $account->currency }}</span>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm text-gray-500 mb-1">Saldo Disponible</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ $account->currency == 'USD' ? '$' : 'S/' }} 
                                    {{ number_format($account->balance, 2) }}
                                </p>
                            </div>

                            @if($account->movements_count > 0)
                                <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                                    <span class="text-xs text-amber-600 bg-amber-50 px-2 py-1 rounded-md mb-0">
                                        {{ $account->movements_count }} mov. sin conciliar
                                    </span>
                                    <span class="text-indigo-600 text-sm font-medium flex items-center group-hover:translate-x-1 transition-transform">
                                        Ver detalles &rarr;
                                    </span>
                                </div>
                            @endif
                        </div>
                    </a>
                    @empty
                    <div class="col-span-full bg-white rounded-2xl border border-dashed border-gray-300 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay cuentas registradas</h3>
                        <p class="mt-1 text-sm text-gray-500">Comienza registrando la primera cuenta bancaria del laboratorio.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Right: Form Register Account -->
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200 h-fit">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Registrar Nueva Cuenta</h3>
                    
                    <form action="{{ route('banks.storeAccount') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <x-input-label for="bank_name" :value="__('Nombre del Banco')" />
                            <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full" placeholder="Ej: BCP, Interbank" required />
                            <x-input-error class="mt-2" :messages="$errors->get('bank_name')" />
                        </div>

                        <div>
                            <x-input-label for="account_number" :value="__('Número de Cuenta')" />
                            <x-text-input id="account_number" name="account_number" type="text" class="mt-1 block w-full font-mono text-sm" placeholder="XXXX-XXXX-XXXX-XXXX" required />
                            <x-input-error class="mt-2" :messages="$errors->get('account_number')" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="currency" :value="__('Moneda')" />
                                <select id="currency" name="currency" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="PEN">Soles (PEN)</option>
                                    <option value="USD">Dólares (USD)</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('currency')" />
                            </div>

                            <div>
                                <x-input-label for="balance" :value="__('Saldo Inicial')" />
                                <x-text-input id="balance" name="balance" type="number" step="0.01" value="0.00" class="mt-1 block w-full text-right" required />
                                <x-input-error class="mt-2" :messages="$errors->get('balance')" />
                            </div>
                        </div>

                        <div class="pt-2">
                            <x-primary-button class="w-full justify-center">
                                {{ __('Guardar Cuenta') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
