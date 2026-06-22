<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hallazgo extends Model
{
    protected $fillable = ['auditoria_id', 'clasificacion', 'clausula', 'evidencia_objetiva', 'desvio_detectado', 'analisis_causa', 'correccion', 'responsable_correccion', 'fecha_correccion', 'accion_correctiva', 'responsable_accion_correctiva', 'fecha_accion_correctiva', 'fecha_limite', 'estado'];
    protected $table = 'hallazgos';

    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class);
    }
}
