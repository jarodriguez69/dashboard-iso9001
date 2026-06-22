<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Auditoría #{{ $auditoria->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 11pt; color: #000; background-color: #fff; }
        .report-header { text-align: center; margin-bottom: 20px; font-weight: bold; font-size: 14pt; }
        .section-title { background-color: #f0f0f0; padding: 5px 10px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; border: 1px solid #ccc; }
        .info-table th { width: 30%; background-color: #f8f9fa; }
        .findings-table th { background-color: #e9ecef; text-align: center; }
        
        /* Estilos específicos para cuando el usuario presiona "Imprimir" o "Guardar como PDF" */
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 0; }
            .page-break { page-break-before: always; }
            .findings-table, .info-table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>
</head>
<body class="p-4">

    <div class="text-end mb-4 no-print">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Imprimir / Guardar PDF</button>
        <a href="{{ route('auditorias.index') }}" class="btn btn-secondary">Volver</a>
    </div>

    <div class="report-header">
        INFORME DE AUDITORÍA INTERNA AL SGC - ETAPA 2 (PRESENCIAL) [cite: 1]
    </div>

    <table class="table table-bordered info-table table-sm">
        <tbody>
            <tr>
                <th>Fecha de Informe</th>
                <td>{{ $auditoria->hallazgos->first() ? \Carbon\Carbon::parse($auditoria->hallazgos->first()->created_at)->format('d/m/Y') : \Carbon\Carbon::now()->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Unidad</th>
                <td>{{ $auditoria->unidad->nombre }}</td>
            </tr>
            <tr>
                <th>Alcance</th>
                <td>{{ $auditoria->alcance ?? 'No especificado' }}</td>
            </tr>
            <tr>
                <th>Fecha de realización de la Auditoría:</th>
                <td>{{ \Carbon\Carbon::parse($auditoria->fecha_programada)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Criterios de auditoría:</th>
                <td>Norma ISO 9001: 2015 - Procedimientos de la Unidad</td>
            </tr>
            <tr>
                <th>Auditores/as</th>
                <td>{{ $auditoria->auditores->pluck('nombre')->join(', ') }}</td>
            </tr>
            <tr>
                <th>Personas entrevistadas</th>
                <td>{{ $auditoria->comentarios ?? 'No registradas' }}</td>
            </tr>
            <tr>
                <th>Sitios auditados</th>
                <td>{{ $auditoria->unidad->domicilio ?? 'No especificado' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Cumplimiento de la Auditoría</div>
    <p>Se cumplieron los siguientes objetivos:</p>
    <ul>
        <li>Verificar la eficacia del SGC.</li>
        <li>Orientar la auditoría a la contribución en la mejora del sistema de gestión.</li>
        <li>Verificar el cumplimiento de los requisitos de la Norma ISO 9001:2015.</li>
    </ul>
    <strong>Cumplimiento del plan de auditorías</strong>
    <p>La auditoría se realizó conforme al plan de auditoría presentado.</p>

    <div class="section-title">Seguimiento de Hallazgos de Auditorías Anteriores</div>
    <p>En continuidad con la auditoría documental realizada previamente, se efectuó la verificación en sitio de la implementación y eficacia de las acciones adoptadas respecto de los hallazgos identificados en auditorías internas y externas anteriores, conforme a las cláusulas 9.2 y 10.2 de la Norma ISO 9001:2015.</p>
    <p>La presente instancia tiene por objeto constatar:</p>
    <ul>
        <li>La efectiva implementación de las acciones correctivas.</li>
        <li>La eliminación o control del desvío detectado.</li>
        <li>La no reiteración del hallazgo.</li>
        <li>El impacto de la acción en el proceso auditado.</li>
    </ul>

    <table class="table table-bordered findings-table table-sm">
        <thead>
            <tr>
                <th>N°</th><th>Fuente</th><th>Tipo (NC/OB/OM)</th><th>Requisito</th><th>Evidencia verificada</th><th>Resultado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" class="text-center text-muted"><em>(Espacio reservado para seguimiento histórico)</em></td>
            </tr>
        </tbody>
    </table>

    <strong>Criterios para consignar el resultado:</strong>
    <ul>
        <li><strong>Cerrado (verificado eficaz):</strong> cuando la acción implementada demuestra haber resuelto el hallazgo y no se evidencia reiteración del desvío.</li>
        <li><strong>Requiere seguimiento:</strong> cuando la acción implementada no demuestra eficacia suficiente o resulta necesario ampliar el tratamiento.</li>
    </ul>
    <p><strong>Conclusión:</strong> De la verificación realizada en sitio, se determina el cierre definitivo de los hallazgos cuya acción resultó eficaz. Aquellos que requieran seguimiento continuarán su tratamiento conforme al procedimiento vigente.</p>

    <div class="page-break"></div>

    <div class="section-title">Hallazgos de la Presente Auditoría</div>

    <strong>Fortalezas (FO)</strong>
    <p>La Fortaleza es una situación o atributo del sistema de gestión destacable por su buen funcionamiento y su valor con respecto al desempeño general del sistema.</p>
    @if($fortalezas->count() > 0)
        <table class="table table-bordered findings-table table-sm">
            <thead>
                <tr><th style="width: 10%;">FO</th><th style="width: 20%;">Requisito de la Norma</th><th>Redacción del Hallazgo</th></tr>
            </thead>
            <tbody>
                @foreach($fortalezas as $fo)
                <tr>
                    <td class="text-center">{{ $fo->id }}</td>
                    <td class="text-center">{{ $fo->clausula }}</td>
                    <td>{{ $fo->desvio_detectado }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">No se detectaron fortalezas.</p>
    @endif

    <strong>Oportunidades de Mejora (OM)</strong>
    <p>La oportunidad de mejora (OM) es un aspecto del sistema de gestión implementado que el auditor considera oportuno a mejorar y que podría generar un beneficio potencial para la organización.</p>
    @if($oportunidades->count() > 0)
        <table class="table table-bordered findings-table table-sm">
            <thead>
                <tr><th style="width: 10%;">OM</th><th style="width: 20%;">Requisito de la Norma</th><th>Redacción del Hallazgo</th></tr>
            </thead>
            <tbody>
                @foreach($oportunidades as $om)
                <tr>
                    <td class="text-center">{{ $om->id }}</td>
                    <td class="text-center">{{ $om->clausula }}</td>
                    <td>{{ $om->desvio_detectado }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No se detectaron oportunidades de mejora.</p>
    @endif

    <strong>Observaciones (OB)</strong>
    <p>La observación (no conformidad menor) es un incumplimiento puntual o parcial de un requisito normativo.</p>
    <p>La organización auditada debe registrar el tratamiento de cada observación conforme a su procedimiento de auditoría interna y enviar dicho registro conteniendo la determinación de la causa raíz y el plan para las correcciones y acciones correctivas al auditor responsable para su revisión y aceptación.</p>
    @if($observaciones->count() > 0)
        <table class="table table-bordered findings-table table-sm">
            <thead>
                <tr><th style="width: 10%;">OB</th><th style="width: 20%;">Requisito de la Norma</th><th>Redacción del Hallazgo</th></tr>
            </thead>
            <tbody>
                @foreach($observaciones as $ob)
                <tr>
                    <td class="text-center">{{ $ob->id }}</td>
                    <td class="text-center">{{ $ob->clausula }}</td>
                    <td>{{ $ob->desvio_detectado }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No se detectaron observaciones.</p>
    @endif

    <strong>No Conformidades (NC)</strong>
    <p>La no conformidad (no conformidad mayor) es un incumplimiento sistemático de un requisito normativo o incumplimientos puntuales para el sistema de gestión auditado.</p>
    <p>La organización auditada debe registrar el tratamiento de cada no conformidad conforme a su procedimiento de auditoría interna y enviar dicho registro conteniendo la determinación de la causa raíz y del plan para las correcciones y acciones correctivas junto con la evidencia de implementación de ambas al auditor responsable para su revisión, aceptación y verificación.
    @if($noConformidades->count() > 0)
        <table class="table table-bordered findings-table table-sm">
            <thead>
                <tr><th style="width: 10%;">NC</th><th style="width: 20%;">Requisito de la Norma</th><th>Redacción del Hallazgo</th></tr>
            </thead>
            <tbody>
                @foreach($noConformidades as $nc)
                <tr>
                    <td class="text-center">{{ $nc->id }}</td>
                    <td class="text-center">{{ $nc->clausula }}</td>
                    <td>{{ $nc->desvio_detectado }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No se detectaron no conformidades.</p>
    @endif

    <hr style="border-top: 1px dashed #000; margin-top: 40px;">
    <p class="text-end">San Miguel de Tucumán, {{ \Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y') }}</p>

    <div class="mt-5 mb-5">
        <div class="row text-center">
            @foreach($auditoria->auditores as $auditor)
                <div class="col">
                    @if($auditor->firma && file_exists(storage_path('app/public/' . $auditor->firma)))
                        @php
                            // Obtenemos la ruta absoluta del archivo en el servidor
                            $path = storage_path('app/public/' . $auditor->firma);
                            // Convertimos la imagen a binario Base64
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $dataImg = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);
                        @endphp
                        <img src="{{ $base64 }}" style="max-height: 80px; width: auto; display: block; margin: 0 auto;">
                    @else
                        <div style="height: 80px;"></div> 
                    @endif
                    <div style="border-top: 1px solid #000; width: 200px; margin: 5px auto 0;"></div>
                    <p class="mb-0"><strong>{{ $auditor->nombre }}</strong></p>
                    <p class="text-muted small">{{ $auditor->tipo }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-5" style="font-size: 9pt;">
        <table class="table table-bordered table-sm text-center">
            <thead class="table-light">
                <tr><th>Fecha</th><th>Revisión</th><th>Cambios</th><th>Comentario</th></tr>
            </thead>
            <tbody>
                <tr><td>05/04/2024</td><td>0</td><td>-----</td><td>Primera edición</td></tr>
                <tr><td>06/09/2024</td><td>1</td><td>Se agregan los detalles correspondientes a la auditoría documental.</td><td>Segunda edición</td></tr>
                <tr><td>07/03/2025</td><td>2</td><td>Se revisa y se mantiene el formato actual.</td><td>Tercera edición</td></tr>
                <tr><td>02/03/2026</td><td>3</td><td>Se incorpora el apartado 11 referido al cierre de hallazgos de auditorías anteriores.</td><td>Cuarta edición</td></tr>
            </tbody>
        </table>
    </div>

</body>
</html>