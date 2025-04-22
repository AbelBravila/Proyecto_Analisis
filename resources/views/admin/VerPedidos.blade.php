<x-admin-layout>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <b>
        <h1 class="text-lg text-center dark:text-black">PEDIDOS</h1>
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
    <a href="{{ route('pedidos') }}" 
    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
     Hacer nuevo pedido
    </a>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-white">
            <thead class="text-m text-gray-700  bg-gray-50 dark:bg-gray-700 dark:text-white">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Fecha de Pedido
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
            @foreach ($pedidos as $pedido)
                    <tr>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $pedido->id_pedido }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $pedido->fecha_pedido }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $pedido->estado }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">
                        <a class="fa fa-pencil fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline" data-modal-target="editar-modal-proveedor-{{$pedido->id_pedido}}" data-modal-toggle="editar-modal-proveedor-{{ $pedido->id_pedido }}" class="text-blue-600"></a>
                        <a class="fa fa-trash fa-lg font-medium text-blue-600 dark:text-blue-500 hover:underline" href="{{ route('proveedor.cambiar_estado', ['id' => $pedido->id_pedido]) }}" onclick="return confirm('¿Estás seguro de que deseas eliminar este proveedor?')"></a>
                    </td>
                    </tr>
                @endforeach 
            </tbody>
        </table>
    </div>
</x-admin-layout>
