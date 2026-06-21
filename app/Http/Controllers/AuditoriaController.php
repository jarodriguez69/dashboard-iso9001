<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Unidad;
use App\Models\Auditor;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
   public function index()
    {
        // Traemos las auditorías con sus relaciones, ordenadas por fecha (las más recientes primero)
        $auditorias = Auditoria::with(['unidad', 'auditor'])->orderBy('fecha_programada', 'desc')->get();
        return view('auditorias.index', compact('auditorias'));
    }

    public function create()
    {
        // Necesitamos mandar las listas de unidades y auditores para los desplegables del formulario
        $unidades = Unidad::orderBy('nombre')->get();
        $auditores = Auditor::orderBy('nombre')->get();
        
        return view('auditorias.create', compact('unidades', 'auditores'));
    }

    public function store(Request $request)
    {
        // Validamos asegurándonos de que los IDs existan en sus respectivas tablas
        $request->validate([
            'unidad_id' => 'required|exists:unidades,id',
            'auditor_id' => 'required|exists:auditores,id',
            'tipo' => 'required|in:Interna,Externa',
            'fecha_programada' => 'required|date',
        ]);

        // Capturamos los datos
        $data = $request->all();
        // Si el checkbox de "realizada" no se marca, no viaja en el request, por lo que forzamos un boolean
        $data['realizada'] = $request->has('realizada');

        // Guardamos
        Auditoria::create($data);

        return redirect()->route('auditorias.index')
                         ->with('success', 'Auditoría programada correctamente.');
    }

   public function edit(Auditoria $auditoria)
    {
        $unidades = Unidad::orderBy('nombre')->get();
        $auditores = Auditor::orderBy('nombre')->get();
        return view('auditorias.edit', compact('auditoria', 'unidades', 'auditores'));
    }

    public function update(Request $request, Auditoria $auditoria)
    {
        $request->validate([
            'unidad_id' => 'required|exists:unidades,id',
            'auditor_id' => 'required|exists:auditores,id',
            'tipo' => 'required|in:Interna,Externa',
            'fecha_programada' => 'required|date',
        ]);

        $data = $request->all();
        $data['realizada'] = $request->has('realizada');

        $auditoria->update($data);

        return redirect()->route('auditorias.index')->with('success', 'Auditoría actualizada.');
    }

    public function destroy(Auditoria $auditoria)
    {
        $auditoria->delete();
        return redirect()->route('auditorias.index')->with('success', 'Auditoría eliminada.');
    }

    public function informe(Auditoria $auditoria)
    {
        // Cargamos los hallazgos relacionados con esta auditoría
        $hallazgos = $auditoria->hallazgos()->get();
        return view('auditorias.informe', compact('auditoria', 'hallazgos'));
    }
}
