@if($productos->isEmpty())
    <p class="text-center text-gray-500">No hay productos asociados.</p>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-500 border border-gray-200">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Producto</th>
                    <th class="px-4 py-2 border">Precio</th>
                    <th class="px-4 py-2 border">Costo</th>
                    <th class="px-4 py-2 border">Lote</th>
                    <th class="px-4 py-2 border">Fabricante</th>
                    <th class="px-4 py-2 border">Vencimiento</th>
                    <th class="px-4 py-2 border">Pasillo</th>
                    <th class="px-4 py-2 border">Estantería</th>
                    <th class="px-4 py-2 border">Stock</th>
                    <th class="px-4 py-2 border">Proveedor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $index => $producto)
                    <tr class="bg-white border-b">
                        <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 border">{{ $producto->nombre_producto }}</td>
                        <td class="px-4 py-2 border">{{ number_format($producto->precio, 2) }}</td>
                        <td class="px-4 py-2 border">{{ number_format($producto->costo, 2) }}</td>
                        <td class="px-4 py-2 border">{{ $producto->lote }}</td>
                        <td class="px-4 py-2 border">{{ $producto->fabricante }}</td>
                        <td class="px-4 py-2 border">{{ $producto->fecha_vencimiento }}</td>
                        <td class="px-4 py-2 border">{{ $producto->codigo_pasillo }}</td>
                        <td class="px-4 py-2 border">{{ $producto->codigo_estanteria }}</td>
                        <td class="px-4 py-2 border">{{ $producto->stock }}</td>
                        <td class="px-4 py-2 border">{{ $producto->nombre_proveedor }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
