<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allergy_patient', function (Blueprint $table) {
            $table->foreignId('allergy_id')->constrained('allergies')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->primary(['allergy_id', 'patient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allergy_patient');
    }
};
