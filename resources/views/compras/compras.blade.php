<x-admin-layout>
<h3 class="text-lg font-semibold text-gray-900 dark:text-black">
    Compras
</h3>
<form class="p-4 md:p-5">
    @csrf
    <div class="grid gap-4 mb-4 grid-cols-2">
        <div class="col-span-2">
            <label for="codigo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Codigo Usuario</label>
            <input type="text" id="codigo" name="codigo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
            @error('codigo')
                <span class="error text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-span-2">
            <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
            @error('nombre')
                <span class="error text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-span-2">
            <label for="correo_u" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Correo Electrónico</label>
            <input type="email" id="correo_u" name="correo_u" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
            @error('correo_u')
                <span class="error text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-span-2">
            <label for="numero" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Teléfono</label>
            <input type="number" id="numero" name="numero" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
            @error('numero')
                <span class="error text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
        Registrar
    </button>
   
</form>
</x-admin-layout>