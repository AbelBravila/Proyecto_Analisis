<x-admin-layout>
    <b><h1 class="text-lg text-center dark:text-black">COMPRAS</h1></b>
    <form method="GET" action="{{ route('compras') }}" class="mb-4">
        <div class="flex space-x-2">
            <input type="text" name="buscador" placeholder="Buscar por compra o proveedor" 
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Buscar
            </button>
        </div>
    </form>

    <button onclick="window.location='{{ route('compras.registrar') }}'" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
        Ingresar Compra
    </button>

    <br>

    <div class="container">
        <!-- Tabla que mostrará los detalles de las compras -->
        <div class="overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-white">
                <thead class="text-m text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-white">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Compra
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tipo de Compra
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nombre del Proveedor
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Cantidad
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Total de la Compra
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="w-full text-sm text-left text-gray-500 dark:text-black">
                    @foreach ($compras as $compra)
                        <tr>
                            <td class="px-6 py-3">{{ $compra->id_compra }}</td>
                            <td class="px-6 py-3">{{ $compra->nombre_tipo_compra }}</td>
                            <td class="px-6 py-3">{{ $compra->nombre_proveedor }}</td>
                            <td class="px-6 py-3">{{ $compra->cantidad }}</td>
                            <td class="px-6 py-3">{{ number_format($compra->total_compra, 2) }}</td>
                            <td scope="col" class="px-6 py-3 dark:text-black">
                                <a class="fa fa-pencil fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline" class="text-blue-600"></a>
                                <a class="fa fa-trash fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?')"></a>
                                <a class="fa-solid fa-list fa-lg text-blue-600 hover:underline ver-detalles cursor-pointer"
                                data-id="{{ $compra->id_compra }}"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>