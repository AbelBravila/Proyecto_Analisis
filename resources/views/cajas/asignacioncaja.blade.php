<x-admin-layout>
    <b>
        <h1 class="text-lg text-center dark:text-black">ASIGNACION DE CAJA</h1>
    </b>

    <!-- Formulario de búsqueda -->
    <form method="GET" action="{{ route('asignacion-caja.index') }}" class="mb-4">
        <div class="flex space-x-2">
            <input type="text" name="buscador" placeholder="Buscar por usuario o fecha"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <button type="submit"
                class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Buscar
            </button>
        </div>
    </form>

    <!-- Botón para abrir modal -->
    <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        type="button">
        Asignacion de Caja
    </button>


    @if (session('success'))
        <br>
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <br>
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <br>

    <!-- Modal principal -->
    <div id="crud-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Asignacion de Caja
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="crud-modal">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Cerrar</span>
                    </button>
                </div>

                <!-- Modal body -->
                <form class="p-4 md:p-5" action="{{ route('asignacion-caja.store') }}" method="POST">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">



                        <div class="col-span-2">
                            <label for="turno"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecciona un
                                usuario</label>
                            <select id="id_usuario" name="id_usuario"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                                @foreach ($usuarios as $usuario)
                                    <option {{ $usuario->id_usuario == $usuario->id_usuario ? 'selected' : '' }}
                                        value="{{ $usuario->id_usuario }}"> {{ $usuario->nombre_usuario }}</option>
                                @endforeach
                            </select>
                            @error('nombre_usuario')
                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="col-span-2">
                            <label for="caja"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecciona una
                                caja</label>
                            <select id="id_caja" name="id_caja"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                                @foreach ($cajas as $caja)
                                    <option {{ $caja->id_caja == $caja->id_caja ? 'selected' : '' }}
                                        value="{{ $caja->id_caja }}"> {{ $caja->nombre_caja }}</option>
                                @endforeach
                            </select>
                            @error('nombre_caja')
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
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Nombre Caja</th>
                    <th scope="col" class="px-6 py-3">Nombre Usuario</th>
                    <th scope="col" class="px-6 py-3">Fecha de Asignacion</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($asignaciones as $asignacion)
                    <tr>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $asignacion->id_asignacion }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $asignacion->nombre_caja }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $asignacion->nombre_usuario }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $asignacion->fecha_asignacion }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">
                            <a class="fa fa-pencil fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                data-modal-target="editar-modal-cajas-{{ $asignacion->id_asignacion }}"
                                data-modal-toggle="editar-modal-cajas-{{ $asignacion->id_asignacion }}"
                                class="text-blue-600"></a>
                            <a class="fa fa-trash fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                href="{{ route('asignacion-caja.destroy', ['id' => $asignacion->id_asignacion]) }}"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta asignacion?')"></a>
                        </td>
                    </tr>
                    <!-- Modal for editing product -->
                    <div id="editar-modal-cajas-{{ $asignacion->id_asignacion }}" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                <!-- Modal header -->
                                <div
                                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Editar Asignacion
                                    </h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        data-modal-toggle="editar-modal-cajas-{{ $asignacion->id_asignacion }}">
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
                                <form action="{{ route('asignacion-caja.update', $asignacion->id_asignacion) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <div class="col-span-2">
                                            <label for="turno"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecciona
                                                un usuario</label>
                                            <select id="id_usuario" name="id_usuario"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required>
                                                @foreach ($usuarios as $usuario)
                                                    <option
                                                        {{ $usuario->id_usuario == $asignacion->id_usuario ? 'selected' : '' }}
                                                        value="{{ $usuario->id_usuario }}">
                                                        {{ $usuario->nombre_usuario }}</option>
                                                @endforeach
                                            </select>
                                            @error('nombre_usuario')
                                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div class="col-span-2">
                                            <label for="caja"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecciona
                                                una caja</label>
                                            <select id="id_caja" name="id_caja"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required>
                                                @foreach ($cajas as $caja)
                                                    <option
                                                        {{ $caja->id_caja == $asignacion->id_caja ? 'selected' : '' }}
                                                        value="{{ $caja->id_caja }}"> {{ $caja->nombre_caja }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('nombre_caja')
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

</x-admin-layout>
