<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tipo_listados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');                 // Ej: Regular, Destacado, Premium, Granjas de cría
            $table->string('slug')->unique();         // ej: regular, destacado, premium, granjas-de-cria
            $table->text('descripcion')->nullable();

            $table->unsignedInteger('max_productos')->nullable(); // NULL = ilimitado
            $table->boolean('is_ilimitado')->default(false);      // redundante pero útil para lógica rápida

            $table->enum('periodo', ['day','week','month','year'])->default('month');
            $table->unsignedSmallInteger('periodo_cantidad')->default(1); // ej: cada 1 mes

            $table->decimal('precio', 10, 2)->default(0);
            $table->boolean('is_activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tipo_listados');
    }
};

