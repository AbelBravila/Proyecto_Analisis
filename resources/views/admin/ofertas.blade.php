<x-admin-layout>
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
        </div>
    @endif
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <h1 class="text-lg text-center dark:text-black">OFERTAS</h1>

    <form method="GET" action="{{ route('ofertas') }}" class="mb-4">
        <div class="flex space-x-2">
            <input type="text" name="buscador" placeholder="Buscar por nombre"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <button type="submit"
                class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Buscar
            </button>
        </div>
    </form>
    <!-- Modal toggle -->
    <button onclick="window.location='{{ route('ofertas.create') }}'"
        class="block  text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        type="button">
        Nueva Oferta
    </button>
    <br>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-white">
            <thead class="text-m text-gray-700  bg-gray-50 dark:bg-gray-700 dark:text-white">
                <tr>
                    <th scope="col" class="px-6 py-3">
                    nombre de la oferta
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Fecha de Creación
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Fecha de Inicio
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Fecha de Fin
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Estado
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $producto->nombre_oferta }}</td>   
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $producto->fecha }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $producto->fecha_inicio }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $producto->fecha_fin }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $producto->estado }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">
                            <a class="fa fa-pencil fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                data-modal-target="editar-modal-producto-{{ $producto->id_oferta }}"
                                data-modal-toggle="editar-modal-producto-{{ $producto->id_oferta }}"
                                class="text-blue-600"></a>
                            <a class="fa fa-trash fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                href="{{ route('ofertas.eliminar', ['id' => $producto->id_oferta]) }}"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta oferta?')"></a>
                            <a class="fa-solid fa-list fa-lg text-blue-600 hover:underline ver-detalles cursor-pointer"
                                data-id="{{ $producto->id_oferta }}"></a>
                            {{-- <a class="fa-solid fa-list font-medium text-blue-600 dark:text-blue-500 hover:underline" data-modal-target="editar-modal-producto-{{ $producto->id_esquema_producto }}" data-modal-toggle="editar-modal-producto-{{ $producto->id_esquema_producto }}" class="text-blue-600"></a> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <div id="modal-detalles" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-[80%] max-w-none relative">

            <button class="absolute top-2 right-2 text-gray-600 hover:text-red-500 text-xl"
                onclick="cerrarModalDetalles()">&times;</button>
            <h2 class="text-xl font-bold mb-4">Detalles de la Oferta</h2>
            <div id="contenido-detalles">
                <!-- Aquí se mostrarán los detalles -->
            </div>
        </div>
    </div>
     <script>
                document.querySelectorAll('.ver-detalles').forEach(btn => {
                btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-id');
                const response = await fetch(`/ofertas/${id}/detalles`);
                const html = await response.text();
                document.getElementById('contenido-detalles').innerHTML = html;
                const modal = document.getElementById('modal-detalles');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
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
