@extends('layouts.admin')

@section('title', 'Nueva Unidad')
@section('page-title', 'Registrar Nueva Unidad')

@section('content')
<div class="row row-cards">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Datos del Área u Oficina</h3>
            </div>
            <form action="{{ route('unidades.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Nombre de la Unidad</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" placeholder="Ej: Gerencia de Finanzas" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción o Funciones Principales</label>
                        <textarea name="descripcion" class="form-control" rows="4" placeholder="Opcional. Describe brevemente qué hace esta área.">{{ old('descripcion') }}</textarea>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('unidades.index') }}" class="btn btn-link link-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-2"></i> Guardar Unidad
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection