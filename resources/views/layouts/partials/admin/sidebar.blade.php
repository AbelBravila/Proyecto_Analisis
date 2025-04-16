@php
    $links = [
    [
        'name' => 'Dashboard',
        'icon' => 'fa-solid fa-gauge-high',
        'route' => 'welcome',
        'active' => request()->routeIs('welcome')
    ],
    [
        'header' => 'Usuarios',
    ],
    [
        'name' => 'Usuarios',
        'icon' => 'fa-solid fa-gauge-high',
        'route' => 'Usuario',
        'active' => request()->routeIs('Usuario')
    ],
    [
        'header' => 'Proveedores',
    ],
    [
        'name' => 'Proveedores',
        'icon' => 'fa-solid fa-gauge-high',
        'route' => 'proveedor',
        'active' => request()->routeIs('proveedor')
    ],
    [
        'header' => 'Productos',
    ],
    [
        'name' => 'Productos',
        'icon' => 'fa-solid fa-cart-plus',
        'route' => 'producto',
        'active' => request()->routeIs('producto')
    ],
    [
        'header' => 'Compras',
    ],
    [
        'name' => 'Compras',
        'icon' => 'fa-solid fa-shopping-cart',
        'route' => 'compras',
        'active' => request()->routeIs('compras')
    ],
    [
        'header' => 'Ubicaciones',
    ],
    [
        'name' => 'Pasillos',
        'icon' => 'fa-solid fa-gauge-high',
        'route' => 'Pasillo',
        'active' => request()->routeIs('Pasillo')
    ],
    [
        'name' => 'Estantes',
        'icon' => 'fa-solid fa-gauge-high',
        'route' => 'Estanteria',
        'active' => request()->routeIs('Estanteria')
    ],
    [
        'header' => 'Cotización Compras',
    ],
    [
        'name' => 'Pedidos',
        'icon' => 'fa-regular fa-pen-to-square',
        'route' => 'pedidos',
        'active' => request()->routeIs('pedidos')
    ],
    
    [
    'header' => 'Devoluciones',
    
    ],
    [
        'name' => 'Gestión de Devoluciones',
        'icon' => 'fa-solid fa-boxes-packing',
        'route' => 'devoluciones.index',
        'active' => request()->routeIs('devoluciones.*')
    ],
    [
        'header' => 'Tipo Inventario',
    ],
    [
        'name' => 'Tipo_Inventario',
        'icon' => 'fa-regular fa-pen-to-square',
        'route' => 'Tipo_Inventario',
        'active' => request()->routeIs('Tipo_Inventario')
    ],


    ]    
@endphp

<aside id="logo-" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @foreach ($links as $link)
            <li>
                @isset($link['header'])
                <div class="px-3 mt-4 mb-2 text-xs text-gray-500 uppercase dark:text-gray-400">
                    {{$link['header']}}
                </div>
                @else
                    <a href="{{route($link['route'])}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{$link['active'] ? 'bg-gray-100 dark:bg-gray-700' : ''}}">

                    <span class="w-5 h-5 inline-flex justify-center items-center">
                        <i class="{{$link['icon']}} text-gray-500"></i>
                    </span>
                    <span class="ms-3">{{$link['name']}}</span>
                    </a>
                @endisset
            </li>
            @endforeach
        </ul>
    </div>
</aside>