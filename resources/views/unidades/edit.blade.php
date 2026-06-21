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
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion', $unidad->descripcion) }}</textarea>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('unidades.index') }}" class="btn btn-link link-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Unidad</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection