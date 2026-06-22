<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('hallazgos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auditoria_id')->constrained('auditorias')->onDelete('cascade');
            
            // Datos de la IA y Norma
            $table->string('clasificacion')->nullable(); // OM, OB, NC, FO
            $table->string('clausula')->nullable();
            $table->text('evidencia_objetiva')->nullable();
            
            // Datos del desvío y tratamiento
            $table->text('desvio_detectado');
            $table->text('analisis_causa')->nullable();
            $table->text('correccion')->nullable(); // Acción inmediata
            $table->text('responsable_correccion')->nullable(); // responsable de la unidad
            $table->date('fecha_correccion')->nullable(); // fecha tentativa
            $table->text('accion_correctiva')->nullable(); // Acción definitiva
            $table->text('responsable_accion_correctiva')->nullable(); // responsable de la unidad
            $table->date('fecha_accion_correctiva')->nullable(); // fecha tentativa
            $table->date('fecha_limite')->nullable(); // fecha límite para cerrar el hallazgo
            $table->string('estado')->default('Abierta'); // Abierta, En Proceso, Cerrada
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hallazgos');
    }
};
