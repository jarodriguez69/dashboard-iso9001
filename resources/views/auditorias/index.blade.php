@extends('layouts.admin')

@section('title', 'Programa de Auditorías')
@section('page-title', 'Programa de Auditorías')

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

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="card-title">Listado de Auditorías</h3>
                
                <div class="d-flex gap-2">
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" id="buscadorTabla" class="form-control" placeholder="Buscar en la tabla...">
                    </div>
                    
                    <button id="btnExportar" class="btn btn-success">
                        <i class="ti ti-file-spreadsheet me-2"></i> Exportar CSV
                    </button>
                    
                <a href="{{ route('auditorias.create') }}" class="btn btn-primary">
                    <i class="ti ti-calendar-plus me-2"></i> Programar Auditoría
                </a>
                </div>
            </div>
            <div class="table-responsive">
                <table id="miTabla" class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Unidad Auditada</th>
                            <th>Auditor Asignado</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditorias as $auditoria)
                        <tr>
                            <td>
                                <i class="ti ti-calendar text-secondary me-1"></i> 
                                {{ \Carbon\Carbon::parse($auditoria->fecha_programada)->format('d/m/Y') }}
                            </td>
                            <td class="font-weight-medium">{{ $auditoria->unidad->nombre }}</td>
                            <td>{{ $auditoria->auditor->nombre }}</td>
                            <td>
                                @if($auditoria->tipo == 'Interna')
                                    <span class="badge bg-green-lt">Interna</span>
                                @else
                                    <span class="badge bg-orange-lt">Externa</span>
                                @endif
                            </td>
                            <td>
                                @if($auditoria->realizada)
                                    <span class="status status-green">Realizada</span>
                                @else
                                    <span class="status status-yellow">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary">Editar</button>
                                </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-secondary">
                                <i class="ti ti-calendar-off fs-1 d-block mb-2"></i>
                                No hay auditorías programadas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
        link.setAttribute("download", "Reporte_Auditorias.csv");
        document.body.appendChild(link);
        link.click();
        link.remove();
    });
</script>
@endsection