<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta de Satisfacción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .survey-container { max-width: 800px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .brand-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0d6efd; padding-bottom: 15px; }
    </style>
</head>
<body>

<div class="container">
    <div class="survey-container">
        
        <div class="brand-header">
            <h2 class="text-primary">Sistema de Gestión de Calidad</h2>
            <h4 class="text-secondary">{{ $encuesta->titulo }}</h4>
        </div>

        <div class="alert alert-info">
            <strong>Unidad Auditada:</strong> {{ $auditoria->unidad->nombre }} <br>
            <strong>Fecha de Auditoría:</strong> {{ \Carbon\Carbon::parse($auditoria->fecha_programada)->format('d/m/Y') }}
        </div>

        <form action="{{ route('encuestas.guardar_respuesta', ['token' => $auditoria->token_encuesta, 'encuesta_id' => $encuesta->id]) }}" method="POST">
            @csrf

            @foreach($encuesta->preguntas as $pregunta)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">{{ $loop->iteration }}. {{ $pregunta->texto }}</h5>
                        
                        @if($pregunta->tipo == 'ponderacion')
                            <div class="d-flex flex-column gap-2">
                                <label class="form-check p-2 border rounded" style="cursor: pointer; transition: background 0.2s;">
                                    <input class="form-check-input ms-2 me-3" type="radio" name="respuestas[{{ $pregunta->id }}]" value="Muy Satisfecho" required>
                                    Muy Satisfecho
                                </label>
                                <label class="form-check p-2 border rounded" style="cursor: pointer; transition: background 0.2s;">
                                    <input class="form-check-input ms-2 me-3" type="radio" name="respuestas[{{ $pregunta->id }}]" value="Satisfecho" required>
                                    Satisfecho
                                </label>
                                <label class="form-check p-2 border rounded" style="cursor: pointer; transition: background 0.2s;">
                                    <input class="form-check-input ms-2 me-3" type="radio" name="respuestas[{{ $pregunta->id }}]" value="Poco Satisfecho" required>
                                    Poco Satisfecho
                                </label>
                                <label class="form-check p-2 border rounded" style="cursor: pointer; transition: background 0.2s;">
                                    <input class="form-check-input ms-2 me-3" type="radio" name="respuestas[{{ $pregunta->id }}]" value="Nada Satisfecho" required>
                                    Nada Satisfecho
                                </label>
                            </div>
                        @else
                            <textarea name="respuestas[{{ $pregunta->id }}]" class="form-control" rows="4" placeholder="Escriba sus comentarios aquí..." required></textarea>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">Enviar Respuestas</button>
            </div>
        </form>

    </div>
</div>

</body>
</html>