<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nueva Orden de Trabajo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('orders.store') }}" method="POST" class="space-y-6" x-data="orderForm()">
                        @csrf

                        <!-- Paciente -->
                        <div>
                            <x-input-label for="patient_id" :value="__('Paciente *')" />
                            <select id="patient_id" name="patient_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Seleccione un paciente</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }} @if($patient->dni) - {{ $patient->dni }} @endif
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('patient_id')" />
                        </div>

                        <!-- Técnico -->
                        <div>
                            <x-input-label for="technician_id" :value="__('Técnico Asignado')" />
                            <select id="technician_id" name="technician_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Sin asignar (Pendiente)</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ old('technician_id') == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('technician_id')" />
                        </div>

                        <!-- Sección de Tipos de Trabajo Dinámicos -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4 flex justify-between items-center">
                                <span>Detalle de Trabajos</span>
                                <button type="button" @click="addItem()" class="text-sm bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-3 py-1 rounded-md font-medium transition-colors">
                                    + Añadir Trabajo
                                </button>
                            </h3>

                            <div class="space-y-4">
                                <template x-for="(item, index) in items" :key="item.id">
                                    <div class="p-6 bg-gray-50/50 border border-gray-200 rounded-2xl relative group hover:border-indigo-300 transition-colors shadow-sm">
                                        
                                        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                                            <h4 class="text-md font-semibold text-gray-700">Trabajo #<span x-text="index + 1"></span></h4>
                                            
                                            <!-- Botón Eliminar Fila -->
                                            <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-lg text-sm font-medium transition-colors flex items-center gap-1" title="Eliminar este trabajo">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Quitar
                                            </button>
                                        </div>

                                        <div class="space-y-6">
                                            <!-- Tipo de Trabajo (Fila Completa) -->
                                            <div>
                                                <x-input-label x-bind:for="'type_' + index" :value="__('Tipo de Trabajo *')" class="text-gray-700" />
                                                <select x-model="item.type_name" @change="updatePrice(index)" x-bind:name="'items[' + index + '][type_name]'" x-bind:id="'type_' + index" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm bg-white" required>
                                                    <option value="" class="text-gray-500">Seleccione un tratamiento de la base de datos...</option>
                                                    @foreach($workTypes as $wt)
                                                        <option value="{{ $wt->name }}">{{ $wt->name }} - S/ {{ number_format($wt->default_price, 2) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                <!-- Material -->
                                                <div>
                                                    <x-input-label x-bind:for="'material_' + index" :value="__('Especifique Material')" />
                                                    <x-text-input x-model="item.material" x-bind:name="'items[' + index + '][material]'" x-bind:id="'material_' + index" type="text" class="mt-1 block w-full rounded-lg" placeholder="Ej: Zirconio" />
                                                </div>

                                                <!-- Color -->
                                                <div>
                                                    <x-input-label x-bind:for="'color_' + index" :value="__('Color / Tono')" />
                                                    <x-text-input x-model="item.color" x-bind:name="'items[' + index + '][color]'" x-bind:id="'color_' + index" type="text" class="mt-1 block w-full rounded-lg" placeholder="Ej: A1" />
                                                </div>

                                                <!-- Monto S/ -->
                                                <div>
                                                    <x-input-label x-bind:for="'price_' + index" :value="__('Precio (S/) *')" />
                                                    <div class="relative mt-1 rounded-lg shadow-sm">
                                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                            <span class="text-gray-500 sm:text-sm">S/</span>
                                                        </div>
                                                        <x-text-input x-model="item.price" x-bind:name="'items[' + index + '][price]'" x-bind:id="'price_' + index" type="number" step="0.01" min="0" class="block w-full rounded-lg border-indigo-200 pl-8 focus:border-indigo-500 focus:ring-indigo-500 font-semibold text-indigo-900 bg-indigo-50/50" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Monto Total Visual -->
                            <div class="mt-6 flex justify-end items-center text-lg">
                                <span class="font-medium text-gray-700 mr-4">Total de la Orden:</span>
                                <span class="font-bold text-2xl text-[#6B46C1]">S/ <span x-text="totalAmount"></span></span>
                            </div>
                        </div>

                        <!-- Fecha de Entrega -->
                        <div>
                            <x-input-label for="due_date" :value="__('Fecha de Entrega Prometida *')" />
                            <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" :value="old('due_date')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4">
                            <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Guardar Orden') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function orderForm() {
        return {
            items: [
                { id: Date.now(), type_name: '', price: 0, material: '', color: '' }
            ],
            workTypes: @json($workTypes),
            
            addItem() {
                this.items.push({ id: Date.now(), type_name: '', price: 0, material: '', color: '' });
            },
            
            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            },
            
            updatePrice(index) {
                const selectedType = this.workTypes.find(wt => wt.name === this.items[index].type_name);
                if (selectedType) {
                    this.items[index].price = parseFloat(selectedType.default_price || 0).toFixed(2);
                }
            },
            
            get totalAmount() {
                return this.items.reduce((sum, item) => sum + parseFloat(item.price || 0), 0).toFixed(2);
            }
        }
    }
</script>
