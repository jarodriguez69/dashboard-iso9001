@extends('layouts.admin')
@section('title', 'Editar Auditor')
@section('page-title', 'Editar Auditor/a: ' . $auditor->nombre)

@section('content')
<div class="row row-cards">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <form action="{{ route('auditores.update', $auditor->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $auditor->nombre) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Tipo de Auditor</label>
                        <select name="tipo" class="form-select" required>
                            <option value="Interno" {{ (old('tipo', $auditor->tipo) == 'Interno') ? 'selected' : '' }}>Auditor Interno</option>
                            <option value="Externo" {{ (old('tipo', $auditor->tipo) == 'Externo') ? 'selected' : '' }}>Auditor Externo</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('auditores.index') }}" class="btn btn-link link-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-2"></i>Actualizar Auditor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection