@extends('layouts.admin')

@section('title', 'Nuevo Reporte')
@section('page-title', 'Registrar Hallazgo Interno')

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <form action="{{ route('reportes.store') }}" method="POST" class="card">
            @csrf
            
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-2"></i> Estás registrando un hallazgo espontáneo para la unidad: <strong>{{ Auth::user()->unidad->nombre ?? 'Sin asignar' }}</strong>.
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Tipo de Hallazgo</label>
                        <select name="clasificacion" class="form-select" required>
                            <option value="" disabled selected>Seleccione...</option>
                            <option value="NC">No Conformidad (NC)</option>
                            <option value="OM">Oportunidad de Mejora (OM)</option>
                            <option value="OB">Observación (OB)</option>
                            <option value="FO">Fortaleza (FO)</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cláusula ISO 9001 (Opcional)</label>
                        <input type="text" name="clausula" class="form-control" placeholder="Ej: 7.5.2 Creación y actualización">
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label required">Descripción del Hallazgo / Problema</label>
                        <textarea name="desvio_detectado" class="form-control" rows="4" required placeholder="Describa la situación detectada..."></textarea>
                    </div>
                </div>
            </div>
            
            <div class="card-footer text-end">
                <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Registrar e Iniciar Tratamiento</button>
            </div>
        </form>
    </div>
</div>
@endsection