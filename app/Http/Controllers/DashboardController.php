<?php
namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Hallazgo;
use App\Models\Auditor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. KPIs (Nivel Superior)
        // Auditorías programadas de hoy en adelante
        $proximasAuditorias = Auditoria::where('fecha_programada', '>=', Carbon::today())->count();
        
        // No Conformidades abiertas
        $ncPendientes = Hallazgo::where('clasificacion', 'NC')->where('estado', '!=', 'Cerrada')->count();
        
        // Acciones correctivas vencidas (fecha límite menor a hoy y no cerradas)
        $accionesVencidas = Hallazgo::whereNotNull('fecha_limite')
                                    ->where('fecha_limite', '<', Carbon::today())
                                    ->where('estado', '!=', 'Cerrada')
                                    ->count();

        // 2. Gráfico de Dona (Nivel Medio)
        $totalHallazgos = Hallazgo::count() ?: 1; // El "?: 1" evita el error de división por cero si la BD está vacía
        $porcentajes = [
            'NC' => round((Hallazgo::where('clasificacion', 'NC')->count() / $totalHallazgos) * 100),
            'OM' => round((Hallazgo::where('clasificacion', 'OM')->count() / $totalHallazgos) * 100),
            'OB' => round((Hallazgo::where('clasificacion', 'OB')->count() / $totalHallazgos) * 100),
        ];

        // 3. Tabla de Auditorías de la Semana
        // Traemos las auditorías entre el lunes y el domingo de esta semana
        $inicioSemana = Carbon::now()->startOfWeek();
        $finSemana = Carbon::now()->endOfWeek();
        
        $auditoriasSemana = Auditoria::with(['unidad', 'auditor'])
                                    ->whereBetween('fecha_programada', [$inicioSemana, $finSemana])
                                    ->orderBy('fecha_programada', 'asc')
                                    ->get();

        // 4. NUEVO INDICADOR: Top Auditores con más carga
        // Usamos has('auditorias') que es la forma 100% compatible en Laravel para filtrar relaciones
        $topAuditores = Auditor::withCount('auditorias')
                               ->has('auditorias') // Solo trae a los auditores que tengan 1 o más auditorías
                               ->orderBy('auditorias_count', 'desc')
                               ->take(5)
                               ->get();

        return view('dashboard', compact(
            'proximasAuditorias', 'ncPendientes', 'accionesVencidas', 
            'porcentajes', 'auditoriasSemana', 'topAuditores'
        ));
    }
}