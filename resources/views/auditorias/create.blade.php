@extends('layouts.admin')

@section('title', 'Programar Auditoría')
@section('page-title', 'Programar Nueva Auditoría')

@section('content')
<div class="row row-cards">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detalles de la Auditoría</h3>
            </div>
            <form action="{{ route('auditorias.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Unidad a Auditar</label>
                            <select name="unidad_id" class="form-select @error('unidad_id') is-invalid @enderror" required>
                                <option value="">Seleccione una unidad...</option>
                                @foreach($unidades as $unidad)
                                    <option value="{{ $unidad->id }}" {{ old('unidad_id') == $unidad->id ? 'selected' : '' }}>
                                        {{ $unidad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unidad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Auditor Asignado</label>
                            <select name="auditor_id" class="form-select @error('auditor_id') is-invalid @enderror" required>
                                <option value="">Seleccione un auditor...</option>
                                @foreach($auditores as $auditor)
                                    <option value="{{ $auditor->id }}" {{ old('auditor_id') == $auditor->id ? 'selected' : '' }}>
                                        {{ $auditor->nombre }} ({{ $auditor->tipo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('auditor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tipo de Auditoría</label>
                            <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                                <option value="Interna" {{ old('tipo') == 'Interna' ? 'selected' : '' }}>Interna (1ra Parte)</option>
                                <option value="Externa" {{ old('tipo') == 'Externa' ? 'selected' : '' }}>Externa (2da / 3ra Parte)</option>
                            </select>
                            @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Fecha Programada</label>
                            <input type="date" name="fecha_programada" class="form-control @error('fecha_programada') is-invalid @enderror" value="{{ old('fecha_programada') }}" required>
                            @error('fecha_programada') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3 mt-2">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="realizada" value="1" {{ old('realizada') ? 'checked' : '' }}>
                            <span class="form-check-label">Marcar como Realizada (si la auditoría ya ocurrió)</span>
                        </label>
                    </div>

                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('auditorias.index') }}" class="btn btn-link link-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-2"></i> Programar Auditoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection