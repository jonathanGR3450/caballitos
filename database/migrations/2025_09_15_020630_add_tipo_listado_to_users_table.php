<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tipo_listado_id')
                ->nullable()
                ->after('remember_token')
                ->constrained('tipo_listados')
                ->nullOnDelete();

            $table->date('membresia_comprada_en')
                ->nullable()
                ->after('tipo_listado_id');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tipo_listado_id');
            $table->dropColumn('membresia_comprada_en');
        });
    }
};
