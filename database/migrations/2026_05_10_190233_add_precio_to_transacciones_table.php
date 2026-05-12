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
        Schema::table('transacciones', function (Blueprint $table) {
            $table->decimal('precio_unidad', 8, 2)->default(0)->after('cantidad');
            $table->decimal('total', 8, 2)->default(0)->after('precio_unidad');
        });
    }

    public function down(): void
    {
        Schema::table('transacciones', function (Blueprint $table) {
            $table->dropColumn(['precio_unidad', 'total']);
        });
    }
};
