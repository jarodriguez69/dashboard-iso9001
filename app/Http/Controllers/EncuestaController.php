<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Pregunta;
use Illuminate\Http\Request;
use App\Models\Auditoria;
use App\Models\Respuesta;
use Illuminate\Support\Facades\Http;

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

    public function show(Encuesta $encuesta)
    {
        // Cargamos la encuesta con sus preguntas y respuestas
        $encuesta->load('preguntas.respuestas.auditoria.unidad');
        return view('encuestas.show', compact('encuesta'));
    }

    public function analizarConIA(Encuesta $encuesta)
    {
        $encuesta->load('preguntas.respuestas');

        // 1. Armamos los datos en texto para que la IA los entienda
        $datosParaIA = "Título de la Encuesta: " . $encuesta->titulo . "\n\n";
        
        foreach ($encuesta->preguntas as $pregunta) {
            $datosParaIA .= "Pregunta: " . $pregunta->texto . " (Tipo: " . $pregunta->tipo . ")\n";
            $datosParaIA .= "Respuestas:\n";
            foreach ($pregunta->respuestas as $respuesta) {
                $datosParaIA .= "- " . $respuesta->valor . "\n";
            }
            $datosParaIA .= "\n";
        }

        // 2. Armamos el Prompt de experto ISO 9001
        $prompt = "Actúa como un Auditor Líder experto en Sistemas de Gestión de Calidad (ISO 9001:2015). " .
                  "Analiza los siguientes resultados de una encuesta de satisfacción. " .
                  "Debes devolver ÚNICAMENTE el código HTML puro (sin markdown, sin bloques de código con ```html) con la siguiente estructura: " .
                  "<h4>Resumen Ejecutivo</h4><p>(1 párrafo)</p>" .
                  "<h4>Principales Fortalezas</h4><ul><li>...</li></ul>" .
                  "<h4>Oportunidades de Mejora</h4><ul><li>...</li></ul>" .
                  "<h4>Recomendaciones</h4><p>...</p>\n\n" .
                  "Datos de la encuesta:\n" . $datosParaIA;

        try {
            // 3. Llamada a la API de Groq
            $apiKey = env('GROQ_API_KEY');
            
            $response = Http::withToken($apiKey)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                // No usamos JSON format aquí porque le pedimos HTML estructurado para la vista
            ]);

            if ($response->successful()) {
                $respuestaIA = $response->json('choices.0.message.content');
                
                // Limpiamos un poco por si la IA devuelve etiquetas markdown por error
                $respuestaIA = str_replace(['```html', '```'], '', $respuestaIA);

                // 4. Guardamos el resultado en la base de datos
                $encuesta->update([
                    'analisis_ia' => $respuestaIA
                ]);

                return redirect()->back()->with('success', 'Análisis de IA generado exitosamente.');
            }

            return redirect()->back()->with('error', 'Error al contactar con Groq: ' . $response->body());

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hubo un error en el servidor: ' . $e->getMessage());
        }
    }
    
}