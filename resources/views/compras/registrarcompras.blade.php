<x-admin-layout>
    <b><h1 class="text-lg text-center dark:text-black">Registrar Compra</h1></b>

    <style>
        .form-control {
            border-radius: 10px; /* Para bordes redondeados */
        } 

    </style>

    <form action="{{ route('compras.crear') }}" method="POST">
        @csrf

        @php
            $hoy = \Carbon\Carbon::now()->format('Y-m-d');
        @endphp

        <!-- Fecha de compra -->
        <div class="mb-3">
            <label for="fecha_compra" class="form-label">Fecha de Compra</label>&ensp;
            <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" required max="{{ $hoy }}">
        </div>

        <!-- Tipo de compra -->
        <div class="mb-3">
            <label for="id_tipo_compra" class="form-label">Tipo de Compra</label>&ensp;
            <select name="id_tipo_compra" id="id_tipo_compra" class="form-control" required>
                <option value="">Seleccionar Tipo de Compra</option>
                @foreach ($tipo_compra as $tipo)
                    <option value="{{ $tipo->id_tipo_compra }}">{{ $tipo->nombre_tipo_compra }}</option>
                @endforeach
            </select>
        </div>

        <!-- Proveedor -->
        <div class="mb-3">
            <label for="id_proveedor" class="form-label">Proveedor</label>&ensp;
            <select name="id_proveedor" id="id_proveedor" class="form-control" required>
                <option value="">Seleccionar Proveedor</option>
                @foreach ($proveedores as $proveedor)
                    <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->nombre_proveedor }}</option>
                @endforeach
            </select>

        <br>
        <br>
        <!-- Productos -->
        <div class="mb-3">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table id="productos_table" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-white">
                    <thead class="text-m text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-white">
                        <tr>
                            <th class="px-6 py-3">Código Producto</th>
                            <th class="px-6 py-3">Nombre Producto</th>
                            <th class="px-6 py-3">Lote</th>
                            <th class="px-6 py-3">Fabricante</th>
                            <th class="px-6 py-3">Fecha Fabricación</th>
                            <th class="px-6 py-3">Fecha Vencimiento</th>
                            <th class="px-6 py-3">Presentación</th>
                            <th class="px-6 py-3">Cantidad</th>
                            <th class="px-6 py-3">Costo</th>
                            <th class="px-6 py-3">Estantería</th> 
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="w-full text-sm text-left text-gray-500 dark:text-black">
                        <!-- Fila de ejemplo para el primer producto -->
                        <tr>
                            <td>
                                <select name="productos[0][id_esquema_producto]" class="form-control" required onchange="updateProductName(0)">
                                    <option value="">Seleccionar Producto</option>
                                    @foreach ($productos as $prod)
                                        <option value="{{ $prod->id_esquema_producto }}" data-nombre="{{ $prod->nombre_producto }}">
                                            {{ $prod->codigo_producto }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="productos[0][nombre_producto]" class="form-control" readonly></td>
                            <td><input type="text" name="productos[0][lote]" class="form-control" required></td>
                            <td><input type="text" name="productos[0][fabricante]" class="form-control" required></td>
                            <td><input type="date" name="productos[0][fecha_fabricacion]" class="form-control" required max="{{ $hoy }}"></td>
                            <td><input type="date" name="productos[0][fecha_vencimiento]" class="form-control" required min="{{ $hoy }}"></td>
                            <td>
                                <select name="productos[0][id_presentacion]" class="form-control" required>
                                    <option value="">Seleccionar Presentación</option>
                                    @foreach ($presentaciones as $presentacion)
                                        <option value="{{ $presentacion->id_presentacion }}">{{ $presentacion->presentacion }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="productos[0][cantidad]" class="form-control" required min=0></td>
                            <td><input type="number" name="productos[0][costo]" class="form-control" required min=0></td>
                            <td>
                                <select name="productos[0][id_estanteria]" class="form-control" required>
                                    <option value="">Seleccionar Estantería</option>
                                    @foreach ($estanterias as $estanteria)
                                        <option value="{{ $estanteria->id_estanteria }}">{{ $estanteria->codigo_estanteria }}</option>
                                    @endforeach
                                </select>
                            </td> 
                            <td class="px-6 py-3 dark:text-black"><button type="button" class="text-blue-600 dark:text-red-500 hover:underline" onclick="eliminarFila(this)">Eliminar</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            
        </div>
        <button type="button" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" id="addProductBtn">
            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
            Agregar Producto
        </button><br>

        <div class="mb-3 text-center">
        <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
            Registrar Compra
        </button>
        </div>
    </form>

    <!-- Script para agregar productos y autocompletar el nombre del producto -->
    <script>
        // Actualiza el nombre del producto en la columna correspondiente
        function updateProductName(index) {
            var selectElement = document.querySelector(`select[name="productos[${index}][id_esquema_producto]"]`);
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var productName = selectedOption.getAttribute('data-nombre');
            document.querySelector(`input[name="productos[${index}][nombre_producto]"]`).value = productName;
        }

        // Agregar una nueva fila al hacer clic en el botón "Agregar Producto"
        document.getElementById('addProductBtn').addEventListener('click', function() {
            var tableBody = document.querySelector('#productos_table tbody');
            var rowIndex = tableBody.rows.length;

            var newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <select name="productos[${rowIndex}][id_esquema_producto]" class="form-control" required onchange="updateProductName(${rowIndex})">
                        <option value="">Seleccionar Producto</option>
                        @foreach ($productos as $prod)
                            <option value="{{ $prod->id_esquema_producto }}" data-nombre="{{ $prod->nombre_producto }}">
                                {{ $prod->codigo_producto }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="productos[${rowIndex}][nombre_producto]" class="form-control" readonly></td>
                <td><input type="text" name="productos[${rowIndex}][lote]" class="form-control" required></td>
                <td><input type="text" name="productos[${rowIndex}][fabricante]" class="form-control" required></td>
                <td><input type="date" name="productos[${rowIndex}][fecha_fabricacion]" class="form-control" required max="{{ $hoy }}"></td>
                <td><input type="date" name="productos[${rowIndex}][fecha_vencimiento]" class="form-control" required min="{{ $hoy }}"></td>
                <td>
                    <select name="productos[${rowIndex}][id_presentacion]" class="form-control" required>
                        <option value="">Seleccionar Presentación</option>
                        @foreach ($presentaciones as $presentacion)
                            <option value="{{ $presentacion->id_presentacion }}">{{ $presentacion->presentacion }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="productos[${rowIndex}][cantidad]" class="form-control" required min=0></td>
                <td><input type="number" name="productos[${rowIndex}][costo]" class="form-control" required min=0></td>
                <td>
                    <select name="productos[${rowIndex}][id_estanteria]" class="form-control" required>
                        <option value="">Seleccionar Estantería</option>
                        @foreach ($estanterias as $estanteria)
                            <option value="{{ $estanteria->id_estanteria }}">{{ $estanteria->codigo_estanteria }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="px-6 py-3 dark:text-black"><button type="button" class="text-blue-600 dark:text-red-500 hover:underline" onclick="eliminarFila(this)">Eliminar</button></td>
            `;
            tableBody.appendChild(newRow);
        });

        function eliminarFila(button) {
        const row = button.closest('tr');
        row.remove();
        }
    </script>
</x-admin-layout>
