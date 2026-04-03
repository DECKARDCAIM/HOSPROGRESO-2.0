<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // Datos personales
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('first_last_name')->nullable();
            $table->string('second_last_name')->nullable();
            $table->string('married_last_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone', 8)->nullable();

            $table->string('cui', 13)->unique()->nullable();
            $table->date('birth_date')->nullable();


            // Relaciones
            $table->foreignId('gender_id')->nullable()->constrained('genders')->nullOnDelete();
            $table->foreignId('civil_status_id')->nullable()->constrained('civil_statuses')->nullOnDelete();
            $table->foreignId('ethnicity_id')->nullable()->constrained('ethnicities')->nullOnDelete();
            $table->foreignId('linguistic_community_id')->nullable()->constrained('linguistic_communities')->nullOnDelete();

            // Otros datos
            $table->string('education')->nullable();
            $table->string('occupation')->nullable();

            // Dirección
            $table->foreignId('municipality_id')->nullable()->constrained('municipalities')->nullOnDelete();
            $table->string('place')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // Índices
            $table->index('birth_date');
            $table->index('gender_id');
            $table->index('civil_status_id');
            $table->index('ethnicity_id');
            $table->index('linguistic_community_id');
            $table->index('municipality_id');
            $table->index('email');
            $table->index('phone');

            $table->index(['first_last_name', 'second_last_name']);

            $table->fullText([
                'first_name',
                'second_name',
                'first_last_name',
                'second_last_name'
            ], 'patients_names_fulltext');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};