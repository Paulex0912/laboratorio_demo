<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('inventory.products.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Código -->
                            <div>
                                <x-input-label for="code" :value="__('Código Interno')" />
                                <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code')" required autofocus />
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            </div>

                            <!-- Nombre -->
                            <div>
                                <x-input-label for="name" :value="__('Nombre')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Categoría -->
                            <div>
                                <x-input-label for="category_id" :value="__('Categoría')" />
                                <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Ninguna</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>

                            <!-- Unidad de Medida -->
                            <div>
                                <x-input-label for="unit_measure" :value="__('Unidad de Medida')" />
                                <select id="unit_measure" name="unit_measure" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="unidad">Unidad</option>
                                    <option value="caja">Caja</option>
                                    <option value="gramo">Gramo</option>
                                    <option value="ml">Mililitro</option>
                                    <option value="kilo">Kilo</option>
                                </select>
                                <x-input-error :messages="$errors->get('unit_measure')" class="mt-2" />
                            </div>

                            <!-- Stock Inicial -->
                            <div>
                                <x-input-label for="stock_current" :value="__('Stock Inicial')" />
                                <x-text-input id="stock_current" class="block mt-1 w-full" type="number" step="0.01" name="stock_current" :value="old('stock_current', 0)" required />
                                <x-input-error :messages="$errors->get('stock_current')" class="mt-2" />
                            </div>

                            <!-- Stock Mínimo -->
                            <div>
                                <x-input-label for="stock_min" :value="__('Stock Mínimo (Alerta)')" />
                                <x-text-input id="stock_min" class="block mt-1 w-full" type="number" step="0.01" name="stock_min" :value="old('stock_min', 0)" required />
                                <x-input-error :messages="$errors->get('stock_min')" class="mt-2" />
                            </div>

                            <!-- Costo Promedio -->
                            <div class="md:col-span-2">
                                <x-input-label for="cost_price" :value="__('Costo Promedio (Opcional)')" />
                                <x-text-input id="cost_price" class="block mt-1 w-full" type="number" step="0.01" name="cost_price" :value="old('cost_price', 0)" />
                                <x-input-error :messages="$errors->get('cost_price')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('inventory.products.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm">Cancelar</a>
                            <x-primary-button>
                                {{ __('Guardar Producto') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
