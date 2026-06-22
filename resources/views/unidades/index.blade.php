@extends('layouts.admin')

@section('title', 'Unidades Auditables')
@section('page-title', 'Gestión de Unidades')

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
                <h3 class="card-title">Listado de Áreas de la Empresa</h3>
                
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
                    
                    <a href="{{ route('unidades.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-2"></i> Nueva Unidad
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table id="miTabla" class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Área</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unidades as $unidad)
                        <tr>
                            <td><span class="text-secondary">{{ $unidad->id }}</span></td>
                            <td class="font-weight-medium">{{ $unidad->nombre }}</td>
                            <td class="text-secondary text-wrap" style="max-width: 300px;">
                                {{ $unidad->descripcion ?? 'Sin descripción' }}
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('unidades.edit', $unidad->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                
                                <form action="{{ route('unidades.destroy', $unidad->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-secondary">
                                <i class="ti ti-building fs-1 d-block mb-2"></i>
                                Aún no hay unidades registradas. Comienza agregando la primera.
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
        link.setAttribute("download", "Reporte_Unidades.csv");
        document.body.appendChild(link);
        link.click();
        link.remove();
    });
</script>
@endsection