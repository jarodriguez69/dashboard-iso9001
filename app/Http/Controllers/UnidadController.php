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
            'descripcion' => 'nullable|string'
        ]);

        // Guardamos en la base de datos
        Unidad::create($request->all());

        // Redirigimos al listado con un mensaje de éxito
        return redirect()->route('unidades.index')
                         ->with('success', 'Unidad creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unidad $unidad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unidad $unidad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unidad $unidad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unidad $unidad)
    {
        //
    }
}
