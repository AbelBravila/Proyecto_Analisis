<div class="overflow-x-auto">
    <table class="min-w-full text-sm text-left text-gray-500 border border-gray-200">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
            <tr>
                <th class="px-4 py-2 border">No.</th>
                <th class="px-4 py-2 border">Producto</th>
                <th class="px-4 py-2 border">Precio Regular</th>
                <th class="px-4 py-2 border">Precio Oferta</th>
                <th class="px-4 py-2 border">Porcentaje</th>
                <th class="px-4 py-2 border">Lote</th>
                <th class="px-4 py-2 border">Cantidad en Oferta</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalles as $index => $detalle)
                <tr class="bg-white border-b">
                    <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 border">{{ $detalle->Nombre }}</td>
                    <td class="px-4 py-2 border">Q. {{ $detalle->Precio_Producto }}</td>
                    <td class="px-4 py-2 border">Q. {{ $detalle->Precio_Oferta }}</td>
                    <td class="px-4 py-2 border">{{ $detalle->Porcentaje_de_Oferta }}%</td>
                    <td class="px-4 py-2 border">{{ $detalle->Lote }}</td>
                    <td class="px-4 py-2 border">{{ $detalle->Cantidad_Disponible }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
