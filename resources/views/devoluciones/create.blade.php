<x-admin-layout>
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-black mb-4">Nueva Devolución</h2>
            
            <form action="{{ route('devoluciones.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="fecha" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Fecha de Devolución</label>
                        <input type="datetime-local" name="fecha" id="fecha" 
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                               value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-black mb-3">Productos a Devolver</h3>
                    
                    <div id="productos-container" class="space-y-4 text-gray-900 dark:text-white">
                        <!-- Productos se agregarán aquí dinámicamente -->
                    </div>
                    
                    <button type="button" id="agregar-producto" 
                            class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <i class="fas fa-plus mr-2"></i>Agregar Producto
                    </button>
                </div>
                
                <div class="flex justify-end mt-6">
                    <a href="{{ route('devoluciones.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                        Cancelar
                    </a>
                    <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <i class="fas fa-save mr-2"></i>Guardar Devolución
                    </button>
                </div>
            </form>
        </div>
    

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endpush
    
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Script de devoluciones cargado"); // Para depuración
        
        const productosContainer = document.getElementById('productos-container');
        const agregarProductoBtn = document.getElementById('agregar-producto');
        const productos = @json($productos ?? []);
        
        console.log("Productos recibidos:", productos); // Verifica en consola
        
        let productoCount = 0;
        
        function agregarProducto() {
            const productoDiv = document.createElement('div');
            productoDiv.className = 'grid grid-cols-1 md:grid-cols-5 gap-4 items-end p-4 bg-gray-100 dark:bg-gray-800 rounded-lg mb-4';
            productoDiv.innerHTML = `
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Producto</label>
                    <select name="productos[${productoCount}][id_producto]" class="producto-select bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
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
                    <input type="number" name="productos[${productoCount}][cantidad]" min="1" class="cantidad-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Costo Unitario</label>
                    <input type="number" step="0.01" name="productos[${productoCount}][costo]" class="costo-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subtotal</label>
                    <input type="text" class="subtotal bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                </div>
                <div class="flex items-end">
                    <button type="button" class="eliminar-producto text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            
            productosContainer.appendChild(productoDiv);
            
            // Configurar eventos para este producto
            const select = productoDiv.querySelector('.producto-select');
            const cantidadInput = productoDiv.querySelector('.cantidad-input');
            const costoInput = productoDiv.querySelector('.costo-input');
            const subtotalInput = productoDiv.querySelector('.subtotal');
            const eliminarBtn = productoDiv.querySelector('.eliminar-producto');
            
            // Actualizar costo al seleccionar producto
            select.addEventListener('change', function() {
                if (this.value) {
                    const selected = this.options[this.selectedIndex];
                    costoInput.value = selected.dataset.costo;
                    cantidadInput.max = selected.dataset.stock;
                    calcularSubtotal();
                }
            });
            
            // Calcular subtotal
            function calcularSubtotal() {
                const cantidad = parseFloat(cantidadInput.value) || 0;
                const costo = parseFloat(costoInput.value) || 0;
                subtotalInput.value = (cantidad * costo).toFixed(2);
            }
            
            cantidadInput.addEventListener('input', calcularSubtotal);
            costoInput.addEventListener('input', calcularSubtotal);
            
            // Eliminar producto
            eliminarBtn.addEventListener('click', function() {
                productoDiv.remove();
            });
            
            productoCount++;
        }
        
        // Agregar primer producto al cargar
        agregarProducto();
        
        // Manejar clic en "Agregar Producto"
        agregarProductoBtn.addEventListener('click', agregarProducto);
    });
    </script>
    @endpush

</x-admin-layout>