<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ══════════════════════════════════════════
        // TABLA: usuarios (sistema de autenticación)
        // ══════════════════════════════════════════
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('rol', ['admin', 'operador', 'cliente'])->default('cliente');
            $table->string('token_verificacion')->nullable();
            $table->boolean('email_verificado')->default(false);
            $table->string('token_reset')->nullable();
            $table->timestamp('token_reset_expira')->nullable();
            $table->timestamps();
        });

        // ══════════════════════════════════════════
        // TABLA: aerolineas
        // ══════════════════════════════════════════
        Schema::create('aerolineas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo', 10)->unique()->nullable(); // Ej: BOA, AV
            $table->string('pais')->default('Bolivia');
            $table->softDeletes();
            $table->timestamps();
        });

        // ══════════════════════════════════════════
        // TABLA: naves (flota aérea)
        // ══════════════════════════════════════════
        Schema::create('naves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aerolinea_id')->constrained('aerolineas')->onDelete('cascade');
            $table->string('matricula', 10)->unique();
            $table->string('modelo', 50);   // Ej: Boeing 737
            $table->integer('capacidad');   // Nro de asientos
            $table->enum('estado', ['activo', 'mantenimiento', 'retirado'])->default('activo');
            $table->timestamps();
        });

        // ══════════════════════════════════════════
        // TABLA: rutas (aeropuertos origen-destino)
        // ══════════════════════════════════════════
        Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            $table->string('origen');           // Ej: Santa Cruz
            $table->string('destino');          // Ej: La Paz
            $table->string('codigo_origen', 5); // Ej: VVI
            $table->string('codigo_destino', 5);// Ej: LPB
            $table->integer('distancia_km')->nullable();
            $table->integer('duracion_min')->nullable();
            $table->timestamps();
        });

        // ══════════════════════════════════════════
        // TABLA: vuelos
        // ══════════════════════════════════════════
        Schema::create('vuelos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aerolinea_id')->constrained('aerolineas');
            $table->foreignId('nave_id')->nullable()->constrained('naves')->nullOnDelete();
            $table->foreignId('ruta_id')->nullable()->constrained('rutas')->nullOnDelete();
            $table->string('numero_vuelo', 10); // Ej: BOA-101
            $table->string('destino');          // Campo libre (compatible con anterior)
            $table->dateTime('fecha_salida')->nullable();
            $table->dateTime('fecha_llegada')->nullable();
            $table->enum('estado', ['programado', 'abordando', 'en_vuelo', 'aterrizado', 'cancelado'])
                  ->default('programado');
            $table->decimal('precio_base', 8, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        // ══════════════════════════════════════════
        // TABLA: pasajeros
        // ══════════════════════════════════════════
        Schema::create('pasajeros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vuelo_id')->constrained('vuelos')->onDelete('cascade');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->string('nombre_completo');
            $table->string('dni', 20)->nullable();
            $table->string('email')->nullable();
            $table->enum('clase', ['economica', 'business', 'primera'])->default('economica');
            $table->string('asiento', 5)->nullable(); // Ej: 14A
            $table->enum('estado_reserva', ['confirmada', 'pendiente', 'cancelada'])->default('confirmada');
            $table->softDeletes();
            $table->timestamps();
        });

        // ══════════════════════════════════════════
        // TABLA: reservas
        // ══════════════════════════════════════════
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('vuelo_id')->constrained('vuelos')->onDelete('cascade');
            $table->string('codigo_reserva', 10)->unique(); // Ej: RES-00001
            $table->integer('cantidad_pasajeros')->default(1);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'completada'])->default('pendiente');
            $table->timestamps();
        });

        // ══════════════════════════════════════════
        // TABLA: monitoreo_vuelos (tracking en tiempo real)
        // ══════════════════════════════════════════
        Schema::create('monitoreo_vuelos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vuelo_id')->constrained('vuelos')->onDelete('cascade');
            $table->decimal('latitud', 10, 6)->nullable();
            $table->decimal('longitud', 10, 6)->nullable();
            $table->integer('altitud_metros')->nullable();
            $table->integer('velocidad_kmh')->nullable();
            $table->string('estado_actual')->default('En tierra');
            $table->timestamp('registrado_en')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoreo_vuelos');
        Schema::dropIfExists('reservas');
        Schema::dropIfExists('pasajeros');
        Schema::dropIfExists('vuelos');
        Schema::dropIfExists('rutas');
        Schema::dropIfExists('naves');
        Schema::dropIfExists('aerolineas');
        Schema::dropIfExists('usuarios');
    }
};
