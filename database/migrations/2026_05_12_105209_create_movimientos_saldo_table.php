<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_saldo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_us')->constrained('users')->onDelete('cascade');
            $table->enum('tipo', ['ingreso', 'descuento']);
            $table->decimal('cantidad', 8, 2);
            $table->text('comentario')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_saldo');
    }
};
