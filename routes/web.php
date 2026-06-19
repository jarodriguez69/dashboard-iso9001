<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\AuditorController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\HallazgoController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});
    
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/redactor-hallazgos', function () {
    return view('redactor'); // La vista que crearemos ahora
})->name('redactor');

Route::post('/api/analizar-hallazgo', function (Request $request) {
    $borrador = $request->input('borrador');
    $apiKey = env('GROQ_API_KEY'); // Idealmente usa env('GROQ_API_KEY')

    $prompt = "Eres un Auditor Líder experto y muy estricto en la norma ISO 9001:2015. 
    Analiza el siguiente borrador de hallazgo de auditoría: '{$borrador}'.
    
    REGLAS DE CLASIFICACIÓN ESTRICTAS:
    - NC (No Conformidad): Incumplimiento de un requisito de la norma.
    - OM (Oportunidad de Mejora): Se cumple el requisito, pero el proceso se puede optimizar.
    - OB (Observación): Se cumple el requisito, pero hay un riesgo latente de incumplimiento futuro.
    - FO (Fortaleza): Una práctica sobresaliente que supera los requisitos de la norma.

    INSTRUCCIONES DE EVIDENCIA:
    Si el texto menciona 'Evidencia Objetiva', 'EO', o nombra registros/documentos específicos (ej: 'Registro R01'), extráelos de forma clara.

    Debes devolver ÚNICAMENTE un objeto JSON válido (sin texto adicional ni formato markdown) con la siguiente estructura:
    {
        \"clasificacion\": \"(Solo puede ser: OM, OB, NC, FO)\",
        \"clausula\": \"(Número y nombre del apartado de la norma aplicable, ej: 7.5 Información documentada)\",
        \"evidencia_objetiva\": \"(Los documentos o registros encontrados. Si no se menciona ninguna, escribe 'No declarada')\",
        \"redaccion_sugerida\": \"(El hallazgo redactado de forma formal, clara, objetiva e integrando la evidencia mencionada)\"
    }";

    $response = Http::withToken($apiKey)->post('https://api.groq.com/openai/v1/chat/completions', [
        'model' => 'llama-3.1-8b-instant',
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ],
        'response_format' => ['type' => 'json_object'] // Forzamos a Groq a devolver JSON
    ]);

    if ($response->successful()) {
        $contenido = $response->json('choices.0.message.content');
        return response($contenido)->header('Content-Type', 'application/json');
    }

    return response()->json(['error' => 'Hubo un problema con la IA'], 500);
});

Route::post('/api/oraculo-iso', function (Request $request) {
    $pregunta = $request->input('pregunta');
    $apiKey = env('GROQ_API_KEY'); // Mejor si usas env('GROQ_API_KEY')

    // Obligamos a la IA a ser breve y directa
    $prompt = "Eres un consultor experto en ISO 9001:2015. 
    Responde la siguiente pregunta de forma EXTREMADAMENTE breve (máximo 3 oraciones), y menciona la cláusula aplicable si corresponde.
    Pregunta: '{$pregunta}'";

    $response = Http::withToken($apiKey)->post('https://api.groq.com/openai/v1/chat/completions', [
        'model' => 'llama-3.1-8b-instant',
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ]
    ]);

    if ($response->successful()) {
        return response()->json([
            'respuesta' => $response->json('choices.0.message.content')
        ]);
    }

    return response()->json(['error' => 'Error al contactar IA'], 500);
});

// Puedes agrupar esto luego, pero por ahora lo dejamos en la raíz
Route::resource('unidades', UnidadController::class);
Route::resource('auditores', AuditorController::class);
Route::resource('auditorias', AuditoriaController::class);
Route::resource('hallazgos', HallazgoController::class);