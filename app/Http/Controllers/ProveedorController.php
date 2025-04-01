<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    public function index_proveedor()
    {
        $proveedores = Proveedor::all(); 
        // Obtener todos los proveedores
        return view('admin.Proveedores', compact('proveedores'));
    }

    public function store(Request $request)
    {
        // Validar datos
        $request->validate([
            'nombre_proveedor' => 'required|string|max:255',
            'nit' => 'required|string|max:20',
            'correo' => 'required|email',
            'telefono' => 'required|string|max:15',
            'direccion' => 'required|string|max:255',
        ]);

        // Crear proveedor
        Proveedor::create([
            'nombre_proveedor' => $request->nombre_proveedor,
            'nit' => $request->nit,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
        ]);

        return redirect()->route('Proveedores')->with('success', 'Proveedor registrado exitosamente.');
    }

    public function editar(Request $request, string $id)
    {
        $request->validate([
            'nombre_proveedor' => 'required|string|max:255',
            'nit' => 'required|string|max:20',
            'correo' => 'required|email',
            'telefono' => 'required|string|max:15',
            'direccion' => 'required|string|max:255',
        ]);
        $proveedores = Proveedor::findOrFail($id);
        $proveedores->update($request->all());
        return redirect()->route('Proveedores')->with('success', 'Proveedor registrado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_proveedor' => 'required|string|max:100',
            'nit' => 'required|string|max:15',
            'correo' => 'required|strin|max:100',
            'telefono' => 'required|string|max:16',
            'direccion' => 'required|string|max:100',
        ]);
      

    }

    public function destroy($id)
    {
        $proveedor = Proveedor::find($id);
        if (!$proveedor) {
            return redirect()->route('Proveedores')->with('error', 'Proveedor no encontrado.');
        }

        $proveedor->delete();

        return redirect()->route('Proveedores')->with('success', 'Proveedor eliminado exitosamente.');
    }
}
