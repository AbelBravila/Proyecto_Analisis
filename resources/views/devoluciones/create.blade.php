<x-admin-layout>
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-black">Registrar Devolución </h2>
            <a href="{{ route('devoluciones.index') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Volver al listado
            </a>
        </div>
        
        <!-- Filtros de búsqueda -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Buscar Compras</h3>
            
            <div class="grid gap-4 mb-4 grid-cols-1 md:grid-cols-3">
                <div>
                    <label for="proveedor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Proveedor</label>
                    <select id="proveedor" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Todos los proveedores</option>
                        @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->nombre_proveedor }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="fecha_inicio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha Inicio</label>
                    <input type="date" id="fecha_inicio" value="<?php echo date('Y-m-d'); ?>"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <div>
                    <label for="fecha_fin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha Fin</label>
                    <input type="date" id="fecha_fin" value="<?php echo date('Y-m-d'); ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
            </div>
            
            <button id="buscar-compras" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <i class="fas fa-search mr-2"></i> Buscar Compras
            </button>
        </div>
        
        <!-- Contenedor para resultados -->
        <div id="resultados" class="hidden mt-6">
            <div class="overflow-x-auto relative">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Fecha</th>
                            <th scope="col" class="px-6 py-3">Tipo</th>
                            <th scope="col" class="px-6 py-3">Proveedor</th>
                            <th scope="col" class="px-6 py-3">Productos</th>
                            <th scope="col" class="px-6 py-3">Total</th>
                            <th scope="col" class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="compras-list">
                        <!-- Aquí se cargarán los registros -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Mensaje de no resultados -->
        <div id="no-resultados" class="hidden mt-6 p-4 bg-yellow-50 text-yellow-800 rounded-lg">
            No se encontraron compras con los criterios seleccionados.
        </div>
    </div>

    <!-- IMPORTANTE: Pon el script directamente aquí, no dentro de un push -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const buscarBtn = document.getElementById('buscar-compras');
        const resultados = document.getElementById('resultados');
        const comprasList = document.getElementById('compras-list');
        const noResultados = document.getElementById('no-resultados');

        if (buscarBtn) {
            buscarBtn.addEventListener('click', function () {
                // Obtener valores de los campos de búsqueda
                const proveedor = document.getElementById('proveedor').value;
                const fechaInicio = document.getElementById('fecha_inicio').value;
                const fechaFin = document.getElementById('fecha_fin').value;

                // Enviar solicitud AJAX al backend
                fetch('{{ route("devoluciones.buscar") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        proveedor: proveedor || null,
                        fecha_inicio: fechaInicio || null,
                        fecha_fin: fechaFin || null
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Limpiar la tabla
                    comprasList.innerHTML = '';

                    if (data.length > 0) {
                    // Mostrar resultados
                    resultados.classList.remove('hidden');
                    noResultados.classList.add('hidden');

                    data.forEach(compra => {
                        const row = document.createElement('tr');
                        row.className = 'bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600';
                        
                        // Crear la URL de manera dinámica usando un template de ruta
                        const detalleUrl = "{{ route('devoluciones.compra.detalle', ':id') }}".replace(':id', compra.id_compra);
                        
                        row.innerHTML = `
                            <td class="px-6 py-4">${compra.id_compra}</td>
                            <td class="px-6 py-4">${compra.fecha_compra}</td>
                            <td class="px-6 py-4">${compra.nombre_tipo_compra}</td>
                            <td class="px-6 py-4">${compra.nombre_proveedor}</td>
                            <td class="px-6 py-4">
                                <ul class="list-disc pl-4">
                                    ${compra.productos.map(producto => `
                                        <li>
                                            ${producto.nombre_producto} (${producto.presentacion}) - 
                                            Cantidad: ${producto.cantidad}, 
                                            Costo Unitario: $${parseFloat(producto.costo_unitario).toFixed(2)}, 
                                            Subtotal: $${parseFloat(producto.subtotal).toFixed(2)}
                                        </li>
                                    `).join('')}
                                </ul>
                            </td>
                            <td class="px-6 py-4">Q${parseFloat(compra.total).toFixed(2)}</td> <!-- Mostramos el total acumulado -->
                            <td class="px-6 py-4">
                                <a href="${detalleUrl}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                    <i class="fas fa-check-circle"></i> Seleccionar
                                </a>
                            </td>
                        `;
                        comprasList.appendChild(row);
                    });
                } else {
                    // Mostrar mensaje de "No hay resultados"
                    resultados.classList.add('hidden');
                    noResultados.classList.remove('hidden');
                }
                })
                .catch(error => {
                    console.error('Error al buscar compras:', error);
                });
            });
        }
    });
    </script>

    <!-- Si necesitas estilos adicionales -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-admin-layout>