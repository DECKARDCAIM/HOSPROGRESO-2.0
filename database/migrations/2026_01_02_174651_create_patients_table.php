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
            $table->string('dpi', 20)->unique()->nullable();
            $table->date('birth_date')->nullable();
            
            // Relaciones con catálogos
            $table->foreignId('gender_id')->nullable()->constrained('genders')->onDelete('set null');
            $table->foreignId('civil_status_id')->nullable()->constrained('civil_statuses')->onDelete('set null');
            $table->foreignId('ethnicity_id')->nullable()->constrained('ethnicities')->onDelete('set null');
            $table->foreignId('linguistic_community_id')->nullable()->constrained('linguistic_communities')->onDelete('set null');
            
            // Otros datos
            $table->string('education')->nullable();
            $table->string('occupation')->nullable();
            
            // Dirección
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('municipality_id')->nullable()->constrained('municipalities')->onDelete('set null');
            $table->string('place')->nullable();
            
            // Datos de la madre (para menores de edad)
            $table->string('mother_first_name')->nullable();
            $table->string('mother_second_name')->nullable();
            $table->string('mother_third_name')->nullable();
            $table->string('mother_first_last_name')->nullable();
            $table->string('mother_second_last_name')->nullable();
            $table->string('mother_married_last_name')->nullable();
            $table->string('mother_dpi', 20)->nullable();

            // Soft delete (eliminación lógica)
            $table->softDeletes();
            
            $table->timestamps();
            
            // Índices para búsquedas y filtros
            $table->index('dpi');
            $table->index('birth_date');
            $table->index('gender_id');
            $table->index('civil_status_id');
            $table->index('ethnicity_id');
            $table->index('linguistic_community_id');
            $table->index('country_id');
            $table->index('department_id');
            $table->index('municipality_id');
            
            // Índices compuestos para búsquedas complejas
            $table->index(['gender_id', 'country_id']);
            $table->index(['department_id', 'municipality_id']);
            $table->index(['first_name', 'first_last_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};