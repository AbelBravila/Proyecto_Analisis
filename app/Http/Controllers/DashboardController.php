<?php

namespace App\Http\Controllers;

use App\Models\VistaVenta;
use App\Models\VistaCompra;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total de ventas y compras
        $totalVentas = VistaVenta::sum('subtotal');
        $totalCompras = VistaCompra::sum('subtotal');

        // Ventas por día (últimos 7 días)
        $ventasPorDia = VistaVenta::selectRaw('fecha_sin_hora, SUM(subtotal) as total')
            ->groupBy('fecha_sin_hora')
            ->orderBy('fecha_sin_hora', 'desc')
            ->take(7)
            ->get();

            // Compras por proveedor (top 5)
        $ventasPorCliente = VistaVenta::selectRaw('nombre_cliente, SUM(subtotal) as total')
            ->groupBy('nombre_cliente')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Compras por proveedor (top 5)
        $comprasPorProveedor = VistaCompra::selectRaw('nombre_proveedor, SUM(subtotal) as total')
            ->groupBy('nombre_proveedor')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('welcome', compact('totalVentas', 'totalCompras', 'ventasPorDia', 'comprasPorProveedor', 'ventasPorCliente'));
    }
}
