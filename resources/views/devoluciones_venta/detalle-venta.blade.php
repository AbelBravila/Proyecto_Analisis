<x-admin-layout>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-black">Detalle de Venta #{{ $venta->id_venta }}</h2>
        <a href="{{ route('devoluciones_venta.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Búsqueda
        </a>
    </div>

    @if(session('error'))
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Información de la Venta</h3>
            <div class="space-y-2 dark:text-white">
                <p><span class="font-semibold">ID Venta:</span> {{ $venta->id_venta }}</p>
                <p><span class="font-semibold">Fecha:</span> {{ $venta->fecha_venta_formateada }}</p>
                <p><span class="font-semibold">Tipo de Venta:</span> {{ $venta->nombre_tipo_venta }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Información del Cliente</h3>
            <div class="space-y-2 dark:text-white">
                <p><span class="font-semibold">Cliente:</span> {{ $venta->nombre_cliente }}</p>
                <p><span class="font-semibold">NIT:</span> {{ $venta->nit_cliente }}</p>
                <p><span class="font-semibold">Empresa Vendedora:</span> {{ $venta->empresa_vendedora }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('devoluciones_venta.store') }}" method="POST" id="formDevolucionVenta">
        @csrf
        <input type="hidden" name="id_venta" value="{{ $venta->id_venta }}">

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
                        <th scope="col" class="px-6 py-3">Precio Unit.</th>
                        <th scope="col" class="px-6 py-3">Subtotal</th>
                        <th scope="col" class="px-6 py-3">Devolver</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detallesVenta as $detalle)
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
                            Q{{ number_format($detalle->precio, 2) }}
                        </td>
                        <td class="px-6 py-4 font-semibold">
                            Q{{ number_format($detalle->subtotal, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            <input type="number"
                                name="productos[{{ $loop->index }}][cantidad]"
                                min="0"
                                max="{{ $detalle->cantidad }}"
                                class="w-20 px-2 py-1 border rounded"
                                value="0"
                                data-max="{{ $detalle->cantidad }}"
                                onchange="validarCantidadVenta(this)">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="button" onclick="window.location.href='{{ route('devoluciones_venta.create') }}'" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 mr-2">
                Cancelar
            </button>
            <button type="submit" id="btnSubmitVenta" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Guardar Devolución
            </button>
        </div>
    </form>

    <script>
        function validarCantidadVenta(input) {
            const max = parseInt(input.dataset.max);
            const valor = parseInt(input.value) || 0;
    
            if (isNaN(valor) || valor < 0) {
                input.value = 0;
                alert('Por favor ingrese un valor válido');
            } else if (valor > max) {
                input.value = max;
                alert(`No puede devolver más de ${max} unidades`);
            }
        }
    
        document.getElementById('formDevolucionVenta').addEventListener('submit', function(e) {
            const inputs = document.querySelectorAll('input[name^="productos"][name$="[cantidad]"]');
            let hayDevolucion = false;
            let totalDevolver = 0;
    
            inputs.forEach(input => {
                const valor = parseInt(input.value) || 0;
                if (valor > 0) {
                    hayDevolucion = true;
                    totalDevolver += valor;
                }
            });
    
            if (!hayDevolucion) {
                e.preventDefault();
                alert('Debe seleccionar al menos un producto para devolver');
                return false;
            }
            
            // Confirmación de devolución
            if (!confirm(`¿Está seguro de devolver ${totalDevolver} producto(s)? Esta acción no se puede deshacer.`)) {
                e.preventDefault();
                return false;
            }
            
            // Deshabilitar botón para evitar envíos duplicados
            document.getElementById('btnSubmitVenta').disabled = true;
            document.getElementById('btnSubmitVenta').innerHTML = 'Procesando...';
        });
        
        // Inicializar todas las cantidades a 0 al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[name^="productos"][name$="[cantidad]"]');
            inputs.forEach(input => {
                input.value = 0;
            });
        });
    </script>
</x-admin-layout>