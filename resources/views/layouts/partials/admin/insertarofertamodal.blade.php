<x-admin-layout>
    @php $csrf = csrf_token(); @endphp

    <h2 class="text-xl font-bold mb-4">Nueva Oferta</h2>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <input type="text" id="inputNombreOferta" placeholder="Nombre Oferta" class="p-2 border rounded" required>
        <input type="date" id="inputFechaInicio" class="p-2 border rounded" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
        <input type="date" id="inputFechaFin" class="p-2 border rounded" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
    </div>

    <h3 class="text-lg font-semibold mb-2">Agregar producto a la oferta</h3>
    <div class="grid grid-cols-7 gap-4 mb-4">
        <input type="text" id="inputNombreProducto" placeholder="Nombre del Producto" class="p-2 border rounded">
        <select id="selectLote" class="p-2 border rounded">
            <option value="">Seleccione un lote</option>
        </select>
        <input type="text" id="stock" placeholder="En existencia" class="p-2 border rounded" readonly>
        <input type="text" id="precio" placeholder="Precio Regular" class="p-2 border rounded" readonly>
        <input type="number" id="inputPorcentajeOferta" placeholder="% Oferta" class="p-2 border rounded">
        <input type="text" id="inputPrecioOferta" placeholder="Precio Oferta" class="p-2 border rounded" readonly>
        <button id="btnAgregar" class="bg-blue-500 text-white px-4 py-2 rounded">Agregar</button>
    </div>

    <table class="w-full table-auto border-collapse mb-6">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">Producto</th>
                <th class="border px-4 py-2">No. Lote</th>
                <th class="border px-4 py-2">Stock Disponible</th>
                <th class="border px-4 py-2">Precio Regular</th>
                <th class="border px-4 py-2">Porcentaje</th>
                <th class="border px-4 py-2">Precio Oferta</th>
            </tr>
        </thead>
        <tbody id="detalleOferta"></tbody>
    </table>

    <button id="btnGuardarOferta" class="bg-green-600 text-white px-4 py-2 rounded">Guardar Oferta</button>
    <button onclick="window.location='{{ route('ofertas') }}'" class="bg-blue-600 text-white px-4 py-2 rounded">Regresar</button>

    <script>
    let detalles = [];
    const token = '{{ $csrf }}';
    let lotesGlobal = [];

    function debounce(func, delay) {
        let timer;
        return function(...args) {
            clearTimeout(timer);
            timer = setTimeout(() => func.apply(this, args), delay);
        }
    }

    document.getElementById("inputNombreProducto").addEventListener("input", debounce(() => {
        const nombre = document.getElementById("inputNombreProducto").value.trim();
        if (!nombre) return;

        fetch("{{ route('producto.buscar.nombre') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token
            },
            body: JSON.stringify({ inputNombreProducto: nombre })
        })
        .then(res => res.json())
        .then(data => {
            lotesGlobal = data.lotes;
            const select = document.getElementById("selectLote");
            select.innerHTML = '<option value="">Seleccione un lote</option>';
            data.lotes.forEach((lote, index) => {
                const option = document.createElement("option");
                option.value = index;
                option.textContent = lote.lote;
                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Producto no encontrado o sin lotes.");
        });
    }, 500));

    document.getElementById("selectLote").addEventListener("change", function () {
        const index = this.value;
        if (index === "") {
            document.getElementById("stock").value = "";
            document.getElementById("precio").value = "";
            return;
        }

        const data = lotesGlobal[index];
        document.getElementById("stock").value = data.stock;
        document.getElementById("precio").value = data.precio;
        calcularPrecioOferta();
    });

    document.getElementById("inputPorcentajeOferta").addEventListener("input", calcularPrecioOferta);

    function calcularPrecioOferta() {
        const precio = parseFloat(document.getElementById("precio").value) || 0;
        const porcentaje = parseFloat(document.getElementById("inputPorcentajeOferta").value) || 0;
        const oferta = precio - (precio * porcentaje / 100);
        document.getElementById("inputPrecioOferta").value = oferta.toFixed(2);
    }

    document.getElementById("btnAgregar").addEventListener("click", () => {
        const nombre = document.getElementById("inputNombreProducto").value;
        const loteIndex = document.getElementById("selectLote").value;
        const stock = document.getElementById("stock").value;
        const precio = document.getElementById("precio").value;
        const porcentaje = document.getElementById("inputPorcentajeOferta").value;
        const oferta = document.getElementById("inputPrecioOferta").value;

        if (!nombre || loteIndex === "" || !stock || !precio || !porcentaje || !oferta) {
            alert("Completa todos los campos.");
            return;
        }

        const lote = lotesGlobal[loteIndex].lote;   

        detalles.push({id_producto: lotesGlobal[loteIndex].id_producto, id_lote: lotesGlobal[loteIndex].id_lote, nombre, stock, precio_regular:precio, porcentaje, precio_oferta: oferta});  
        const row = `
            <tr>
                <td class="border px-4 py-2">${nombre}</td>
                <td class="border px-4 py-2">${lote}</td>
                <td class="border px-4 py-2">${stock}</td>
                <td class="border px-4 py-2">Q${parseFloat(precio).toFixed(2)}</td>
                <td class="border px-4 py-2">${porcentaje}%</td>
                <td class="border px-4 py-2">Q${parseFloat(oferta).toFixed(2)}</td>
            </tr>
        `;

        document.getElementById("detalleOferta").innerHTML += row;

        // Limpiar campos
        ["inputNombreProducto", "stock", "precio", "inputPorcentajeOferta", "inputPrecioOferta"]
            .forEach(id => document.getElementById(id).value = "");
        document.getElementById("selectLote").innerHTML = '<option value="">Seleccione un lote</option>';
    });

    document.getElementById("btnGuardarOferta").addEventListener("click", () => {
        const nombre = document.getElementById("inputNombreOferta").value;
        const inicio = document.getElementById("inputFechaInicio").value;
        const fin = document.getElementById("inputFechaFin").value;

        if (!nombre || detalles.length === 0) {
            alert("Faltan datos para guardar la oferta.");
            return;
        }
        console.log("Datos que se enviarán:", {
            nombre_oferta: nombre,
            fecha_inicio: inicio,
            fecha_fin: fin,
            productos: detalles
        });
        fetch("{{ route('ofertas.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token
            },
            body: JSON.stringify({
                nombre_oferta: nombre,
                fecha_inicio: inicio,
                fecha_fin: fin,
                productos: detalles
            })
        })
        .then(res => res.json())
        .then(() => {
            alert("Oferta guardada.");
            location.reload();
        })
        .catch(err => {
            console.error(err);
            alert("Error al guardar.");
        });
    });
</script>

</x-admin-layout>