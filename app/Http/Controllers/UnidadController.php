<?php

namespace App\Http\Controllers;

use App\Models\Unidad;
use Illuminate\Http\Request;

class UnidadController extends Controller
{
    public function index()
    {
        // Traemos todas las unidades ordenadas por nombre
        $unidades = Unidad::orderBy('nombre')->get();
        return view('unidades.index', compact('unidades'));
    }

    public function create()
    {
        // Mostramos el formulario de creación
        return view('unidades.create');
    }

    public function store(Request $request)
    {
        // Validamos que el nombre sea obligatorio
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'email' => 'required|email'
        ]);

        // Guardamos en la base de datos
        Unidad::create($request->all());

        // Redirigimos al listado con un mensaje de éxito
        return redirect()->route('unidades.index')
                         ->with('success', 'Unidad creada correctamente.');
    }

    public function edit(Unidad $unidad)
    {
        return view('unidades.edit', compact('unidad'));
    }

    public function update(Request $request, Unidad $unidad)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'email' => 'required|email'
        ]);

        $unidad->update($request->all());

        return redirect()->route('unidades.index')->with('success', 'Unidad actualizada correctamente.');
    }

    public function destroy(Unidad $unidad)
    {
        try {
            $unidad->delete();
            return redirect()->route('unidades.index')->with('success', 'Unidad eliminada.');
        } catch (\Exception $e) {
            return redirect()->route('unidades.index')->with('error', 'No se pudo eliminar la unidad. Asegúrate de que no esté asociada a auditorías o hallazgos.');
        }
       
    }
}
