@extends('layouts.admin')

@section('title', 'Nueva Encuesta')
@section('page-title', 'Crear Encuesta de Satisfacción')

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <form action="{{ route('encuestas.store') }}" method="POST" class="card">
            @csrf
            
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label required fs-4">Título de la Encuesta</label>
                    <input type="text" name="titulo" class="form-control form-control-lg @error('titulo') is-invalid @enderror" 
                           placeholder="Ej: Medición de Satisfacción - Auditorías 2026" required>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="card-title mb-0">Preguntas de la Encuesta</h3>
                    <button type="button" id="btn-add-pregunta" class="btn btn-outline-success btn-sm">
                        <i class="ti ti-plus me-1"></i> Agregar Pregunta
                    </button>
                </div>

                <!-- Contenedor dinámico de preguntas -->
                <div id="preguntas-container">
                    <!-- Pregunta base (Índice 0) -->
                    <div class="row mb-3 pregunta-item align-items-end">
                        <div class="col-md-8">
                            <label class="form-label">Texto de la pregunta</label>
                            <input type="text" name="preguntas[0][texto]" class="form-control" required placeholder="Ej: ¿Cómo evalúa el trato del equipo auditor?">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo de Respuesta</label>
                            <select name="preguntas[0][tipo]" class="form-select" required>
                                <option value="ponderacion">Ponderación (Muy Satisfecho a Nada Satisfecho)</option>
                                <option value="libre">Texto Libre (Comentarios)</option>
                            </select>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-danger btn-icon remove-pregunta" disabled title="No puedes eliminar la única pregunta">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="card-footer text-end">
                <a href="{{ route('encuestas.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Encuesta</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let questionIndex = 1;
        const container = document.getElementById('preguntas-container');
        const btnAdd = document.getElementById('btn-add-pregunta');

        // Agregar nueva pregunta
        btnAdd.addEventListener('click', function () {
            const html = `
                <div class="row mb-3 pregunta-item align-items-end">
                    <div class="col-md-8">
                        <input type="text" name="preguntas[${questionIndex}][texto]" class="form-control" required placeholder="Escriba la pregunta...">
                    </div>
                    <div class="col-md-3">
                        <select name="preguntas[${questionIndex}][tipo]" class="form-select" required>
                            <option value="ponderacion">Ponderación (Muy Satisfecho a Nada Satisfecho)</option>
                            <option value="libre">Texto Libre (Comentarios)</option>
                        </select>
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-danger btn-icon remove-pregunta"><i class="ti ti-trash"></i></button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            questionIndex++;
        });

        // Eliminar pregunta (Delegación de eventos)
        container.addEventListener('click', function (e) {
            if (e.target.closest('.remove-pregunta')) {
                const btn = e.target.closest('.remove-pregunta');
                // Evitar que eliminen todas las preguntas
                if (container.querySelectorAll('.pregunta-item').length > 1) {
                    btn.closest('.pregunta-item').remove();
                }
            }
        });
    });
</script>
@endsection