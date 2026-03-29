<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($workType) ? __('Editar Tipo de Trabajo') : __('Nuevo Tipo de Trabajo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 border border-gray-100">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ isset($workType) ? route('work_types.update', $workType) : route('work_types.store') }}">
                        @csrf
                        @if(isset($workType))
                            @method('PUT')
                        @endif

                        <!-- Nombre -->
                        <div>
                            <x-input-label for="name" :value="__('Nombre del Tipo de Trabajo *')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $workType->name ?? '')" required autofocus placeholder="Ej: Corona Zirconio, Prótesis Total..." />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Precio Recomendado -->
                        <div class="mt-4">
                            <x-input-label for="default_price" :value="__('Precio Referencial (S/)')" />
                            <x-text-input id="default_price" class="block mt-1 w-full" type="number" step="0.01" min="0" name="default_price" :value="old('default_price', $workType->default_price ?? '0.00')" />
                            <x-input-error :messages="$errors->get('default_price')" class="mt-2" />
                            <p class="text-sm text-gray-500 mt-1">Precio sugerido al crear la orden. Puede modificarse.</p>
                        </div>

                        <!-- Descripción -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Descripción / Detalles Internos')" />
                            <textarea id="description" name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" rows="4">{{ old('description', $workType->description ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('work_types.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <x-primary-button class="ms-4">
                                {{ isset($workType) ? __('Actualizar') : __('Guardar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
