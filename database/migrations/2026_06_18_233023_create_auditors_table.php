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
        Schema::create('auditores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo')->default('Interno'); // Puede ser 'Interno' o 'Externo'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditores');
    }
};
