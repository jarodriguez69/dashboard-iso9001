@extends('layouts.admin')
@section('title', 'Editar Auditor')
@section('page-title', 'Editar Auditor/a: ' . $auditor->nombre)

@section('content')
<div class="row row-cards">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <form action="{{ route('auditores.update', $auditor->id) }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-12 mb-3">
                        <label class="form-label">Actualizar Imagen de la Firma</label>
                        <input type="file" name="firma" class="form-control @error('firma') is-invalid @enderror" accept="image/png, image/jpeg, image/jpg">
                        <small class="form-hint text-secondary">
                            Formatos permitidos: PNG, JPG o JPEG. Tamaño máximo: 1MB. Se recomienda usar una imagen PNG con fondo transparente para un acabado más limpio en el informe impreso.
                        </small>
                        @error('firma')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if($auditor->firma && file_exists(storage_path('app/public/' . $auditor->firma)))
                            <div class="mt-3 p-3 bg-light rounded border d-inline-block">
                                <span class="form-label text-muted mb-2 fs-5">Firma cargada actualmente en el SGC:</span>
                                @php
                                    $path = storage_path('app/public/' . $auditor->firma);
                                    $type = pathinfo($path, PATHINFO_EXTENSION);
                                    $dataImg = file_get_contents($path);
                                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);
                                @endphp
                                <div class="text-center bg-white p-2 border rounded">
                                    <img src="{{ $base64 }}" alt="Firma de {{ $auditor->nombre }}" style="max-height: 70px; width: auto;">
                                </div>
                            </div>
                        @else
                            <div class="mt-2 text-warning fs-5">
                                <i class="ti ti-alert-triangle me-1"></i> Este auditor aún no dispone de una firma digitalizada para los informes.
                            </div>
                        @endif
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