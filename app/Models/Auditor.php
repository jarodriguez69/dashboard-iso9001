<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditor extends Model
{
    protected $fillable = ['nombre', 'tipo', 'firma'];
    protected $table = 'auditores';

    public function auditorias()
    {
        return $this->belongsToMany(Auditoria::class);
    }
}
