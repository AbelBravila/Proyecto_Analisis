<x-admin-layout>

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
                        <a title="Editar pedido"
                           class="fa fa-pencil fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline"
                           data-modal-target="editar-modal-proveedor-{{$pedido->id_pedido}}"
                           data-modal-toggle="editar-modal-proveedor-{{$pedido->id_pedido}}">
                        </a>
                        <a title="Eliminar pedido"
                           class="fa fa-trash fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline"
                           href="{{ route('proveedor.cambiar_estado', ['id' => $pedido->id_pedido]) }}"
                           onclick="return confirm('¿Estás seguro de que deseas eliminar este pedido?')">
                        </a>
                        <a title="Ver detalles"
                           class="fa-solid fa-list fa-lg text-blue-600 hover:underline ver-detalles-pedidos cursor-pointer"
                           data-id="{{ $pedido->id_pedido }}">
                        </a>
                    </td>
                </tr>
                <!-- Modal de edición -->
                <div id="editar-modal-proveedor-{{$pedido->id_pedido}}" tabindex="-1" aria-hidden="true"
                    class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] max-w-xl relative">
                        <button class="absolute top-2 right-2 text-gray-600 hover:text-red-500 text-xl"
                            onclick="document.getElementById('editar-modal-proveedor-{{$pedido->id_pedido}}').classList.add('hidden')">
                            &times;
                        </button>
                        <h2 class="text-xl font-bold mb-4">Editar Pedido #{{ $pedido->id_pedido }}</h2>
                        <form method="POST" action="{{ route('pedidos.editar', $pedido->id_pedido) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="estado" class="block mb-2 text-sm font-medium text-gray-700">Estado</label>
                                <select name="estado" id="estado"
                                    class="w-full border border-gray-300 p-2 rounded-lg dark:bg-gray-700 dark:text-white">
                                    <option value="Pendiente" {{ $pedido->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="Enviado" {{ $pedido->estado == 'Enviado' ? 'selected' : '' }}>Enviado</option>
                                    <option value="Entregado" {{ $pedido->estado == 'Entregado' ? 'selected' : '' }}>Entregado</option>
                                </select>
                            </div>

                            <div class="flex justify-end space-x-2">
                                <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Guardar</button>
                                <button type="button"
                                    class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500"
                                    onclick="document.getElementById('editar-modal-proveedor-{{$pedido->id_pedido}}').classList.add('hidden')">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach 
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="modal-detalles-pedidos" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
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
