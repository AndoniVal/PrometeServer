<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    // id_us nullable en materiales
    Schema::table('materiales', function (Blueprint $table) {
        $table->unsignedBigInteger('id_us')->nullable()->change();
    });

    // Cambiar estado en prestamos
    Schema::table('prestamos', function (Blueprint $table) {
        $table->dropColumn('estado');
    });
    Schema::table('prestamos', function (Blueprint $table) {
        $table->enum('estado', ['pendiente', 'aprobado', 'devuelto'])->default('pendiente')->after('fecha_devolucion');
    });
}

public function down(): void
{
    Schema::table('materiales', function (Blueprint $table) {
        $table->unsignedBigInteger('id_us')->nullable(false)->change();
    });
    Schema::table('prestamos', function (Blueprint $table) {
        $table->dropColumn('estado');
    });
    Schema::table('prestamos', function (Blueprint $table) {
        $table->enum('estado', ['activo', 'devuelto'])->default('activo')->after('fecha_devolucion');
    });
}
};
