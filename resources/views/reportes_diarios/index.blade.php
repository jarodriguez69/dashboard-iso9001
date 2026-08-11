@extends('layouts.admin')

@section('title', 'Mejora Continua')
@section('page-title', 'Reportes Diarios de mi Unidad')

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Gestión de Hallazgos Internos</h3>
                <a href="{{ route('reportes.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-2"></i> Nuevo Reporte
                </a>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Cláusula</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hallazgos as $hallazgo)
                        <tr>
                            <td><span class="text-secondary">#{{ $hallazgo->id }}</span></td>
                            <td>
                                @if($hallazgo->clasificacion == 'NC') <span class="badge bg-danger text-white">NC</span>
                                @elseif($hallazgo->clasificacion == 'OM') <span class="badge bg-warning text-white">OM</span>
                                @elseif($hallazgo->clasificacion == 'OB') <span class="badge bg-info text-white">OB</span>
                                @else <span class="badge bg-success text-white">FO</span> @endif
                            </td>
                            <td>{{ $hallazgo->clausula ?? 'N/A' }}</td>
                            <td>{{ Str::limit($hallazgo->descripcion, 50) }}</td>
                            <td>
                                @if($hallazgo->estado == 'Abierto') <span class="status status-red">Abierto</span>
                                @elseif($hallazgo->estado == 'En Proceso') <span class="status status-yellow">En Proceso</span>
                                @else <span class="status status-green">Cerrado</span> @endif
                            </td>
                            <td>
                                <a href="{{ route('reportes.edit', $hallazgo->id) }}" class="btn btn-sm btn-outline-primary">Tratamiento</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Excelente. No hay hallazgos internos registrados aún.
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