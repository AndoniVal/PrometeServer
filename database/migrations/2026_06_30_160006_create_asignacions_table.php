<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->string('google_event_id');                  // ID del evento de Google (el nexo)
            $table->string('google_event_titulo')->nullable();  // nombre del evento, guardado por comodidad
            $table->foreignId('id_mat')->constrained('materiales')->onDelete('cascade');
            $table->foreignId('id_us')->constrained('users')->onDelete('cascade'); // admin que asignó
            $table->timestamps();

            $table->unique('id_mat');          // un material solo puede estar en UN evento a la vez
            $table->index('google_event_id');  // para buscar rápido los materiales de un evento
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacions');
    }
};
