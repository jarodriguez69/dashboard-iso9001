<?php
namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Hallazgo;
use App\Models\Auditor;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener el año seleccionado (por defecto el año actual)
        $anoSeleccionado = $request->input('ano', Carbon::now()->year);

        // 2. Obtener la lista de todos los años donde hay auditorías (para el combo)
        // Extraemos los años de forma segura y compatible con SQLite/MySQL
        $anosDisponibles = Auditoria::pluck('fecha_programada')
            ->map(fn($fecha) => Carbon::parse($fecha)->year)
            ->unique()
            ->sortDesc()
            ->values();

        // Si la base de datos está vacía, aseguramos al menos el año actual en el combo
        if ($anosDisponibles->isEmpty()) {
            $anosDisponibles = collect([Carbon::now()->year]);
        }

        // 3. KPIs del Año Seleccionado
        $proximasAuditorias = Auditoria::whereYear('fecha_programada', $anoSeleccionado)
                                        ->where('fecha_programada', '>=', Carbon::today())
                                        ->count();

        $ncPendientes = Hallazgo::where('clasificacion', 'NC')
                                ->where('estado', '!=', 'Cerrada')
                                ->whereHas('auditoria', function($q) use ($anoSeleccionado) {
                                    $q->whereYear('fecha_programada', $anoSeleccionado);
                                })->count();

        $accionesVencidas = Hallazgo::whereNotNull('fecha_limite')
                                    ->where('fecha_limite', '<', Carbon::today())
                                    ->where('estado', '!=', 'Cerrada')
                                    ->whereHas('auditoria', function($q) use ($anoSeleccionado) {
                                        $q->whereYear('fecha_programada', $anoSeleccionado);
                                    })->count();

        // 4. GRAFICO 1: Avance (Realizadas vs Pendientes) del año seleccionado
        $auditoriasAnio = Auditoria::whereYear('fecha_programada', $anoSeleccionado)->get();
        $realizadas = $auditoriasAnio->where('realizada', true)->count();
        $pendientes = $auditoriasAnio->where('realizada', false)->count();

        // 5. GRAFICO 2: Jurisdiccionales vs No Jurisdiccionales del año seleccionado
        $jurisdiccionales = Auditoria::whereYear('fecha_programada', $anoSeleccionado)
            ->whereHas('unidad', function($q) {
                $q->where('jurisdiccional', true);
            })->count();

        $noJurisdiccionales = $auditoriasAnio->count() - $jurisdiccionales;

        // 6. Distribución de Hallazgos para las barras de progreso
        $totalHallazgos = Hallazgo::whereHas('auditoria', function($q) use ($anoSeleccionado) {
            $q->whereYear('fecha_programada', $anoSeleccionado);
        })->count() ?: 1;

        $porcentajes = [
            'NC' => round((Hallazgo::where('clasificacion', 'NC')->whereHas('auditoria', fn($q) => $q->whereYear('fecha_programada', $anoSeleccionado))->count() / $totalHallazgos) * 100),
            'OM' => round((Hallazgo::where('clasificacion', 'OM')->whereHas('auditoria', fn($q) => $q->whereYear('fecha_programada', $anoSeleccionado))->count() / $totalHallazgos) * 100),
            'OB' => round((Hallazgo::where('clasificacion', 'OB')->whereHas('auditoria', fn($q) => $q->whereYear('fecha_programada', $anoSeleccionado))->count() / $totalHallazgos) * 100),
        ];

        // 7. Auditorías de la semana y Top Auditores (se mantienen)
        $inicioSemana = Carbon::now()->startOfWeek();
        $finSemana = Carbon::now()->endOfWeek();
        $auditoriasSemana = Auditoria::with(['unidad', 'auditores'])->whereBetween('fecha_programada', [$inicioSemana, $finSemana])->get();
        // 4. INDICADOR CORREGIDO: Top Auditores filtrado por el año seleccionado
        $topAuditores = Auditor::withCount(['auditorias' => function($query) use ($anoSeleccionado) {
                                    $query->whereYear('fecha_programada', $anoSeleccionado);
                               }])
                               ->whereHas('auditorias', function($query) use ($anoSeleccionado) {
                                    $query->whereYear('fecha_programada', $anoSeleccionado);
                               })
                               ->orderBy('auditorias_count', 'desc')
                               ->take(5)
                               ->get();

        // Matriz de Control por Unidad (Filtrado por Año)
        // Traemos las unidades e incluimos solo las auditorías de este año con sus respectivos hallazgos
        $resumenUnidades = Unidad::with(['auditorias' => function($query) use ($anoSeleccionado) {
            $query->whereYear('fecha_programada', $anoSeleccionado)->with('hallazgos');
        }])->orderBy('nombre')->get()->map(function($unidad) {
            
            $internas = $unidad->auditorias->where('tipo', 'Interna')->count();
            $externas = $unidad->auditorias->where('tipo', 'Externa')->count();
            $totalAuditorias = $unidad->auditorias->count();
            
            // Colapsamos los hallazgos de todas las auditorías de esta unidad en este año
            $hallazgos = $unidad->auditorias->pluck('hallazgos')->collapse();

            return (object) [
                'nombre' => $unidad->nombre,
                'jurisdiccional' => $unidad->jurisdiccional,
                'internas' => $internas,
                'externas' => $externas,
                'total_auditorias' => $totalAuditorias,
                'nc' => $hallazgos->where('clasificacion', 'NC')->count(),
                'om' => $hallazgos->where('clasificacion', 'OM')->count(),
                'ob' => $hallazgos->where('clasificacion', 'OB')->count(),
                'fo' => $hallazgos->where('clasificacion', 'FO')->count(),
            ];
        });

        return view('dashboard', compact(
            'proximasAuditorias', 'ncPendientes', 'accionesVencidas', 'porcentajes',
            'auditoriasSemana', 'topAuditores', 'anoSeleccionado', 'anosDisponibles',
            'realizadas', 'pendientes', 'jurisdiccionales', 'noJurisdiccionales',
            'resumenUnidades' // <-- Pasamos la matriz a la vista
        ));
    }
}