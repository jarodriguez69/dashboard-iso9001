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
        Schema::table('auditorias', function (Blueprint $table) {
            $table->string('token_encuesta')->nullable()->unique()->after('comentarios');
            $table->timestamp('encuesta_completada_at')->nullable()->after('token_encuesta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auditorias', function (Blueprint $table) {
            $table->dropColumn('token_encuesta');
            $table->dropColumn('encuesta_completada_at');
        });
    }
};
