@extends('layouts.admin')
@section('title', 'Editar Unidad')
@section('page-title', 'Editar: ' . $unidad->nombre)

@section('content')
<div class="row row-cards">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <form action="{{ route('unidades.update', $unidad->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Nombre de la Unidad</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $unidad->nombre) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Domicilio</label>
                        <input type="text" name="domicilio" class="form-control" value="{{ old('domicilio', $unidad->domicilio) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Centro Judicial</label>
                        <input type="text" name="centro_judicial" class="form-control" value="{{ old('centro_judicial', $unidad->centro_judicial) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jurisdiccional</label>
                        <select name="jurisdiccional" class="form-select">
                            <option value="0" {{ old('jurisdiccional', $unidad->jurisdiccional) == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('jurisdiccional', $unidad->jurisdiccional) == '1' ? 'selected' : '' }}>Sí</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Activo</label>
                        <select name="activo" class="form-select">
                            <option value="1" {{ old('activo', $unidad->activo) == '1' ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ old('activo', $unidad->activo) == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Correo Electrónico de Contacto</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $unidad->email) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion', $unidad->descripcion) }}</textarea>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('unidades.index') }}" class="btn btn-link link-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-2"></i>Actualizar Unidad</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection