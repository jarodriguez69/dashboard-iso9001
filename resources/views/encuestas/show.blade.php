@extends('layouts.admin')

@section('title', 'Resultados de Encuesta')
@section('page-title', 'Reporte de Satisfacción')

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $encuesta->titulo }}</h3>
            </div>
            
            <div class="card-body">
                @forelse($encuesta->preguntas as $pregunta)
                    <div class="mb-4">
                        <h4>{{ $loop->iteration }}. {{ $pregunta->texto }} 
                            <span class="badge bg-secondary ms-2">{{ ucfirst($pregunta->tipo) }}</span>
                        </h4>
                        
                        @if($pregunta->respuestas->count() > 0)
                            <ul class="list-group list-group-flush mt-2">
                                @foreach($pregunta->respuestas as $respuesta)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            @if($pregunta->tipo == 'ponderacion')
                                                <!-- Estilización rápida para las ponderaciones -->
                                                @if($respuesta->valor == 'Muy Satisfecho') <span class="badge bg-success">Muy Satisfecho</span>
                                                @elseif($respuesta->valor == 'Satisfecho') <span class="badge bg-info">Satisfecho</span>
                                                @elseif($respuesta->valor == 'Poco Satisfecho') <span class="badge bg-warning">Poco Satisfecho</span>
                                                @else <span class="badge bg-danger">{{ $respuesta->valor }}</span>
                                                @endif
                                            @else
                                                <i class="ti ti-quote text-muted me-2"></i> {{ $respuesta->valor }}
                                            @endif
                                        </span>
                                        <small class="text-muted">Unidad: {{ $respuesta->auditoria->unidad->nombre ?? 'N/A' }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted fst-italic">Aún no hay respuestas registradas para esta pregunta.</p>
                        @endif
                    </div>
                    @if(!$loop->last) <hr> @endif
                @empty
                    <p class="text-center text-muted">Esta encuesta no tiene preguntas configuradas.</p>
                @endforelse
            </div>
            
            <div class="card-footer text-end">
                <a href="{{ route('encuestas.index') }}" class="btn btn-primary">Volver al Listado</a>
            </div>
        </div>
    </div>
</div>
@endsection