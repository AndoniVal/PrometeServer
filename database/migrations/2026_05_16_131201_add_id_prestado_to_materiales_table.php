<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('materiales', function (Blueprint $table) {
        $table->foreignId('id_prestado')->nullable()->after('id_us')->constrained('users')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('materiales', function (Blueprint $table) {
        $table->dropForeign(['id_prestado']);
        $table->dropColumn('id_prestado');
    });
}
};
