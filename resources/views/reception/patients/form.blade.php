<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($patient) ? __('Editar Doctor/Clínica') : __('Registrar Doctor/Clínica') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 border border-gray-100">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ isset($patient) ? route('patients.update', $patient) : route('patients.store') }}">
                        @csrf
                        @if(isset($patient))
                            @method('PUT')
                        @endif

                        <!-- Nombre -->
                        <div>
                            <x-input-label for="name" :value="__('Nombre Completo')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $patient->name ?? '')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- DNI -->
                        <div class="mt-4">
                            <x-input-label for="dni" :value="__('DNI/Documento')" />
                            <x-text-input id="dni" class="block mt-1 w-full" type="text" name="dni" :value="old('dni', $patient->dni ?? '')" />
                            <x-input-error :messages="$errors->get('dni')" class="mt-2" />
                        </div>

                        <!-- RUC -->
                        <div class="mt-4">
                            <x-input-label for="ruc" :value="__('RUC')" />
                            <x-text-input id="ruc" class="block mt-1 w-full" type="text" name="ruc" :value="old('ruc', $patient->ruc ?? '')" />
                            <x-input-error :messages="$errors->get('ruc')" class="mt-2" />
                        </div>

                        <!-- Teléfono -->
                        <div class="mt-4">
                            <x-input-label for="phone" :value="__('Teléfono')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $patient->phone ?? '')" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $patient->email ?? '')" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Notas Dentales -->
                        <div class="mt-4">
                            <x-input-label for="dental_notes" :value="__('Notas / Historial Dental')" />
                            <textarea id="dental_notes" name="dental_notes" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" rows="4">{{ old('dental_notes', $patient->dental_notes ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('dental_notes')" class="mt-2" />
                        </div>

                        <!-- Observaciones -->
                        <div class="mt-4">
                            <x-input-label for="observations" :value="__('Observaciones')" />
                            <textarea id="observations" name="observations" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" rows="4">{{ old('observations', $patient->observations ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('observations')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('patients.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <x-primary-button class="ms-4">
                                {{ isset($patient) ? __('Actualizar') : __('Guardar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
