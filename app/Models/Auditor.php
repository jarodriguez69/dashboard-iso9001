<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditor extends Model
{
    protected $fillable = ['nombre', 'tipo'];
    protected $table = 'auditores';

    public function auditorias()
    {
        return $this->hasMany(Auditoria::class);
    }
}
