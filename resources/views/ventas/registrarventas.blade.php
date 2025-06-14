<x-admin-layout>
    <b><h1 class="text-lg text-center dark:text-black ">Registrar Venta</h1></b>

    <form action="{{ route('ventas.crear') }}" method="POST">
        @csrf

        <style>
        .form-control {
            border-radius: 10px; /* Para bordes redondeados */
        } 

        </style>

        <!-- Fecha de venta -->
        <div class="mb-3">
            <label for="fecha_venta" class="form-label">Fecha de Venta</label>&ensp;
            <input type="date" name="fecha_venta" id="fecha_venta" class="form-control" value="{{ \Carbon\Carbon::parse($fecha_venta)->toDateString() }}" readonly>
        </div>

        <!-- Cliente -->
        <div class="mb-3">
            <label for="id_cliente" class="form-label">Cliente</label>&ensp;
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
            <label for="id_tipo_venta" class="form-label">Tipo de Venta</label>&ensp;
            <select name="id_tipo_venta" id="id_tipo_venta" class="form-control" required>
                <option value="">Seleccionar Tipo de Venta</option>
                @foreach ($tipo_venta as $tipo)
                    <option value="{{ $tipo->id_tipo_venta }}">{{ $tipo->nombre_tipo_venta }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tipo de pago -->
        <div class="mb-3">
            <label for="id_tipo_pago" class="form-label">Tipo de Pago</label> &ensp;
            <select name="id_tipo_pago" id="id_tipo_pago" class="form-control" required>
                <option value="">Seleccionar Tipo de Pago</option>
                @foreach ($tipo_pago as $tipo_p)
                    <option value="{{ $tipo_p->id_tipo_pago }}">{{ $tipo_p->nombre_tipo_pago }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tipo de documento -->
        <div class="mb-3">
            <label for="id_tipo_documento" class="form-label">Tipo de Documento</label>&ensp;
            <select name="id_tipo_documento" id="id_tipo_documento" class="form-control" required>
                <option value="">Seleccionar Tipo de Documento</option>
                @foreach ($tipo_documento as $tipo_d)
                    <option value="{{ $tipo_d->id_tipo_documento }}">{{ $tipo_d->nombre_documento }}</option>
                @endforeach
            </select>
        </div>

        <script>
            const productosData = @json($productosAgrupados);
        </script>

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
                                <input type="text" name="productos[0][codigo_producto]" class="form-control" readonly>
                            </td>
                            <td>
                                <select name="productos[0][nombre_producto]" class="form-control" required onchange="onNombreProductoChange(0)">
                                    <option value="">Seleccionar Producto</option>
                                    @foreach ($productos->unique('esquema.codigo_producto') as $producto)
                                        <option value="{{ $producto->esquema->codigo_producto }}" data-oferta="{{ $producto->oferta ? 1 : 0 }}"
                                        data-codigo="{{ $producto->esquema->codigo_producto }}" data-nombre="{{ $producto->esquema->nombre_producto }}">
                                            {{ $producto->esquema->nombre_producto }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="productos[0][cantidad]" class="form-control" required min="1" step="1" onchange="updateDetails(0)"></td>
                            <td><input type="number" name="productos[0][precio_p]" class="form-control" readonly></td>
                            <td>
                                <select name="productos[0][id_presentacion_venta]" class="form-control presentacion-select" required onchange="updateDetails(0)">
                                    <option value="">Seleccionar Presentación</option>
                                </select>
                            </td>
                            <!--  
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
                            </td> -->
                            <td>
                                <select name="productos[0][id_producto]" class="form-control" required onchange="onLoteChange(0)">
                                    <option value="">Seleccionar Lote</option>
                                </select>
                            </td>
                            <td><input type="text" name="productos[0][total_producto]" class="form-control" readonly></td>
                            <td class="px-6 py-3 dark:text-black"><button type="button" class="text-blue-600 dark:text-red-500 hover:underline" onclick="eliminarFila(this)">Eliminar</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mb-3">
            <button type="button" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" id="addProductBtn">
            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                Agregar Producto
            </button>
        </div>

        <!-- Totales -->
        <div class="form-group">
            <label for="subtotal">Subtotal</label>&ensp;
            <input type="text" id="subtotal" class="form-control" value="{{ old('subtotal', 0) }}" readonly>
        </div>
        <br>
        <div class="form-group">
            <label for="descuento">Descuento</label>&ensp;
            <input type="text" id="descuento" class="form-control" value="{{ old('descuento', 0) }}" readonly>
        </div>
        <br>
        <div class="form-group">
            <label for="total">Total</label>&ensp;
            <input type="text" id="total" class="form-control" value="{{ old('total', 0) }}" readonly>
        </div>
        <br>
        <div class="flex justify-center gap-4 divide-x divide-gray-300 items-center mb-3">
            <button type="submit" class="text-white inline-flex items-center bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" >
            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                Registrar Venta
            </button>
            <a href="{{ route('ventas') }}" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Regresar
            </a>
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
            updateTotals();
        }


        function onNombreProductoChange(index) {
            const nombreSelect = document.querySelector(`select[name="productos[${index}][nombre_producto]"]`);
            const loteSelect = document.querySelector(`select[name="productos[${index}][id_producto]"]`);
            const codigoInput = document.querySelector(`input[name="productos[${index}][codigo_producto]"]`);

            const codigo = nombreSelect.value; // el value sigue siendo el código
            loteSelect.innerHTML = `<option value="">Seleccionar Lote</option>`;

            if (codigo && productosData[codigo]) {
                const lotes = productosData[codigo];
                codigoInput.value = codigo; // se muestra el código relacionado al nombre seleccionado

                lotes.forEach((item) => {
                    const option = document.createElement('option');
                    option.value = item.id_producto;
                    option.text = item.lote;
                    option.dataset.precio = item.precio;
                    option.dataset.lote = item.lote;
                    loteSelect.appendChild(option);
                });
            } else {
                codigoInput.value = '';
            }
            // Limpia precio y lote
            document.querySelector(`input[name="productos[${index}][precio_p]"]`).value = '';
        }

        const presentaciones = @json($presentaciones);
        const productosAgrupados = @json($productosAgrupados);
        const productos = @json($productos);

        function onLoteChange(index) {
            const loteSelect = document.querySelector(`select[name="productos[${index}][id_producto]"]`);
            const precio = loteSelect.options[loteSelect.selectedIndex]?.dataset?.precio || 0;
            const lote = loteSelect.options[loteSelect.selectedIndex]?.dataset?.lote || '';

            document.querySelector(`input[name="productos[${index}][precio_p]"]`).value = parseFloat(precio).toFixed(2);
            // document.querySelector(`input[name="productos[${index}][lote]"]`).value = lote;

            const selectPresentacion = document.querySelector(`select[name="productos[${index}][id_presentacion_venta]"]`);
            selectPresentacion.innerHTML = `<option value="">Seleccionar Presentación</option>`;

            const productoId = loteSelect.value;
            console.log('Producto seleccionado ID:', productoId);
            const producto = productos.find(p => p.id_producto == productoId);
            console.log('Producto encontrado:', producto);

            if (!producto) {
                console.warn('Producto no encontrado');
                return;
            }

            const oferta = producto.oferta;
            console.log('Oferta del producto:', oferta);
            console.log('Presentaciones disponibles:', presentaciones);

            presentaciones.forEach(p => {
                if (oferta) {
                    if (p.id_presentacion_venta == 3) {
                        const option = document.createElement('option');
                        option.value = p.id_presentacion_venta;
                        option.textContent = p.nombre_presentacion;
                        option.dataset.cantidad = p.cantidad;
                        option.dataset.descuento = p.descuento;
                        selectPresentacion.appendChild(option);
                    }
                } else {
                    const option = document.createElement('option');
                    option.value = p.id_presentacion_venta;
                    option.textContent = p.nombre_presentacion;
                    option.dataset.cantidad = p.cantidad;
                    option.dataset.descuento = p.descuento;
                    selectPresentacion.appendChild(option);
                }
            });

            updateDetails(index);
        }

        /*function onLoteChange(index) {
            const loteSelect = document.querySelector(`select[name="productos[${index}][id_producto]"]`);
            const precio = loteSelect.options[loteSelect.selectedIndex]?.dataset?.precio || 0;
            const lote = loteSelect.options[loteSelect.selectedIndex]?.dataset?.lote || '';

            document.querySelector(`input[name="productos[${index}][precio_p]"]`).value = parseFloat(precio).toFixed(2);
            document.querySelector(`input[name="productos[${index}][lote]"]`).value = lote;

            updateDetails(index); // Para recalcular total del producto y actualizar totales
        }*/

        // Función para actualizar los detalles de la presentación (descuento y cantidad)
        function updateDetails(index) {
            // obtenemos el select de presentación y el option seleccionado
            const presSel = document.querySelector(`select[name="productos[${index}][id_presentacion_venta]"]`);
            const presOpt = presSel.options[presSel.selectedIndex];

            // obtenemos el select de lote y el option seleccionado
            const loteSel = document.querySelector(`select[name="productos[${index}][id_producto]"]`);
            const loteOpt = loteSel.options[loteSel.selectedIndex];

            // base de precio (viene de dataset.precio del option de lote)
            const basePrecio = parseFloat(loteOpt?.dataset.precio) || 0;
            // cantidad y descuento de la presentación
            const mult    = parseFloat(presOpt?.dataset.cantidad) || 1;
            const descuentoPres = parseFloat(presOpt?.dataset.descuento) || 0;

            // ajustamos el precio unitario
            const precioAjustado = (basePrecio * mult) * (1 - descuentoPres/100);
            document.querySelector(`input[name="productos[${index}][precio_p]"]`).value = precioAjustado.toFixed(2);

            // recalcular total de la fila y totales generales
            updateTotalProducto(index);
        }



        // Función para calcular el total por producto
        function updateTotalProducto(index) {
            const cantidad = parseFloat(document.querySelector(`input[name="productos[${index}][cantidad]"]`).value) || 0;
            const precio = parseFloat(document.querySelector(`input[name="productos[${index}][precio_p]"]`).value) || 0;
            const totalProducto = cantidad * precio; // Calculamos el total para esa fila
            document.querySelector(`input[name="productos[${index}][total_producto]"]`).value = totalProducto.toFixed(2);
            updateTotals();
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

       /*function updateProductData(index) {
            const selectProducto = document.querySelector(`select[name="productos[${index}][id_esquema_producto]"]`);
            const selectedOption = selectProducto.options[selectProducto.selectedIndex];

            const codigo = selectedOption.getAttribute('data-codigo') || '';
            const nombre = selectedOption.getAttribute('data-nombre') || '';
            const oferta = selectedOption.getAttribute('data-oferta') == '1';

            document.querySelector(`input[name="productos[${index}][codigo_producto]"]`).value = codigo;

            // Obtener el select de presentaciones
            const selectPresentacion = document.querySelector(`select[name="productos[${index}][id_presentacion]"]`);
            selectPresentacion.innerHTML = `<option value="">Seleccionar Presentación</option>`;

            // Lista completa de presentaciones en JS (inyectada desde PHP)
            const presentaciones = @json($presentaciones);

            presentaciones.forEach(p => {
                if (!oferta || p.id_presentacion == 3) {
                    const option = document.createElement('option');
                    option.value = p.id_presentacion;
                    option.textContent = p.presentacion;
                    selectPresentacion.appendChild(option);
                }
            });
        }*/


        // Agregar producto
        document.getElementById('addProductBtn').addEventListener('click', function() {
            var tableBody = document.querySelector('#productos_table tbody');
            var rowIndex = tableBody.rows.length;

            var newRow = document.createElement('tr');
            newRow.innerHTML = `
                            <td>
                                <input type="text" name="productos[${rowIndex}][codigo_producto]" class="form-control" readonly>
                            </td>
                            <td>
                                <select name="productos[${rowIndex}][nombre_producto]" class="form-control" required onchange="onNombreProductoChange(${rowIndex})">
                                    <option value="">Seleccionar Producto</option>
                                    @foreach ($productos->unique('esquema.codigo_producto') as $producto)
                                        <option value="{{ $producto->esquema->codigo_producto }}">
                                            {{ $producto->esquema->nombre_producto }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="productos[${rowIndex}][cantidad]" class="form-control" required min="1" step="1" onchange="updateDetails(${rowIndex})"></td>
                            <td><input type="number" name="productos[${rowIndex}][precio_p]" class="form-control" readonly></td>
                            <td>
                                <select name="productos[${rowIndex}][id_presentacion_venta]" class="form-control presentacion-select" required onchange="updateDetails(${rowIndex})">
                                    <option value="">Seleccionar Presentación</option>
                                </select>
                            </td>
                            <td>
                                <select name="productos[${rowIndex}][id_producto]" class="form-control" required onchange="onLoteChange(${rowIndex})">
                                    <option value="">Seleccionar Lote</option>
                                </select>
                            </td>
                            <td><input type="text" name="productos[${rowIndex}][total_producto]" class="form-control" readonly></td>
                            <td class="px-6 py-3 dark:text-black"><button type="button" class="text-blue-600 dark:text-red-500 hover:underline" onclick="eliminarFila(this)">Eliminar</button></td>                    
            `;
            tableBody.appendChild(newRow);
        });
    </script>
</x-admin-layout>
