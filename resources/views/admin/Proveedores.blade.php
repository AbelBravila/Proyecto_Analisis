<x-admin-layout>

    <!-- Botón para agregar proveedor -->
    <button data-modal-target="crud-modal-proveedor" data-modal-toggle="crud-modal-proveedor"
        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        type="button">
        Agregar Proveedor
    </button>
    <br>
    <!-- Modal para agregar proveedor -->
    <div id="crud-modal-proveedor" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Encabezado del modal -->
                <div
                    class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ingresar un Nuevo Proveedor</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="crud-modal-proveedor">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <!-- Formulario del modal -->
                <form class="p-4" action="{{ route('Proveedores.Guardar') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="nombre_proveedor"
                            class="block text-sm font-medium text-gray-900 dark:text-white">Nombre del Proveedor</label>
                        <input type="text" id="nombre_proveedor" name="nombre_proveedor"
                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 dark:bg-gray-600"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="nit"
                            class="block text-sm font-medium text-gray-900 dark:text-white">NIT</label>
                        <input type="text" id="nit" name="nit"
                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 dark:bg-gray-600"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="correo" class="block text-sm font-medium text-gray-900 dark:text-white">Correo
                            Electrónico</label>
                        <input type="email" id="correo" name="correo"
                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 dark:bg-gray-600"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="telefono"
                            class="block text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
                        <input type="text" id="telefono" name="telefono"
                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 dark:bg-gray-600"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="direccion"
                            class="block text-sm font-medium text-gray-900 dark:text-white">Dirección</label>
                        <input type="text" id="direccion" name="direccion"
                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 dark:bg-gray-600"
                            required>
                    </div>
                    <button type="submit" class="text-white bg-blue-700 px-5 py-2.5 rounded-lg">
                        Registrar
                    </button>
                </form>
            </div>
        </div>
    </div>


    <!-- Tabla de proveedores -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-m text-gray-700 bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Nombre Proveedor</th>
                    <th scope="col" class="px-6 py-3">NIT</th>
                    <th scope="col" class="px-6 py-3">Correo</th>
                    <th scope="col" class="px-6 py-3">Teléfono</th>
                    <th scope="col" class="px-6 py-3">Dirección</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proveedores as $proveedor)
                    <tr>

                        <td class="px-6 py-3">{{ $proveedor->nombre_proveedor }}</td>
                        <td class="px-6 py-3">{{ $proveedor->nit }}</td>
                        <td class="px-6 py-3">{{ $proveedor->correo }}</td>
                        <td class="px-6 py-3">{{ $proveedor->telefono }}</td>
                        <td class="px-6 py-3">{{ $proveedor->direccion }}</td>
                        <td class="px-6 py-3">
                            <!-- Enlace para editar pasando el id_proveedor del proveedor -->
                            <a data-modal-target="editar-modal-proveedor" data-modal-toggle="editar-modal-proveedor"
                                class="text-blue-600">Editar</a>
                            <!-- Formulario para eliminar el proveedor -->
                            <form action="{{ route('Proveedores.Eliminar', $proveedor->id_proveedor) }}" method="POST"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <!-- Modal para editar proveedor -->
                    <div id="editar-modal-proveedor" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                <!-- Encabezado del modal0 -->
                                <div
                                    class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600 border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ingresar un Nuevo
                                        Proveedor</h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        data-modal-toggle="editar-modal-proveedor">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                    </button>
                                </div>
                                <!-- Formulario del modal -->
                                <form class="p-4"
                                    action="{{ route('Proveedores.Editar', $proveedor->id_proveedor) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label for="nombre_proveedor"
                                            class="block text-sm font-medium text-gray-900 dark:text-white">Nombre del
                                            Proveedor</label>
                                        <input type="text" id="nombre_proveedor" name="nombre_proveedor"
                                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 dark:bg-gray-600"
                                            required value="{{ $proveedor->nombre_proveedor }}">
                                    </div>
                                    <div class="mb-4">
                                        <label for="nit"
                                            class="block text-sm font-medium text-gray-900 dark:text-white">NIT</label>
                                        <input type="text" id="nit" name="nit"
                                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 dark:bg-gray-600"
                                            required value="{{ $proveedor->nit }}" />
                                    </div>
                                    <div class="mb-4">
                                        <label for="correo"
                                            class="block text-sm font-medium text-gray-900 dark:text-white">Correo
                                            Electrónico</label>
                                        <input type="email" id="correo" name="correo"
                                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 dark:bg-gray-600"
                                            required value="{{ $proveedor->correo }}" />
                                    </div>
                                    <div class="mb-4">
                                        <label for="telefono"
                                            class="block text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
                                        <input type="text" id="telefono" name="telefono"
                                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 dark:bg-gray-600"
                                            required value="{{ $proveedor->telefono }}" />
                                    </div>
                                    <div class="mb-4">
                                        <label for="direccion"
                                            class="block text-sm font-medium text-gray-900 dark:text-white">Dirección</label>
                                        <input type="text" id="direccion" name="direccion"
                                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 dark:bg-gray-600"
                                            required value="{{ $proveedor->direccion }}" />
                                    </div>
                                    <button type="submit" class="text-white bg-blue-700 px-5 py-2.5 rounded-lg">
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

</x-admin-layout>
