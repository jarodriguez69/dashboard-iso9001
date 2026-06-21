<?php

namespace App\Http\Controllers;

use App\Models\Hallazgo;
use App\Models\Auditoria;
use Illuminate\Http\Request;

class HallazgoController extends Controller
{
    public function index()
    {
        // Traemos los hallazgos con la info de la auditoría y la unidad
        $hallazgos = Hallazgo::with('auditoria.unidad')->orderBy('created_at', 'desc')->get();
        return view('hallazgos.index', compact('hallazgos'));
    }

    public function create()
    {
        // Traemos las auditorías para el select
        $auditorias = Auditoria::with('unidad')->orderBy('fecha_programada', 'desc')->get();
        return view('hallazgos.create', compact('auditorias'));
    }

    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            'auditoria_id' => 'required|exists:auditorias,id',
            'clasificacion' => 'required|string',
            'desvio_detectado' => 'required|string',
        ]);

        // Guardamos todo en bloque (asegúrate de que los nombres de los inputs coincidan con la BD)
        Hallazgo::create($request->all());

        return redirect()->route('hallazgos.index')
                         ->with('success', 'Hallazgo registrado y guardado en la base de datos.');
    }

    public function edit(Hallazgo $hallazgo)
    {
        $auditorias = Auditoria::with('unidad')->orderBy('fecha_programada', 'desc')->get();
        return view('hallazgos.edit', compact('hallazgo', 'auditorias'));
    }

    public function update(Request $request, Hallazgo $hallazgo)
    {
        $request->validate([
            'auditoria_id' => 'required|exists:auditorias,id',
            'clasificacion' => 'required|string',
            'desvio_detectado' => 'required|string',
        ]);

        $hallazgo->update($request->all());

        return redirect()->route('hallazgos.index')->with('success', 'Hallazgo actualizado y gestionado.');
    }

    public function destroy(Hallazgo $hallazgo)
    {
        $hallazgo->delete();
        return redirect()->route('hallazgos.index')->with('success', 'Hallazgo eliminado del sistema.');
    }
}
