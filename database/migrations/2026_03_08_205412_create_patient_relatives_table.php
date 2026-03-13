<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('patient_relatives', function (Blueprint $table) {

            $table->id();

            // Relación con paciente
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();

            // Tipo de relación
            $table->foreignId('relationship_type_id')->nullable()->constrained('relationship_types')->nullOnDelete();

            // Datos del familiar
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('third_name')->nullable();

            $table->string('first_last_name')->nullable();
            $table->string('second_last_name')->nullable();
            $table->string('married_last_name')->nullable();

            $table->string('dpi', 20)->nullable();

            $table->timestamps();

            // Índices
            $table->index('patient_id');
            $table->index('relationship_type_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_relatives');
    }
};