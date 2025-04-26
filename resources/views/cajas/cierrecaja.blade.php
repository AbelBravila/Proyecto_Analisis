<x-admin-layout>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <b>
        <h1 class="text-lg text-center dark:text-black">MOVIMIENTOS CAJAS CERRADAS</h1>
    </b>

    <!-- Formulario de búsqueda -->
    <form method="GET" action="{{ route('cierre-caja.index') }}" class="mb-4">
        <div class="flex space-x-2">
            <input type="text" name="buscador" placeholder="Buscar por usuario o caja"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <button type="submit"
                class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Buscar
            </button>
        </div>
    </form>

    @if (session('success'))
        <br>
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <br>
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <br>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-white">
            <thead class="text-m text-gray-700  bg-gray-50 dark:bg-gray-700 dark:text-white">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Nombre Caja</th>
                    <th scope="col" class="px-6 py-3">Turno</th>
                    <th scope="col" class="px-6 py-3">Usuario</th>
                    <th scope="col" class="px-6 py-3">Monto de Apertura</th>
                    <th scope="col" class="px-6 py-3">Monto Final</th>
                    <th scope="col" class="px-6 py-3">Fecha de Apertura</th>
                    <th scope="col" class="px-6 py-3">Fecha de Cierre</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($aperturas as $caja)
                    <tr>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $caja->ID_Apertura }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $caja->nombre_caja }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $caja->nombre_usuario }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $caja->descripcion_turno }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">
                            {{ number_format($caja->MontoInicial, 2, '.', '') }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">
                            {{ number_format($caja->MontoFinal, 2, '.', '') }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $caja->FechaHoraApertura }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">{{ $caja->FechaHoraCierre }}</td>
                        <td scope="col" class="px-6 py-3 dark:text-black">
                            <button onclick="verMovimientos({{ $caja->ID_Apertura }})"
                                class="text-blue-600 hover:underline"><a
                                    class="fa-solid fa-list fa-lg text-blue-600 hover:underline ver-detalles cursor-pointer"></a></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="movimientosModal"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white w-3/4 max-h-[90vh] overflow-y-auto rounded p-6 relative">
            <h2 class="text-lg font-semibold mb-4">Movimientos de Caja</h2>
            <div id="contenidoModalMovimientos">

            </div>
            <button onclick="cerrarModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black">✖</button>
        </div>
    </div>

    <script>
        function verMovimientos(idApertura) {
            fetch(`/apertura-caja/${idApertura}/movimientos-json`)
                .then(res => res.json())
                .then(data => {
                    const modal = document.getElementById('movimientosModal');
                    const contenido = document.getElementById('contenidoModalMovimientos');

                    if (data.error) {
                        contenido.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                    } else {
                        const apertura = data.apertura;
                        const resumen = data.resumen;
                        const movimientos = data.movimientos;
                        const saldo = parseFloat(data.saldo_final).toFixed(2);

                        let html = `
                        <table class="table-auto w-full text-sm border" border="1" cellpadding="5" cellspacing="0">
                        <tr>
                        <th>Monto inicial</th>
                        <th>Total ingresos</th>
                        <th>Total egresos</th>
                        <th>Saldo final</th>
                        </tr>
                        <tr>
                        <td class="text-center">Q${apertura?.MontoInicial ?? 0}</td>
                        <td class="text-center">Q${resumen?.total_ingresos ?? 0}</td>
                        <td class="text-center">Q${resumen?.total_egresos ?? 0}</td>
                        <td class="text-center">Q${saldo ?? 0}</td>
                        </tr>
                        </table>
                        <hr class="my-4">
                        <table class="table-auto w-full text-sm border">
                            <thead>
                                <tr>
                                    <th class="border px-2 py-1">Fecha</th>
                                    <th class="border px-2 py-1">Tipo</th>
                                    <th class="border px-2 py-1">Descripción</th>
                                    <th class="border px-2 py-1">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                        movimientos.forEach(mov => {
                            html += `
                            <tr>
                                <td class="border px-2 py-1 text-center">${new Date(mov.fecha).toLocaleString()}</td>
                                <td class="border px-2 py-1 text-center" >${mov.tipo}</td>
                                <td class="border px-2 py-1 text-center">${mov.descripcion}</td>
                                <td class="border px-2 py-1 text-center">Q${parseFloat(mov.monto).toFixed(2)}</td>
                            </tr>
                        `;
                        });

                        html += `</tbody></table>`;
                        contenido.innerHTML = html;
                    }

                    modal.classList.remove('hidden');
                })
                .catch(err => {
                    console.error(err);
                    alert("Error al cargar los movimientos");
                });
        }

        function cerrarModal() {
            document.getElementById('movimientosModal').classList.add('hidden');
        }
    </script>
</x-admin-layout>
