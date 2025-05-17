<x-admin-layout>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">¡Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <b><h1 class="text-2xl text-center dark:text-black">VENTAS</h1></b>

    <!-- Formulario de búsqueda -->
    <form method="GET" action="{{ route('ventas') }}" class="mb-4">
        <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-2">
            <input type="text" name="buscador" placeholder="Buscar por venta o cliente" 
                value="{{ request('buscador') }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full md:w-[650px] dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">

            <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full md:w-[200px] dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            
            <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full md:w-[200px] dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">

            <button type="submit"
                class="bg-blue-700 text-white px-4 py-2 rounded-lg w-full md:w-[150px] hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Buscar
            </button>
        </div>
    </form>

    <!-- Botón para ingresar nueva venta -->
    <button onclick="window.location='{{ route('ventas.registrar') }}'" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
        Ingresar Venta
    </button>

    <br>

    <div class="container">
        <!-- Tabla que mostrará los detalles de las ventas -->
        <div class="overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-white">
                <thead class="text-m text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-white">
                    <tr>
                        <th scope="col" class="px-6 py-3">Venta</th>
                        <th scope="col" class="px-6 py-3">Fecha</th>
                        <th scope="col" class="px-6 py-3">Tipo de Venta</th>
                        <th scope="col" class="px-6 py-3">Cliente</th>
                        <th scope="col" class="px-6 py-3">Tipo de Documento</th>
                        <th scope="col" class="px-6 py-3">Cantidad</th>
                        <th scope="col" class="px-6 py-3">Total de la Venta</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="w-full text-sm text-left text-gray-500 dark:text-black">
                    @foreach ($ventas as $venta)
                        <tr>
                            <td class="px-6 py-3">{{ $venta->id_venta }}</td>
                            <td class="px-6 py-3">{{ $venta->fecha_venta }}</td>
                            <td class="px-6 py-3">{{ $venta->nombre_tipo_venta }}</td>
                            <td class="px-6 py-3">{{ $venta->nombre_cliente }}</td>
                            <td class="px-6 py-3">{{ $venta->nombre_documento }}</td>
                            <td class="px-6 py-3">{{ $venta->cantidad }}</td>
                            <td class="px-6 py-3">{{ number_format($venta->total_venta, 2) }}</td>
                            <td class="px-6 py-3 dark:text-black">
                                <a class="fa fa-trash fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline" 
                                   href="{{ route('ventas.anular', ['id' => $venta->id_venta]) }}"
                                   onclick="return confirm('¿Estás seguro de que deseas anular esta venta?')"></a>
                                <a class="fa fa-list fa-lg text-blue-600 hover:underline ver-detalle cursor-pointer"
                                   data-id="{{ $venta->id_venta }}"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de detalle -->
    <div id="modal-detalle" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-[80%] max-w-none relative">
            <button class="absolute top-2 right-2 text-gray-600 hover:text-red-500 text-xl" onclick="cerrarModalDetalle()">&times;</button>
            <h2 class="text-xl font-bold mb-4">Detalle de la venta <span id="detalle-venta-id"></span></h2>
            <div id="contenido-detalle"></div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.ver-detalle').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-id');
                document.getElementById('detalle-venta-id').textContent = `#${id}`;
                const response = await fetch(`/ventas/${id}/detalle`);
                const html = await response.text();
                document.getElementById('contenido-detalle').innerHTML = html;
                document.getElementById('modal-detalle').classList.remove('hidden');
                document.getElementById('modal-detalle').classList.add('flex');
            });
        });

        function cerrarModalDetalle() {
            const modal = document.getElementById('modal-detalle');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>

</x-admin-layout>
