<x-admin-layout>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <b>
        <h1 class="text-lg text-center dark:text-black">PASILLOS</h1>
    </b>

    <form method="GET" action="{{ route('Pasillo') }}" class="mb-4">
        <div class="flex space-x-2">
            <input type="text" name="buscador" placeholder="Buscar por código"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <button type="submit"
                class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Buscar
            </button>
        </div>
    </form>

    <!-- Modal toggle -->
    <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
        Agregar Pasillo
      </button>

      <br>
      
      <!-- Main modal -->
      <div id="crud-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
          <div class="relative p-4 w-full max-w-md max-h-full">
              <!-- Modal content -->
              <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                  <!-- Modal header -->
                  <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                              Ingresar un Nuevo Pasillo
                          </h3>
                          <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                              </svg>
                              <span class="sr-only">Cerrar</span>
                          </button>
                      </div>
                  <!-- Modal body -->
        <div class="container">
       
            @if(session('success'))
            <p class="success">{{ session('success') }}</p>
            @endif

            <form class="p-4 md:p-5" action="{{ route('Pasillo') }}" method="POST">
            @csrf
            <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="codigo_pasillo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Codigo del Pasillo</label>
                            <input type="text" id="codigo_pasillo" name="codigo_pasillo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                            @error('codigo_pasillo')
                                <span class="error text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        Registrar
                    </button>
                
                </form>
              </div>
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
                   Codigo del Pasillo
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
            @foreach ($pasillos as $pasillo)
                <tr>
                    <td scope="col" class="px-6 py-3 dark:text-black">{{ $pasillo->id_pasillo}}</td>
                    <td scope="col" class="px-6 py-3 dark:text-black">{{ $pasillo->codigo_pasillo}}</td>
                    <td scope="col" class="px-6 py-3 dark:text-black">{{ $pasillo->estado }}</td>
                    <td scope="col" class="px-6 py-3 dark:text-black">
                    <a class="fa fa-pencil fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                data-modal-target="editar-modal-pasillo-{{ $pasillo->id_pasillo }}"
                                data-modal-toggle="editar-modal-pasillo-{{ $pasillo->id_pasillo }}"
                                class="text-blue-600">
                    </a>
                    <a class="fa fa-trash fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                href="{{ route('Pasillo.cambiar_estado', ['id' => $pasillo->id_pasillo]) }}"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este pasillo?')">
                    </a>
                    </td>
                </tr>
                <div id="editar-modal-pasillo-{{ $pasillo->id_pasillo }}" tabindex="-1"
                        aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                <!-- Modal header -->
                                <div
                                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Editar Pasillo
                                    </h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        data-modal-toggle="editar-modal-pasillo-{{ $pasillo->id_pasillo }}">
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
                                    action="{{ route('Pasillo.actualizar_pasillo', $pasillo->id_pasillo) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <div class="col-span-2">
                                            <label for="codigo_pasillo"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Codigo
                                                del Pasillo</label>
                                            <input type="text" id="codigo_pasillo" name="codigo_pasillo"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required
                                                value="{{ old('codigo_pasillo', $pasillo->codigo_pasillo) }}">
                                            @error('codigo')
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