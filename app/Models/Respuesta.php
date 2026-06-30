<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;

    protected $fillable = ['auditoria_id', 'pregunta_id', 'valor'];

    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class);
    }

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }
}