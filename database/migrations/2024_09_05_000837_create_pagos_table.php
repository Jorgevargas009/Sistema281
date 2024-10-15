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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carro_compra_id')->constrained('carro_compras');
            $table->enum('forma_pago', ['tarjeta', 'paypal', 'transferencia', 'qr']); // Agregar 4 formas de pago
            $table->bigInteger('codigo'); // Para el número de tarjeta o traspaso
            $table->enum('estado', ['pendiente', 'aceptado'])->default('pendiente'); // Estado del pago
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
