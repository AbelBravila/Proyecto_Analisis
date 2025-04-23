<x-admin-layout>
    <b><h1 class="text-lg text-center dark:text-black">Registrar Venta</h1></b>

    <form action="{{ route('ventas.crear') }}" method="POST">
        @csrf

        @php
            $hoy = \Carbon\Carbon::now()->format('Y-m-d');
        @endphp

        <!-- Fecha de venta -->
        <div class="mb-3">
            <label for="fecha_venta" class="form-label">Fecha de Venta</label>
            <input type="date" name="fecha_venta" id="fecha_venta" class="form-control" required max="{{ $hoy }}">
        </div>

        <!-- Cliente -->
        <div class="mb-3">
            <label for="id_cliente" class="form-label">Cliente</label>
            <select name="id_cliente" id="id_cliente" class="form-control" required>
                <option value="">Seleccionar Cliente</option>
                @foreach ($clientesConDescuento as $cliente)
                    <option value="{{ $cliente->id_cliente }}" 
                        data-descuento="{{ $cliente->descuento }}">
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
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="w-full text-sm text-left text-dark-500 dark:text-black">
                        <tr>
                            <td>
                                <select name="productos[0][id_producto]" class="form-control" required onchange="updateProductDetails(0)">
                                    <option value="">Seleccionar Producto</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id_producto }}" 
                                            data-nombre="{{ $producto->esquema->nombre_producto }}" 
                                            data-precio="{{ $producto->precio }}"
                                            data-presentacion="{{ $producto->presentacion->presentacion }}"
                                            data-lote="{{ $producto->lote->lote }}">
                                            {{ $producto->esquema->codigo_producto }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="productos[0][nombre_producto]" class="form-control" disabled></td>
                            <td><input type="number" name="productos[0][cantidad]" class="form-control" required></td>
                            <td><input type="number" name="productos[0][precio]" class="form-control" disabled></td>
                            <td><input type="text" name="productos[0][presentacion]" class="form-control" disabled></td>
                            <td><input type="text" name="productos[0][lote]" class="form-control" disabled></td>
                            <td><button type="button" class="text-blue-600 dark:text-red-500 hover:underline" onclick="eliminarFila(this)">Eliminar</button></td
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary btn-lg text-blue-600 dark:text-blue-500 hover:underline" id="addProductBtn">Agregar Producto</button>
        </div>
        <!-- Total y descuento -->
        <div class="form-group">
            <label for="subtotal">Subtotal</label>
            <input type="text" id="subtotal" class="form-control" value="{{ old('subtotal', 0) }}" readonly>
        </div>

        <div class="form-group">
            <label for="descuento">Descuento</label>
            <input type="text" id="descuento" class="form-control" value="{{ old('descuento', 0) }}" readonly>
        </div>

        <div class="form-group">
            <label for="total">Total</label>
            <input type="text" id="total" class="form-control" value="{{ old('total', 0) }}" readonly>
        </div>

        <div class="mb-3 text-center">
            <button type="submit" class="btn btn-primary">Registrar Venta</button>
        </div>
    </form>



    <script>
        // Función para actualizar los detalles del producto (nombre, precio, presentación, lote)
        function updateProductDetails(index) {
            var selectElement = document.querySelector(`select[name="productos[${index}][id_producto]"]`);
            var selectedOption = selectElement.options[selectElement.selectedIndex];

            var productName = selectedOption.getAttribute('data-nombre');
            var productPrice = parseFloat(selectedOption.getAttribute('data-precio')) || 0; // Asegúrate de que el precio esté definido
            var productPresentation = selectedOption.getAttribute('data-presentacion');
            var productLot = selectedOption.getAttribute('data-lote');

            document.querySelector(`input[name="productos[${index}][nombre_producto]"]`).value = productName;
            document.querySelector(`input[name="productos[${index}][precio]"]`).value = productPrice;
            document.querySelector(`input[name="productos[${index}][presentacion]"]`).value = productPresentation;
            document.querySelector(`input[name="productos[${index}][lote]"]`).value = productLot;

            updateTotals(); // Actualizar los totales cuando se cambia el producto
        }
        document.addEventListener('DOMContentLoaded', function() {
            updateTotals(0); // Llamar con descuento 0 cuando la página cargue sin productos
        });

        // Función para actualizar los totales (Subtotal, Descuento, Total)
        function updateTotals(descuento = 0) {
            let subtotal = 0;

            // Verificamos si hay productos en la tabla
            const rows = document.querySelectorAll('#productos_table tbody tr');
            if (rows.length === 0) {
                // Si no hay productos, establecemos los valores en 0
                document.getElementById('subtotal').value = (0).toFixed(2);
                document.getElementById('descuento').value = (0).toFixed(2);
                document.getElementById('total').value = (0).toFixed(2);
                return; // No continuamos con los cálculos si no hay productos
            }

            // Iteramos sobre las filas para calcular el subtotal
            rows.forEach((row, index) => {
                let cantidad = parseFloat(row.querySelector(`input[name="productos[${index}][cantidad]"]`).value) || 0;
                let precio = parseFloat(row.querySelector(`input[name="productos[${index}][precio]"]`).value) || 0;
                subtotal += cantidad * precio;
            });

            // Mostrar el subtotal
            document.getElementById('subtotal').value = subtotal.toFixed(2);

            // Calcular y mostrar el descuento
            let descuentoMonto = subtotal * (descuento / 100);
            document.getElementById('descuento').value = descuentoMonto.toFixed(2);

            // Calcular y mostrar el total
            let total = subtotal - descuentoMonto;
            document.getElementById('total').value = total.toFixed(2);
        }

        // Función para eliminar una fila de la tabla y actualizar los totales
        function eliminarFila(button) {
            const row = button.closest('tr');
            row.remove();
            updateTotals(); // Actualizar los totales después de eliminar una fila
        }

        document.getElementById('id_cliente').addEventListener('change', function() {
            var tipoCliente = this.value; // Obtener el tipo de cliente seleccionado

            let descuento = 0;
            // Verificar si hay un cliente seleccionado
            if (tipoCliente) {
                // Obtener el descuento del cliente seleccionado (si está disponible)
                const cliente = @json($clientes); // Datos del cliente ya disponibles en la vista
                const clienteSeleccionado = cliente.find(c => c.id_cliente == tipoCliente);

                if (clienteSeleccionado) {
                    descuento = clienteSeleccionado.descuento;
                }
            }

            // Recalcular los totales
            updateTotals(descuento); // Llamar a la función de actualización de totales con el descuento actualizado
        });

        document.getElementById('addProductBtn').addEventListener('click', function() {
            var tableBody = document.querySelector('#productos_table tbody');
            var rowIndex = tableBody.rows.length;

            var newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <select name="productos[${rowIndex}][id_producto]" class="form-control" required onchange="updateProductDetails(${rowIndex})">
                        <option value="">Seleccionar Producto</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id_producto }}" 
                                data-nombre="{{ $producto->esquema->nombre_producto }}" 
                                data-precio="{{ $producto->precio }}" 
                                data-presentacion="{{ $producto->presentacion->presentacion }} "
                                data-lote="{{ $producto->lote->lote }}">
                                {{ $producto->esquema->codigo_producto }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="productos[${rowIndex}][nombre_producto]" class="form-control" readonly></td>
                <td><input type="number" name="productos[${rowIndex}][cantidad]" class="form-control" required min="1" onchange="updateTotals()"></td>
                <td><input type="number" name="productos[${rowIndex}][precio]" class="form-control" required min="0" step="0.01" readonly></td>
                <td><input type="text" name="productos[${rowIndex}][presentacion]" class="form-control" readonly></td>
                <td><input type="text" name="productos[${rowIndex}][lote]" class="form-control" readonly></td>
                <td><button type="button" class="text-blue-600 dark:text-red-500 hover:underline" onclick="eliminarFila(this)">Eliminar</button></td>
            `;
            tableBody.appendChild(newRow);
            updateTotals(); // Actualizar totales al agregar un producto
        });


    </script>
</x-admin-layout>
