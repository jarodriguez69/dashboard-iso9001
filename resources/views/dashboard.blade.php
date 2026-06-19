@extends('layouts.admin')

@section('title', 'Panel Principal')
@section('page-title', 'Resumen Ejecutivo del SGC')

@section('content')
<div class="row row-cards mb-3">
    <div class="col-sm-6 col-lg-4">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-primary text-white avatar"><i class="ti ti-calendar-event fs-2"></i></span>
                    </div>
                    <div class="col">
                        <div class="font-weight-bold fs-2">{{ $proximasAuditorias }}</div>
                        <div class="text-secondary">Próximas Auditorías</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-danger text-white avatar"><i class="ti ti-alert-octagon fs-2"></i></span>
                    </div>
                    <div class="col">
                        <div class="font-weight-bold fs-2">{{ $ncPendientes }}</div>
                        <div class="text-secondary">NC Pendientes de Cierre</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-warning text-white avatar"><i class="ti ti-clock-exclamation fs-2"></i></span>
                    </div>
                    <div class="col">
                        <div class="font-weight-bold fs-2">{{ $accionesVencidas }}</div>
                        <div class="text-secondary">Acciones Atrasadas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mb-3">
    <div class="col-lg-6">
        <div class="card h-100 border-primary border-bottom-0 border-end-0 border-start-0 border-3">
            <div class="card-header">
                <h3 class="card-title text-primary"><i class="ti ti-bulb text-yellow me-2"></i> Consultor Rápido ISO 9001 (IA)</h3>
            </div>
            <div class="card-body d-flex flex-column">
                <p class="text-secondary mb-3">¿Dudas sobre la norma? Pregúntale a nuestro asistente impulsado por Inteligencia Artificial.</p>
                <div class="input-group mb-3">
                    <input type="text" id="preguntaIA" class="form-control" placeholder="Ej: ¿Es obligatorio el manual de calidad?">
                    <button class="btn btn-primary" id="btnPreguntarIA" type="button">Consultar</button>
                </div>
                <div class="flex-grow-1 p-3 bg-light rounded border overflow-auto" style="max-height: 150px;">
                    <div id="loadingIA" style="display: none;" class="text-center text-secondary mt-2">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div> Pensando...
                    </div>
                    <p id="respuestaIA" class="mb-0 text-dark" style="font-size: 0.9rem;">
                        <i>La respuesta de la norma aparecerá aquí...</i>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Distribución de Hallazgos Abiertos</h3>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div class="w-100">
                    <div class="d-flex mb-2"><div class="text-secondary">No Conformidades (NC)</div><div class="ms-auto font-weight-bold">{{ $porcentajes['NC'] }}%</div></div>
                    <div class="progress progress-sm mb-3"><div class="progress-bar bg-danger" style="width: {{ $porcentajes['NC'] }}%"></div></div>
                    
                    <div class="d-flex mb-2"><div class="text-secondary">Oportunidades de Mejora (OM)</div><div class="ms-auto font-weight-bold">{{ $porcentajes['OM'] }}%</div></div>
                    <div class="progress progress-sm mb-3"><div class="progress-bar bg-warning" style="width: {{ $porcentajes['OM'] }}%"></div></div>
                    
                    <div class="d-flex mb-2"><div class="text-secondary">Observaciones (OB)</div><div class="ms-auto font-weight-bold">{{ $porcentajes['OB'] }}%</div></div>
                    <div class="progress progress-sm"><div class="progress-bar bg-info" style="width: {{ $porcentajes['OB'] }}%"></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Auditorías de esta Semana</h3>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Unidad Auditada</th>
                            <th>Auditor Lider</th>
                            <th>Acciones Rápidas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditoriasSemana as $auditoria)
                        <tr>
                            <td>
                                <i class="ti ti-calendar text-secondary me-2"></i> 
                                {{ \Carbon\Carbon::parse($auditoria->fecha_programada)->format('d/m/Y') }}
                            </td>
                            <td class="font-weight-medium">{{ $auditoria->unidad->nombre }}</td>
                            <td>{{ $auditoria->auditor->nombre }}</td>
                            <td>
                                <button onclick="enviarRecordatorio('{{ $auditoria->unidad->nombre }}')" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-mail-forward me-1"></i> Enviar Recordatorio
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-secondary">No hay auditorías programadas para esta semana.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Carga de Auditores (Top 5)</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-vcenter card-table">
                    <tbody>
                        @forelse($topAuditores as $top)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm me-3 bg-blue-lt">{{ substr($top->nombre, 0, 2) }}</span>
                                    <div class="font-weight-medium">{{ $top->nombre }}</div>
                                </div>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-green text-green-fg">{{ $top->auditorias_count }} auditorías</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center py-3 text-secondary">Aún no hay auditorías asignadas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function enviarRecordatorio(area) {
        alert(`¡Correo de recordatorio enviado exitosamente al auditor y al responsable de ${area}!`);
    }

    document.getElementById('btnPreguntarIA').addEventListener('click', function() {
        const pregunta = document.getElementById('preguntaIA').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        if (!pregunta.trim()) return;

        document.getElementById('respuestaIA').style.display = 'none';
        document.getElementById('loadingIA').style.display = 'block';
        this.disabled = true;

        fetch('/api/oraculo-iso', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ pregunta: pregunta })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('loadingIA').style.display = 'none';
            document.getElementById('respuestaIA').innerHTML = `<b>Respuesta:</b> ${data.respuesta}`;
            document.getElementById('respuestaIA').style.display = 'block';
            document.getElementById('btnPreguntarIA').disabled = false;
        })
        .catch(error => {
            document.getElementById('loadingIA').style.display = 'none';
            document.getElementById('respuestaIA').innerText = 'Hubo un error al consultar la norma.';
            document.getElementById('respuestaIA').style.display = 'block';
            document.getElementById('btnPreguntarIA').disabled = false;
        });
    });

    document.getElementById('preguntaIA').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') document.getElementById('btnPreguntarIA').click();
    });
</script>
@endsection