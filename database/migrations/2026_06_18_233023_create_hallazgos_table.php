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
            $table->text('accion_correctiva')->nullable(); // Acción definitiva
            
            // Gestión
            $table->string('responsable')->nullable(); // Por ahora texto, luego puede ser una FK
            $table->date('fecha_limite')->nullable();
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
