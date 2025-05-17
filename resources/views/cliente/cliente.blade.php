<x-admin-layout>
    <b>
        <h1 class="text-lg text-center dark:text-black">Clientes</h1>
    </b>

    <form method="GET" action="{{ route('cliente') }}" class="mb-4">
        <div class="flex space-x-2">
            <input type="text" name="buscador" placeholder="Buscar por id, nit o nombre"
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
        Agregar cliente
    </button>
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
                        Ingresar un Nuevo Cliente
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
                <form class="p-4 md:p-5" action="{{ route('cliente') }}" method="POST">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="id_tipo_cliente"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de
                                Cliente</label>
                            <div class="relative">
                                <input type="hidden" name="id_tipo_cliente" id="id_tipo_cliente">
                                <button id="dropdownDividerButton" data-dropdown-toggle="dropdownDivider"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    type="button">
                                    <span id="selectedOption">Selecciona un tipo cliente</span>
                                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>

                                <div id="dropdownDivider"
                                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600 absolute">
                                    <ul id="dropdownList" class="py-2 text-sm text-gray-700 dark:text-gray-200">
                                        @foreach ($tipos_cliente as $tipo)
                                            <li>
                                                <button type="button"
                                                    class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600"
                                                    onclick="seleccionarTipoCliente('{{ $tipo->id_tipo_cliente }}', '{{ $tipo->nombre_tipo_cliente }}')">
                                                    {{ $tipo->nombre_tipo_cliente }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            @error('id_tipo_cliente')
                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-2">
                            <label for="nombre_cliente"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del
                                Cliente</label>
                            <input type="text" id="nombre_cliente" name="nombre_cliente"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                            @error('nombre_cliente')
                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-2">
                            <label for="dpi"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">DPI del
                                Cliente</label>
                            <input type="text" id="dpi" name="dpi"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                            @error('dpi')
                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-2">
                            <label for="nit"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nit del
                                Cliente</label>
                            <input type="text" id="nit" name="nit"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                            @error('nit')
                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-2">
                            <label for="telefono"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telefono del
                                Cliente</label>
                            <input type="text" id="telefono" name="telefono"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                            @error('telefono')
                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-2">
                            <label for="correo"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo</label>
                            <input type="email" id="correo" name="correo"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                            @error('correo')
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
                        Tipo de cleinte
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nombre del Cliente
                    </th>
                    <th scope="col" class="px-6 py-3">
                        DPI
                    </th>
                    <th scope="col" class="px-6 py-3">
                        NIT
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Telefono
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Correo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $cliente->id_cliente }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $cliente->nombre_tipo_cliente }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $cliente->nombre_cliente }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $cliente->dpi }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $cliente->nit }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $cliente->telefono }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $cliente->correo }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">
                            <a class="fa fa-pencil fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                data-modal-target="editar-modal-cliente-{{ $cliente->id_cliente }}"
                                data-modal-toggle="editar-modal-cliente-{{ $cliente->id_cliente }}"
                                class="text-blue-600"></a>
                            <a class="fa fa-trash fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                href="{{ route('cliente.cambiar_estado', ['id' => $cliente->id_cliente]) }}"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?')"></a>
                        </td>
                    </tr>
                    <!-- Modal for editing product -->
                    <div id="editar-modal-cliente-{{ $cliente->id_cliente }}" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                <!-- Modal header -->
                                <div
                                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Editar Cliente</h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        data-modal-toggle="editar-modal-cliente-{{ $cliente->id_cliente }}">
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
                                <form action="{{ route('cliente.actualizar_cliente', $cliente->id_cliente) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <div class="col-span-2">
                                            <label for="id_tipo_cliente"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID
                                                del tipo de cliente <p>{{ $cliente->id_cliente }}</p>
                                            </label>
                                            <input type="hidden" name="id_tipo_cliente"
                                                id="id_tipo_cliente_{{ $cliente->id_cliente }}"
                                                value="{{ $cliente->id_tipo_cliente }}">
                                            <select id="id_usuario" name="id_usuario"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required>
                                                @foreach ($tipos_cliente as $tipo)
                                                    <option
                                                        {{ $tipo->id_tipo_cliente == $cliente->id_tipo_cliente ? 'selected' : '' }}
                                                        value="{{ $tipo->id_tipo_cliente }}">
                                                        {{ $tipo->nombre_tipo_cliente }}</option>
                                                @endforeach
                                            </select>

                                            @error('id_tipo_cliente')
                                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-span-2">
                                            <label for="nombre_cliente"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre
                                                del Cliente</label>
                                            <input type="text" id="nombre_cliente" name="nombre_cliente"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required
                                                value="{{ old('nombre_cliente', $cliente->nombre_cliente) }}">
                                            @error('nombre_cliente')
                                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-span-2">
                                            <label for="dpi"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">DPI
                                                del Cliente</label>
                                            <input type="text" id="dpi" name="dpi"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required value="{{ old('dpi', $cliente->dpi) }}">
                                            @error('dpi')
                                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-span-2">
                                            <label for="nit"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nit
                                                del Cliente</label>
                                            <input type="text" id="nit" name="nit"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required value="{{ old('nit', $cliente->nit) }}">
                                            @error('nit')
                                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-span-2">
                                            <label for="telefono"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telefono
                                                Proveedor</label>
                                            <input type="text" id="telefono" name="telefono"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required value="{{ old('telefono', $cliente->telefono) }}">
                                            @error('telefono')
                                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-span-2">
                                            <label for="correo"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo</label>
                                            <input type="email" id="correo" name="correo"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required value="{{ old('correo', $cliente->correo) }}">
                                            @error('correo')
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
    <script>
        function seleccionarTipoCliente(id, nombre) {
            document.getElementById('id_tipo_cliente').value = id;
            document.getElementById('selectedOption').innerText = nombre;
        }
    </script>
    <script>
        function seleccionarTipoClienteEditar(clienteId, id, nombre) {
            document.getElementById('id_tipo_cliente_' + clienteId).value = id;
            document.getElementById('selectedOptionEditar_' + clienteId).textContent = nombre;
        }
    </script>


</x-admin-layout>
