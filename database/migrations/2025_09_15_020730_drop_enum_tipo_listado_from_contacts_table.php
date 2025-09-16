<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasColumn('contacts', 'tipo_listado')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->dropColumn('tipo_listado');
            });
        }
    }

    public function down(): void {
        // Si necesitaras revertir, vuelve a crear el enum como antes (no recomendado).
        Schema::table('contacts', function (Blueprint $table) {
            $table->enum('tipo_listado', ['normal','destacado','premium'])->default('normal')->after('estado');
        });
    }
};

