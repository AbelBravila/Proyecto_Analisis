@if($total_detalle->isEmpty())
    <p class="text-center text-gray-500">No hay productos asociados a esta compra.</p>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-500 border border-gray-200">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Producto</th>
                    <th class="px-4 py-2 border">Cantidad</th>
                    <th class="px-4 py-2 border">Costo Unitario</th>
                    <th class="px-4 py-2 border">Lote</th>
                    <th class="px-4 py-2 border">Fabricante</th>
                </tr>
            </thead>
            <tbody>
                @foreach($total_detalle as $index => $detalle)
                    <tr class="bg-white border-b">
                        <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 border">{{ $detalle->nombre_producto }}</td>
                        <td class="px-4 py-2 border">{{ $detalle->cantidad }}</td>
                        <td class="px-4 py-2 border">{{ number_format($detalle->costo, 2) }}</td>
                        <td class="px-4 py-2 border">{{ $detalle->lote }}</td>
                        <td class="px-4 py-2 border">{{ $detalle->fabricante }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
