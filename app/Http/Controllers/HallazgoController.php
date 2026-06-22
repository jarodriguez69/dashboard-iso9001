<?php

namespace App\Http\Controllers;

use App\Models\Hallazgo;
use App\Models\Auditoria;
use Illuminate\Http\Request;

class HallazgoController extends Controller
{
    public function index(Request $request)
    {
        // 1. Iniciamos la consulta base
        $query = Hallazgo::with('auditoria.unidad');

        // 2. Aplicamos filtros si el usuario buscó algo
        if ($request->filled('buscar')) {
            $query->where('desvio_detectado', 'like', '%' . $request->buscar . '%')
                ->orWhere('evidencia_objetiva', 'like', '%' . $request->buscar . '%')
                ->orWhereHas('auditoria.unidad', function($q) use ($request) {
                    $q->where('nombre', 'like', '%' . $request->buscar . '%');
                })
                // <-- AGREGAR ESTO para buscar también por fecha de la auditoría asociada
                ->orWhereHas('auditoria', function($q) use ($request) {
                    $q->where('fecha_programada', 'like', '%' . $request->buscar . '%');
                });
        }

        if ($request->filled('clasificacion')) {
            $query->where('clasificacion', $request->clasificacion);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // 3. ¿El usuario presionó el botón de Exportar?
        if ($request->has('exportar')) {
            $hallazgosExport = $query->orderBy('created_at', 'desc')->get();
            $fileName = 'Hallazgos_SGC_' . date('Y-m-d') . '.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use($hallazgosExport) {
                $file = fopen('php://output', 'w');
                // Esto asegura que Excel lea bien los acentos (UTF-8 BOM)
                fputs($file, "\xEF\xBB\xBF"); 
                
                // 1. Agregas el encabezado en la cabecera del CSV
                fputcsv($file, ['ID', 'Unidad', 'Fecha Auditoría', 'Clasificación', 'Cláusula', 'Estado', 'Descripción del Desvío']);

                // 2. Agregas el dato real dentro del ciclo foreach
                foreach ($hallazgosExport as $h) {
                    fputcsv($file, [
                        $h->id,
                        $h->auditoria->unidad->nombre ?? 'N/A',
                        $h->auditoria->fecha_programada ?? 'N/A', 
                        $h->clasificacion,
                        $h->clausula,
                        $h->estado,
                        $h->desvio_detectado,
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // 4. Si no exporta, paginamos de a 10 registros y mantenemos la URL con sus filtros
        $hallazgos = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->all());

        return view('hallazgos.index', compact('hallazgos'));
    }

    public function create()
    {
        // Traemos las auditorías para el select
        $auditorias = Auditoria::with('unidad')->orderBy('fecha_programada', 'desc')->get();
        return view('hallazgos.create', compact('auditorias'));
    }

    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            'auditoria_id' => 'required|exists:auditorias,id',
            'clasificacion' => 'required|string',
            'desvio_detectado' => 'required|string',
        ]);

        // Guardamos todo en bloque (asegúrate de que los nombres de los inputs coincidan con la BD)
        Hallazgo::create($request->all());

        return redirect()->route('hallazgos.index')
                         ->with('success', 'Hallazgo registrado y guardado en la base de datos.');
    }

    public function edit(Hallazgo $hallazgo)
    {
        $auditorias = Auditoria::with('unidad')->orderBy('fecha_programada', 'desc')->get();
        return view('hallazgos.edit', compact('hallazgo', 'auditorias'));
    }

    public function update(Request $request, Hallazgo $hallazgo)
    {
        $request->validate([
            'auditoria_id' => 'required|exists:auditorias,id',
            'clasificacion' => 'required|string',
            'desvio_detectado' => 'required|string',
        ]);

        $hallazgo->update($request->all());

        return redirect()->route('hallazgos.index')->with('success', 'Hallazgo actualizado y gestionado.');
    }

    public function destroy(Hallazgo $hallazgo)
    {
        $hallazgo->delete();
        return redirect()->route('hallazgos.index')->with('success', 'Hallazgo eliminado del sistema.');
    }
}
