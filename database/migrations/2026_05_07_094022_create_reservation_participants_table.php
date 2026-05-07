<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservation_participants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reservation_id')
                ->constrained('reservations')
                ->onDelete('cascade');

            $table->foreignId('reservation_import_id')
                ->nullable()
                ->constrained('reservation_imports')
                ->onDelete('set null');

            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('position')->nullable();

            // Control de evaluación
            $table->tinyInteger('evaluation_status')->default(0);
            /* 0 = pendiente
               1 = enviada
               2 = respondida
            */
            // Asistencia
            $table->boolean('attendance')->default(true);
            // Calificación final
            $table->decimal('grade', 5, 2)->nullable();
            // Constancia generada
            $table->boolean('certificate_generated')->default(false);
            // Observaciones
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_participants');
    }
};
