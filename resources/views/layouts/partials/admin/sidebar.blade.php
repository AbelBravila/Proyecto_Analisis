@php
    $links = 
    [    

        [
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-gauge-high',
            'route' => 'welcome',
            'active' => request()->routeIs('welcome'),
        ],
        [
            'header' => 'Usuarios',
        ],
        [
            'name' => 'Usuarios',
            'icon' => 'fa-solid fa-user',
            'route' => 'Usuario',
            'active' => request()->routeIs('Usuario'),
        ],
        [
            'header' => 'Proveedores',
        ],
        [
            'name' => 'Proveedores',
            'icon' => 'fa-solid fa-truck',
            'route' => 'proveedor',
            'active' => request()->routeIs('proveedor'),
        ],
        [
            'header' => 'Productos',
        ],
        [
            'name' => 'Productos',
            'icon' => 'fa-solid fa-cart-plus',
            'route' => 'producto',
            'active' => request()->routeIs('producto'),
        ],
        [
            'name' => 'ofertas',
            'icon' => 'fa-solid fa-tags',
            'route' => 'ofertas',
            'active' => request()->routeIs('ofertas'),
        ],
        [
            'header' => 'Compras',
        ],
        [
            'name' => 'Compras',
            'icon' => 'fa-solid fa-cart-plus',
            'route' => 'compras',
            'active' => request()->routeIs('compras'),
        ],
        [
            'header' => 'Clientes',
        ],
        [
            'name' => 'Clientes',
            'icon' => 'fa-solid fa-users',
            'route' => 'cliente',
            'active' => request()->routeIs('cliente'),
        ],
        [
            'header' => 'Ventas',
        ],
        [
            'name' => 'Ventas',
            'icon' => 'fa-solid fa-tags',
            'route' => 'ventas',
            'active' => request()->routeIs('ventas'),
        ],
        [
            'header' => 'Ubicaciones',
        ],
        [
            'name' => 'Pasillos',
            'icon' => 'fa-solid fa-table-columns',
            'route' => 'Pasillo',
            'active' => request()->routeIs('Pasillo'),
        ],
        [
            'name' => 'Estantes',
            'icon' => 'fa-solid fa-table-cells-large',
            'route' => 'Estanteria',
            'active' => request()->routeIs('Estanteria'),
        ],
        [
            'header' => 'Cotización Compras',
        ],
        [
            'name' => 'Pedidos',
            'icon' => 'fa-regular fa-pen-to-square',
            'route' => 'pedidos',
            'active' => request()->routeIs('pedidos'),
        ],
        [
            'name' => 'Ver Pedidos',
            'icon' => 'fa-solid fa-receipt',
            'route' => 'pedidos.realizados',
            'active' => request()->routeIs('pedidos.realizados'),
        ],

        [
            'header' => 'Devoluciones',
        ],
        [
            'name' => 'Devoluciones de Compras',
            'icon' => 'fa-solid fa-boxes-packing',
            'route' => 'devoluciones.index',
            'active' => request()->routeIs('devoluciones.*'),
        ],
        [
            'name' => 'Devoluciones de Ventas',
            'icon' => 'fa-solid fa-boxes-packing',
            'route' => 'devoluciones_venta.index',
            'active' => request()->routeIs('devoluciones_venta.*'),
        ],
        [
            'header' => 'Tipos',
        ],
        [
            'name' => 'Tipos de Compra',
            'icon' => 'fa-solid fa-pen-to-square',
            'route' => 'tipo_compra',
            'active' => request()->routeIs('tipo_compra')
        ],
        [
            'name' => 'Tipos de Clientes',
            'icon' => 'fa-solid fa-pen-to-square',
            'route' => 'tipo_cliente',
            'active' => request()->routeIs('tipo_cliente')
        ],
        [
            'name' => 'Tipo Inventario',
            'icon' => 'fa-regular fa-pen-to-square',
            'route' => 'Tipo_Inventario',
            'active' => request()->routeIs('Tipo_Inventario'),
        ],
        [
            'name' => 'Tipo Pago',
            'icon' => 'fa-regular fa-pen-to-square',
            'route' => 'Pago',
            'active' => request()->routeIs('Tipo_Pago'),
        ],
        [
            'name' => 'Tipo Venta',
            'icon' => 'fa-regular fa-pen-to-square',
            'route' => 'Tventa',
            'active' => request()->routeIs('Tipo_Venta'),
        ],
        [
            'name' => 'Tipo Documento',
            'icon' => 'fa-regular fa-pen-to-square',
            'route' => 'Documento',
            'active' => request()->routeIs('Tipo_Documento'),
        ],
        [
            'name' => 'Tipo Presentacion',
            'icon' => 'fa-regular fa-pen-to-square',
            'route' => 'Presentacion',
            'active' => request()->routeIs('Tipo_Presentacion'),
        ],
        [
            'header' => 'Cajas',
        ],
        [
            'name' => 'Cajas',
            'icon' => 'fa-solid fa-box-archive',
            'route' => 'cajas',
            'active' => request()->routeIs('cajas'),
        ],
        [
            'name' => 'Apertura de Caja',
            'icon' => 'fa-solid fa-boxes-packing',
            'route' => 'apertura-caja.index',
            'active' => request()->routeIs('apertura-caja.index'),
        ],
        [
            'name' => 'Cajas Cerradas',
            'icon' => 'fa-solid fa-shop-lock',
            'route' => 'cierre-caja.index',
            'active' => request()->routeIs('cierre-caja.index'),
        ],
        [
            'name' => 'Asignacion de Caja',
            'icon' => 'fa-solid fa-hand-holding-dollar',
            'route' => 'asignacion-caja.index',
            'active' => request()->routeIs('asignacion-caja.index'),
        ],
        [
            'name' => 'Turno',
            'icon' => 'fa-solid fa-clock',
            'route' => 'turnos',
            'active' => request()->routeIs('turnos'),
        ],
    ];

@endphp

<aside id="logo-"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @foreach ($links as $link)
                <li>
                    @isset($link['header'])
                        <div class="px-3 mt-4 mb-2 text-xs text-gray-500 uppercase dark:text-gray-400">
                            {{ $link['header'] }}
                        </div>
                    @else
                        <a href="{{ route($link['route']) }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ $link['active'] ? 'bg-gray-100 dark:bg-gray-700' : '' }}">

                            <span class="w-5 h-5 inline-flex justify-center items-center">
                                <i class="{{ $link['icon'] }} text-gray-500"></i>
                            </span>
                            <span class="ms-3">{{ $link['name'] }}</span>
                        </a>
                    @endisset
                </li>
            @endforeach
        </ul>
    </div>
</aside>
