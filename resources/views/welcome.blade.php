<x-admin-layout>
    <div class="p-6 bg-gray-100 min-h-screen">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">POS GUATE</h2>

        <!-- Cards resumen -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
            <div class="p-5 bg-white rounded-xl shadow flex items-center justify-between hover:shadow-lg transition">
                <div>
                    <h3 class="text-gray-500 text-sm uppercase">Total Ventas</h3>
                    <p class="text-2xl font-bold text-green-600">Q{{ number_format($totalVentas, 2) }}</p>
                </div>
                <i class="fa-solid fa-cart-shopping text-green-500 text-3xl"></i>
            </div>

            <div class="p-5 bg-white rounded-xl shadow flex items-center justify-between hover:shadow-lg transition">
                <div>
                    <h3 class="text-gray-500 text-sm uppercase">Total Compras</h3>
                    <p class="text-2xl font-bold text-blue-600">Q{{ number_format($totalCompras, 2) }}</p>
                </div>
                <i class="fa-solid fa-boxes-stacked text-blue-500 text-3xl"></i>
            </div>

            <div class="p-5 bg-white rounded-xl shadow flex items-center justify-between hover:shadow-lg transition">
                <div>
                    <h3 class="text-gray-500 text-sm uppercase">Productos Vendidos</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $ventasPorDia->sum('total') > 0 ? $ventasPorDia->count() : 0 }}</p>
                </div>
                <i class="fa-solid fa-tag text-purple-500 text-3xl"></i>
            </div>

            <div class="p-5 bg-white rounded-xl shadow flex items-center justify-between hover:shadow-lg transition">
                <div>
                    <h3 class="text-gray-500 text-sm uppercase">Proveedores Activos</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $comprasPorProveedor->count() }}</p>
                </div>
                <i class="fa-solid fa-truck-field text-yellow-500 text-3xl"></i>
            </div>
        </div>

        <!-- Gráficas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-gray-800 font-semibold mb-4">📅 Últimas ventas</h3>
                <canvas id="ventasChart"></canvas>
            </div>

            <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-gray-800 font-semibold mb-4">🏢 Top de proveedores con más compras</h3>
                <canvas id="proveedoresChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ventasCtx = document.getElementById('ventasChart');
        const proveedoresCtx = document.getElementById('proveedoresChart');

        // === Ventas por día ===
        new Chart(ventasCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($ventasPorDia->pluck('fecha_sin_hora')) !!},
                datasets: [{
                    label: 'Total Ventas (Q)',
                    data: {!! json_encode($ventasPorDia->pluck('total')) !!},
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22,163,74,0.2)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // === Compras por proveedor ===
        new Chart(proveedoresCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($comprasPorProveedor->pluck('nombre_proveedor')) !!},
                datasets: [{
                    label: 'Total Compras (Q)',
                    data: {!! json_encode($comprasPorProveedor->pluck('total')) !!},
                    backgroundColor: 'rgba(59,130,246,0.6)',
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</x-admin-layout>
