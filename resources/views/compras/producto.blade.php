<x-admin-layout>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <b>
        <h1 class="text-lg text-center dark:text-black">PRODUCTOS</h1>
    </b>

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
    <!-- Modal toggle -->
    <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        type="button">
        Agregar Producto
    </button>
    <br>
    <!-- Main modal -->
    <div id="crud-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Ingresar un Nuevo Producto
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="crud-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Cerrar</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form class="p-4 md:p-5" action="{{ route('producto') }}" method="POST">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="codigo_product"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Codigo del
                                Producto</label>
                            <input type="text" id="codigo_product" name="codigo_product"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                            @error('codigo_product')
                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-2">
                            <label for="nombre_product"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del
                                Producto</label>
                            <input type="text" id="nombre_product" name="nombre_product"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                            @error('nombre_product')
                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-2">
                            <label for="descripcion_product"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripcion</label>
                            <input type="text" id="descripcion_product" name="descripcion_product"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                            @error('descripcion_product')
                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit"
                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Registrar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-white">
            <thead class="text-m text-gray-700  bg-gray-50 dark:bg-gray-700 dark:text-white">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Codigo del Producto
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nombre del Producto
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Descripcion
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Stock
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $producto->id_esquema_producto }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $producto->codigo_producto }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $producto->nombre_producto }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $producto->descripcion }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $producto->stock_total }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">
                            <a class="fa fa-pencil fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                data-modal-target="editar-modal-producto-{{ $producto->id_esquema_producto }}"
                                data-modal-toggle="editar-modal-producto-{{ $producto->id_esquema_producto }}"
                                class="text-blue-600"></a>
                            <a class="fa fa-trash fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                href="{{ route('producto.cambiar_estado', ['id' => $producto->id_esquema_producto]) }}"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?')"></a>
                            <a class="fa-solid fa-list fa-lg text-blue-600 hover:underline ver-detalles cursor-pointer"
                                data-id="{{ $producto->id_esquema_producto }}"></a>
                            {{-- <a class="fa-solid fa-list font-medium text-blue-600 dark:text-blue-500 hover:underline" data-modal-target="editar-modal-producto-{{ $producto->id_esquema_producto }}" data-modal-toggle="editar-modal-producto-{{ $producto->id_esquema_producto }}" class="text-blue-600"></a> --}}
                        </td>
                    </tr>
                    <!-- Modal for editing product -->
                    <div id="editar-modal-producto-{{ $producto->id_esquema_producto }}" tabindex="-1"
                        aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                <!-- Modal header -->
                                <div
                                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Editar Producto
                                    </h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        data-modal-toggle="editar-modal-producto-{{ $producto->id_esquema_producto }}">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Cerrar</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <form
                                    action="{{ route('producto.actualizar_producto', $producto->id_esquema_producto) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <div class="col-span-2">
                                            <label for="codigo_product"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Codigo
                                                del Producto</label>
                                            <input type="text" id="codigo_product" name="codigo_product"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required
                                                value="{{ old('codigo_product', $producto->codigo_producto) }}">
                                            @error('codigo_product')
                                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-span-2">
                                            <label for="nombre_product"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre
                                                del Producto</label>
                                            <input type="text" id="nombre_product" name="nombre_product"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required
                                                value="{{ old('nombre_product', $producto->nombre_producto) }}">
                                            @error('nombre_product')
                                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-span-2">
                                            <label for="descripcion_product"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripcion</label>
                                            <input type="text" id="descripcion_product" name="descripcion_product"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required
                                                value="{{ old('descripcion_product', $producto->descripcion) }}">
                                            @error('descripcion_product')
                                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="submit"
                                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Actualizar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>


    <div id="modal-detalles" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-[80%] max-w-none relative">

            <button class="absolute top-2 right-2 text-gray-600 hover:text-red-500 text-xl"
                onclick="cerrarModalDetalles()">&times;</button>
            <h2 class="text-xl font-bold mb-4">Detalles del Producto</h2>
            <div id="contenido-detalles">
                <!-- Aquí se mostrarán los detalles -->
            </div>
        </div>
    </div>


    <script>
        document.querySelectorAll('.ver-detalles').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-id');
                const response = await fetch(`/producto/${id}/detalles`);
                const html = await response.text();
                document.getElementById('contenido-detalles').innerHTML = html;
                document.getElementById('modal-detalles').classList.remove('hidden');
                document.getElementById('modal-detalles').classList.add('flex');
            });
        });

        function cerrarModalDetalles() {
            const modal = document.getElementById('modal-detalles');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>

    <style>
        .input {
            @apply bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500;
        }

        .btn-primary {
            @apply text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800;
        }
    </style>
</x-admin-layout>
