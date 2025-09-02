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
        Schema::create('product_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('ubicacion')->nullable();
            $table->string('raza')->nullable();
            $table->integer('edad')->nullable();
            $table->enum('genero', ['Macho', 'Hembra'])->nullable();
            $table->boolean('pedigri')->default(false);
            $table->string('entrenamiento')->nullable();
            $table->text('historial_salud')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_extras');
    }
};
