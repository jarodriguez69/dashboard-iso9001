@extends('layouts.admin')
@section('title', 'Gestionar Hallazgo')
@section('page-title', 'Gestionar Hallazgo #' . $hallazgo->id)

@section('content')
<form action="{{ route('hallazgos.update', $hallazgo->id) }}" method="POST">
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
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Auditoría Origen</label>
                            <select name="auditoria_id" class="form-select" required>
                                @foreach($auditorias as $auditoria)
                                    <option value="{{ $auditoria->id }}" {{ (old('auditoria_id', $hallazgo->auditoria_id) == $auditoria->id) ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($auditoria->fecha_programada)->format('d/m/Y') }} - {{ $auditoria->unidad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label required">Clasificación</label>
                            <select name="clasificacion" class="form-select" required>
                                <option value="NC" {{ (old('clasificacion', $hallazgo->clasificacion) == 'NC') ? 'selected' : '' }}>No Conformidad (NC)</option>
                                <option value="OM" {{ (old('clasificacion', $hallazgo->clasificacion) == 'OM') ? 'selected' : '' }}>Oportunidad de Mejora (OM)</option>
                                <option value="OB" {{ (old('clasificacion', $hallazgo->clasificacion) == 'OB') ? 'selected' : '' }}>Observación (OB)</option>
                                <option value="FO" {{ (old('clasificacion', $hallazgo->clasificacion) == 'FO') ? 'selected' : '' }}>Fortaleza (FO)</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Cláusula ISO 9001</label>
                            <input type="text" name="clausula" class="form-control" value="{{ old('clausula', $hallazgo->clausula) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Evidencia Objetiva</label>
                            <textarea name="evidencia_objetiva" class="form-control" rows="2">{{ old('evidencia_objetiva', $hallazgo->evidencia_objetiva) }}</textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label required">Descripción del Hallazgo / Desvío</label>
                            <textarea name="desvio_detectado" class="form-control font-weight-bold" rows="3" required>{{ old('desvio_detectado', $hallazgo->desvio_detectado) }}</textarea>
                        </div>
                    </div>

                    <hr>
                    <h4 class="mb-3 text-primary"><i class="ti ti-tools me-2"></i> Tratamiento y Resolución</h4>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Análisis de Causa Raíz</label>
                            <textarea name="analisis_causa" class="form-control" rows="3">{{ old('analisis_causa', $hallazgo->analisis_causa) }}</textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Corrección (Acción Inmediata)</label>
                            <textarea name="correccion" class="form-control" rows="3">{{ old('correccion', $hallazgo->correccion) }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Responsable Corrección</label>
                            <input type="text" name="responsable_correccion" class="form-control" value="{{ old('responsable_correccion', $hallazgo->responsable_correccion) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Corrección</label>
                            <input type="date" name="fecha_correccion" class="form-control" value="{{ old('fecha_correccion', $hallazgo->fecha_correccion ? \Carbon\Carbon::parse($hallazgo->fecha_correccion)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Acción Correctiva Implementada</label>
                            <textarea name="accion_correctiva" class="form-control" rows="3">{{ old('accion_correctiva', $hallazgo->accion_correctiva) }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Responsable Acción Correctiva</label>
                            <input type="text" name="responsable_accion_correctiva" class="form-control" value="{{ old('responsable_accion_correctiva', $hallazgo->responsable_accion_correctiva) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Acción Correctiva</label>
                            <input type="date" name="fecha_accion_correctiva" class="form-control" value="{{ old('fecha_accion_correctiva', $hallazgo->fecha_accion_correctiva ? \Carbon\Carbon::parse($hallazgo->fecha_accion_correctiva)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Límite</label>
                            <input type="date" name="fecha_limite" class="form-control" value="{{ old('fecha_limite', $hallazgo->fecha_limite ? \Carbon\Carbon::parse($hallazgo->fecha_limite)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Estado Actual</label>
                            <select name="estado" class="form-select bg-blue-lt">
                                <option value="Abierta" {{ (old('estado', $hallazgo->estado) == 'Abierta') ? 'selected' : '' }}>Abierta</option>
                                <option value="En Proceso" {{ (old('estado', $hallazgo->estado) == 'En Proceso') ? 'selected' : '' }}>En Proceso</option>
                                <option value="Cerrada" {{ (old('estado', $hallazgo->estado) == 'Cerrada') ? 'selected' : '' }}>Cerrada</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('hallazgos.index') }}" class="btn btn-link link-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary"> <i class="ti ti-device-floppy me-2"></i>  Actualizar Hallazgo</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection