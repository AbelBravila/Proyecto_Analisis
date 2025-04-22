<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\EsquemaProducto;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = EsquemaProducto::where('estado', 'A')->get();
        return view('compras.producto', compact('productos'));


    }

    public function index_producto(Request $request)
    {
        $buscar = $request->input('buscador');  // Recibe el término de búsqueda

        // Filtra los productos por el término de búsqueda o muestra todos
        $productos = EsquemaProducto::where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('codigo_producto', 'LIKE', "%{$buscar}%")
                             ->orWhere('nombre_producto', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación

        return view('compras.producto', compact('productos', 'buscar'));
    }

    private function validateProduct(Request $request)
    {
        $request->validate([
            'codigo_product' => 'required|string|max:15',
            'nombre_product' => 'required|string|max:100',
            'descripcion_product' => 'required|string|max:100'
        ]);
    }

    public function agregar(Request $request)
    {
        $this->validateProduct($request);

        $codigoExistente = EsquemaProducto::where('codigo_producto', $request->codigo_product)->first();

        if ($codigoExistente) {
            return redirect()->route('producto')->with('error', 'El codigo ya está existe.');
        }

        DB::statement("EXEC sp_Insert_Esquema_Producto
            @codigo_producto = ?,
            @nombre_producto = ?,
            @descripcion =?",
            [$request->codigo_product, $request->nombre_product, $request->descripcion_product]
        );

        return redirect()->route('producto')->with('success', 'Producto registrado exitosamente');
    }

    public function editar_producto($id)
    {
        $productos = EsquemaProducto::findOrFail($id);

        return view('compras.producto', compact('productos'));
    }

    public function actualizar_producto(Request $request, $id)
    {
        $this->validateProduct($request, [
            'codigo_product' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('esquema_producto')->ignore($id, 'id_esquema_producto') // Ignora el producto actual
            ],
        ]);
    
        // Procedimiento almacenado para actualizar el producto
        DB::statement('EXEC sp_actualizar_esquema_producto
            @id_esquema_producto = ?, 
            @codigo_producto = ?, 
            @nombre_producto = ?, 
            @descripcion = ?',
            [
                $id, 
                $request->codigo_product, 
                $request->nombre_product, 
                $request->descripcion_product
            ]
        );
    
        // Redirigir con éxito
        return redirect()->route('producto')->with('success', 'Producto actualizado exitosamente.');
        
    }


    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoEsquemaProducto ?', [$id]);

        return redirect()->route('producto')->with('success', 'Estado del producto actualizado');
    }

    public function show($id)
{
    $total_productos = DB::table('producto')
        ->join('esquema_producto', 'producto.id_esquema_producto', '=', 'esquema_producto.id_esquema_producto')
        ->join('lote', 'producto.id_lote', '=', 'lote.id_lote')
        ->join('proveedor', 'producto.id_proveedor', '=', 'proveedor.id_proveedor')
        ->join('presentacion', 'producto.id_presentacion', '=', 'presentacion.id_presentacion')
        ->join('estanteria', 'producto.id_estanteria', '=', 'estanteria.id_estanteria')
        ->join('empresa', 'producto.id_empresa', '=', 'empresa.id_empresa')
        ->join('pasillo', 'estanteria.id_pasillo', '=', 'pasillo.id_pasillo')
        ->select(
            'esquema_producto.codigo_producto',
            'esquema_producto.nombre_producto',
            'producto.precio',
            'producto.costo',
            'lote.lote',
            'lote.fabricante',
            'lote.fecha_fabricacion',
            'lote.fecha_vencimiento',
            'presentacion.presentacion',
            'pasillo.codigo_pasillo',
            'estanteria.codigo_estanteria',
            'proveedor.nombre_proveedor',
            'producto.stock'
        )
        ->where('esquema_producto.id_esquema_producto', $id)
        ->get();

    return view('partials.detalles_modal', compact('total_productos'));
}

public function mostrarDetalles($id)
{

    $total_productos = DB::table('producto')
        ->join('esquema_producto', 'producto.id_esquema_producto', '=', 'esquema_producto.id_esquema_producto')
        ->join('lote', 'producto.id_lote', '=', 'lote.id_lote')
        ->join('proveedor', 'producto.id_proveedor', '=', 'proveedor.id_proveedor')
        ->join('presentacion', 'producto.id_presentacion', '=', 'presentacion.id_presentacion')
        ->join('estanteria', 'producto.id_estanteria', '=', 'estanteria.id_estanteria')
        ->join('empresa', 'producto.id_empresa', '=', 'empresa.id_empresa')
        ->join('pasillo', 'estanteria.id_pasillo', '=', 'pasillo.id_pasillo')
        ->select(
            'esquema_producto.codigo_producto',
            'esquema_producto.nombre_producto',
            'producto.precio',
            'producto.costo',
            'lote.lote',
            'lote.fabricante',
            'lote.fecha_fabricacion',
            'lote.fecha_vencimiento',
            'presentacion.presentacion',
            'pasillo.codigo_pasillo',
            'estanteria.codigo_estanteria',
            'proveedor.nombre_proveedor',
            'producto.stock'
        )
        ->where('esquema_producto.id_esquema_producto', $id)
        ->get()
        ->map(function ($producto) {
            $producto->fecha_fabricacion = $producto->fecha_fabricacion 
                ? Carbon::parse($producto->fecha_fabricacion)->format('d/m/Y') 
                : null;

            $producto->fecha_vencimiento = $producto->fecha_vencimiento 
                ? Carbon::parse($producto->fecha_vencimiento)->format('d/m/Y') 
                : null;

            return $producto;
        });

    return view('producto.partials.detalles_producto', compact('total_productos'));
}

}