<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'jurisdiccional', 'activo', 'centro_judicial'];
    protected $table = 'unidades';
    
    public function auditorias()
    {
        return $this->hasMany(Auditoria::class);
    }
}
