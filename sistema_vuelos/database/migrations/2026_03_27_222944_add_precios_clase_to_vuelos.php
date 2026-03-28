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
        Schema::table('vuelos', function (Blueprint $table) {
            $table->decimal('precio_economica', 8, 2)->nullable()->after('precio_base');
            $table->decimal('precio_business', 8, 2)->nullable()->after('precio_economica');
            $table->decimal('precio_primera', 8, 2)->nullable()->after('precio_business');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vuelos', function (Blueprint $table) {
            //
        });
    }
};
