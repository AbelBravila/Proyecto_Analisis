<x-admin-layout>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-black">Detalle de Venta #{{ $venta->id_venta }}</h2>
        <a href="{{ route('devoluciones_venta.create') }}"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Volver a Búsqueda
        </a>
    </div>

    @if (session('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- Información de la venta -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Información de la Venta</h3>
            <div class="space-y-2 dark:text-white">
                <input type="hidden" name="id_venta" value="{{ $venta->id_venta }}">
                <p><span class="font-semibold">ID Venta:</span> {{ $venta->id_venta }}</p>
                <p><span class="font-semibold">Fecha:</span> {{ $venta->fecha_venta_formateada }}</p>
                <p><span class="font-semibold">Tipo de Venta:</span> {{ $venta->nombre_tipo_venta }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Información del Cliente</h3>
            <div class="space-y-2 dark:text-white">
                <input type="hidden" name="id_cliente" value="{{ $venta->id_cliente }}">
                <p><span class="font-semibold">Cliente:</span> {{ $venta->nombre_cliente }}</p>
                <p><span class="font-semibold">NIT:</span> {{ $venta->nit_cliente }}</p>
                <p><span class="font-semibold">Empresa Vendedora:</span> {{ $venta->empresa_vendedora }}</p>
            </div>
        </div>
    </div>

    <!-- Formulario de devolución -->
    <form action="{{ route('devoluciones_venta.store') }}" method="POST" id="formDevolucionVenta">
        @csrf
        <input type="hidden" name="id_venta" value="{{ $venta->id_venta }}">

        <!-- Campos ocultos para cada producto -->
        @foreach ($detallesVenta as $index => $detalle)
            <input type="hidden" name="productos[{{ $index }}][id_producto]"
                value="{{ $detalle->id_producto }}">
            <input type="hidden" name="productos[{{ $index }}][precio]" value="{{ $detalle->precio }}">
            <input type="hidden" name="productos[{{ $index }}][cantidad]" value="0">
            <input type="hidden" name="productos[{{ $index }}][tipo_devolucion]" value="">
            <input type="hidden" name="productos[{{ $index }}][producto_cambio_id]" value="">
            <input type="hidden" name="productos[{{ $index }}][danado]" value="0">
        @endforeach

        <!-- Tabla de productos -->
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
                        <th scope="col" class="px-6 py-3">Información</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detallesVenta as $index => $detalle)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                            data-index="{{ $index }}">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $detalle->nombre_producto }}
                                <input type="hidden" name="productos[{{ $index }}][nombre_producto]"
                                    value="{{ $detalle->nombre_producto }}">
                            </td>
                            <td class="px-6 py-4">{{ $detalle->descripcion_producto }}</td>
                            <td class="px-6 py-4">{{ $detalle->presentacion }}</td>
                            <td class="px-6 py-4">{{ $detalle->fabricante }}</td>
                            <td class="px-6 py-4">{{ $detalle->lote }}</td>
                            <td class="px-6 py-4">{{ $detalle->ubicacion_almacen }}</td>
                            <td class="px-6 py-4">{{ $detalle->cantidad }}</td>
                            <td class="px-6 py-4">Q{{ number_format($detalle->precio, 2) }}</td>
                            <td class="px-6 py-4 font-semibold">Q{{ number_format($detalle->subtotal, 2) }}</td>
                            <td class="px-6 py-4">
                                <button type="button" onclick="abrirModal({{ $index }})"
                                    class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded">
                                    Devolver
                                </button>
                            </td>
                            <!-- En tu tabla, modifica la última columna para incluir el contenedor de info -->
                            <td class="px-6 py-4 devolucion-info">
                                <!-- Aquí se insertará la información de devolución -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Agrega esto antes de los botones de acción -->
        <div class="mt-6 flex justify-between items-center">
            <div id="resumenDevolucion" class="text-lg font-semibold">
                <!-- Aquí se mostrará el resumen -->
            </div>
            <div class="flex space-x-4">
                <button type="button" onclick="window.location.href='{{ route('devoluciones_venta.create') }}'"
                    class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 mr-2">
                    Cancelar
                </button>
                <button type="submit" id="btnSubmitVenta"
                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Guardar Devolución
                </button>
            </div>

        </div>
    </form>

    <!-- Modal -->
    <div id="modalDevolucion" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 mx-auto my-8">
            <h3 class="text-lg font-semibold mb-4">Devolución del producto</h3>
            <input type="hidden" id="modalIndex">

            <div class="mb-4">
                <label class="block text-sm font-medium">Producto:</label>
                <p class="font-semibold" id="modalProductoNombre"></p>
                <p class="text-sm text-gray-600">Cantidad vendida: <span id="modalCantidadVendida"></span></p>
                <p class="text-sm text-gray-600">Precio unitario: <span id="modalPrecioUnitario"></span></p>
                <p class="text-sm text-gray-600 font-bold">Subtotal: <span id="modalSubtotal"></span></p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Cantidad a devolver:</label>
                <input type="number" id="cantidadInput" min="1" value="1"
                    class="w-full px-3 py-2 border rounded" oninput="validarCantidad(this)" />
                <p class="text-red-500 text-xs hidden" id="errorCantidad">La cantidad no puede ser mayor a la vendida
                </p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">¿Es un cambio?</label>
                <select id="tipoDevolucionSelect" class="w-full px-3 py-2 border rounded">
                    <option value="">-- Seleccionar --</option>
                    <option value="N">No (Devolución con reembolso)</option>
                    <option value="C">Sí (Cambio por otro producto)</option>
                </select>
            </div>

            <div class="mb-4 hidden" id="productoCambioContainer">
                <label class="block text-sm font-medium">Producto de cambio:</label>
                <select id="productoCambioSelect" class="w-full px-3 py-2 border rounded">
                    @foreach ($productosDisponibles as $producto)
                        <option value="{{ $producto->id_producto }}" data-precio="{{ $producto->precio }}"
                            data-id_presentacion="{{ $producto->presentacion }}"
                            data-stock="{{ $producto->stock }}">
                            {{ $producto->nombre_producto }} -
                            Precio: Q.{{ number_format($producto->precio, 2) }} -
                            Stock: {{ $producto->stock }} -
                            Presentacion: {{ $producto->presentacion }}
                        </option>
                    @endforeach
                </select>
                <div class="mt-2">
                    <label class="block text-sm font-medium">Cantidad del nuevo producto:</label>
                    <input type="number" id="cantidadCambioInput" min="1" value="1"
                        class="w-full px-3 py-2 border rounded" />
                </div>
                <div class="mt-2 p-2 bg-gray-100 rounded hidden" id="diferenciaContainer">
                    <p class="text-sm font-semibold">Diferencia:</p>
                    <p id="diferenciaMonto" class="text-lg"></p>
                </div>
            </div>

            <div class="mb-4 hidden" id="montoDevolverContainer">
                <div class="p-2 bg-gray-100 rounded">
                    <p class="text-sm font-semibold">Monto a devolver:</p>
                    <p id="montoDevolver" class="text-lg text-green-600">Q0.00</p>
                </div>
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" id="danadoCheckbox" class="mr-2">
                    Producto dañado
                </label>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="cancelarDevolucion()"
                    class="bg-gray-500 text-white px-4 py-2 rounded">
                    Cancelar
                </button>
                <button type="button" onclick="guardarDatosModal()"
                    class="bg-green-600 text-white px-4 py-2 rounded">
                    Guardar
                </button>
            </div>
        </div>
    </div>
    <script>
        // Variables globales
        let devolucionesPorProducto = {};
        let detallesVentaData = {!! json_encode(
            $detallesVenta->mapWithKeys(function ($item) {
                    return [
                        $item->id_producto => [
                            'id_producto' => $item->id_producto,
                            'nombre_producto' => $item->nombre_producto ?? '',
                            'descripcion_producto' => $item->descripcion_producto ?? '',
                            'presentacion' => $item->presentacion ?? '',
                            'fabricante' => $item->fabricante ?? '',
                            'lote' => $item->lote ?? '',
                            'cantidad' => intval($item->cantidad) ?? 0,
                            'precio' => floatval($item->precio) ?? 0,
                            'subtotal' => floatval($item->subtotal) ?? 0,
                        ],
                    ];
                })->toArray(),
        ) !!};

        // Función para abrir el modal de devolución
        function abrirModal(index) {
            const detalleInput = document.querySelector(`input[name="productos[${index}][id_producto]"]`);
            if (!detalleInput) return;

            const detalle = detallesVentaData[detalleInput.value];
            if (!detalle) return;

            // Convertir valores a números
            const precio = parseFloat(detalle.precio) || 0;
            const cantidad = parseInt(detalle.cantidad) || 0;
            const subtotal = precio * cantidad;

            document.getElementById('modalIndex').value = index;
            document.getElementById('modalProductoNombre').textContent = detalle.nombre_producto || 'Producto desconocido';
            document.getElementById('modalCantidadVendida').textContent = cantidad;
            document.getElementById('modalPrecioUnitario').textContent = 'Q' + precio.toFixed(2);
            document.getElementById('modalSubtotal').textContent = 'Q' + subtotal.toFixed(2);

            // Obtener datos existentes para este producto o inicializar nuevos
            const productoId = detalleInput.value;
            const datosProducto = devolucionesPorProducto[productoId] || {
                cantidad: 0,
                tipo_devolucion: '',
                producto_cambio_id: null,
                cantidad_cambio: 0,
                monto_diferencia: 0,
                monto_devolver: 0,
                danado: false
            };

            // Establecer valores en el modal
            document.getElementById('cantidadInput').value = datosProducto.cantidad;
            document.getElementById('cantidadInput').max = cantidad;
            document.getElementById('tipoDevolucionSelect').value = datosProducto.tipo_devolucion;
            document.getElementById('danadoCheckbox').checked = datosProducto.danado;

            const container = document.getElementById('productoCambioContainer');
            if (datosProducto.tipo_devolucion === 'C') {
                container.classList.remove('hidden');
                document.getElementById('productoCambioSelect').value = datosProducto.producto_cambio_id || '';
                document.getElementById('cantidadCambioInput').value = datosProducto.cantidad_cambio || 1;
            } else {
                container.classList.add('hidden');
            }

            // Mostrar el modal
            const modal = document.getElementById('modalDevolucion');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Calcular montos iniciales
            calcularDiferencia();
        }

        // Función para cerrar el modal
        function cerrarModal() {
            const modal = document.getElementById('modalDevolucion');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Función para cancelar la devolución
        function cancelarDevolucion() {
            const index = document.getElementById('modalIndex').value;
            const detalleInput = document.querySelector(`input[name="productos[${index}][id_producto]"]`);

            if (detalleInput) {
                const productoId = detalleInput.value;
                delete devolucionesPorProducto[productoId];

                const row = document.querySelector(`tr[data-index="${index}"]`);
                if (row) {
                    const devolucionCell = row.querySelector('.devolucion-info');
                    if (devolucionCell) {
                        devolucionCell.innerHTML = '';
                    }
                }
            }

            actualizarResumenGlobal();
            cerrarModal();
        }

        // Función para validar el stock del producto de cambio
        function validarStockProductoCambio() {
            const productoSelect = document.getElementById('productoCambioSelect');
            const cantidadInput = document.getElementById('cantidadCambioInput');
            const errorStock = document.getElementById('errorStock');

            if (!productoSelect || !cantidadInput) return;

            const selectedOption = productoSelect.options[productoSelect.selectedIndex];
            const stockDisponible = parseInt(selectedOption.getAttribute('data-stock')) || 0;
            const cantidadRequerida = parseInt(cantidadInput.value) || 0;

            if (cantidadRequerida > stockDisponible) {
                if (!errorStock) {
                    const errorElement = document.createElement('p');
                    errorElement.id = 'errorStock';
                    errorElement.className = 'text-red-500 text-xs';
                    errorElement.textContent = `No hay suficiente stock. Disponible: ${stockDisponible}`;
                    cantidadInput.parentNode.appendChild(errorElement);
                } else {
                    errorStock.textContent = `No hay suficiente stock. Disponible: ${stockDisponible}`;
                    errorStock.classList.remove('hidden');
                }
                cantidadInput.value = stockDisponible;
            } else if (errorStock) {
                errorStock.classList.add('hidden');
            }
        }

        // Función para actualizar el resumen global de devoluciones
        function actualizarResumenGlobal() {
            const resumenElement = document.getElementById('resumenDevolucion');
            let totalDevolver = 0;
            let totalCobrar = 0;
            let hayDevolucionesValidas = false;

            // Calcular totales
            Object.values(devolucionesPorProducto).forEach(producto => {
                if (producto.cantidad > 0 && producto.tipo_devolucion) {
                    hayDevolucionesValidas = true;
                    if (producto.tipo_devolucion === 'C') {
                        if (producto.monto_diferencia > 0) {
                            totalDevolver += producto.monto_diferencia;
                        } else if (producto.monto_diferencia < 0) {
                            totalCobrar += Math.abs(producto.monto_diferencia);
                        }
                    } else if (producto.tipo_devolucion === 'N') {
                        totalDevolver += producto.monto_devolver;
                    }
                }
            });

            // Mostrar el resumen
            if (hayDevolucionesValidas) {
                resumenElement.classList.remove('hidden');

                if (totalDevolver > 0 && totalCobrar === 0) {
                    resumenElement.innerHTML =
                        `<span class="text-red-600">Total a devolver: Q${totalDevolver.toFixed(2)}</span>`;
                } else if (totalCobrar > 0 && totalDevolver === 0) {
                    resumenElement.innerHTML =
                        `<span class="text-green-600">Total a cobrar: Q${totalCobrar.toFixed(2)}</span>`;
                } else if (totalDevolver > totalCobrar) {
                    const diferencia = totalDevolver - totalCobrar;
                    resumenElement.innerHTML =
                        `<span class="text-red-600">Total a devolver: Q${diferencia.toFixed(2)}</span>`;
                } else if (totalCobrar > totalDevolver) {
                    const diferencia = totalCobrar - totalDevolver;
                    resumenElement.innerHTML =
                        `<span class="text-green-600">Total a cobrar: Q${diferencia.toFixed(2)}</span>`;
                } else {
                    resumenElement.innerHTML = `<span class="text-blue-600">Transacción equilibrada</span>`;
                }
            } else {
                resumenElement.classList.add('hidden');
                resumenElement.innerHTML = '';
            }
        }

        // Función para validar la cantidad ingresada
        function validarCantidad(input) {
            const max = parseInt(input.max);
            const value = parseInt(input.value) || 0;
            const errorElement = document.getElementById('errorCantidad');

            if (value > max) {
                errorElement.classList.remove('hidden');
                input.value = max;
            } else {
                errorElement.classList.add('hidden');
            }

            calcularDiferencia();
        }

        // Función para calcular la diferencia en el modal
        function calcularDiferencia() {
            const index = document.getElementById('modalIndex').value;
            const detalleInput = document.querySelector(`input[name="productos[${index}][id_producto]"]`);
            if (!detalleInput) return;

            const detalle = detallesVentaData[detalleInput.value];
            if (!detalle) return;

            const cantidadDevolver = parseInt(document.getElementById('cantidadInput').value) || 0;
            const precioOriginal = parseFloat(detalle.precio) || 0;
            const montoDevolver = cantidadDevolver * precioOriginal;

            const tipoDevolucion = document.getElementById('tipoDevolucionSelect').value;
            const montoDevolverContainer = document.getElementById('montoDevolverContainer');
            const diferenciaContainer = document.getElementById('diferenciaContainer');

            if (tipoDevolucion === 'C') {
                // Cálculo para cambio de producto
                const productoCambioSelect = document.getElementById('productoCambioSelect');
                const precioCambio = parseFloat(productoCambioSelect.options[productoCambioSelect.selectedIndex]
                    .getAttribute('data-precio'));
                const cantidadCambio = parseInt(document.getElementById('cantidadCambioInput').value) || 0;
                const montoCambio = cantidadCambio * precioCambio;

                const diferencia = montoDevolver - montoCambio;
                const diferenciaMonto = document.getElementById('diferenciaMonto');

                diferenciaContainer.classList.remove('hidden');
                montoDevolverContainer.classList.add('hidden');

                if (diferencia > 0) {
                    diferenciaMonto.textContent = `Se debe devolver: Q${diferencia.toFixed(2)}`;
                    diferenciaMonto.className = 'text-lg text-red-600';
                } else if (diferencia < 0) {
                    diferenciaMonto.textContent = `El cliente debe pagar: Q${Math.abs(diferencia).toFixed(2)}`;
                    diferenciaMonto.className = 'text-lg text-green-600';
                } else {
                    diferenciaMonto.textContent = 'Transacción equilibrada';
                    diferenciaMonto.className = 'text-lg text-blue-600';
                }
            } else {
                // Mostrar monto simple a devolver
                montoDevolverContainer.classList.remove('hidden');
                diferenciaContainer.classList.add('hidden');
                document.getElementById('montoDevolver').textContent = `Q${montoDevolver.toFixed(2)}`;
            }
        }

        // Función para guardar los datos del modal
        function guardarDatosModal() {
            const index = document.getElementById('modalIndex').value;
            const detalleInput = document.querySelector(`input[name="productos[${index}][id_producto]"]`);
            if (!detalleInput) return;

            const detalle = detallesVentaData[detalleInput.value];
            if (!detalle) return;

            const cantidad = parseInt(document.getElementById('cantidadInput').value) || 0;
            const maxCantidad = parseInt(document.getElementById('cantidadInput').max);
            const tipoDevolucion = document.getElementById('tipoDevolucionSelect').value;

            // Validaciones
            if (cantidad <= 0) {
                alert("Ingrese una cantidad válida.");
                return;
            }

            if (cantidad > maxCantidad) {
                document.getElementById('errorCantidad').classList.remove('hidden');
                return;
            }

            // Nueva validación para el tipo de devolución
            if (!tipoDevolucion) {
                alert("Debe seleccionar un tipo de devolución (¿Es un cambio?).");
                return;
            }

            const precio = parseFloat(detalle.precio) || 0;
            const productoId = detalleInput.value;

            let producto_cambio_id = null;
            let cantidad_cambio = 0;
            let monto_diferencia = 0;
            let monto_devolver = cantidad * precio;

            if (tipoDevolucion === 'C') {
                producto_cambio_id = document.getElementById('productoCambioSelect').value;
                cantidad_cambio = parseInt(document.getElementById('cantidadCambioInput').value) || 1;

                const productoCambioSelect = document.getElementById('productoCambioSelect');
                const precioCambio = parseFloat(productoCambioSelect.options[productoCambioSelect.selectedIndex]
                    .getAttribute('data-precio'));
                const montoCambio = cantidad_cambio * precioCambio;

                monto_diferencia = monto_devolver - montoCambio;
            }

            const danado = document.getElementById('danadoCheckbox').checked;

            // Guardar los datos
            devolucionesPorProducto[productoId] = {
                cantidad: cantidad,
                precio: precio,
                tipo_devolucion: tipoDevolucion,
                producto_cambio_id,
                cantidad_cambio,
                monto_diferencia,
                monto_devolver: tipoDevolucion === 'N' ? monto_devolver : 0,
                danado
            };

            // Actualizar la tabla
            const row = document.querySelector(`tr[data-index="${index}"]`);
            if (row) {
                let devolucionCell = row.querySelector('.devolucion-info');
                if (!devolucionCell) {
                    devolucionCell = document.createElement('td');
                    devolucionCell.className = 'px-6 py-4 devolucion-info';
                    row.appendChild(devolucionCell);
                }

                let info = `Devolver: ${cantidad}`;
                if (tipoDevolucion === 'C') {
                    const productoNombre = document.querySelector(
                        `#productoCambioSelect option[value="${producto_cambio_id}"]`).text.split(' - ')[0];
                    info += ` (Cambio por: ${productoNombre} x ${cantidad_cambio})`;

                    if (monto_diferencia !== 0) {
                        info += ` | Dif: Q${Math.abs(monto_diferencia).toFixed(2)} `;
                        info += monto_diferencia > 0 ? '(a devolver)' : '(a cobrar)';
                    }
                } else {
                    info += ` | Monto a devolver: Q${monto_devolver.toFixed(2)}`;
                }
                if (danado) info += ' (Dañado)';

                devolucionCell.innerHTML = info;
            }

            actualizarResumenGlobal();
            cerrarModal();
        }

        // Event listeners cuando el DOM está cargado
        document.addEventListener('DOMContentLoaded', function() {
            // Evento para cambiar el tipo de devolución
            document.getElementById('tipoDevolucionSelect')?.addEventListener('change', function() {
                const container = document.getElementById('productoCambioContainer');
                container.classList.toggle('hidden', this.value !== 'C');
                calcularDiferencia();
            });

            // Evento para cambios en la cantidad a devolver
            document.getElementById('cantidadInput')?.addEventListener('input', function() {
                calcularDiferencia();
            });

            // Evento para cambios en el producto de cambio
            document.getElementById('productoCambioSelect')?.addEventListener('change', function() {
                validarStockProductoCambio();
                calcularDiferencia();
            });

            // Evento para cambios en la cantidad del producto de cambio
            document.getElementById('cantidadCambioInput')?.addEventListener('input', function() {
                validarStockProductoCambio();
                calcularDiferencia();
            });

            // Evento para el envío del formulario
            // En tu función de envío del formulario, modifica así:
            document.getElementById('formDevolucionVenta').addEventListener('submit', function(e) {
                e.preventDefault();

                // Convertir el objeto devolucionesPorProducto a array
                const productosArray = Object.entries(devolucionesPorProducto)
                    .filter(([id, p]) => p.cantidad > 0)
                    .map(([id, p]) => ({
                        id_producto: id, // solo agregas el id del producto aquí
                        cantidad: p.cantidad,
                        precio: p.precio,
                        tipo_devolucion: p.tipo_devolucion,
                        producto_cambio_id: p.producto_cambio_id,
                        cantidad_cambio: p.cantidad_cambio,
                        monto_diferencia: p.monto_diferencia,
                        monto_devolver: p.monto_devolver,
                        danado: p.danado
                    }));

                // Crear FormData
                const formData = new FormData();
                formData.append('id_venta', document.querySelector('input[name="id_venta"]').value);
                formData.append('id_cliente', {{ $venta->id_cliente }});
                formData.append('productos', JSON.stringify(productosArray)); // <-- Asegúrate de stringify

                // Agregar CSRF token
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                // Enviar
                fetch("{{ route('devoluciones_venta.store') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest' // Importante para Laravel
                        }
                    })
                    .then(response => response.json())
                    // .then(data => console.log(data));
                    .then(data => {
                        if (data.success) {
                            window.location.href = "{{ route('devoluciones_venta.index') }}";
                        } else {
                            alert(data.message || 'Error al procesar la devolución');
                        }
                    });
                // .catch(error => {
                //     console.error('Error:', error);
                //     alert('Error de conexión');
                // });
            });

            // Función auxiliar para agregar inputs ocultos
            function addHiddenInput(form, name, value) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                form.appendChild(input);
            }
        });
    </script>
</x-admin-layout>
