<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $fillable = ['unidad_id', 'auditor_id', 'auditor_id','tipo', 'fecha_programada', 'realizada', 'alcance', 'comentarios'];
    protected $table = 'auditorias';

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function auditor()
    {
        return $this->belongsTo(Auditor::class);
    }

    public function hallazgos()
    {
        return $this->hasMany(Hallazgo::class);
    }
}
