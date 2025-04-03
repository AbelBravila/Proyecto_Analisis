<x-admin-layout>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table id="productTable" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Cantidad</th>
                    <th class="px-6 py-3">Código Producto</th>
                    <th class="px-6 py-3">Descripción</th>
                    <th class="px-6 py-3">Precio Unitario</th>
                    <th class="px-6 py-3">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr class="producto-row">
                    <td><input type="number" name="cantidad[]" class="cantidad w-full"></td>
                    <td><input type="text" name="codigo_producto[]" class="codigo_producto w-full"></td>
                    <td><input type="text" name="nombre_producto[]" disabled class="nombre_producto w-full bg-gray-100"></td>
                    <td><input type="number" name="precio_unitario[]" class="precio_unitario w-full"></td>
                    <td><input type="text" name="costo[]" class="costo w-full bg-gray-100" readonly></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex gap-4 mt-4">
        <button id="addRowButton" class="bg-blue-500 text-white py-2 px-4 rounded">Agregar Producto</button>
        <form method="POST" action="{{ route('pedidos.guardar') }}">
            @csrf   
            <button class="bg-green-500 text-white py-2 px-4 rounded">Guardar Pedido</button>
        </form> 
    </div>
    
    <script>
        document.getElementById("addRowButton").addEventListener("click", function() {
            const tableBody = document.querySelector("#productTable tbody");
            const newRow = document.createElement("tr");
            newRow.className = "producto-row";
            newRow.innerHTML = `
                <td><input type="number" name="cantidad[]" class="cantidad w-full"></td>
                <td><input type="text" name="codigo_producto[]" class="codigo_producto w-full"></td>
                <td><input type="text" name="nombre_producto[]" disabled class="nombre_producto w-full bg-gray-100"></td>
                <td><input type="number" name="precio_unitario[]" class="precio_unitario w-full"></td>
                <td><input type="text" name="costo[]" class="costo w-full bg-gray-100" readonly></td>
            `;
            tableBody.appendChild(newRow);
        });

        // Event delegation para búsqueda de producto
        document.querySelector("#productTable tbody").addEventListener("blur", function(event) {
            if (!event.target.classList.contains("codigo_producto")) return;
            
            let fila = event.target.closest("tr");
            let codigoProducto = event.target.value.trim();
            if (codigoProducto === "") return;

            fetch("{{ route('pedidos.buscar') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ codigo_producto: codigoProducto })
            })
            .then(response => response.json())
            .then(data => {
                fila.querySelector(".nombre_producto").value = data.nombre_producto ?? "No encontrado";
            })
            .catch(() => {
                fila.querySelector(".nombre_producto").value = "No encontrado";
            });
        }, true);

        // Cálculo automático del costo
        document.addEventListener("input", function(event) {
            if (!event.target.classList.contains("cantidad") && !event.target.classList.contains("precio_unitario")) return;

            let row = event.target.closest("tr");
            let cantidad = parseFloat(row.querySelector(".cantidad").value) || 0;
            let precioUnitario = parseFloat(row.querySelector(".precio_unitario").value) || 0;
            let costoInput = row.querySelector(".costo");

            let costo = cantidad * precioUnitario;
            costoInput.value = costo.toFixed(2);
        });
    </script>
</x-admin-layout>
