<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Encuesta de Satisfacción</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    
    <div style="text-align: center; border-bottom: 2px solid #206bc4; padding-bottom: 15px; margin-bottom: 20px;">
        <h2 style="color: #206bc4; margin: 0;">Sistema de Gestión de Calidad</h2>
    </div>

    <p>Hola, equipo de <strong>{{ $auditoria->unidad->nombre }}</strong>.</p>
    
    <p>Con el objetivo de mantener la mejora continua de nuestros procesos y cumplir con los requisitos de la Norma ISO 9001:2015, los invitamos a completar la encuesta de satisfacción correspondiente a la auditoría realizada el {{ \Carbon\Carbon::parse($auditoria->fecha_programada)->format('d/m/Y') }}.</p>
    
    <p>Sus respuestas son fundamentales para retroalimentar al equipo auditor e implementar mejoras. El proceso le tomará menos de 2 minutos.</p>
    
    <div style="text-align: center; margin: 40px 0;">
        <!-- El enlace incluye el token único de esta auditoría -->
        <a href="{{ route('encuestas.responder', ['token' => $auditoria->token_encuesta, 'encuesta_id' => $encuesta->id]) }}" style="background-color: #206bc4; color: white; padding: 12px 25px; text-decoration: none; border-radius: 4px; font-weight: bold;">
            Completar Encuesta Ahora
        </a>
    </div>

    <p style="font-size: 0.9em; color: #666;">Nota: No es necesario contar con un usuario o contraseña para acceder al formulario. Este enlace es único y seguro para su unidad.</p>

</body>
</html>