<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hallazgos', function (Blueprint $table) {
            // Agregamos el origen (Auditoría o Reporte Diario)
            $table->string('origen')->default('Auditoría')->after('id');
            
            // Agregamos la relación directa con la unidad
            $table->foreignId('unidad_id')->nullable()->constrained('unidades')->cascadeOnDelete()->after('auditoria_id');
            
            // Hacemos que la auditoría ya no sea obligatoria
            $table->unsignedBigInteger('auditoria_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('hallazgos', function (Blueprint $table) {
            $table->dropColumn('origen');
            $table->dropForeign(['unidad_id']);
            $table->dropColumn('unidad_id');
            $table->unsignedBigInteger('auditoria_id')->nullable(false)->change();
        });
    }
};
