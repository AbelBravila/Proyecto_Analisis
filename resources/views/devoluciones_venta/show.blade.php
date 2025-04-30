<x-admin-layout>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-black">Detalle de Devolución #{{ $devolucion->id_devolucion_venta }}</h2>
        <a href="{{ route('devoluciones_venta.index') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Volver al listado
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Información General</h3>
            <div class="space-y-2 dark:text-white">
                <p><span class="font-semibold">ID Devolución:</span> {{ $devolucion->id_devolucion_venta }}</p>
                <p><span class="font-semibold">Fecha:</span> {{ \Carbon\Carbon::parse($devolucion->fecha_devolucion)->format('d/m/Y H:i') }}</p>
                <p><span class="font-semibold">Cliente:</span> 
                    @if($devolucion->cliente)
                    {{ $devolucion->cliente->nombre_cliente }}
                @else
                    Cliente no encontrado
                @endif</p>
                <p><span class="font-semibold">Estado:</span> 
                    <span class="{{ $devolucion->estado == 'A' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $devolucion->estado == 'A' ? 'Activo' : 'Inactivo' }}
                    </span>
                </p>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Resumen</h3>
            <div class="space-y-2 dark:text-white">
                <p><span class="font-semibold">Total Productos:</span> {{ $devolucion->detalles->count() }}</p>
                <p><span class="font-semibold">Cantidad Total:</span> {{ $devolucion->detalles->sum('cantidad') }}</p>
                <p><span class="font-semibold">Monto Total:</span> Q{{ number_format($devolucion->detalles->sum(function($item) { return $item->cantidad * $item->precio; }), 2) }}</p>
            </div>
        </div>
    </div>
    
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Producto</th>
                    <th scope="col" class="px-6 py-3">Código</th>
                    <th scope="col" class="px-6 py-3">Cantidad</th>
                    <th scope="col" class="px-6 py-3">Costo Unitario</th>
                    <th scope="col" class="px-6 py-3">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($devolucion->detalles as $detalle)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $detalle->producto->esquema->nombre_producto }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $detalle->producto->esquema->codigo_producto }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $detalle->cantidad }}
                    </td>
                    <td class="px-6 py-4">
                        Q{{ number_format($detalle->precio, 2) }}
                    </td>
                    <td class="px-6 py-4 font-semibold">
                        Q{{ number_format($detalle->cantidad * $detalle->precio, 2) }}
                    </td>
                </tr>
                @endforeach
                <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                    <td colspan="3" class="px-6 py-4"></td>
                    <td class="px-6 py-4">Total:</td>
                    <td class="px-6 py-4">Q{{ number_format($devolucion->detalles->sum(function($item) { return $item->cantidad * $item->precio; }), 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="mt-6 flex justify-end">
        <button onclick="window.print()" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 mr-2">
            <i class="fas fa-print mr-2"></i>Imprimir
        </button>
        <a href="#" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
        </a>
    </div>
</x-admin-layout>