@extends('layouts.admin')

@section('title', 'Editar Encuesta')
@section('page-title', 'Modificar Encuesta')

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <form action="{{ route('encuestas.update', $encuesta->id) }}" method="POST" class="card">
            @csrf
            @method('PUT')
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9 mb-4">
                        <label class="form-label required fs-4">Título de la Encuesta</label>
                        <input type="text" name="titulo" class="form-control form-control-lg" value="{{ $encuesta->titulo }}" required>
                    </div>
                    <div class="col-md-3 mb-4">
                        <label class="form-label required fs-4">Estado</label>
                        <select name="activa" class="form-select form-select-lg">
                            <option value="1" {{ $encuesta->activa ? 'selected' : '' }}>Activa</option>
                            <option value="0" {{ !$encuesta->activa ? 'selected' : '' }}>Inactiva</option>
                        </select>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="ti ti-alert-triangle me-2"></i> <strong>Atención:</strong> Si esta encuesta ya tiene respuestas registradas, modificar o eliminar preguntas alterará los resultados históricos.
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="card-title mb-0">Preguntas de la Encuesta</h3>
                    <button type="button" id="btn-add-pregunta" class="btn btn-outline-success btn-sm">
                        <i class="ti ti-plus me-1"></i> Agregar Pregunta
                    </button>
                </div>

                <div id="preguntas-container">
                    @foreach($encuesta->preguntas as $index => $pregunta)
                    <div class="row mb-3 pregunta-item align-items-end">
                        <div class="col-md-8">
                            <label class="form-label {{ $index === 0 ? '' : 'd-none' }}">Texto de la pregunta</label>
                            <input type="text" name="preguntas[{{ $index }}][texto]" class="form-control" value="{{ $pregunta->texto }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label {{ $index === 0 ? '' : 'd-none' }}">Tipo de Respuesta</label>
                            <select name="preguntas[{{ $index }}][tipo]" class="form-select" required>
                                <option value="ponderacion" {{ $pregunta->tipo == 'ponderacion' ? 'selected' : '' }}>Ponderación</option>
                                <option value="libre" {{ $pregunta->tipo == 'libre' ? 'selected' : '' }}>Texto Libre</option>
                            </select>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-danger btn-icon remove-pregunta" {{ $index === 0 ? 'disabled' : '' }}>
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="card-footer text-end">
                <a href="{{ route('encuestas.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializamos el índice basándonos en la cantidad actual de preguntas
        let questionIndex = {{ $encuesta->preguntas->count() }};
        const container = document.getElementById('preguntas-container');
        const btnAdd = document.getElementById('btn-add-pregunta');

        btnAdd.addEventListener('click', function () {
            const html = `
                <div class="row mb-3 pregunta-item align-items-end">
                    <div class="col-md-8">
                        <input type="text" name="preguntas[${questionIndex}][texto]" class="form-control" required placeholder="Nueva pregunta...">
                    </div>
                    <div class="col-md-3">
                        <select name="preguntas[${questionIndex}][tipo]" class="form-select" required>
                            <option value="ponderacion">Ponderación</option>
                            <option value="libre">Texto Libre</option>
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

        container.addEventListener('click', function (e) {
            if (e.target.closest('.remove-pregunta')) {
                const btn = e.target.closest('.remove-pregunta');
                if (container.querySelectorAll('.pregunta-item').length > 1) {
                    btn.closest('.pregunta-item').remove();
                }
            }
        });
    });
</script>
@endsection