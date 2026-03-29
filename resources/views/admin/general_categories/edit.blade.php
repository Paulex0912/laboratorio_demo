<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($generalCategory) ? 'Editar Categoría' : 'Nueva Categoría' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form 
                        action="{{ isset($generalCategory) ? route('admin.general_categories.update', $generalCategory) : route('admin.general_categories.store') }}" 
                        method="POST">
                        @csrf
                        @if(isset($generalCategory))
                            @method('PUT')
                        @endif

                        <div class="mb-4">
                            <x-input-label for="type" :value="__('Tipo de Categoría')" />
                            <select id="type" name="type" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                @foreach(['Gasto', 'Compra', 'Servicio', 'Otro'] as $t)
                                    <option value="{{ $t }}" {{ (old('type', $generalCategory->type ?? 'Gasto') == $t) ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nombre de la Categoría')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $generalCategory->name ?? '')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Descripción (opcional)')" />
                            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description', $generalCategory->description ?? '')" />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4">
                            <a href="{{ route('admin.general_categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Guardar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
