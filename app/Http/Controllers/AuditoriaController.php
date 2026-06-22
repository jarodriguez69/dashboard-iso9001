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
        // Cambiamos 'auditor' por 'auditores' dentro del arreglo de con qué relaciones traerlos
        $auditorias = Auditoria::with(['unidad', 'auditores'])->orderBy('fecha_programada', 'desc')->get();
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
        $request->validate([
            'unidad_id' => 'required|exists:unidades,id',
            'auditores' => 'required|array', // Ahora validamos que sea un array
            'auditores.*' => 'exists:auditores,id', // Validamos que cada ID exista
            'tipo' => 'required|in:Interna,Externa',
            'fecha_programada' => 'required|date',
        ]);

        $data = $request->except('auditores'); // Sacamos los auditores del data principal
        $data['realizada'] = $request->has('realizada');

        // 1. Creamos la auditoría
        $auditoria = Auditoria::create($data);
        
        // 2. Sincronizamos los auditores en la tabla intermedia
        $auditoria->auditores()->sync($request->auditores);

        return redirect()->route('auditorias.index')->with('success', 'Auditoría programada correctamente.');
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
            'auditores' => 'required|array',
            'auditores.*' => 'exists:auditores,id',
            'tipo' => 'required|in:Interna,Externa',
            'fecha_programada' => 'required|date',
        ]);

        $data = $request->except('auditores');
        $data['realizada'] = $request->has('realizada');

        // 1. Actualizamos la auditoría
        $auditoria->update($data);
        
        // 2. Sincronizamos los auditores (agrega los nuevos y quita los desmarcados)
        $auditoria->auditores()->sync($request->auditores);

        return redirect()->route('auditorias.index')->with('success', 'Auditoría actualizada.');
    }

    public function destroy(Auditoria $auditoria)
    {
        $auditoria->delete();
        return redirect()->route('auditorias.index')->with('success', 'Auditoría eliminada.');
    }

    public function informe(Auditoria $auditoria)
    {
        // Cargamos la unidad, los auditores y los hallazgos asociados
        $auditoria->load(['unidad', 'auditores', 'hallazgos']);

        // Separamos los hallazgos por clasificación para facilitar el armado de las tablas en Blade
        $fortalezas = $auditoria->hallazgos->where('clasificacion', 'FO');
        $oportunidades = $auditoria->hallazgos->where('clasificacion', 'OM');
        $observaciones = $auditoria->hallazgos->where('clasificacion', 'OB');
        $noConformidades = $auditoria->hallazgos->where('clasificacion', 'NC');

        return view('auditorias.informe', compact(
            'auditoria', 'fortalezas', 'oportunidades', 'observaciones', 'noConformidades'
        ));
    }
}
