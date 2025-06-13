<x-admin-layout>
    @php $csrf = csrf_token(); @endphp

    <h2 class="text-xl font-bold mb-4">Nueva Oferta</h2>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <input type="text" id="inputNombreOferta" placeholder="Nombre Oferta" class="p-2 border rounded" required>
        <input type="text" id="codigo_oferta" placeholder="Código Oferta" class="p-2 border rounded" required>
        <input type="date" id="inputFechaInicio" class="p-2 border rounded" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
        <input type="date" id="inputFechaFin" class="p-2 border rounded" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
    </div>

    <h3 class="text-lg font-semibold mb-2">Agregar producto a la oferta</h3>
    <div class="grid grid-cols-9 gap-4 mb-4">
        <input type="text" id="inputNombreProducto" placeholder="Nombre del Producto" class="p-2 border rounded">
        <select id="selectLote" class="p-2 border rounded">
            <option value="">Seleccione un lote</option>
        </select>
        <input type="text" id="stock" placeholder="En existencia" class="p-2 border rounded" readonly>
        <input type="text" id="precio" placeholder="Precio Regular" class="p-2 border rounded" readonly>
        <input type="number" id="inputPorcentajeOferta" placeholder="% Oferta" min="1" max="100" class="p-2 border rounded">
        <input type="number" id="cantidadoferta" placeholder="Cantidad a Ofertar" min="1" class="p-2 border rounded">
        <input type="number" id="unidadoferta" placeholder="Unidad Oferta" min="1" class="p-2 border rounded">
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
                <th class="border px-4 py-2">Cantidad a Ofertar</th>
                <th class="border px-4 py-2">Unidad Oferta</th>
                <th class="border px-4 py-2">Precio Oferta</th>
                <th class="border px-4 py-2">Acción</th>
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
            data.lotes.forEach((lote) => {
                const option = document.createElement("option");
                option.value = lote.id_lote;
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
        const loteSeleccionado = lotesGlobal.find(l => l.id_lote == this.value);
        if (!loteSeleccionado) {
            document.getElementById("stock").value = "";
            document.getElementById("precio").value = "";
            return;
        }

        document.getElementById("stock").value = loteSeleccionado.stock;
        document.getElementById("precio").value = loteSeleccionado.precio;
        calcularPrecioOferta();
    });

    document.getElementById("inputPorcentajeOferta").addEventListener("input", calcularPrecioOferta);

    function calcularPrecioOferta() {
        const precio = parseFloat(document.getElementById("precio").value) || 0;
        const porcentaje = parseFloat(document.getElementById("inputPorcentajeOferta").value) || 0;
        const oferta = precio - (precio * porcentaje / 100);
        document.getElementById("inputPrecioOferta").value = oferta.toFixed(2);
    }

    function renderDetalleOferta() {
        const tbody = document.getElementById("detalleOferta");
        tbody.innerHTML = "";
        detalles.forEach((d, i) => {
            const row = `
                <tr>
                    <td class="border px-2 py-1">${d.nombre_producto}</td>
                    <td class="border px-2 py-1">${d.id_lote}</td>
                    <td class="border px-2 py-1">${d.stock}</td>
                    <td class="border px-2 py-1">${d.precio_regular}</td>
                    <td class="border px-2 py-1">${d.porcentaje}%</td>
                    <td class="border px-2 py-1">${d.cantidad}</td>
                    <td class="border px-2 py-1">${d.unidad}</td>
                    <td class="border px-2 py-1">${d.precio_oferta}</td>
                    <td class="border px-2 py-1">
                        <button onclick="eliminarDetalle(${i})" class="bg-red-500 text-white px-2 py-1 rounded">Eliminar</button>
                    </td>
                </tr>`;
            tbody.innerHTML += row;
        });
    }

    function limpiarCampos() {
        document.getElementById("inputNombreProducto").value = "";
        document.getElementById("selectLote").innerHTML = '<option value="">Seleccione un lote</option>';
        document.getElementById("stock").value = "";
        document.getElementById("precio").value = "";
        document.getElementById("inputPorcentajeOferta").value = "";
        document.getElementById("cantidadoferta").value = "";
        document.getElementById("unidadoferta").value = "";
        document.getElementById("inputPrecioOferta").value = "";
    }

    function eliminarDetalle(index) {
        detalles.splice(index, 1);
        renderDetalleOferta();
    }

    document.getElementById("btnAgregar").addEventListener("click", () => {
        const nombre_producto = document.getElementById("inputNombreProducto").value;
        const loteId = document.getElementById("selectLote").value;
        const stock = document.getElementById("stock").value;
        const precio = document.getElementById("precio").value;
        const porcentaje = document.getElementById("inputPorcentajeOferta").value;
        const cantidad = document.getElementById("cantidadoferta").value;
        const unidad = document.getElementById("unidadoferta").value;
        const oferta = document.getElementById("inputPrecioOferta").value;

        if (!nombre_producto || !loteId || !stock || !precio || !porcentaje || !cantidad || !unidad || !oferta) {
            alert("Completa todos los campos.");
            return;
        }

        const producto = lotesGlobal.find(l => l.id_lote == loteId);

        detalles.push({
            id_producto: producto.id_producto,
            id_lote: loteId,
            nombre_producto,
            stock,
            precio_regular: precio,
            porcentaje,
            cantidad,
            unidad,
            precio_oferta: oferta
        });

        renderDetalleOferta();
        limpiarCampos();
    });

    document.getElementById("btnGuardarOferta").addEventListener("click", () => {
        if (detalles.length === 0) {
            alert("Agrega al menos un producto a la oferta.");
            return;
        }

        fetch("{{ route('ofertas.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token
            },
            body: JSON.stringify({
                nombre_oferta: document.getElementById("inputNombreOferta").value,
                codigo_oferta: document.getElementById("codigo_oferta").value,
                fecha_inicio: document.getElementById("inputFechaInicio").value,
                fecha_fin: document.getElementById("inputFechaFin").value,
                productos: detalles
            })
        })
        .then(res => res.json())
        .then(() => {
            alert("Oferta guardada correctamente.");
            location.reload();
        })
        .catch(err => {
            console.error(err);
            alert("Error al guardar la oferta.");
        });
    });
    </script>
</x-admin-layout>
