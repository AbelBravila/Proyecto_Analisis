@if($total_productos->isEmpty())
    <div class="flex justify-center items-center min-h-[150px]">
        <p class="text-gray-500 text-center">No hay productos asociados.</p>
    </div>
@else
    <div class="overflow-x-auto mt-4">
        <table class="min-w-full text-sm text-left text-gray-700 border border-gray-200 shadow-sm rounded">
            <thead class="text-xs uppercase bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Nombre</th>
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
                @foreach($total_productos as $index => $producto)
                    <tr class="bg-white border-b hover:bg-gray-50">
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
