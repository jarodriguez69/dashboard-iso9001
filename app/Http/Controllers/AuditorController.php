<?php

namespace App\Http\Controllers;

use App\Models\Auditor;
use Illuminate\Http\Request;

class AuditorController extends Controller
{
    public function index()
    {
        // Traemos todos los auditores ordenados alfabéticamente
        $auditores = Auditor::orderBy('nombre')->get();
        return view('auditores.index', compact('auditores'));
    }

    public function create()
    {
        // Mostramos el formulario
        return view('auditores.create');
    }

    public function store(Request $request)
    {
        // Validamos los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:Interno,Externo'
        ]);

        // Guardamos en la base de datos
        Auditor::create($request->all());

        // Redirigimos con éxito
        return redirect()->route('auditores.index')
                         ->with('success', 'Auditor registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Auditor $auditor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Auditor $auditor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Auditor $auditor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auditor $auditor)
    {
        //
    }
}
