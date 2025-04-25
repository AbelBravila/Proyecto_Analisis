<x-admin-layout>
    <b><h1 class="text-lg text-center dark:text-black">Registrar Venta</h1></b>

    <form action="{{ route('ventas.crear') }}" method="POST">
        @csrf

        @php
            $hoy = \Carbon\Carbon::now()->format('Y-m-d');
        @endphp

        <style>
        .form-control {
            border-radius: 10px; /* Cambia el valor según la redondez que desees */
        }
        </style>

        <!-- Fecha de venta -->
        <div class="mb-3">
            <label for="fecha_venta" class="form-label">Fecha de Venta</label>
            <input type="date" name="fecha_venta" id="fecha_venta" class="form-control" required max="{{ $hoy }}">
        </div>

        <!-- Cliente -->
        <div class="mb-3">
            <label for="id_cliente" class="form-label">Cliente</label>
            <select name="id_cliente" id="id_cliente" class="form-control" required onchange="updateClienteDescuento()">
                <option value="">Seleccionar Cliente</option>
                @foreach ($clientesConDescuento as $cliente)
                    <option value="{{ $cliente->id_cliente }}" data-descuento="{{ $cliente->descuento }}">
                        {{ $cliente->nombre_cliente }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Tipo de venta -->
        <div class="mb-3">
            <label for="id_tipo_venta" class="form-label">Tipo de Venta</label>
            <select name="id_tipo_venta" id="id_tipo_venta" class="form-control" required>
                <option value="">Seleccionar Tipo de Venta</option>
                @foreach ($tipo_venta as $tipo)
                    <option value="{{ $tipo->id_tipo_venta }}">{{ $tipo->nombre_tipo_venta }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tipo de pago -->
        <div class="mb-3">
            <label for="id_tipo_pago" class="form-label">Tipo de Pago</label>
            <select name="id_tipo_pago" id="id_tipo_pago" class="form-control" required>
                <option value="">Seleccionar Tipo de Pago</option>
                @foreach ($tipo_pago as $tipo_p)
                    <option value="{{ $tipo_p->id_tipo_pago }}">{{ $tipo_p->nombre_tipo_pago }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tipo de documento -->
        <div class="mb-3">
            <label for="id_tipo_documento" class="form-label">Tipo de Documento</label>
            <select name="id_tipo_documento" id="id_tipo_documento" class="form-control" required>
                <option value="">Seleccionar Tipo de Documento</option>
                @foreach ($tipo_documento as $tipo_d)
                    <option value="{{ $tipo_d->id_tipo_documento }}">{{ $tipo_d->nombre_documento }}</option>
                @endforeach
            </select>
        </div>

        <!-- Productos -->
        <div class="mb-3">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table id="productos_table" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-white">
                    <thead class="text-m text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-white">
                        <tr>
                            <th class="px-6 py-3">Código Producto</th>
                            <th class="px-6 py-3">Nombre Producto</th>
                            <th class="px-6 py-3">Cantidad</th>
                            <th class="px-6 py-3">Precio</th>
                            <th class="px-6 py-3">Presentación</th>
                            <th class="px-6 py-3">Lote</th>
                            <th class="px-6 py-3">Total Producto</th>
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="productos_body" class="w-full text-sm text-left text-gray-500 dark:text-black">
                        <tr>
                            <td>
                            <select name="productos[0][id_producto]" class="form-control" required onchange="updateDetails(0)">
                                <option value="">Seleccionar Producto</option>
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->id_producto }}" 
                                            data-nombre="{{ $producto->esquema->nombre_producto }}" 
                                            data-precio="{{ $producto->precio }}"
                                            data-lote="{{ $producto->lote->lote }}">
                                        {{ $producto->esquema->codigo_producto }}
                                    </option>
                                @endforeach
                            </select>
                            </td>
                            <td><input type="text" name="productos[0][nombre_producto]" class="form-control" readonly></td>
                            <td><input type="number" name="productos[0][cantidad]" class="form-control" required min="1" onchange="updateDetails(0)"></td>
                            <td><input type="number" name="productos[0][precio_p]" class="form-control" readonly required min=0></td>
                            <td>
                            <select name="productos[0][id_presentacion_venta]" class="form-control" required onchange="updateDetails(0)">
                                <option value="">Seleccionar Presentación</option>
                                @foreach ($presentaciones as $presentacion)
                                    <option value="{{ $presentacion->id_presentacion_venta }}" 
                                            data-cantidad="{{ $presentacion->cantidad }}" 
                                            data-descuento="{{ $presentacion->descuento }}">
                                        {{ $presentacion->nombre_presentacion }}
                                    </option>
                                @endforeach
                            </select>
                            </td>
                            <td><input type="text" name="productos[0][lote]" class="form-control" readonly></td>
                            <td><input type="text" name="productos[0][total_producto]" class="form-control" readonly></td>
                            <td><button type="button" class="text-blue-600 dark:text-red-500 hover:underline" onclick="eliminarFila(this)">Eliminar</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary btn-lg text-blue-600 dark:text-blue-500 hover:underline" id="addProductBtn">Agregar Producto</button>
        </div>

        <!-- Totales -->
        <div class="form-group">
            <label for="subtotal">Subtotal</label>
            <input type="text" id="subtotal" class="form-control" value="{{ old('subtotal', 0) }}" readonly>
        </div>
        <br>
        <div class="form-group">
            <label for="descuento">Descuento</label>
            <input type="text" id="descuento" class="form-control" value="{{ old('descuento', 0) }}" readonly>
        </div>
        <br>
        <div class="form-group">
            <label for="total">Total</label>
            <input type="text" id="total" class="form-control" value="{{ old('total', 0) }}" readonly>
        </div>
        <br>
        <div class="mb-3 text-center">
            <button type="submit" class="btn btn-outline-secondary btn-lg text-blue-600 dark:text-blue-500 hover:underline" >Registrar Venta</button>
        </div>
    </form>

    <script>
        // Variable global para el descuento
        let descuentoGlobal = 0;

        // Función que se llama cuando se selecciona un cliente
        function updateClienteDescuento() {
            const clienteSelect = document.getElementById('id_cliente');
            const selectedOption = clienteSelect.options[clienteSelect.selectedIndex];
            descuentoGlobal = parseFloat(selectedOption.getAttribute('data-descuento')) || 0;

            // No actualizar el campo de descuento visualmente
            // document.getElementById('descuento').value = descuentoGlobal.toFixed(2);
            
            // Actualizamos los totales con el nuevo descuento
            updateTotals();
        }

        // Función para actualizar los detalles de la presentación (descuento y cantidad)
        function updateDetails(index) {
            var selectProducto = document.querySelector(`select[name="productos[${index}][id_producto]"]`);
            var selectedOption = selectProducto.options[selectProducto.selectedIndex];
            var selectPresentation = document.querySelector(`select[name="productos[${index}][id_presentacion_venta]"]`);
            var selectedPresentation = selectPresentation.options[selectPresentation.selectedIndex];

            var productName = selectedOption.getAttribute('data-nombre');
            var productPrice = parseFloat(selectedOption.getAttribute('data-precio').replace(',', '.')) || 0;
            var productLot = selectedOption.getAttribute('data-lote');
            var presentationQuantity = parseInt(selectedPresentation.getAttribute('data-cantidad')) || 1;
            var presentationDiscount = parseFloat(selectedPresentation.getAttribute('data-descuento')) || 0;

            // Calculamos el precio ajustado por la cantidad y el descuento de la presentación
            var adjustedPrice = (productPrice * presentationQuantity) * (1 - (presentationDiscount / 100));

            // Actualizamos el precio unitario
            document.querySelector(`input[name="productos[${index}][precio_p]"]`).value = adjustedPrice.toFixed(2);
            document.querySelector(`input[name="productos[${index}][nombre_producto]"]`).value = productName;
            document.querySelector(`input[name="productos[${index}][lote]"]`).value = productLot;

            updateTotalProducto(index); // Actualizamos el total por producto
            updateTotals();
        }



        // Función para calcular el total por producto
        function updateTotalProducto(index) {
            const cantidad = parseFloat(document.querySelector(`input[name="productos[${index}][cantidad]"]`).value) || 0;
            const precio = parseFloat(document.querySelector(`input[name="productos[${index}][precio_p]"]`).value) || 0;
            const totalProducto = cantidad * precio; // Calculamos el total para esa fila
            document.querySelector(`input[name="productos[${index}][total_producto]"]`).value = totalProducto.toFixed(2);
        }

        // Función para actualizar los totales (Subtotal, Descuento, Total)
        function updateTotals() {
            let subtotal = 0;
            let descuentoMonto = 0;
            let total = 0;

            // Sumar los totales de todos los productos
            const rows = document.querySelectorAll('#productos_table tbody tr');
            rows.forEach((row, index) => {
                let totalProducto = parseFloat(row.querySelector(`input[name="productos[${index}][total_producto]"]`).value) || 0;
                subtotal += totalProducto; // Acumulamos el subtotal
            });

            descuentoMonto = subtotal * (descuentoGlobal / 100);
            total = subtotal - descuentoMonto;

            // Mostrar el subtotal
            document.getElementById('subtotal').value = subtotal.toFixed(2);
            document.getElementById('descuento').value = descuentoMonto.toFixed(2); // Esto es opcional, si quieres mostrar el descuento.
            document.getElementById('total').value = total.toFixed(2);
        }

        // Función para eliminar una fila de la tabla y actualizar los totales
        function eliminarFila(button) {
            const row = button.closest('tr');
            row.remove();
            updateTotals(); // Actualizar los totales después de eliminar una fila
        }

        // Agregar producto
        document.getElementById('addProductBtn').addEventListener('click', function() {
            var tableBody = document.querySelector('#productos_table tbody');
            var rowIndex = tableBody.rows.length;

            var newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <select name="productos[${rowIndex}][id_producto]" class="form-control" required onchange="updateDetails(${rowIndex})">
                        <option value="">Seleccionar Producto</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id_producto }}" 
                                    data-nombre="{{ $producto->esquema->nombre_producto }}" 
                                    data-precio="{{ $producto->precio }} "
                                    data-lote="{{ $producto->lote->lote }}">
                                {{ $producto->esquema->codigo_producto }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="productos[${rowIndex}][nombre_producto]" class="form-control" readonly></td>
                <td><input type="number" name="productos[${rowIndex}][cantidad]" class="form-control" required min="1" onchange="updateDetails(${rowIndex})"></td>
                <td><input type="number" name="productos[${rowIndex}][precio_p]" class="form-control" readonly required min=0></td>
                <td>
                    <select name="productos[${rowIndex}][id_presentacion_venta]" class="form-control" required onchange="updateDetails(${rowIndex})">
                        <option value="">Seleccionar Presentación</option>
                        @foreach ($presentaciones as $presentacion)
                            <option value="{{ $presentacion->id_presentacion_venta }}" 
                                    data-cantidad="{{ $presentacion->cantidad }}" 
                                    data-descuento="{{ $presentacion->descuento }}">
                                {{ $presentacion->nombre_presentacion }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="productos[${rowIndex}][lote]" class="form-control" readonly></td>
                <td><input type="text" name="productos[${rowIndex}][total_producto]" class="form-control" readonly></td>
                <td><button type="button" class="text-blue-600 dark:text-red-500 hover:underline" onclick="eliminarFila(this)">Eliminar</button></td>
            `;
            tableBody.appendChild(newRow);
        });

    </script>
</x-admin-layout>
