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
    Schema::create('products', function (Blueprint $table) {
        $table->id(); // 🆔 ID automático - ¿Por qué es importante?
        $table->string('nombre'); // 📛 VARCHAR(255) en MySQL
        $table->decimal('precio_actual', 8, 2); // 💰 999999.99
        $table->decimal('precio_original', 8, 2);
        $table->string('categoria'); // 🏷️ figures, consoles, etc.
        $table->string('enlace_producto'); // 🔗 URL relativa
        $table->string('imagen_url'); // 🖼️ URL de imagen
        $table->boolean('disponible')->default(true); // ✅/❌
        $table->decimal('valoracion', 3, 1)->default(5.0); // ⭐ 4.5
        $table->timestamps(); // 🕐 created_at, updated_at AUTOMÁTICOS
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
