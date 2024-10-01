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
        schema::table('users', function (Blueprint $table) {    $table->unsignedBigInteger('comunidad_id')->nullable();    // Establecer la relaci%C3%B3n con la tabla comunidades    
            $table->foreign('comunidad_id')->references('id')->on('comunidades')->onDelete('set null');});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
