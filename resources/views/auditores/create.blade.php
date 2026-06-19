@extends('layouts.admin')

@section('title', 'Nuevo Auditor')
@section('page-title', 'Registrar Nuevo Auditor')

@section('content')
<div class="row row-cards">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Datos del Perfil Auditor</h3>
            </div>
            <form action="{{ route('auditores.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" placeholder="Ej: Ing. María Pérez" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Tipo de Auditor</label>
                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                            <option value="Interno" {{ old('tipo') == 'Interno' ? 'selected' : '' }}>Auditor Interno (Personal de la empresa)</option>
                            <option value="Externo" {{ old('tipo') == 'Externo' ? 'selected' : '' }}>Auditor Externo (Consultor / Ente Certificador)</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('auditores.index') }}" class="btn btn-link link-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-2"></i> Guardar Auditor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection