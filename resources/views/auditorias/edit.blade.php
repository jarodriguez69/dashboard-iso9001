@extends('layouts.admin')
@section('title', 'Editar Auditoría')
@section('page-title', 'Reprogramar / Editar Auditoría')

@section('content')
<div class="row row-cards">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <form action="{{ route('auditorias.update', $auditoria->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Unidad a Auditar</label>
                            <select name="unidad_id" class="form-select" required>
                                @foreach($unidades as $unidad)
                                    <option value="{{ $unidad->id }}" {{ (old('unidad_id', $auditoria->unidad_id) == $unidad->id) ? 'selected' : '' }}>{{ $unidad->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Auditor Asignado</label>
                            <select name="auditor_id" class="form-select" required>
                                @foreach($auditores as $auditor)
                                    <option value="{{ $auditor->id }}" {{ (old('auditor_id', $auditoria->auditor_id) == $auditor->id) ? 'selected' : '' }}>{{ $auditor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tipo</label>
                            <select name="tipo" class="form-select" required>
                                <option value="Interna" {{ (old('tipo', $auditoria->tipo) == 'Interna') ? 'selected' : '' }}>Interna</option>
                                <option value="Externa" {{ (old('tipo', $auditoria->tipo) == 'Externa') ? 'selected' : '' }}>Externa</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Fecha Programada</label>
                            <input type="date" name="fecha_programada" class="form-control" value="{{ old('fecha_programada', \Carbon\Carbon::parse($auditoria->fecha_programada)->format('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div class="mb-3 mt-2">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="realizada" value="1" {{ old('realizada', $auditoria->realizada) ? 'checked' : '' }}>
                            <span class="form-check-label">Auditoría Realizada</span>
                        </label>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('auditorias.index') }}" class="btn btn-link link-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Programa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection