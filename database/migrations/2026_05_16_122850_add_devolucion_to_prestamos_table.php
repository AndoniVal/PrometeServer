<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('prestamos', function (Blueprint $table) {
        $table->timestamp('fecha_devolucion')->nullable()->after('fecha');
        $table->enum('estado', ['activo', 'devuelto'])->default('activo')->after('fecha_devolucion');
    });
}

public function down(): void
{
    Schema::table('prestamos', function (Blueprint $table) {
        $table->dropColumn(['fecha_devolucion', 'estado']);
    });
}
};
