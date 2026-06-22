@extends('layouts.admin')

@section('title', 'Panel Principal')

@section('page-title')
<div class="d-flex justify-content-between align-items-center w-100 flex-wrap gap-2">
    <span>Resumen Ejecutivo del SGC</span>
    
    <form action="{{ route('dashboard') }}" method="GET" class="d-flex align-items-center gap-2">
        <label class="form-label mb-0 text-secondary">Año:</label>
        <select name="ano" class="form-select auto-width" onchange="this.form.submit()">
            @foreach($anosDisponibles as $a)
                <option value="{{ $a }}" {{ $anoSeleccionado == $a ? 'selected' : '' }}>{{ $a }}</option>
            @endforeach
        </select>
    </form>
</div>
@endsection

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
                        <div class="text-secondary">Auditorías Activas ({{ $anoSeleccionado }})</div>
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
                        <div class="text-secondary">NC Abiertas del Período</div>
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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Avance del Plan de Auditorías ({{ $anoSeleccionado }})</h3>
            </div>
            <div class="card-body">
                @if($realizadas == 0 && $pendientes == 0)
                    <p class="text-center text-secondary py-4">No hay datos de auditorías para este año.</p>
                @else
                    <div id="chart-avance" style="min-height: 250px;"></div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Jurisdiccional vs No Jurisdiccionales</h3>
            </div>
            <div class="card-body">
                @if($jurisdiccionales == 0 && $noJurisdiccionales == 0)
                    <p class="text-center text-secondary py-4">No hay datos de unidades auditadas para este año.</p>
                @else
                    <div id="chart-jurisdiccion" style="min-height: 250px;"></div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mb-3">
    <div class="col-lg-6">
        <div class="card h-100 border-primary border-3 border-bottom-0 border-end-0 border-start-0">
            <div class="card-header">
                <h3 class="card-title text-primary"><i class="ti ti-bulb text-yellow me-2"></i> Consultor Rápido ISO 9001 (IA)</h3>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="input-group mb-3">
                    <input type="text" id="preguntaIA" class="form-control" placeholder="Ej: ¿Qué pide el punto 7.5?">
                    <button class="btn btn-primary" id="btnPreguntarIA" type="button">Consultar</button>
                </div>
                <div class="flex-grow-1 p-3 bg-light rounded border overflow-auto" style="max-height: 120px;">
                    <div id="loadingIA" style="display: none;" class="text-center text-secondary mt-2">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div> Pensando...
                    </div>
                    <p id="respuestaIA" class="mb-0 text-dark" style="font-size: 0.9rem;"><i>La respuesta aparecerá aquí...</i></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Severidad de Desvíos Registrados</h3>
            </div>
            <div class="card-body d-flex align-items-center">
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
            <div class="card-header"><h3 class="card-title">Auditorías de esta Semana</h3></div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap">
                    <thead>
                        <tr><th>Fecha</th><th>Unidad Auditada</th><th>Auditor Lider</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                        @forelse($auditoriasSemana as $auditoria)
                        <tr>
                            <td><i class="ti ti-calendar text-secondary me-2"></i>{{ \Carbon\Carbon::parse($auditoria->fecha_programada)->format('d/m/Y') }}</td>
                            <td class="font-weight-medium">{{ $auditoria->unidad->nombre }}</td>
                            <td>{{ $auditoria->auditores->pluck('nombre')->join(', ') }}</td>
                            <td><button onclick="alert('Recordatorio enviado')" class="btn btn-sm btn-outline-primary">Recordatorio</button></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-secondary">Sin auditorías agendadas esta semana.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><h3 class="card-title">Carga Total de Auditores</h3></div>
            <div class="card-body p-0">
                <table class="table table-vcenter card-table">
                    <tbody>
                        @foreach($topAuditores as $top)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm me-3 bg-blue-lt">{{ substr($top->nombre, 0, 2) }}</span>
                                    <div class="font-weight-medium">{{ $top->nombre }}</div>
                                </div>
                            </td>
                            <td class="text-end"><span class="badge bg-green text-green-fg">{{ $top->auditorias_count }} ud.</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mt-3 mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h3 class="card-title text-white">
                    <i class="ti ti-table me-2"></i> Matriz de Control del SGC por Unidad / Oficina (Año {{ $anoSeleccionado }})
                </h3>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap table-hover border">
                    <thead class="bg-light">
                        <tr>
                            <th rowspan="2" class="align-middle border-end">Unidad / Área</th>
                            <th rowspan="2" class="align-middle text-center border-end">Tipo</th>
                            <th colspan="3" class="text-center bg-blue-lt border-bottom border-end">Auditorías Realizadas</th>
                            <th colspan="4" class="text-center bg-orange-lt border-bottom">Desvíos & Hallazgos Detectados</th>
                        </tr>
                        <tr>
                            <th class="text-center bg-transparent">Int.</th>
                            <th class="text-center bg-transparent">Ext.</th>
                            <th class="text-center bg-transparent border-end font-weight-bold text-blue">Total</th>
                            <th class="text-center bg-transparent text-danger font-weight-bold">NC</th>
                            <th class="text-center bg-transparent text-warning font-weight-bold">OM</th>
                            <th class="text-center bg-transparent text-info font-weight-bold">OB</th>
                            <th class="text-center bg-transparent text-success font-weight-bold">FO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resumenUnidades as $resumen)
                        <tr>
                            <td class="font-weight-medium border-end">{{ $resumen->nombre }}</td>
                            <td class="text-center border-end">
                                @if($resumen->jurisdiccional)
                                    <span class="badge bg-purple-lt">Jurisdiccional</span>
                                @else
                                    <span class="badge bg-secondary-lt">No Jurisd.</span>
                                @endif
                            </td>
                            <td class="text-center text-secondary">{{ $resumen->internas }}</td>
                            <td class="text-center text-secondary">{{ $resumen->externas }}</td>
                            <td class="text-center border-end font-weight-bold text-blue bg-muted-lt">{{ $resumen->total_auditorias }}</td>
                            
                            <td class="text-center">
                                <span class="badge {{ $resumen->nc > 0 ? 'bg-danger text-white' : 'bg-transparent text-secondary' }}">{{ $resumen->nc }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $resumen->om > 0 ? 'bg-warning text-white' : 'bg-transparent text-secondary' }}">{{ $resumen->om }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $resumen->ob > 0 ? 'bg-info text-white' : 'bg-transparent text-secondary' }}">{{ $resumen->ob }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $resumen->fo > 0 ? 'bg-success text-white' : 'bg-transparent text-secondary' }}">{{ $resumen->fo }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-secondary">No hay registros cargados en el sistema.</td>
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
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    // Gráfico 1: Avance Realizadas vs Pendientes
    @if($realizadas > 0 || $pendientes > 0)
    new ApexCharts(document.getElementById('chart-avance'), {
        chart: { type: 'donut', height: 250 },
        series: [{{ $realizadas }}, {{ $pendientes }}],
        labels: ['Realizadas', 'Pendientes'],
        colors: ['#2fb344', '#f59f00'],
        legend: { position: 'bottom' }
    }).render();
    @endif

    // Gráfico 2: Jurisdiccionales vs No Jurisdiccionales
    @if($jurisdiccionales > 0 || $noJurisdiccionales > 0)
    new ApexCharts(document.getElementById('chart-jurisdiccion'), {
        chart: { type: 'pie', height: 250 },
        series: [{{ $jurisdiccionales }}, {{ $noJurisdiccionales }}],
        labels: ['Jurisdiccionales', 'No Jurisdiccionales'],
        colors: ['#206bc4', '#c13333'],
        legend: { position: 'bottom' }
    }).render();
    @endif

    // Script del Consultor IA (Se mantiene igual)
    document.getElementById('btnPreguntarIA').addEventListener('click', function() {
        const pregunta = document.getElementById('preguntaIA').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (!pregunta.trim()) return;

        document.getElementById('respuestaIA').style.display = 'none';
        document.getElementById('loadingIA').style.display = 'block';

        fetch('/api/oraculo-iso', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ pregunta: pregunta })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('loadingIA').style.display = 'none';
            document.getElementById('respuestaIA').innerHTML = `<b>Respuesta:</b> ${data.respuesta}`;
            document.getElementById('respuestaIA').style.display = 'block';
        });
    });
</script>
@endsection