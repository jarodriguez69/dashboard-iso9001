@extends('layouts.admin')

@section('title', 'Encuestas de Satisfacción')
@section('page-title', 'Gestión de Encuestas')

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Listado de Encuestas</h3>
                <a href="{{ route('encuestas.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-2"></i> Crear Encuesta
                </a>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título de la Encuesta</th>
                            <th>Cant. Preguntas</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($encuestas as $encuesta)
                        <tr>
                            <td><span class="text-secondary">#{{ $encuesta->id }}</span></td>
                            <td class="font-weight-medium">{{ $encuesta->titulo }}</td>
                            <td><span class="badge bg-blue-lt">{{ $encuesta->preguntas->count() }}</span></td>
                            <td>
                                @if($encuesta->activa)
                                    <span class="status status-green">Activa</span>
                                @else
                                    <span class="status status-red">Inactiva</span>
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('encuestas.show', $encuesta->id) }}" class="btn btn-sm btn-outline-info" title="Ver Resultados">
                                    <i class="ti ti-chart-bar"></i>
                                </a>
                                <a href="{{ route('encuestas.edit', $encuesta->id) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="ti ti-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-secondary">
                                No hay encuestas creadas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection