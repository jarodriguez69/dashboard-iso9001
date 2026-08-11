<?php

namespace App\Http\Controllers;

use App\Models\Hallazgo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReporteDiarioController extends Controller
{
    public function index()
    {
        // Preparamos la consulta base
        $query = Hallazgo::where('origen', 'Reporte Diario')->latest();

        // Si NO es Admin, filtramos para que solo vea su propia unidad
        if (Auth::user()->rol !== 'Admin') {
            $query->where('unidad_id', Auth::user()->unidad_id);
        }

        $hallazgos = $query->get();
                             
        return view('reportes_diarios.index', compact('hallazgos'));
    }

    public function create()
    {
        return view('reportes_diarios.create');
    }

    public function store(Request $request)
    {
        try
        {
            $request->validate([
            'tipo' => 'required|in:NC,OM,OB,FO',
            'clausula' => 'nullable|string|max:255',
            'desvio_detectado' => 'required|string',
            // Agrega aquí otras validaciones que tengas en tu HallazgoController (ej: analisis_causa, acciones, etc.)
        ]);

        // Guardamos el hallazgo inyectando automáticamente los datos de contexto
        Hallazgo::create([
            'origen' => 'Reporte Diario',
            'unidad_id' => Auth::user()->unidad_id,
            'auditoria_id' => null, // No pertenece a ninguna auditoría
            'tipo' => $request->tipo,
            'clausula' => $request->clausula,
            'desvio_detectado' => $request->desvio_detectado,
            'estado' => 'Abierto', // Estado inicial por defecto
        ]);

        return redirect()->route('reportes.index')->with('success', 'Reporte registrado exitosamente para la Mejora Continua.');
        }
        catch (\Exception $e)
        {
            return redirect()->back()->with('error', 'Error al guardar el reporte: ' . $e->getMessage());
        }
        
    }

    // Funciones de edición limitadas a la propia unidad
    public function edit(Hallazgo $reporte)
    {
        // Bloqueamos SOLO si no es Admin Y tampoco pertenece a esa unidad
        if (Auth::user()->rol !== 'Admin' && $reporte->unidad_id !== Auth::user()->unidad_id) {
            abort(403, 'No tienes permiso para editar este reporte.');
        }

        return view('reportes_diarios.edit', compact('reporte'));
    }

    public function update(Request $request, Hallazgo $reporte)
    {
        if (Auth::user()->rol !== 'Admin' && $reporte->unidad_id !== Auth::user()->unidad_id) {
            abort(403);
        }

        $request->validate([
            'tipo' => 'required|in:NC,OM,OB,FO',
            'clausula' => 'nullable|string|max:255',
            'desvio_detectado' => 'required|string',
            'estado' => 'required|in:Abierto,En Proceso,Cerrado',
        ]);

        $reporte->update($request->all());

        return redirect()->route('reportes.index')->with('success', 'Reporte actualizado.');
    }
}