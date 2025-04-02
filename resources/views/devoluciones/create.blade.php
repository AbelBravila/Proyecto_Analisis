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
                    <input type="date" id="fecha_inicio" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <div>
                    <label for="fecha_fin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha Fin</label>
                    <input type="date" id="fecha_fin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
            </div>
            
            <button id="buscar-compras" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <i class="fas fa-search mr-2"></i> Buscar Compras
            </button>
        </div>
        
        <!-- Tabla de resultados -->
        <div id="resultados" class="hidden">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-6">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Fecha</th>
                            <th scope="col" class="px-6 py-3">Tipo</th>
                            <th scope="col" class="px-6 py-3">Proveedor</th>
                            <th scope="col" class="px-6 py-3">Total</th>
                            <th scope="col" class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="compras-list">
                        <!-- Las compras se cargarán aquí via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Detalle de la compra seleccionada -->
        <div id="detalle-compra-container" class="hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detalle de Compra</h3>
                    <span id="compra-info" class="text-sm text-gray-600 dark:text-gray-300"></span>
                </div>
                
                <div class="relative overflow-x-auto sm:rounded-lg mb-4">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Producto</th>
                                <th class="px-6 py-3">Lote</th>
                                <th class="px-6 py-3">Cantidad Comprada</th>
                                <th class="px-6 py-3">Cantidad a Devolver</th>
                                <th class="px-6 py-3">Costo Unitario</th>
                                <th class="px-6 py-3">Subtotal</th>
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
                        <textarea id="motivo" name="motivo" rows="3" required class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Escribe el motivo de la devolución..."></textarea>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <button type="button" onclick="cancelarDevolucion()" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            Cancelar
                        </button>
                        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800">
                            <i class="fas fa-save mr-2"></i> Registrar Devolución
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Mensajes de alerta -->
        <div id="no-resultados" class="hidden p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
            <span class="font-medium">No se encontraron compras</span> con los filtros seleccionados. Intenta con otros criterios de búsqueda.
        </div>
        
        <div id="error-message" class="hidden p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-300" role="alert">
            <span class="font-medium">¡Error!</span> <span id="error-text"></span>
        </div>
        
        <div id="success-message" class="hidden p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-300" role="alert">
            <span class="font-medium">¡Éxito!</span> <span id="success-text"></span>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar fechas
            const hoy = new Date();
            const fechaFinInput = document.getElementById('fecha_fin');
            fechaFinInput.valueAsDate = hoy;
            
            const unMesAtras = new Date();
            unMesAtras.setMonth(unMesAtras.getMonth() - 1);
            const fechaInicioInput = document.getElementById('fecha_inicio');
            fechaInicioInput.valueAsDate = unMesAtras;
            
            // Buscar compras por proveedor y fecha
            document.getElementById('buscar-compras').addEventListener('click', function() {
                const proveedorId = document.getElementById('proveedor').value;
                const fechaInicio = document.getElementById('fecha_inicio').value;
                const fechaFin = document.getElementById('fecha_fin').value;
                
                if(!fechaInicio || !fechaFin) {
                    mostrarError('Por favor complete al menos las fechas de búsqueda');
                    return;
                }
                
                // Ocultar mensajes previos
                document.getElementById('no-resultados').classList.add('hidden');
                document.getElementById('error-message').classList.add('hidden');
                document.getElementById('success-message').classList.add('hidden');
                
                // Mostrar indicador de carga
                const buscarBtn = document.getElementById('buscar-compras');
                const btnText = buscarBtn.innerHTML;
                buscarBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Buscando...';
                buscarBtn.disabled = true;
                
                // Parámetros de búsqueda
                let params = `fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
                if(proveedorId) {
                    params += `&proveedor_id=${proveedorId}`;
                }
                
                fetch(`{{ route('devoluciones.buscar-compras') }}?${params}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const comprasList = document.getElementById('compras-list');
                        comprasList.innerHTML = '';
                        
                        if(data.length === 0) {
                            document.getElementById('no-resultados').classList.remove('hidden');
                            document.getElementById('resultados').classList.add('hidden');
                            return;
                        }
                        
                        data.forEach(compra => {
                            const row = document.createElement('tr');
                            row.className = 'bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600';
                            
                            // Formatear fecha
                            const fecha = new Date(compra.fecha_compra);
                            const fechaFormateada = fecha.toLocaleDateString('es-ES', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric'
                            });
                            
                            // Determinar proveedor (desde el primer producto)
                            let proveedor = 'No especificado';
                            if (compra.detalle && compra.detalle.length > 0 && 
                                compra.detalle[0].producto && 
                                compra.detalle[0].producto.proveedor) {
                                proveedor = compra.detalle[0].producto.proveedor.nombre_proveedor;
                            }
                            
                            row.innerHTML = `
                                <td class="px-6 py-4">${compra.id_compra}</td>
                                <td class="px-6 py-4">${fechaFormateada}</td>
                                <td class="px-6 py-4">${compra.tipo_compra ? compra.tipo_compra.nombre_tipo_compra : 'N/A'}</td>
                                <td class="px-6 py-4">${proveedor}</td>
                                <td class="px-6 py-4">$${compra.total.toFixed(2)}</td>
                                <td class="px-6 py-4">
                                    <button onclick="seleccionarCompra(${compra.id_compra})" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                        <i class="fas fa-eye mr-1"></i> Seleccionar
                                    </button>
                                </td>
                            `;
                            comprasList.appendChild(row);
                        });
                        
                        document.getElementById('resultados').classList.remove('hidden');
                        document.getElementById('detalle-compra-container').classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        mostrarError('Ocurrió un error al buscar las compras. Inténtalo de nuevo.');
                    })
                    .finally(() => {
                        // Restaurar botón
                        buscarBtn.innerHTML = btnText;
                        buscarBtn.disabled = false;
                    });
            });
            
            // Manejar el envío del formulario de devolución
            document.getElementById('devolucion-form').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const productos = [];
                let hayProductos = false;
                
                document.querySelectorAll('#detalle-compra tr').forEach(row => {
                    const cantidadInput = row.querySelector('input[name="cantidades[]"]');
                    if(cantidadInput && parseFloat(cantidadInput.value) > 0) {
                        hayProductos = true;
                        productos.push({
                            id_producto: row.dataset.productId,
                            cantidad: cantidadInput.value,
                            costo: parseFloat(row.dataset.costo)
                        });
                    }
                });
                
                if(!hayProductos) {
                    mostrarError('Seleccione al menos un producto para devolver');
                    return;
                }
                
                if(!document.getElementById('motivo').value.trim()) {
                    mostrarError('Ingrese el motivo de la devolución');
                    return;
                }
                
                formData.append('productos', JSON.stringify(productos));
                
                // Mostrar indicador de carga
                const submitBtn = document.querySelector('#devolucion-form button[type="submit"]');
                const btnText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
                submitBtn.disabled = true;
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if(data.success) {
                        document.getElementById('success-text').textContent = 'Devolución registrada correctamente.';
                        document.getElementById('success-message').classList.remove('hidden');
                        
                        // Limpiar formulario y ocultar secciones
                        document.getElementById('devolucion-form').reset();
                        document.getElementById('detalle-compra-container').classList.add('hidden');
                        document.getElementById('resultados').classList.add('hidden');
                        
                        // Redirigir después de un tiempo
                        setTimeout(() => {
                            window.location.href = "{{ route('devoluciones.index') }}";
                        }, 3000);
                    } else {
                        mostrarError(data.message || 'Error al registrar la devolución');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarError('Ocurrió un error al registrar la devolución');
                })
                .finally(() => {
                    // Restaurar botón
                    submitBtn.innerHTML = btnText;
                    submitBtn.disabled = false;
                });
            });
        });
        
        function seleccionarCompra(compraId) {
            // Mostrar indicador de carga
            document.getElementById('detalle-compra').innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Cargando detalles...
                    </td>
                </tr>
            `;
            
            document.getElementById('detalle-compra-container').classList.remove('hidden');
            
            fetch(`{{ route('devoluciones.compra.detalle', '') }}/${compraId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('id_compra').value = compraId;
                    
                    // Formatear fecha
                    const fecha = new Date(data.fecha_compra);
                    const fechaFormateada = fecha.toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                    
                    document.getElementById('compra-info').textContent = `Compra #${compraId} - ${fechaFormateada}`;
                    
                    const detalleCompra = document.getElementById('detalle-compra');
                    detalleCompra.innerHTML = '';
                    
                    if(data.detalle.length === 0) {
                        detalleCompra.innerHTML = `
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center">
                                    No hay productos disponibles para devolución
                                </td>
                            </tr>
                        `;
                        return;
                    }
                    
                    data.detalle.forEach(item => {
                        const row = document.createElement('tr');
                        row.dataset.productId = item.id_producto;
                        row.dataset.costo = item.costo;
                        row.className = 'bg-white border-b dark:bg-gray-800 dark:border-gray-700';
                        
                        let nombreProducto = 'Producto no disponible';
                        let lote = 'N/A';
                        
                        if(item.producto && item.producto.esquema) {
                            nombreProducto = item.producto.esquema.nombre_producto;
                        }
                        
                        if(item.producto && item.producto.lote) {
                            lote = item.producto.lote.lote;
                        }
                        
                        row.innerHTML = `
                            <td class="px-6 py-4">${nombreProducto}</td>
                            <td class="px-6 py-4">${lote}</td>
                            <td class="px-6 py-4">${item.cantidad}</td>
                            <td class="px-6 py-4">
                                <input type="number" name="cantidades[]" min="0" max="${item.cantidad}" step="any"
                                       oninput="calcularSubtotal(this)"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </td>
                            <td class="px-6 py-4">$${item.costo.toFixed(2)}</td>
                            <td class="px-6 py-4 subtotal">$0.00</td>
                        `;
                        detalleCompra.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('detalle-compra').innerHTML = `
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-red-600">
                                <i class="fas fa-exclamation-circle mr-2"></i> Error al cargar el detalle de la compra
                            </td>
                        </tr>
                    `;
                });
        }
        
        function calcularSubtotal(input) {
            const row = input.closest('tr');
            const cantidad = parseFloat(input.value) || 0;
            const costo = parseFloat(row.dataset.costo);
            const subtotal = cantidad * costo;
            
            const subtotalCell = row.querySelector('.subtotal');
            subtotalCell.textContent = `$${subtotal.toFixed(2)}`;
        }
        
        function cancelarDevolucion() {
            // Ocultar sección de detalle
            document.getElementById('detalle-compra-container').classList.add('hidden');
            
            // Limpiar formulario
            document.getElementById('devolucion-form').reset();
            document.getElementById('detalle-compra').innerHTML = '';
        }
        
        function mostrarError(mensaje) {
            const errorMessage = document.getElementById('error-message');
            document.getElementById('error-text').textContent = mensaje;
            errorMessage.classList.remove('hidden');
            
            // Scroll hasta el mensaje
            errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Ocultar después de un tiempo
            setTimeout(() => {
                errorMessage.classList.add('hidden');
            }, 5000);
        }
    </script>
    @endpush

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endpush
</x-admin-layout>