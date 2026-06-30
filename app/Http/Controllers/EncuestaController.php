<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Pregunta;
use Illuminate\Http\Request;
use App\Models\Auditoria;
use App\Models\Respuesta;

class EncuestaController extends Controller
{
   
    public function index()
    {
        $encuestas = Encuesta::with('preguntas')->get();
        return view('encuestas.index', compact('encuestas'));
    }

    public function create()
    {
        return view('encuestas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'preguntas' => 'required|array|min:1',
            'preguntas.*.texto' => 'required|string',
            'preguntas.*.tipo' => 'required|in:ponderacion,libre',
        ]);

        // Creamos la encuesta
        $encuesta = Encuesta::create([
            'titulo' => $request->titulo,
            'activa' => true,
        ]);

        // Guardamos cada pregunta asociada
        foreach ($request->preguntas as $pregunta) {
            $encuesta->preguntas()->create([
                'texto' => $pregunta['texto'],
                'tipo' => $pregunta['tipo'],
            ]);
        }

        return redirect()->route('encuestas.index')->with('success', 'Encuesta creada exitosamente.');
    }
    
    public function edit(Encuesta $encuesta)
    {
        $encuesta->load('preguntas');
        return view('encuestas.edit', compact('encuesta'));
    }

    public function update(Request $request, Encuesta $encuesta)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'preguntas' => 'required|array|min:1',
            'preguntas.*.texto' => 'required|string',
            'preguntas.*.tipo' => 'required|in:ponderacion,libre',
        ]);

        // Actualizamos la encuesta
        $encuesta->update([
            'titulo' => $request->titulo,
        ]);

        // Eliminamos las preguntas existentes y agregamos las nuevas
        $encuesta->preguntas()->delete();
        foreach ($request->preguntas as $pregunta) {
            $encuesta->preguntas()->create([
                'texto' => $pregunta['texto'],
                'tipo' => $pregunta['tipo'],
            ]);
        }

        return redirect()->route('encuestas.index')->with('success', 'Encuesta actualizada exitosamente.');
    }

    public function destroy(Encuesta $encuesta)
    {
        $encuesta->delete();
        return redirect()->route('encuestas.index')->with('success', 'Encuesta eliminada exitosamente.');
    }

    // Muestra el formulario al cliente
    public function responder($token, $encuesta_id)
    {
        $auditoria = Auditoria::where('token_encuesta', $token)->firstOrFail();

        if ($auditoria->encuesta_completada_at) {
            return response('<div style="text-align:center; padding: 50px; font-family: sans-serif;"><h2>Esta encuesta ya ha sido completada.</h2><p>¡Muchas gracias por su tiempo y compromiso con la mejora continua!</p></div>');
        }

        // AQUÍ ESTÁ LA MAGIA: Ahora buscamos específicamente la encuesta que seleccionaste
        $encuesta = Encuesta::with('preguntas')->findOrFail($encuesta_id);

        return view('encuestas.responder', compact('auditoria', 'encuesta'));
    }

    // Procesa y guarda las respuestas
    public function guardarRespuesta(Request $request, $token, $encuesta_id)
    {
        $auditoria = Auditoria::where('token_encuesta', $token)->firstOrFail();

        if ($auditoria->encuesta_completada_at) {
            abort(403, 'La encuesta ya fue enviada anteriormente.');
        }

        if ($request->has('respuestas')) {
            foreach ($request->respuestas as $pregunta_id => $valor) {
                Respuesta::create([
                    'auditoria_id' => $auditoria->id,
                    'pregunta_id' => $pregunta_id,
                    'valor' => $valor,
                ]);
            }
        }

        $auditoria->update(['encuesta_completada_at' => now()]);

        return response('<div style="text-align:center; padding: 50px; font-family: sans-serif; color: #206bc4;"><h2>¡Respuestas Registradas!</h2><p>Su retroalimentación ha sido guardada exitosamente. Ya puede cerrar esta ventana.</p></div>');
    }
    
}