<x-admin-layout>
    <div >
        <!-- Modal para agregar devolución -->
        <div id="crud-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-4xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Registrar Nueva Devolución
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Cerrar</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form class="p-4 md:p-5" id="devolucionForm" action="{{ route('devoluciones.store') }}" method="POST">
                        @csrf
                        <div class="grid gap-4 mb-4 grid-cols-1">
                            <div>
                                <label for="fecha" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha</label>
                                <input type="datetime-local" name="fecha" id="fecha" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                            </div>
                            
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Productos</label>
                                <div class="space-y-4" id="productos-container">
                                    <!-- Los productos se agregarán aquí dinámicamente -->
                                </div>
                                <button type="button" id="agregar-producto" class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <i class="fas fa-plus mr-2"></i> Agregar Producto
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800">
                            <i class="fas fa-save mr-2"></i> Registrar Devolución
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de devoluciones -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            ID Devolución
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Fecha
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Productos
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Cantidad Total
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devoluciones as $devolucion)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">
                            {{ $devolucion->id_devolucion }}
                        </td>
                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($devolucion->fecha_devolucion)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <ul class="list-disc pl-4">
                                @foreach($devolucion->detalles as $detalle)
                                <li>{{ $detalle->producto->nombre_producto }} ({{ $detalle->cantidad }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-6 py-4">
                            {{ $devolucion->detalles->sum('cantidad') }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('devoluciones.show', $devolucion->id_devolucion) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Ver</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
            <!-- Botón para agregar devolución -->
            <br>
    <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
        Agregar Devolución
    </button>
    </div>
    

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endpush

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Script de devoluciones cargado!"); // Para depuración
        
        const productosContainer = document.getElementById('productos-container');
        const agregarProductoBtn = document.getElementById('agregar-producto');
        const productos = @json($productos ?? []);
        
        console.log("Productos cargados:", productos); // Verifica que los productos se cargan
        
        let productoCount = 0;
        
        function agregarProducto() {
            console.log("Agregando producto...");
            const productoDiv = document.createElement('div');
            productoDiv.className = 'grid grid-cols-1 md:grid-cols-5 gap-4 items-end p-4 bg-gray-50 dark:bg-gray-800 rounded-lg mb-4';
            productoDiv.innerHTML = `
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Producto</label>
                    <select name="productos[${productoCount}][id_producto]" class="select-producto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <option value="">Seleccione un producto</option>
                        ${productos.map(p => `
                            <option value="${p.id_producto}" 
                                    data-costo="${p.costo}"
                                    data-stock="${p.stock}">
                                ${p.codigo} - ${p.nombre} (Stock: ${p.stock})
                            </option>
                        `).join('')}
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cantidad</label>
                    <input type="number" name="productos[${productoCount}][cantidad]" min="1" class="input-cantidad bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Costo Unitario</label>
                    <input type="number" step="0.01" name="productos[${productoCount}][costo]" class="input-costo bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subtotal</label>
                    <input type="text" class="input-subtotal bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                </div>
                <div class="flex items-end">
                    <button type="button" class="btn-eliminar text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800 h-fit">
                        <i class="fas fa-trash mr-2"></i> Eliminar
                    </button>
                </div>
            `;
            
            productosContainer.appendChild(productoDiv);
            
            // Configurar eventos para este producto
            configurarEventosProducto(productoDiv);
            
            productoCount++;
        }
        
        function configurarEventosProducto(productoDiv) {
            const select = productoDiv.querySelector('.select-producto');
            const inputCantidad = productoDiv.querySelector('.input-cantidad');
            const inputCosto = productoDiv.querySelector('.input-costo');
            const inputSubtotal = productoDiv.querySelector('.input-subtotal');
            const btnEliminar = productoDiv.querySelector('.btn-eliminar');
            
            // Actualizar costo y establecer máximo según stock
            select.addEventListener('change', function() {
                if (this.value) {
                    const selectedOption = this.options[this.selectedIndex];
                    inputCosto.value = selectedOption.dataset.costo;
                    inputCantidad.max = selectedOption.dataset.stock;
                    calcularSubtotal();
                }
            });
            
            // Calcular subtotal
            function calcularSubtotal() {
                const cantidad = parseFloat(inputCantidad.value) || 0;
                const costo = parseFloat(inputCosto.value) || 0;
                inputSubtotal.value = (cantidad * costo).toFixed(2);
            }
            
            inputCantidad.addEventListener('input', calcularSubtotal);
            inputCosto.addEventListener('input', calcularSubtotal);
            
            // Eliminar producto
            btnEliminar.addEventListener('click', function() {
                productoDiv.remove();
            });
        }
        
        // Agregar primer producto al cargar
        agregarProducto();
        
        // Manejar clic en "Agregar Producto"
        agregarProductoBtn.addEventListener('click', agregarProducto);
        
        // Manejar envío del formulario
        document.getElementById('devolucionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            console.log("Formulario enviado!");
            this.submit();
        });
    });
    </script>
    @endpush
</x-admin-layout>