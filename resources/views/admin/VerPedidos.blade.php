<x-admin-layout>
    <!-- Alerta de información -->
    @if (session('mensaje'))
        <div id="alert-1"
            class="flex items-center p-4 mb-4 text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
            role="alert">
            <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                {{ session('mensaje') }}
            </div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700"
                data-dismiss-target="#alert-1" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    @endif
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <h1 class="text-lg text-center dark:text-black font-bold">PEDIDOS</h1>

    <form method="GET" action="{{ route('producto') }}" class="mb-4">
        <div class="flex space-x-2">
            <input type="text" name="buscador" placeholder="Buscar por código o nombre"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <button type="submit"
                class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Buscar
            </button>
        </div>
    </form>


    <!-- Botón para nuevo pedido -->
    <a href="{{ route('pedidos') }}"
        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        Hacer nuevo pedido
    </a>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-white">
            <thead class="text-m text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-white">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Fecha de Pedido</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr>
                        <td class="px-6 py-3 dark:text-black">{{ $pedido->id_pedido }}</td>
                        <td class="px-6 py-3 dark:text-black">{{ $pedido->fecha_pedido }}</td>
                        <td class="px-6 py-3 dark:text-black">{{ $pedido->estado }}</td>
                        <td class="px-6 py-3 dark:text-black">
                            <a href="{{ route('compras.fromPedido', ['id_pedido' => $pedido->id_pedido]) }}" class="fa-solid fa-shopping-cart fa-lg text-blue-600 hover:underline"
                                title="Hacer compra"> 
                            </a>
                            <a title="Eliminar pedido"
                                class="fa fa-trash fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                href="{{ route('pedidos.eliminar', ['id' => $pedido->id_pedido]) }}"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este pedido?')">
                            </a>
                            <a title="Ver detalles"
                                class="fa-solid fa-list fa-lg text-blue-600 hover:underline ver-detalles-pedidos cursor-pointer"
                                data-id="{{ $pedido->id_pedido }}">
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="modal-detalles-pedidos"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-[80%] max-w-none relative">
            <button class="absolute top-2 right-2 text-gray-600 hover:text-red-500 text-xl"
                onclick="cerrarModalDetallesPedidos()">&times;</button>
            <h2 class="text-xl font-bold mb-4">Detalles del Pedido</h2>
            <div id="contenido-detalles-pedidos">
                <!-- Aquí se mostrarán los detalles -->
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.ver-detalles-pedidos').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-id');
                try {
                    const response = await fetch(`/pedidos/${id}/detalles`);
                    if (!response.ok) throw new Error('Error al obtener detalles');
                    const html = await response.text();
                    document.getElementById('contenido-detalles-pedidos').innerHTML = html;
                    document.getElementById('modal-detalles-pedidos').classList.remove('hidden');
                    document.getElementById('modal-detalles-pedidos').classList.add('flex');
                } catch (error) {
                    alert('No se pudieron cargar los detalles del pedido.');
                }
            });
        });

        function cerrarModalDetallesPedidos() {
            const modal = document.getElementById('modal-detalles-pedidos');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>

</x-admin-layout>
 