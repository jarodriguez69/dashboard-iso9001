@extends('layouts.admin')

@section('title', 'Resultados de Encuesta')
@section('page-title', 'Análisis de Satisfacción')

@section('content')
<div class="row row-cards">
    
    <!-- ENCABEZADO -->
    <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0 text-primary">{{ $encuesta->titulo }}</h2>
            <p class="text-muted mb-0">Total de unidades que respondieron: <strong>{{ $encuesta->preguntas->first()->respuestas->count() ?? 0 }}</strong></p>
        </div>
        <a href="{{ route('encuestas.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-2"></i> Volver al listado
        </a>
    </div>

    <!-- PANEL DE INTELIGENCIA ARTIFICIAL -->
    <div class="col-12 mb-4">
        <div class="card bg-primary-lt border-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title text-primary">
                    <i class="ti ti-robot fs-2 me-2"></i> Análisis Inteligente (ISO 9001)
                </h3>
                <form action="{{ route('encuestas.analizar_ia', $encuesta->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm me-2\'></span> Procesando...' ">
                        <i class="ti ti-wand me-2"></i> {{ $encuesta->analisis_ia ? 'Regenerar Análisis' : 'Generar Análisis con IA' }}
                    </button>
                </form>
            </div>
            <div class="card-body">
                @if($encuesta->analisis_ia)
                    <!-- Mostramos el HTML que nos devolvió la IA -->
                    <div class="ia-content">
                        {!! $encuesta->analisis_ia !!}
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="ti ti-brain fs-1 mb-2"></i>
                        <p>La Inteligencia Artificial aún no ha analizado estos resultados.</p>
                        <p class="small">Presiona el botón para generar un reporte automático de oportunidades de mejora.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- DESGLOSE DE PREGUNTAS DINÁMICAS -->
    <div class="col-12">
        <div class="row row-cards">
            @forelse($encuesta->preguntas as $pregunta)
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3 class="card-title">{{ $loop->iteration }}. {{ $pregunta->texto }}</h3>
                        </div>
                        <div class="card-body">
                            
                            <!-- SI ES PONDERACIÓN (BARRAS DE PROGRESO) -->
                            @if($pregunta->tipo == 'ponderacion')
                                @php
                                    $total = $pregunta->respuestas->count();
                                    $muy = $pregunta->respuestas->where('valor', 'Muy Satisfecho')->count();
                                    $sat = $pregunta->respuestas->where('valor', 'Satisfecho')->count();
                                    $poco = $pregunta->respuestas->where('valor', 'Poco Satisfecho')->count();
                                    $nada = $pregunta->respuestas->where('valor', 'Nada Satisfecho')->count();
                                @endphp

                                @if($total > 0)
                                    <!-- Muy Satisfecho -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Muy Satisfecho</span>
                                            <span class="text-success fw-bold">{{ round(($muy/$total)*100) }}% ({{ $muy }})</span>
                                        </div>
                                        <div class="progress progress-sm"><div class="progress-bar bg-success" style="width: {{ ($muy/$total)*100 }}%"></div></div>
                                    </div>
                                    <!-- Satisfecho -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Satisfecho</span>
                                            <span class="text-info fw-bold">{{ round(($sat/$total)*100) }}% ({{ $sat }})</span>
                                        </div>
                                        <div class="progress progress-sm"><div class="progress-bar bg-info" style="width: {{ ($sat/$total)*100 }}%"></div></div>
                                    </div>
                                    <!-- Poco Satisfecho -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Poco Satisfecho</span>
                                            <span class="text-warning fw-bold">{{ round(($poco/$total)*100) }}% ({{ $poco }})</span>
                                        </div>
                                        <div class="progress progress-sm"><div class="progress-bar bg-warning" style="width: {{ ($poco/$total)*100 }}%"></div></div>
                                    </div>
                                    <!-- Nada Satisfecho -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Nada Satisfecho</span>
                                            <span class="text-danger fw-bold">{{ round(($nada/$total)*100) }}% ({{ $nada }})</span>
                                        </div>
                                        <div class="progress progress-sm"><div class="progress-bar bg-danger" style="width: {{ ($nada/$total)*100 }}%"></div></div>
                                    </div>
                                @else
                                    <p class="text-muted text-center">Sin respuestas</p>
                                @endif

                            <!-- SI ES TEXTO LIBRE (LISTA DE COMENTARIOS) -->
                            @else
                                <div class="list-group list-group-flush list-group-hoverable">
                                    @forelse($pregunta->respuestas as $respuesta)
                                        <div class="list-group-item px-0">
                                            <div class="d-flex align-items-start">
                                                <i class="ti ti-quote text-muted fs-2 me-2 mt-1"></i>
                                                <div>
                                                    <p class="mb-1 text-italic">"{{ $respuesta->valor }}"</p>
                                                    <small class="text-muted">Unidad: {{ $respuesta->auditoria->unidad->nombre ?? 'Anónima' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center mt-3">Sin comentarios registrados.</p>
                                    @endforelse
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">
                    <p>Esta encuesta no tiene preguntas configuradas.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection