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
            <label for="fecha_compra" class="form-label">Fecha de Venta</label>&ensp;
            <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" value="{{ \Carbon\Carbon::parse($fecha_compra)->toDateString() }}" readonly>
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
                            <td><input type="text" name="productos[0][codigo_producto]" class="form-control" readonly></td>
                            <td>
                                <select name="productos[0][id_esquema_producto]" class="form-control" required onchange="updateProductData(0)">
                                    <option value="">Seleccionar Producto</option>
                                    @foreach ($productos as $prod)
                                        <option value="{{ $prod->id_esquema_producto }}" data-codigo="{{ $prod->codigo_producto }}">
                                            {{ $prod->nombre_producto }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
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
                            <td><input type="number" name="productos[0][cantidad]" class="form-control" required min="1" step="1"></td>
                            <td><input type="number" name="productos[0][costo]" class="form-control" required  min="0.01" step="0.01"></td>
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

        <div class="flex justify-center gap-4 divide-x divide-gray-300 items-center mb-3">
            <button type="submit" class="text-white inline-flex items-center bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" >                <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                Registrar Compra
            </button>
            <a href="{{ route('compras') }}" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Regresar
            </a>
        </div>
    </form>

    <script>
        function updateProductData(index) {
            const select = document.querySelector(`select[name="productos[${index}][id_esquema_producto]"]`);
            const selectedOption = select.options[select.selectedIndex];

            const codigo = selectedOption.getAttribute('data-codigo') || '';
            const nombre = selectedOption.getAttribute('data-nombre') || '';

            document.querySelector(`input[name="productos[${index}][codigo_producto]"]`).value = codigo;
            document.querySelector(`input[name="productos[${index}][nombre_producto]"]`).value = nombre;
        }

        document.getElementById('addProductBtn').addEventListener('click', function() {
            var tableBody = document.querySelector('#productos_table tbody');
            var rowIndex = tableBody.rows.length;

            var newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" name="productos[${rowIndex}][codigo_producto]" class="form-control" readonly></td>
                <td>
                    <select name="productos[${rowIndex}][id_esquema_producto]" class="form-control" required onchange="updateProductData(${rowIndex})">
                        <option value="">Seleccionar Producto</option>
                        @foreach ($productos as $prod)
                            <option value="{{ $prod->id_esquema_producto }}" data-codigo="{{ $prod->codigo_producto }}">
                                {{ $prod->nombre_producto }}
                            </option>
                        @endforeach
                    </select>
                </td>
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
                <td><input type="number" name="productos[${rowIndex}][cantidad]" class="form-control" required  min="1" step="1"></td>
                <td><input type="number" name="productos[${rowIndex}][costo]" class="form-control" required  min="0.01" step="0.01"></td>
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
