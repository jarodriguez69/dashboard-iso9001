@extends('layouts.admin')

@section('title', 'Nuevo Auditor')
@section('page-title', 'Registrar Nuevo Auditor/a')

@section('content')
<div class="row row-cards">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            
            <form action="{{ route('auditores.store') }}" method="POST" enctype="multipart/form-data">
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
                            <option value="Interno" {{ old('tipo') == 'Interno' ? 'selected' : '' }}>Auditor Interno</option>
                            <option value="Externo" {{ old('tipo') == 'Externo' ? 'selected' : '' }}>Auditor Externo</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Imagen de la Firma (PNG con fondo transparente recomendado)</label>
                        <input type="file" name="firma" class="form-control @error('firma') is-invalid @enderror">
                        @if(isset($auditor) && $auditor->firma)
                            <div class="mt-2">
                                <small class="text-muted d-block">Firma actual:</small>
                                <img src="{{ asset('storage/' . $auditor->firma) }}" width="150" class="border p-1">
                            </div>
                        @endif
                        @error('firma') <div class="invalid-feedback">{{ $message }}</div> @enderror
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