<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $fillable = ['unidad_id', 'tipo', 'fecha_programada', 'realizada', 'alcance', 'comentarios', 'token_encuesta', 'encuesta_completada_at'];
    protected $table = 'auditorias';

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function auditores()
    {
        return $this->belongsToMany(Auditor::class);
    }

    public function hallazgos()
    {
        return $this->hasMany(Hallazgo::class);
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }
}
