<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
    {
        Schema::create('auditor_auditoria', function (Blueprint $table) {
            $table->id();
            // Vinculamos la auditoría
            $table->foreignId('auditoria_id')->constrained('auditorias')->onDelete('cascade');
            // Vinculamos al auditor
            $table->foreignId('auditor_id')->constrained('auditores')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auditor_auditoria');
    }
};
