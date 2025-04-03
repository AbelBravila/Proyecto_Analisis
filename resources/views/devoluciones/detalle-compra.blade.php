<x-admin-layout>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-black">Detalle de Compra #{{ $compra->id_compra }}</h2>
        <a href="{{ route('devoluciones.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Búsqueda
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Información de la Compra</h3>
            <div class="space-y-2 dark:text-white">
                <p><span class="font-semibold">ID Compra:</span> {{ $compra->id_compra }}</p>
                <p><span class="font-semibold">Fecha:</span> {{ $compra->fecha_compra_formateada }}</p>
                <p><span class="font-semibold">Tipo de Compra:</span> {{ $compra->nombre_tipo_compra }}</p>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Información del Proveedor</h3>
            <div class="space-y-2 dark:text-white">
                <p><span class="font-semibold">Proveedor:</span> {{ $compra->nombre_proveedor }}</p>
                <p><span class="font-semibold">NIT:</span> {{ $compra->nit }}</p>
                <p><span class="font-semibold">Empresa Compradora:</span> {{ $compra->empresa_compradora }}</p>
            </div>
        </div>
    </div>
    
    <form action="{{ route('devoluciones.store') }}" method="POST" id="formDevolucion">
        @csrf
        <input type="hidden" name="id_compra" value="{{ $compra->id_compra }}">
        
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-6">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Producto</th>
                        <th scope="col" class="px-6 py-3">Descripción</th>
                        <th scope="col" class="px-6 py-3">Presentación</th>
                        <th scope="col" class="px-6 py-3">Fabricante</th>
                        <th scope="col" class="px-6 py-3">Lote</th>
                        <th scope="col" class="px-6 py-3">Ubicación</th>
                        <th scope="col" class="px-6 py-3">Cantidad</th>
                        <th scope="col" class="px-6 py-3">Costo Unit.</th>
                        <th scope="col" class="px-6 py-3">Subtotal</th>
                        <th scope="col" class="px-6 py-3">Devolver</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detallesCompra as $detalle)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $detalle->nombre_producto }}
                            <input type="hidden" name="productos[{{ $loop->index }}][nombre_producto]" value="{{ $detalle->nombre_producto }}">
                        </td>
                        <td class="px-6 py-4">
                            {{ $detalle->descripcion_producto }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $detalle->presentacion }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $detalle->fabricante }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $detalle->lote }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $detalle->ubicacion_almacen }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $detalle->cantidad }}
                        </td>
                        <td class="px-6 py-4">
                            ${{ number_format($detalle->costo_unitario, 2) }}
                        </td>
                        <td class="px-6 py-4 font-semibold">
                            ${{ number_format($detalle->subtotal, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            <input type="number" 
                                name="productos[{{ $loop->index }}][cantidad]" 
                                min="0" 
                                max="{{ $detalle->cantidad }}" 
                                class="w-20 px-2 py-1 border rounded" 
                                value="0" 
                                data-max="{{ $detalle->cantidad }}"
                                onchange="validarCantidad(this)">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button type="button" onclick="window.location.href='{{ route('devoluciones.create') }}'" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 mr-2">
                Cancelar
            </button>
            <button type="submit" id="btnSubmit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Guardar Devolución
            </button>
        </div>
    </form>
    
    <script>
        function validarCantidad(input) {
            const max = parseInt(input.dataset.max);
            const valor = parseInt(input.value);
            
            if (valor < 0) {
                input.value = 0;
            } else if (valor > max) {
                input.value = max;
                alert(`No puede devolver más de ${max} unidades`);
            }
        }
        
        document.getElementById('formDevolucion').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Verificar si hay al menos un producto con cantidad > 0
            const inputs = document.querySelectorAll('input[name^="productos"][name$="[cantidad]"]');
            let hayDevolucion = false;
            
            inputs.forEach(input => {
                if (parseInt(input.value) > 0) {
                    hayDevolucion = true;
                }
            });
            
            if (!hayDevolucion) {
                alert('Debe seleccionar al menos un producto para devolver');
                return;
            }
            
            this.submit();
        });
    </script>
</x-admin-layout>