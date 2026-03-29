<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuración del Laboratorio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.settings.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Razón Social -->
                        <div class="mt-4">
                            <x-input-label for="razon_social" :value="__('Razón Social')" />
                            <x-text-input id="razon_social" class="block mt-1 w-full" type="text" name="settings[razon_social]" :value="old('settings.razon_social', $settings['razon_social'] ?? '')" required autofocus />
                            <x-input-error :messages="$errors->get('settings.razon_social')" class="mt-2" />
                        </div>

                        <!-- RUC -->
                        <div class="mt-4">
                            <x-input-label for="ruc" :value="__('RUC')" />
                            <x-text-input id="ruc" class="block mt-1 w-full" type="text" name="settings[ruc]" :value="old('settings.ruc', $settings['ruc'] ?? '')" required />
                            <x-input-error :messages="$errors->get('settings.ruc')" class="mt-2" />
                        </div>

                        <!-- Dirección -->
                        <div class="mt-4">
                            <x-input-label for="direccion" :value="__('Dirección')" />
                            <x-text-input id="direccion" class="block mt-1 w-full" type="text" name="settings[direccion]" :value="old('settings.direccion', $settings['direccion'] ?? '')" required />
                            <x-input-error :messages="$errors->get('settings.direccion')" class="mt-2" />
                        </div>

                        <!-- IGV -->
                        <div class="mt-4">
                            <x-input-label for="igv" :value="__('IGV (%)')" />
                            <x-text-input id="igv" class="block mt-1 w-full" type="number" step="0.01" name="settings[igv]" :value="old('settings.igv', $settings['igv'] ?? '18')" required />
                            <x-input-error :messages="$errors->get('settings.igv')" class="mt-2" />
                        </div>

                        <!-- Series de Factura -->
                        <div class="mt-4">
                            <x-input-label for="serie_factura" :value="__('Serie de Factura por defecto')" />
                            <x-text-input id="serie_factura" class="block mt-1 w-full" type="text" name="settings[serie_factura]" :value="old('settings.serie_factura', $settings['serie_factura'] ?? 'F001')" required />
                            <x-input-error :messages="$errors->get('settings.serie_factura')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Guardar Configuración') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
