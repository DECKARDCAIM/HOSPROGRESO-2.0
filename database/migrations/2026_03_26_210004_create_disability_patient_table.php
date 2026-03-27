<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disability_patient', function (Blueprint $table) {
            $table->foreignId('disability_id')->constrained('disabilities')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->primary(['disability_id', 'patient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disability_patient');
    }
};
