@extends('layouts.admin')

@section('title', 'Registro de Hallazgos')
@section('page-title', 'Gestión de Hallazgos y No Conformidades')

@section('content')
<div class="row row-cards">
    <div class="col-12">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <div class="d-flex">
                    <div><i class="ti ti-check fs-2 me-2"></i></div>
                    <div>{{ session('success') }}</div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif

        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('hallazgos.index') }}" method="GET" class="row g-3 align-items-end">
                    
                    <div class="col-md-4">
                        <label class="form-label">Buscar palabra clave:</label>
                        <input type="text" name="buscar" class="form-control" placeholder="Ej: firmas, procedimiento, área..." value="{{ request('buscar') }}">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Clasificación:</label>
                        <select name="clasificacion" class="form-select">
                            <option value="">Todas</option>
                            <option value="NC" {{ request('clasificacion') == 'NC' ? 'selected' : '' }}>No Conformidad (NC)</option>
                            <option value="OM" {{ request('clasificacion') == 'OM' ? 'selected' : '' }}>Oportunidad de Mejora (OM)</option>
                            <option value="OB" {{ request('clasificacion') == 'OB' ? 'selected' : '' }}>Observación (OB)</option>
                            <option value="FO" {{ request('clasificacion') == 'FO' ? 'selected' : '' }}>Fortaleza (FO)</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Estado:</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="Abierta" {{ request('estado') == 'Abierta' ? 'selected' : '' }}>Abierta</option>
                            <option value="En Proceso" {{ request('estado') == 'En Proceso' ? 'selected' : '' }}>En Proceso</option>
                            <option value="Cerrada" {{ request('estado') == 'Cerrada' ? 'selected' : '' }}>Cerrada</option>
                        </select>
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-2"></i> Filtrar
                        </button>
                        
                        <a href="{{ route('hallazgos.index') }}" class="btn btn-outline-secondary" title="Limpiar Filtros">
                            <i class="ti ti-eraser"></i>
                        </a>

                        <button type="submit" name="exportar" value="1" class="btn btn-success w-100">
                            <i class="ti ti-file-spreadsheet me-2"></i> Exportar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Listado de Hallazgos</h3>
                <a href="{{ route('hallazgos.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-2"></i> Registrar Nuevo
                </a>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Unidad Auditada</th>
                            <th>Fecha Auditoria</th>
                            <th>Tipo</th>
                            <th>Cláusula</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hallazgos as $hallazgo)
                        <tr>
                            <td><span class="text-secondary">#{{ $hallazgo->id }}</span></td>
                            <td class="font-weight-medium">{{ $hallazgo->auditoria->unidad->nombre ?? 'N/A' }}</td>
                            <td>{{ $hallazgo->auditoria->fecha_programada }}</td>
                            <td>
                                @if($hallazgo->clasificacion == 'NC') <span class="badge bg-danger text-white">NC</span>
                                @elseif($hallazgo->clasificacion == 'OM') <span class="badge bg-warning text-white">OM</span>
                                @elseif($hallazgo->clasificacion == 'OB') <span class="badge bg-info text-white">OB</span>
                                @elseif($hallazgo->clasificacion == 'FO') <span class="badge bg-success text-white">FO</span>
                                @endif
                            </td>
                            <td class="text-secondary">{{ $hallazgo->clausula }}</td>
                            <td>
                                @if($hallazgo->estado == 'Abierta') <span class="status status-red">Abierta</span>
                                @elseif($hallazgo->estado == 'En Proceso') <span class="status status-yellow">En Proceso</span>
                                @else <span class="status status-green">Cerrada</span>
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('hallazgos.edit', $hallazgo->id) }}" class="btn btn-sm btn-outline-primary">Gestionar</a>
                                @if(Auth::user()->rol === 'Admin')
                                <form action="{{ route('hallazgos.destroy', $hallazgo->id) }}" method="POST" onsubmit="return confirm('¿Seguro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-secondary">
                                <i class="ti ti-clipboard-x fs-1 d-block mb-2"></i>
                                No hay hallazgos que coincidan con la búsqueda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($hallazgos->hasPages())
            <div class="card-footer d-flex align-items-center justify-content-between">
                {{ $hallazgos->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    // 1. LÓGICA DEL BUSCADOR EN TIEMPO REAL
    document.getElementById('buscadorTabla').addEventListener('keyup', function() {
        let filtro = this.value.toLowerCase();
        let filas = document.querySelectorAll('#miTabla tbody tr');

        filas.forEach(fila => {
            // Lee todo el texto de la fila (ID, Nombre, Descripción)
            let textoFila = fila.innerText.toLowerCase();
            // Si el texto incluye lo que escribimos, la muestra, si no, la oculta
            fila.style.display = textoFila.includes(filtro) ? '' : 'none';
        });
    });

    // 2. LÓGICA PARA EXPORTAR A EXCEL (CSV)
    document.getElementById('btnExportar').addEventListener('click', function() {
        let csv = [];
        // Seleccionamos todas las filas de la tabla (incluso el encabezado)
        let filas = document.querySelectorAll("#miTabla tr");
        
        for (let i = 0; i < filas.length; i++) {
            let filaArray = [];
            let columnas = filas[i].querySelectorAll("td, th");
            
            // Recorremos las columnas, restando 1 al final para NO exportar la columna de "Acciones" (botones)
            for (let j = 0; j < columnas.length - 1; j++) { 
                // Limpiamos el texto y lo encerramos en comillas para evitar errores con comas
                let textoFila = columnas[j].innerText.replace(/"/g, '""');
                filaArray.push('"' + textoFila.trim() + '"');
            }
            csv.push(filaArray.join(","));
        }

        // Creamos el archivo CSV y forzamos la descarga
        let csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
        let encodedUri = encodeURI(csvContent);
        let link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "Reporte_Hallazgos.csv");
        document.body.appendChild(link);
        link.click();
        link.remove();
    });
</script>
@endsection