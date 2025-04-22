@if($productos->isEmpty())
    <p>No hay productos asociados.</p>
@else
    <div class="list-group">
        @foreach($total_productos as $producto)
            <div class="list-group-item">
                <p><strong>Nombre:</strong> {{ $producto->nombre_producto }}</p>
                <p><strong>Precio:</strong> {{ $producto->precio }} | <strong>Costo:</strong> {{ $producto->costo }}</p>
                <p><strong>Lote:</strong> {{ $producto->lote }} | <strong>Fabricante:</strong> {{ $producto->fabricante }}</p>
                <p><strong>Vencimiento:</strong> {{ $producto->fecha_vencimiento }}</p>
                <p><strong>Pasillo:</strong> {{ $producto->codigo_pasillo }} | <strong>Estantería:</strong> {{ $producto->codigo_estanteria }}</p>
                <p><strong>Stock:</strong> {{ $producto->stock }} | <strong>Proveedor:</strong> {{ $producto->nombre_proveedor }}</p>
            </div>
        @endforeach
    </div>
@endif