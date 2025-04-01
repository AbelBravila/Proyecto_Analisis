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
        Schema::create('proveedors', function (Blueprint $table) {
        $table->id();
        $table->string('nombre_proveedor');
        $table->string('nit')->unique();
        $table->string('correo')->unique();
        $table->string('telefono');
        $table->string('direccion');
        $table->timestamps(); // Crea las columnas created_at y updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedors');
    }
};
