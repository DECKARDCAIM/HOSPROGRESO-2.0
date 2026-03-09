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
        Schema::create('clinical_records', function (Blueprint $table) {
            $table->id();
            
            // Relación Uno a Uno con Paciente
            $table->foreignId('patient_id')->unique()->constrained('patients')->cascadeOnDelete();
            
            // Número de expediente clínico
            $table->string('record_number')->unique();
            
            $table->softDeletes();
            $table->timestamps();

            // Índices adicionales si se requiere en el futuro
            $table->index('record_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_records');
    }
};
