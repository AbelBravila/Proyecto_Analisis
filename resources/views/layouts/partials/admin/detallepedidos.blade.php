<div class="overflow-x-auto">
    <table class="min-w-full text-sm text-left text-gray-500 border border-gray-200">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
            <tr>
                <th class="px-4 py-2 border">Pedido No.</th>
                <th class="px-4 py-2 border">No.</th>
                <th class="px-4 py-2 border">Producto</th>
                <th class="px-4 py-2 border">Cantidad</th>
                <th class="px-4 py-2 border">Costo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalles as $index => $detalle)
                <tr class="bg-white border-b">
                    <td class="px-4 py-2 border">{{ $detalle->id_pedido }}</td>
                    <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 border">{{ $detalle->nombre_producto }}</td>
                    <td class="px-4 py-2 border">{{ $detalle->cantidad }}</td>
                    <td class="px-4 py-2 border">Q{{ number_format($detalle->costo, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
