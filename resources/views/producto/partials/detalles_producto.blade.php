@if($total_productos->isEmpty())
    <div class="flex justify-center items-center min-h-[150px]">
        <p class="text-gray-500 text-center">No hay productos asociados.</p>
    </div>
    @else
    <div class="list-group flex flex-col gap-4">
        @foreach($total_productos as $producto)
            <div class="list-group-item p-4 border rounded bg-gray-50 shadow text-sm">
                <p class="text-center">
                    <strong>Nombre:</strong> {{ $producto->nombre_producto }} |
                    <strong>Precio:</strong> {{ $producto->precio }} |
                    <strong>Costo:</strong> {{ $producto->costo }} |
                    <strong>Lote:</strong> {{ $producto->lote }} |
                    <strong>Fabricante:</strong> {{ $producto->fabricante }} |
                    <strong>Vencimiento:</strong> {{ $producto->fecha_vencimiento }} |
                    <strong>Pasillo:</strong> {{ $producto->codigo_pasillo }} |
                    <strong>Estantería:</strong> {{ $producto->codigo_estanteria }} |
                    <strong>Stock:</strong> {{ $producto->stock }} |
                    <strong>Proveedor:</strong> {{ $producto->nombre_proveedor }}
                </p>
            </div>
        @endforeach
    </div>
@endif