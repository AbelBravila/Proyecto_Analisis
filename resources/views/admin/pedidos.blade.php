<x-admin-layout>
    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-2">Agregar producto</h2>
        <div class="grid grid-cols-5 gap-4">
            <input id="inputCantidad" type="number" placeholder="Cantidad" class="p-2 border rounded">
            <input id="inputCodigo" type="text" placeholder="Código Producto" class="p-2 border rounded">
            <input id="inputDescripcion" type="text" placeholder="Descripción" class="p-2 border rounded">
            <input id="inputPrecio" type="number" placeholder="Precio Unitario" class="p-2 border rounded">
            <button id="btnAgregar" class="bg-blue-500 text-white px-4 py-2 rounded">Agregar</button>
        </div>
    </div>

    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-2">Productos agregados</h2>
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">Cantidad</th>
                    <th class="border px-4 py-2">Código</th>
                    <th class="border px-4 py-2">Descripción</th>
                    <th class="border px-4 py-2">Precio</th>
                    <th class="border px-4 py-2">Total</th>
                </tr>
            </thead>
            <tbody id="listaProductos"></tbody>
        </table>
    </div>

    <!-- Formulario oculto solo para tener el token CSRF -->
    <form id="csrfForm" style="display: none;">
        @csrf
    </form>

    <button id="btnGuardarPedido" class="bg-green-600 text-white px-4 py-2 rounded">Guardar Pedido</button>

    <script>
        let productos = [];

        const csrfToken = document.querySelector('input[name="_token"]').value;

        // Agregar producto a la lista temporal
        document.getElementById('btnAgregar').addEventListener('click', () => {
            let cantidad = parseFloat(document.getElementById('inputCantidad').value) || 0;
            let codigo = document.getElementById('inputCodigo').value.trim();
            let descripcion = document.getElementById('inputDescripcion').value.trim();
            let precio = parseFloat(document.getElementById('inputPrecio').value) || 0;
            let total = cantidad * precio;

            if (!codigo || !cantidad || !precio) {
                alert("Completa todos los campos antes de agregar.");
                return;
            }

            productos.push({ cantidad, codigo, descripcion, precio, total });

            const tbody = document.getElementById("listaProductos");
            tbody.innerHTML += `
                <tr>
                    <td class="border px-4 py-2">${cantidad}</td>
                    <td class="border px-4 py-2">${codigo}</td>
                    <td class="border px-4 py-2">${descripcion}</td>
                    <td class="border px-4 py-2">Q${precio.toFixed(2)}</td>
                    <td class="border px-4 py-2">Q${total.toFixed(2)}</td>
                </tr>
            `;

            // Limpiar campos
            document.getElementById('inputCantidad').value = '';
            document.getElementById('inputCodigo').value = '';
            document.getElementById('inputDescripcion').value = '';
            document.getElementById('inputPrecio').value = '';
        });

        // Guardar pedido
        document.getElementById("btnGuardarPedido").addEventListener("click", function () {
            if (productos.length === 0) {
                alert("Debes agregar al menos un producto.");
                return;
            }

            fetch("{{ route('pedidos.guardar') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({ productos })
            })
            .then(response => response.json())
            .then(data => {
                alert("Pedido guardado correctamente.");
                window.location.reload();
            })
            .catch(error => {
                console.error("Error al enviar el pedido:", error);
            });
        });

        // Búsqueda por código de producto cuando se pierde el foco
        document.getElementById('inputCodigo').addEventListener('blur', function () {
            let codigoProducto = this.value.trim();
            if (!codigoProducto) return;

            fetch("{{ route('producto') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({ codigo_producto: codigoProducto })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("inputDescripcion").value = data.nombre_producto ?? "No encontrado";
            })
            .catch(() => {
                document.getElementById("inputDescripcion").value = "No encontrado";
            });
        });
    </script>
</x-admin-layout>
