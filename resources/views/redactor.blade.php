@extends('layouts.admin')

@section('title', 'Asistente IA - Redactor de Hallazgos')
@section('page-title', 'Asistente IA para Redacción de Hallazgos')

@section('content')
<div class="row row-cards">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Borrador del Auditor</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Describe lo que observaste en la auditoría:</label>
                    <textarea id="borradorText" class="form-control" rows="6" placeholder="Ej: Fui al área de ventas y pedí los objetivos del año, pero el gerente me dijo que todavía no los armaron porque están con mucho trabajo..."></textarea>
                </div>
                <meta name="csrf-token" content="{{ csrf_token() }}">
            </div>
            <div class="card-footer text-end">
                <button id="btnAnalizar" class="btn btn-primary">
                    <i class="ti ti-wand fs-2 me-2"></i> Analizar y Mejorar con IA
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Análisis Normativo ISO 9001:2015</h3>
            </div>
            <div class="card-body" id="resultadoCaja">
                <div id="estadoInicial" class="text-center text-secondary py-5">
                    <i class="ti ti-robot fs-1 mb-3 d-block"></i>
                    <p>Ingresa un borrador a la izquierda y presiona el botón para que la IA estructure el hallazgo.</p>
                </div>

                <div id="estadoCargando" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3">Analizando la norma y estructurando redacción...</p>
                </div>

                <div id="estadoResultados" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="text-secondary mb-1">Clasificación</div>
                            <span id="resClasificacion" class="badge bg-blue text-blue-fg fs-3">--</span>
                        </div>
                        <div class="col-6">
                            <div class="text-secondary mb-1">Cláusula Afectada</div>
                            <div id="resClausula" class="font-weight-bold fs-3 text-primary">--</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="text-secondary mb-1"><i class="ti ti-file-search"></i> Evidencia Objetiva</div>
                        <div class="p-2 bg-light border-start border-3 border-secondary rounded">
                            <span id="resEvidencia" class="text-dark">--</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-secondary mb-1">Redacción Sugerida para el Informe</div>
                        <div class="p-3 bg-blue-lt rounded border">
                            <p id="resRedaccion" class="mb-0 text-dark">--</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('btnAnalizar').addEventListener('click', function() {
        const borrador = document.getElementById('borradorText').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        if (!borrador.trim()) {
            alert('Por favor, escribe un borrador primero.');
            return;
        }

        // 1. Cambiamos la vista a "Cargando"
        document.getElementById('estadoInicial').style.display = 'none';
        document.getElementById('estadoResultados').style.display = 'none';
        document.getElementById('estadoCargando').style.display = 'block';
        this.disabled = true;

        // 2. Hacemos la petición a nuestra ruta en Laravel usando JS puro
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
            // 3. Ocultamos el spinner y mostramos resultados
            document.getElementById('estadoCargando').style.display = 'none';
            document.getElementById('estadoResultados').style.display = 'block';
            document.getElementById('btnAnalizar').disabled = false;

            // 4. Llenamos los datos en el DOM
            document.getElementById('resClausula').innerText = data.clausula;
            document.getElementById('resRedaccion').innerText = data.redaccion_sugerida;
            document.getElementById('resEvidencia').innerText = data.evidencia_objetiva;
            
            // 5. Lógica para pintar el Badge según el tipo de hallazgo
            const badge = document.getElementById('resClasificacion');
            badge.innerText = data.clasificacion;
            badge.className = 'badge text-white fs-3 '; // Reseteamos clases base
            
            if (data.clasificacion === 'NC') badge.classList.add('bg-danger'); // Rojo
            else if (data.clasificacion === 'OM') badge.classList.add('bg-warning'); // Amarillo
            else if (data.clasificacion === 'OB') badge.classList.add('bg-info'); // Celeste
            else if (data.clasificacion === 'FO') badge.classList.add('bg-success'); // Verde
            else badge.classList.add('bg-secondary'); // Por defecto
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al procesar el hallazgo.');
            document.getElementById('estadoCargando').style.display = 'none';
            document.getElementById('estadoInicial').style.display = 'block';
            document.getElementById('btnAnalizar').disabled = false;
        });
    });
</script>
@endsection