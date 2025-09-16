<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreignId('tipo_listado_id')
                ->nullable()
                ->constrained('tipo_listados')
                ->nullOnDelete();
            // Nota: dejamos la columna enum 'tipo_listado' para migrar datos con el comando.
        });
    }

    public function down(): void {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tipo_listado_id');
        });
    }
};

