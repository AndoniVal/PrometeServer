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
    Schema::create('prestamos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_us')->constrained('users')->onDelete('cascade');
        $table->foreignId('id_mat')->constrained('materiales')->onDelete('cascade');
        $table->string('nombre_material');
        $table->timestamp('fecha')->useCurrent();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
