<x-admin-layout>

    <!-- Modal toggle -->
    <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
        Agregar Usuario
      </button>
      
      <!-- Main modal -->
      <div id="crud-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
          <div class="relative p-4 w-full max-w-md max-h-full">
              <!-- Modal content -->
              <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                  <!-- Modal header -->
                  <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                          Registrar Usuario
                      </h3>
                      <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                          </svg>
                          <span class="sr-only">Close modal</span>
                      </button>
                  </div>
                  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
                  <!-- Modal body -->
                  <div class="container">
       

        @if(session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif

        <form action="{{ route('Usuario') }}" method="POST">
            @csrf
            <label for="codigo">Codigo Usuario</label>
            <input type="text" id="codigo" name="codigo" required>
            @error('codigo')
                <span class="error">{{ $message }}</span>
            @enderror

            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>
            @error('nombre')
                <span class="error">{{ $message }}</span>
            @enderror

            <label for="correo_u">Correo Electrónico</label>
            <input type="email" id="correo_u" name="correo_u" required>
            @error('correo_u')
                <span class="error">{{ $message }}</span>
            @enderror

            <label for="numero">Teléfono</label>
            <input type="number" id="numero" name="numero" required>
            @error('numero')
                <span class="error">{{ $message }}</span>
            @enderror
            
            <button type="submit">REGISTRAR</button>
            <button class="btn btn-primary" onclick="window.location.href='{{ route('welcome') }}'">
                REGRESAR
            </button>
        </form>
    </div>
              </div>
          </div>
      </div> 
      
    </x-admin-layout>