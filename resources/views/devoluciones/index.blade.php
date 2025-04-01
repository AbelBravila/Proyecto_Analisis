<x-admin-layout>
    <div class="p-4">
        <!-- Tabla de devoluciones -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Fecha</th>
                        <th scope="col" class="px-6 py-3">Proveedor</th>
                        <th scope="col" class="px-6 py-3">Productos</th>
                        <th scope="col" class="px-6 py-3">Cantidad Total</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devoluciones as $devolucion)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ $devolucion->id_devolucion }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($devolucion->fecha_devolucion)->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">{{ $devolucion->compra->proveedor->nombre_proveedor ?? 'Proveedor no especificado' }}</td>
                        <td class="px-6 py-4">
                            <ul class="list-disc pl-4">
                                @foreach($devolucion->detalles as $detalle)
                                <li>{{ $detalle->producto->esquema->nombre_producto }} ({{ $detalle->cantidad }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-6 py-4">{{ $devolucion->detalles->sum('cantidad') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('devoluciones.show', $devolucion->id_devolucion) }}" 
                               class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-2">Ver</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center">No hay devoluciones registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($devoluciones->hasPages())
            <div class="p-4">
                {{ $devoluciones->links() }}
            </div>
            @endif
        </div>

        <!-- Botón para agregar devolución -->
        <button data-modal-target="devolucion-modal" data-modal-toggle="devolucion-modal" 
                class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <i class="fas fa-plus mr-2"></i> Agregar Devolución
        </button>

        <!-- Modal para agregar devolución -->
        <div id="devolucion-modal" tabindex="-1" aria-hidden="true" 
             class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-6xl max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Registrar Nueva Devolución
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="devolucion-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Cerrar</span>
                        </button>
                    </div>
                    
                    <!-- Modal body -->
                    <div class="p-4 md:p-5">
                        <!-- Paso 1: Seleccionar proveedor y fechas -->
                        <div id="step-1">
                            <div class="grid gap-4 mb-4 grid-cols-1 md:grid-cols-3">
                                <div>
                                    <label for="proveedor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Proveedor</label>
                                    <select id="proveedor" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">Seleccione un proveedor</option>
                                        @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->nombre_proveedor }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="fecha_inicio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha Inicio</label>
                                    <input type="date" id="fecha_inicio" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="fecha_fin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha Fin</label>
                                    <input type="date" id="fecha_fin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>
                            </div>
                            <button id="buscar-compras" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Buscar Compras
                            </button>
                        </div>

                        <!-- Paso 2: Seleccionar compra -->
                        <div id="step-2" class="hidden mt-4">
                            <h4 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Compras encontradas</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th class="px-6 py-3">ID</th>
                                            <th class="px-6 py-3">Fecha</th>
                                            <th class="px-6 py-3">Tipo</th>
                                            <th class="px-6 py-3">Total</th>
                                            <th class="px-6 py-3">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="compras-list">
                                        <!-- Las compras se cargarán aquí via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Paso 3: Detalle de compra y productos a devolver -->
                        <div id="step-3" class="hidden mt-4">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Detalle de Compra</h4>
                                <span id="compra-info" class="text-sm text-gray-600 dark:text-gray-300"></span>
                            </div>
                            
                            <div class="overflow-x-auto mb-4">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th class="px-6 py-3">Producto</th>
                                            <th class="px-6 py-3">Lote</th>
                                            <th class="px-6 py-3">Cantidad Comprada</th>
                                            <th class="px-6 py-3">Cantidad a Devolver</th>
                                            <th class="px-6 py-3">Costo Unitario</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detalle-compra">
                                        <!-- El detalle de la compra se cargará aquí -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <form id="devolucion-form" action="{{ route('devoluciones.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_compra" id="id_compra">
                                <input type="hidden" name="fecha_devolucion" value="{{ now()->format('Y-m-d H:i:s') }}">
                                
                                <div class="mb-4">
                                    <label for="motivo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Motivo de la devolución</label>
                                    <textarea id="motivo" name="motivo" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                </div>
                                
                                <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800">
                                    Registrar Devolución
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Buscar compras por proveedor y fecha
            document.getElementById('buscar-compras').addEventListener('click', function() {
                const proveedorId = document.getElementById('proveedor').value;
                const fechaInicio = document.getElementById('fecha_inicio').value;
                const fechaFin = document.getElementById('fecha_fin').value;
                
                if(!proveedorId || !fechaInicio || !fechaFin) {
                    alert('Por favor complete todos los campos');
                    return;
                }
                
                fetch(`{{ route('devoluciones.buscar-compras') }}?proveedor_id=${proveedorId}&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`)
                    .then(response => response.json())
                    .then(data => {
                        const comprasList = document.getElementById('compras-list');
                        comprasList.innerHTML = '';
                        
                        if(data.length === 0) {
                            comprasList.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center">No se encontraron compras</td></tr>';
                            return;
                        }
                        
                        data.forEach(compra => {
                            const row = document.createElement('tr');
                            row.className = 'bg-white border-b dark:bg-gray-800 dark:border-gray-700';
                            row.innerHTML = `
                                <td class="px-6 py-4">${compra.id_compra}</td>
                                <td class="px-6 py-4">${new Date(compra.fecha_compra).toLocaleDateString()}</td>
                                <td class="px-6 py-4">${compra.tipo_compra.nombre_tipo_compra}</td>
                                <td class="px-6 py-4">$${compra.total.toFixed(2)}</td>
                                <td class="px-6 py-4">
                                    <button onclick="seleccionarCompra(${compra.id_compra})" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Seleccionar</button>
                                </td>
                            `;
                            comprasList.appendChild(row);
                        });
                        
                        document.getElementById('step-1').classList.add('hidden');
                        document.getElementById('step-2').classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Ocurrió un error al buscar las compras');
                    });
            });
            
            // Manejar el envío del formulario de devolución
            document.getElementById('devolucion-form').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const productos = [];
                
                document.querySelectorAll('#detalle-compra tr').forEach(row => {
                    const cantidadInput = row.querySelector('input[name="cantidades[]"]');
                    if(cantidadInput && cantidadInput.value > 0) {
                        productos.push({
                            id_producto: row.dataset.productId,
                            cantidad: cantidadInput.value,
                            costo: row.querySelector('td:nth-child(5)').textContent.replace('$', '')
                        });
                    }
                });
                
                if(productos.length === 0) {
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
                    if(data.success) {
                        alert('Devolución registrada correctamente');
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
        
        function seleccionarCompra(compraId) {
            fetch(`{{ route('devoluciones.compra.detalle', '') }}/${compraId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('id_compra').value = compraId;
                    document.getElementById('compra-info').textContent = `Compra #${compraId} - ${new Date(data.fecha_compra).toLocaleDateString()}`;
                    
                    const detalleCompra = document.getElementById('detalle-compra');
                    detalleCompra.innerHTML = '';
                    
                    data.detalle.forEach(item => {
                        const row = document.createElement('tr');
                        row.dataset.productId = item.id_producto;
                        row.className = 'bg-white border-b dark:bg-gray-800 dark:border-gray-700';
                        row.innerHTML = `
                            <td class="px-6 py-4">${item.producto.esquema.nombre_producto}</td>
                            <td class="px-6 py-4">${item.producto.lote.lote}</td>
                            <td class="px-6 py-4">${item.cantidad}</td>
                            <td class="px-6 py-4">
                                <input type="number" name="cantidades[]" min="0" max="${item.cantidad}" 
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </td>
                            <td class="px-6 py-4">$${item.costo.toFixed(2)}</td>
                        `;
                        detalleCompra.appendChild(row);
                    });
                    
                    document.getElementById('step-2').classList.add('hidden');
                    document.getElementById('step-3').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al cargar el detalle de la compra');
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