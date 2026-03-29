<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Inventario de Productos') }}
            </h2>
            <a href="{{ route('inventory.products.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                + Nuevo Producto
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3 text-sm font-semibold text-gray-600">Código</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Nombre</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Categoría</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Stock Actual</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Stock Min.</th>
                                <th class="p-3 text-sm font-semibold text-gray-600 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 text-sm font-medium text-gray-900">{{ $product->code }}</td>
                                <td class="p-3 text-sm text-gray-700">
                                    {{ $product->name }}
                                    <div class="text-xs text-gray-500">{{ $product->unit_measure }}</div>
                                </td>
                                <td class="p-3 text-sm text-gray-700">{{ $product->category->name ?? 'Sin Categoría' }}</td>
                                <td class="p-3 text-sm">
                                    <span class="px-2 py-1 rounded {{ $product->stock_current <= $product->stock_min ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ floatval($product->stock_current) }}
                                    </span>
                                </td>
                                <td class="p-3 text-sm text-gray-700">{{ floatval($product->stock_min) }}</td>
                                <td class="p-3 text-sm text-right flex justify-end space-x-2">
                                    <!-- Aquí se agregarían botones de ver y editar -->
                                    <span class="text-xs text-blue-600 cursor-pointer">Editar</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">No hay productos registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
