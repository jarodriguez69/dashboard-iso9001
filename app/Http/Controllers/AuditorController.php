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

    public function edit(Auditor $auditor)
    {
        return view('auditores.edit', compact('auditor'));
    }

    public function update(Request $request, Auditor $auditor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:Interno,Externo'
        ]);

        $auditor->update($request->all());

        return redirect()->route('auditores.index')->with('success', 'Auditor actualizado.');
    }

    public function destroy(Auditor $auditor)
    {
        $auditor->delete();
        return redirect()->route('auditores.index')->with('success', 'Auditor eliminado.');
    }
}
