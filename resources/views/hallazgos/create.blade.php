@extends('layouts.admin')

@section('title', 'Nuevo Hallazgo')
@section('page-title', 'Registrar Hallazgo con Asistencia IA')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="card bg-blue-lt border-blue">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <label class="form-label text-blue font-weight-bold"><i class="ti ti-wand"></i> Borrador del Auditor (Asistente IA)</label>
                        <textarea id="borradorText" class="form-control" rows="2" placeholder="Escribe lo que observaste de forma rápida y presiona el botón para que la IA estructure el hallazgo..."></textarea>
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                    </div>
                    <div class="col-md-4 text-center mt-3 mt-md-0">
                        <button id="btnAnalizar" class="btn btn-primary w-100">
                            <span id="btnText"><i class="ti ti-sparkles me-2"></i> Autocompletar con IA</span>
                            <span id="btnLoading" style="display: none;"><span class="spinner-border spinner-border-sm me-2" role="status"></span> Analizando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('hallazgos.store') }}" method="POST">
    @csrf
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Datos Formales del Hallazgo</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Auditoría Origen</label>
                            <select name="auditoria_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($auditorias as $auditoria)
                                    <option value="{{ $auditoria->id }}">
                                        {{ \Carbon\Carbon::parse($auditoria->fecha_programada)->format('d/m/Y') }} - {{ $auditoria->unidad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label required">Clasificación</label>
                            <select name="clasificacion" id="inputClasificacion" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="NC">No Conformidad (NC)</option>
                                <option value="OM">Oportunidad de Mejora (OM)</option>
                                <option value="OB">Observación (OB)</option>
                                <option value="FO">Fortaleza (FO)</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Cláusula ISO 9001</label>
                            <input type="text" name="clausula" id="inputClausula" class="form-control" placeholder="Ej: 7.1.2">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Evidencia Objetiva</label>
                            <textarea name="evidencia_objetiva" id="inputEvidencia" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label required">Descripción del Hallazgo / Desvío</label>
                            <textarea name="desvio_detectado" id="inputDesvio" class="form-control font-weight-bold" rows="3" required></textarea>
                        </div>
                    </div>

                    <hr>
                    <h4 class="mb-3">Tratamiento (Para completar luego por el auditado)</h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Análisis de Causa Raíz</label>
                            <textarea name="analisis_causa" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Acción Correctiva Propuesta</label>
                            <textarea name="accion_correctiva" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Responsable</label>
                            <input type="text" name="responsable" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha Límite</label>
                            <input type="date" name="fecha_limite" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="Abierta">Abierta</option>
                                <option value="En Proceso">En Proceso</option>
                                <option value="Cerrada">Cerrada</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('hallazgos.index') }}" class="btn btn-link link-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-2"></i> Guardar Hallazgo Definitivo
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.getElementById('btnAnalizar').addEventListener('click', function(e) {
        e.preventDefault(); // Evitamos que envíe el formulario principal
        
        const borrador = document.getElementById('borradorText').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        if (!borrador.trim()) {
            alert('Escribe un borrador primero.');
            return;
        }

        // UX: Mostramos que está cargando
        document.getElementById('btnText').style.display = 'none';
        document.getElementById('btnLoading').style.display = 'inline-block';
        this.disabled = true;

        fetch('/api/analizar-hallazgo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ borrador: borrador })
        })
        .then(response => response.json())
        .then(data => {
            // Restauramos el botón
            document.getElementById('btnText').style.display = 'inline-block';
            document.getElementById('btnLoading').style.display = 'none';
            document.getElementById('btnAnalizar').disabled = false;

            // ¡MAGIA! Rellenamos los inputs del formulario oficial con la respuesta de la IA
            document.getElementById('inputClasificacion').value = data.clasificacion;
            document.getElementById('inputClausula').value = data.clausula;
            document.getElementById('inputEvidencia').value = data.evidencia_objetiva;
            document.getElementById('inputDesvio').value = data.redaccion_sugerida;
            
            // Un pequeño efecto visual para mostrar que se autocompletó
            document.getElementById('inputDesvio').focus();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error con la IA. Puedes llenar los campos manualmente.');
            document.getElementById('btnText').style.display = 'inline-block';
            document.getElementById('btnLoading').style.display = 'none';
            document.getElementById('btnAnalizar').disabled = false;
        });
    });
</script>
@endsection