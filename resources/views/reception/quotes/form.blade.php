<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ isset($quote) ? __('Editar Cotización #') . $quote->id : __('Nueva Cotización') }}
            </h2>
            <a href="{{ route('quotes.index') }}" class="text-sm text-gray-600 hover:text-gray-900 border border-gray-300 rounded-md px-3 py-1 bg-white shadow-sm font-medium">Volver al listado</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl border border-gray-100">
                <div class="p-8 text-gray-900">
                    
                    <form 
                        action="{{ isset($quote) ? route('quotes.update', $quote) : route('quotes.store') }}" 
                        method="POST" 
                        class="space-y-8" 
                        x-data="quoteForm()"
                    >
                        @csrf
                        @if(isset($quote))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Paciente -->
                            <div>
                                <x-input-label for="patient_id" :value="__('Paciente *')" class="text-gray-700" />
                                <select id="patient_id" name="patient_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm bg-gray-50 text-gray-900 py-2.5" required>
                                    <option value="">Seleccione o busque un paciente</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" 
                                            {{ (old('patient_id', isset($quote) ? $quote->patient_id : '')) == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->name }} @if($patient->dni) - DNI: {{ $patient->dni }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('patient_id')" />
                            </div>

                            <!-- Validez de la Cotización -->
                            <div>
                                <x-input-label for="valid_until" :value="__('Válida Hasta *')" class="text-gray-700" />
                                <x-text-input 
                                    id="valid_until" 
                                    name="valid_until" 
                                    type="date" 
                                    class="mt-1 block w-full rounded-lg bg-gray-50 border-gray-300 py-2.5" 
                                    :value="old('valid_until', isset($quote) ? $quote->valid_until->format('Y-m-d') : now()->addDays(7)->format('Y-m-d'))" 
                                    required 
                                />
                                <p class="text-xs text-gray-500 mt-1">Por defecto: 7 días desde hoy.</p>
                                <x-input-error class="mt-2" :messages="$errors->get('valid_until')" />
                            </div>
                        </div>

                        <!-- Estado (Solo al Editar) -->
                        @if(isset($quote))
                        <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                            <x-input-label for="status" :value="__('Estado Actual')" class="text-indigo-900" />
                            <select id="status" name="status" class="mt-1 block w-full sm:w-1/3 border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-indigo-900 bg-white">
                                <option value="borrador" {{ $quote->status === 'borrador' ? 'selected' : '' }}>Borrador</option>
                                <option value="enviada" {{ $quote->status === 'enviada' ? 'selected' : '' }}>Enviada al Cliente</option>
                                <option value="rechazada" {{ $quote->status === 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                                <option value="vencida" {{ $quote->status === 'vencida' ? 'selected' : '' }}>Vencida</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>
                        @else
                            <input type="hidden" name="status" value="borrador">
                        @endif

                        <!-- Sección de Servicios a Cotizar -->
                        <div class="mt-10 border-t pt-8">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">Servicios o Trabajos a Cotizar</h3>
                                    <p class="text-sm text-gray-500">Agregue los tratamientos y materiales que desee presupuestar.</p>
                                </div>
                                <button type="button" @click="addItem()" class="inline-flex items-center text-sm bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-lg font-medium transition-all shadow-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Añadir Fila
                                </button>
                            </div>

                            <div class="space-y-4">
                                <!-- Lista Dinámica -->
                                <template x-for="(item, index) in items" :key="item.id">
                                    <div class="p-5 bg-white border border-gray-200 rounded-xl relative shadow-sm group hover:border-indigo-300 hover:shadow-md transition-all">
                                        
                                        <!-- Botón Quitar Flotante Absoluto -->
                                        <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="absolute -top-3 -right-3 bg-red-100 text-red-600 hover:bg-red-600 hover:text-white rounded-full p-1.5 shadow-sm transition-colors border border-red-200" title="Quitar línea">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>

                                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                                            
                                            <!-- Trabajo (Ocupa 5 col) -->
                                            <div class="lg:col-span-4">
                                                <x-input-label x-bind:for="'type_' + index" :value="__('Tipo de Trabajo *')" class="text-xs uppercase text-gray-500 font-bold tracking-wider" />
                                                <select 
                                                    x-model="item.type_name" 
                                                    @change="updateDetails(index)" 
                                                    x-bind:name="'items[' + index + '][type_name]'" 
                                                    x-bind:id="'type_' + index" 
                                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm" 
                                                    required
                                                >
                                                    <option value="" disabled>Seleccionar trabajo...</option>
                                                    <template x-for="wt in workTypes" :key="wt.id">
                                                        <option x-bind:value="wt.name" x-text="wt.name"></option>
                                                    </template>
                                                </select>
                                                <!-- Campo oculto para el category ID si hiciese falta a futuro -->
                                                <input type="hidden" x-model="item.work_type_id" x-bind:name="'items[' + index + '][work_type_id]'">
                                            </div>

                                            <!-- Material (Ocupa 3 col) -->
                                            <div class="lg:col-span-3">
                                                <x-input-label x-bind:for="'material_' + index" :value="__('Material (Opcional)')" class="text-xs uppercase text-gray-500 font-bold tracking-wider" />
                                                <x-text-input x-model="item.material" x-bind:name="'items[' + index + '][material]'" x-bind:id="'material_' + index" type="text" class="mt-1 block w-full sm:text-sm rounded-md" placeholder="Ej: Zirconio" />
                                            </div>

                                            <!-- Color (Ocupa 2 col) -->
                                            <div class="lg:col-span-2">
                                                <x-input-label x-bind:for="'color_' + index" :value="__('Color')" class="text-xs uppercase text-gray-500 font-bold tracking-wider" />
                                                <x-text-input x-model="item.color" x-bind:name="'items[' + index + '][color]'" x-bind:id="'color_' + index" type="text" class="mt-1 block w-full sm:text-sm rounded-md" placeholder="A1" />
                                            </div>

                                            <!-- Importe (Ocupa 3 col) -->
                                            <div class="lg:col-span-3">
                                                <x-input-label x-bind:for="'price_' + index" :value="__('Importe S/ *')" class="text-xs uppercase text-indigo-600 font-bold tracking-wider" />
                                                <div class="relative mt-1">
                                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                        <span class="text-gray-500 sm:text-sm">S/</span>
                                                    </div>
                                                    <x-text-input 
                                                        x-model="item.price" 
                                                        x-bind:name="'items[' + index + '][price]'" 
                                                        x-bind:id="'price_' + index" 
                                                        type="number" step="0.01" min="0" 
                                                        class="block w-full pl-8 sm:text-sm rounded-md border-indigo-300 focus:border-indigo-500 focus:ring-indigo-500 bg-indigo-50 font-semibold" 
                                                        required 
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Totales de Cotización Visibles -->
                            <div class="mt-8 bg-gray-50 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-center border border-gray-200">
                                <div class="text-gray-500 text-sm mb-4 md:mb-0">
                                    <p>Las cotizaciones no generan órdenes de trabajo automáticamente.</p>
                                    <p>Se aprobarán una vez el paciente confirme el presupuesto.</p>
                                </div>
                                <div class="text-right w-full md:w-auto">
                                    <div class="flex justify-between text-gray-600 mb-2 w-full md:w-64">
                                        <span>Subtotal:</span>
                                        <span class="font-medium">S/ <span x-text="totalAmount"></span></span>
                                    </div>
                                    <div class="flex justify-between text-gray-600 mb-2 w-full md:w-64 pb-2 border-b border-gray-300">
                                        <span>IGV (0% ref):</span>
                                        <span class="font-medium">S/ 0.00</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xl w-full md:w-64 mt-2">
                                        <span class="font-semibold text-gray-900">Total a Pagar:</span>
                                        <span class="font-bold text-indigo-700 bg-indigo-100 px-3 py-1 rounded-lg">S/ <span x-text="totalAmount"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                            <a href="{{ route('quotes.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ isset($quote) ? __('Actualizar Cotización') : __('Guardar Cotización') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function quoteForm() {
        return {
            items: @json(isset($quote) ? $quote->lines_json : [['id' => time(), 'work_type_id' => '', 'type_name' => '', 'price' => 0, 'material' => '', 'color' => '']]),
            workTypes: @json($workTypes),
            
            addItem() {
                this.items.push({ id: Date.now(), work_type_id: '', type_name: '', price: 0, material: '', color: '' });
            },
            
            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            },
            
            updateDetails(index) {
                const selectedTypeName = this.items[index].type_name;
                const match = this.workTypes.find(wt => wt.name === selectedTypeName);
                if (match) {
                    this.items[index].work_type_id = match.id;
                    this.items[index].price = parseFloat(match.default_price).toFixed(2);
                }
            },
            
            get totalAmount() {
                return this.items.reduce((sum, item) => sum + parseFloat(item.price || 0), 0).toFixed(2);
            }
        }
    }
</script>
