<x-admin-layout>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table id="productTable" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Cantidad
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Código Producto
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Descripción
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Precio Unitario
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">
                        <input type="text" id="cantidad" name="cantidad" style="width: 75px; height: 40px;" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </td>
                    <td class="px-6 py-4">
                        <input type="text" id="codigo_producto" name="codigo_producto" style="width: 115px; height: 40px;" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </td>
                    <td class="px-6 py-4">
                        <input type="text" id="nombre_producto" name="nombre_producto" disabled class="mb-5 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed" value="{{ $nombre_producto ?? '' }}">
                    </td>
                    <td class="px-6 py-4">
                        <input type="text" id="precio_unitario" style="width: 100px; height: 40px;" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </td>
                    <td class="px-6 py-4">
                        <input type="text" id="costo" name="costo" class="mb-5 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed" value="">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Botón para agregar filas -->
    <div class="flex justify mt-4 gap-4">
        <button id="addRowButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Agregar Producto
        </button>
        <form method="POST" action="{{ route('pedidos.guardar') }}">
            @csrf   
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Guardar Pedido
            </button>

        </form> 
    </div>
    
    <script>

        // Función para agregar una nueva fila
        document.getElementById("addRowButton").addEventListener("click", function() {
            const tableBody = document.querySelector("#productTable tbody");
    
            // Crear una nueva fila
            const newRow = document.createElement("tr");
            newRow.className = "bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600";
    
            // Agregar celdas a la nueva fila
            newRow.innerHTML = `
                <td class="px-6 py-4">
                        <input type="text" id="cantidad" name="cantidad" style="width: 75px; height: 40px;" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </td>
                    <td class="px-6 py-4">
                        <input type="text" id="codigo_producto" name="codigo_producto" style="width: 115px; height: 40px;" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </td>
                    <td class="px-6 py-4">
                        <input type="text" id="nombre_producto" name="nombre_producto" disabled class="mb-5 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed" value="{{ $nombre_producto ?? '' }}">
                    </td>
                    <td class="px-6 py-4">
                        <input type="text" id="precio_unitario" style="width: 100px; height: 40px;" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </td>
                    <td class="px-6 py-4">
                        <input type="text" id="costo" name="costo" class="mb-5 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed" value="">
                    </td>
            `;
    
            // Añadir la nueva fila al cuerpo de la tabla
            tableBody.appendChild(newRow);
        });
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Seleccionar el campo de código del producto
        document.getElementById("codigo_producto").addEventListener("blur", function () {
            let codigoProducto = this.value;
            
            if (codigoProducto.trim() === "") return; // Evitar solicitudes vacías

            fetch("{{ route('pedidos.buscar') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" // Token CSRF para Laravel
                },
                body: JSON.stringify({ codigo_producto: codigoProducto })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Producto no encontrado");
                }
                return response.json();
            })
            .then(data => {
                document.getElementById("nombre_producto").value = data.nombre_producto;
            })
            .catch(error => {
                console.error("Error:", error);
                document.getElementById("nombre_producto").value = "No encontrado";
            });
        });
    });
</script>


    
</x-admin-layout>