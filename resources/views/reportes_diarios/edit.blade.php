@extends('layouts.admin')
@section('title', 'Gestionar Reporte Diario')
@section('page-title', 'Gestionar Reporte Diario #' . $reporte->id)

@section('content')
<form action="{{ route('reportes.update', $reporte->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Datos Formales del Hallazgo</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Adaptado: Mostramos la Unidad en lugar de la Auditoría -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Unidad Origen</label>
                            <input type="text" class="form-control bg-light" value="{{ $reporte->unidad->nombre ?? 'N/A' }}" readonly>
                            <small class="text-muted">Origen: {{ $reporte->origen }}</small>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label required">Clasificación</label>
                            <select name="clasificacion" class="form-select" required>
                                <option value="NC" {{ (old('clasificacion', $reporte->clasificacion) == 'NC') ? 'selected' : '' }}>No Conformidad (NC)</option>
                                <option value="OM" {{ (old('clasificacion', $reporte->clasificacion) == 'OM') ? 'selected' : '' }}>Oportunidad de Mejora (OM)</option>
                                <option value="OB" {{ (old('clasificacion', $reporte->clasificacion) == 'OB') ? 'selected' : '' }}>Observación (OB)</option>
                                <option value="FO" {{ (old('clasificacion', $reporte->clasificacion) == 'FO') ? 'selected' : '' }}>Fortaleza (FO)</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Cláusula ISO 9001</label>
                            <input type="text" name="clausula" class="form-control" value="{{ old('clausula', $reporte->clausula) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Evidencia Objetiva</label>
                            <textarea name="evidencia_objetiva" class="form-control" rows="2">{{ old('evidencia_objetiva', $reporte->evidencia_objetiva) }}</textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label required">Descripción del Hallazgo / Desvío</label>
                            <textarea name="desvio_detectado" class="form-control font-weight-bold" rows="3" required>{{ old('desvio_detectado', $reporte->desvio_detectado) }}</textarea>
                        </div>
                    </div>

                    <hr>
                    <h4 class="mb-3 text-primary"><i class="ti ti-tools me-2"></i> Tratamiento y Resolución</h4>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Análisis de Causa Raíz</label>
                            <textarea name="analisis_causa" class="form-control" rows="3">{{ old('analisis_causa', $reporte->analisis_causa) }}</textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Corrección (Acción Inmediata)</label>
                            <textarea name="correccion" class="form-control" rows="3">{{ old('correccion', $reporte->correccion) }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Responsable Corrección</label>
                            <input type="text" name="responsable_correccion" class="form-control" value="{{ old('responsable_correccion', $reporte->responsable_correccion) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Corrección</label>
                            <input type="date" name="fecha_correccion" class="form-control" value="{{ old('fecha_correccion', $reporte->fecha_correccion ? \Carbon\Carbon::parse($reporte->fecha_correccion)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Acción Correctiva Implementada</label>
                            <textarea name="accion_correctiva" class="form-control" rows="3">{{ old('accion_correctiva', $reporte->accion_correctiva) }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Responsable Acción Correctiva</label>
                            <input type="text" name="responsable_accion_correctiva" class="form-control" value="{{ old('responsable_accion_correctiva', $reporte->responsable_accion_correctiva) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Acción Correctiva</label>
                            <input type="date" name="fecha_accion_correctiva" class="form-control" value="{{ old('fecha_accion_correctiva', $reporte->fecha_accion_correctiva ? \Carbon\Carbon::parse($reporte->fecha_accion_correctiva)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Límite</label>
                            <input type="date" name="fecha_limite" class="form-control" value="{{ old('fecha_limite', $reporte->fecha_limite ? \Carbon\Carbon::parse($reporte->fecha_limite)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Estado Actual</label>
                            <select name="estado" class="form-select bg-blue-lt">
                                <option value="Abierto" {{ (old('estado', $reporte->estado) == 'Abierto') ? 'selected' : '' }}>Abierto</option>
                                <option value="En Proceso" {{ (old('estado', $reporte->estado) == 'En Proceso') ? 'selected' : '' }}>En Proceso</option>
                                <option value="Cerrado" {{ (old('estado', $reporte->estado) == 'Cerrado') ? 'selected' : '' }}>Cerrado</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('reportes.index') }}" class="btn btn-link link-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary"> <i class="ti ti-device-floppy me-2"></i>  Actualizar Reporte</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection