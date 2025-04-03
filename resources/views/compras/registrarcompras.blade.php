<x-admin-layout>
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-black">Registrar Compra </h2>
            <a href="{{ route('compras') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Volver al listado
            </a>
        </div>
        <form class="p-4 md:p-5" action="{{ route('Usuario') }}" method="POST">
            @csrf
            <div class="grid gap-4 mb-4 grid-cols-2">
                <div>
                    <label for="proveedor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Proveedor</label>
                    <select id="proveedor" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Todos los proveedores</option>
                        @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->nombre_proveedor }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tipo_compra" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de la Compra</label>
                    <select id="tipo_compra" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Tipo de la compra</option>
                        @foreach($tipo_compra as $tipos)
                        <option value="{{ $tipos->id_tipo_compra }}">{{ $tipos->nombre_tipo_compra }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="fecha_compra" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha de Compra</label>
                    <input type="date" id="fecha_compra" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        max="{{ \Carbon\Carbon::today()->toDateString() }}">
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>