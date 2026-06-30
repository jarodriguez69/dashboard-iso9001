<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Unidad;
use App\Models\Auditor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EncuestaMail;

class AuditoriaController extends Controller
{
    public function index()
    {
        // Obtenemos las auditorías (ajusta tus relaciones según lo que ya tengas)
        $auditorias = Auditoria::all(); 
        
        // NUEVO: Obtenemos todas las encuestas que estén activas
        $encuestasActivas = \App\Models\Encuesta::where('activa', true)->get();

        return view('auditorias.index', compact('auditorias', 'encuestasActivas'));
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

    // Agregamos Request $request para poder recibir el dato del formulario
    public function enviarEncuesta(Request $request, Auditoria $auditoria)
    {
        // Validamos que hayan elegido una encuesta
        $request->validate([
            'encuesta_id' => 'required|exists:encuestas,id'
        ]);

        if (!$auditoria->unidad->email) {
            return redirect()->back()->with('error', 'No se puede enviar la encuesta: la unidad auditada no tiene un correo electrónico configurado.');
        }

        // Buscamos LA ENCUESTA ESPECÍFICA que eligió el usuario
        $encuestaSeleccionada = \App\Models\Encuesta::findOrFail($request->encuesta_id);

        if (!$auditoria->token_encuesta) {
            $auditoria->token_encuesta = \Illuminate\Support\Str::random(40);
            $auditoria->save();
        }

        // Enviamos el correo pasando la encuesta elegida
        Mail::to($auditoria->unidad->email)->send(new EncuestaMail($auditoria, $encuestaSeleccionada));

        return redirect()->back()->with('success', 'La encuesta ha sido enviada exitosamente al correo de la unidad.');
    }
}
