<x-admin-layout>
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">¡Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    <div class="p-4">
        <!-- Botón para agregar devolución -->
        <a href="{{ route('devoluciones_venta.create') }}" 
        class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <i class="fas fa-plus mr-2"></i> Agregar Devolución de Venta
        </a> 

        <!-- Tabla de devoluciones -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Fecha</th>
                        <th scope="col" class="px-6 py-3">Cliente</th>
                        <th scope="col" class="px-6 py-3">Productos</th>
                        <th scope="col" class="px-6 py-3">Cantidad Total</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devoluciones as $devolucion)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ $devolucion->id_devolucion_venta }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($devolucion->fecha_devolucion_venta)->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            @if($devolucion->cliente)
                                {{ $devolucion->cliente->nombre_cliente }}
                            @else
                                Cliente no encontrado
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <ul class="list-disc pl-4">
                                @foreach ($devolucion->detalles as $detalle)
                                    <li>{{ $detalle->producto->esquema->nombre_producto }} ({{ $detalle->cantidad }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-6 py-4">{{ $devolucion->detalles->sum('cantidad') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('devoluciones_venta.show', $devolucion->id_devolucion_venta) }}" 
                               class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-2">Ver</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center">No hay devoluciones de ventas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Paginación -->
            @if($devoluciones->hasPages())
            <div class="p-4">
                {{ $devoluciones->links() }}
            </div>
            @endif
        </div>

        <br>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Buscar ventas por cliente y fecha
            document.getElementById('buscar-ventas').addEventListener('click', function () {
                const clienteId = document.getElementById('cliente').value;
                const fechaInicio = document.getElementById('fecha_inicio').value;
                const fechaFin = document.getElementById('fecha_fin').value;

                if (!clienteId || !fechaInicio || !fechaFin) {
                    alert('Por favor complete todos los campos');
                    return;
                }

                fetch(`{{ route('devoluciones_venta.buscar-ventas') }}?cliente_id=${clienteId}&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`)
                    .then(response => response.json())
                    .then(data => {
                        const ventasList = document.getElementById('ventas-list');
                        ventasList.innerHTML = '';

                        if (data.length === 0) {
                            ventasList.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center">No se encontraron ventas</td></tr>';
                            return;
                        }

                        data.forEach(venta => {
                            const row = document.createElement('tr');
                            row.className = 'bg-white border-b dark:bg-gray-800 dark:border-gray-700';
                            row.innerHTML = `
                                <td class="px-6 py-4">${venta.id_venta}</td>
                                <td class="px-6 py-4">${new Date(venta.fecha_venta).toLocaleDateString()}</td>
                                <td class="px-6 py-4">${venta.nombre_tipo_venta}</td>
                                <td class="px-6 py-4">Q${venta.total.toFixed(2)}</td>
                                <td class="px-6 py-4">
                                    <button onclick="seleccionarVenta(${venta.id_venta})" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Seleccionar</button>
                                </td>
                            `;
                            ventasList.appendChild(row);
                        });

                        document.getElementById('step-1').classList.add('hidden');
                        document.getElementById('step-2').classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Ocurrió un error al buscar las ventas');
                    });
            });

            // Manejar envío del formulario de devolución
            document.getElementById('devolucion-form').addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const productos = [];

                document.querySelectorAll('#detalle-venta tr').forEach(row => {
                    const cantidadInput = row.querySelector('input[name="cantidades[]"]');
                    if (cantidadInput && cantidadInput.value > 0) {
                        productos.push({
                            id_producto: row.dataset.productId,
                            cantidad: cantidadInput.value,
                            precio: row.querySelector('td:nth-child(5)').textContent.replace('$', '')
                        });
                    }
                });

                if (productos.length === 0) {
                    alert('Seleccione al menos un producto para devolver');
                    return;
                }

                formData.append('productos', JSON.stringify(productos));

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Devolución de venta registrada correctamente');
                        window.location.reload();
                    } else {
                        alert(data.message || 'Error al registrar la devolución');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al registrar la devolución');
                });
            });
        });

        // Seleccionar venta
        function seleccionarVenta(ventaId) {
            fetch(`{{ route('devoluciones_venta.venta.detalle', '') }}/${ventaId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('id_venta').value = ventaId;
                    document.getElementById('venta-info').textContent = `Venta #${ventaId} - ${new Date(data.fecha_venta).toLocaleDateString()}`;

                    const detalleVenta = document.getElementById('detalle-venta');
                    detalleVenta.innerHTML = '';

                    data.detalle.forEach(item => {
                        const row = document.createElement('tr');
                        row.dataset.productId = item.id_producto;
                        row.className = 'bg-white border-b dark:bg-gray-800 dark:border-gray-700';
                        row.innerHTML = `
                            <td class="px-6 py-4">${item.nombre_producto}</td>
                            <td class="px-6 py-4">${item.presentacion_venta ?? '-'}</td>
                            <td class="px-6 py-4">${item.cantidad}</td>
                            <td class="px-6 py-4">
                                <input type="number" name="cantidades[]" min="0" max="${item.cantidad}" 
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </td>
                            <td class="px-6 py-4">$${item.precio.toFixed(2)}</td>
                        `;
                        detalleVenta.appendChild(row);
                    });

                    document.getElementById('step-2').classList.add('hidden');
                    document.getElementById('step-3').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al cargar el detalle de la venta');
                });
        }
    </script>
    @endpush

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hidden {
            display: none;
        }
    </style>
    @endpush
</x-admin-layout>