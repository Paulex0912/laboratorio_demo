<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('expenses.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Nueva Rendición de Gastos
            </h2>
        </div>
    </x-slot>

    <div class="py-12" x-data="expenseForm()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                @if($errors->any())
                    <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <!-- Datos Generales -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
                    <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5">Motivo del Gasto</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Título de la Rendición <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required value="{{ old('title') }}" placeholder="Ej: Gastos de representación Viaje Arequipa" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Líneas Dinámicas -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Comprobantes Adjuntos</h3>
                        <div class="text-xl font-bold text-indigo-600">Total: S/ <span x-text="calculateTotal()"></span></div>
                    </div>

                    <div class="divide-y divide-gray-100 bg-gray-50/50 p-6 space-y-6">
                        <template x-for="(line, index) in lines" :key="index">
                            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative group">
                                <button type="button" @click="removeLine(index)" class="absolute -top-3 -right-3 bg-red-100 text-red-600 hover:bg-red-200 w-8 h-8 rounded-full flex items-center justify-center shadow-sm opacity-0 group-hover:opacity-100 transition-opacity" x-show="lines.length > 1">
                                    <svg class="w-4 h-4 shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
                                    <div class="col-span-1 md:col-span-3">
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Categoría</label>
                                        <select :name="`lines[${index}][category_id]`" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" x-model="line.category_id">
                                            <option value="">Seleccione...</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-1 md:col-span-4">
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Descripción del Ítem</label>
                                        <input type="text" :name="`lines[${index}][description]`" x-model="line.description" required placeholder="Consumo Almuerzo / Movilidad" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div class="col-span-1 md:col-span-2">
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Monto (S/)</label>
                                        <input type="number" :name="`lines[${index}][amount]`" x-model="line.amount" required step="0.01" min="0.01" class="w-full text-sm font-semibold border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div class="col-span-1 md:col-span-3">
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Foto Comprobante <span class="text-indigo-500 text-[10px]">(Max 5MB)</span></label>
                                        <input type="file" :name="`lines[${index}][receipt]`" accept="image/png, image/jpeg, application/pdf" class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors cursor-pointer">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="p-6 bg-white border-t border-gray-100">
                        <button type="button" @click="addLine" class="inline-flex items-center px-4 py-2 border border-dashed border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:text-indigo-600 hover:border-indigo-400 hover:bg-indigo-50 bg-gray-50 transition-colors w-full justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Agregar otro comprobante o línea de gasto
                        </button>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex justify-end gap-3 items-center">
                    <button type="submit" name="action" value="draft" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-colors shadow-sm">
                        Guardar Borrador
                    </button>
                    <button type="submit" name="action" value="submit" class="px-5 py-2.5 bg-indigo-600 border border-transparent text-white font-semibold rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                        Enviar para Aprobación
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('expenseForm', () => ({
                lines: [
                    { category_id: '', description: '', amount: '' }
                ],
                addLine() {
                    this.lines.push({ category_id: '', description: '', amount: '' });
                },
                removeLine(index) {
                    if (this.lines.length > 1) {
                        this.lines.splice(index, 1);
                    }
                },
                calculateTotal() {
                    let total = 0;
                    this.lines.forEach(line => {
                        let amount = parseFloat(line.amount);
                        if (!isNaN(amount)) {
                            total += amount;
                        }
                    });
                    return total.toFixed(2);
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
