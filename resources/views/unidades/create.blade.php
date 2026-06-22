@extends('layouts.admin')

@section('title', 'Nueva Unidad')
@section('page-title', 'Registrar Nueva Unidad')

@section('content')
<div class="row row-cards">
    <div class="col-md-8 offset-md-2">
        <div class="card">

            <form action="{{ route('unidades.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Nombre de la Unidad</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" placeholder="Ej: Oficina de Gestión Judicial" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Domicilio</label>
                        <input type="text" name="domicilio" class="form-control @error('domicilio') is-invalid @enderror" placeholder="Ej: Calle Principal, Ciudad" value="{{ old('domicilio') }}">
                        @error('domicilio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>  
                    <div class="mb-3">
                        <label class="form-label">Centro Judicial</label>
                        <input type="text" name="centro_judicial" class="form-control @error('centro_judicial') is-invalid @enderror" placeholder="Ej: Centro Judicial de San José" value="{{ old('centro_judicial') }}">
                        @error('centro_judicial')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>  

                    <div class="mb-3">
                        <label class="form-label">Jurisdiccional</label>
                        <select name="jurisdiccional" class="form-select @error('jurisdiccional') is-invalid @enderror">
                            <option value="0" {{ old('jurisdiccional') == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('jurisdiccional') == '1' ? 'selected' : '' }}>Sí</option>
                        </select>
                        @error('jurisdiccional')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Activo</label>
                        <select name="activo" class="form-select @error('activo') is-invalid @enderror">
                            <option value="1" {{ old('activo') == '1' ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ old('activo') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                        @error('activo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción o Funciones Principales</label>
                        <textarea name="descripcion" class="form-control" rows="4" placeholder="Opcional. Describe brevemente qué hace esta unidad.">{{ old('descripcion') }}</textarea>
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