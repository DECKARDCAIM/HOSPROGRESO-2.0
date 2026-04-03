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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            
            // Personal & Identification data
            $table->string('cui', 13)->nullable()->unique();
            $table->string('nit')->nullable()->unique();
            $table->foreignId('civil_status_id')->nullable()->constrained('civil_statuses')->nullOnDelete();
            $table->string('phone', 8)->nullable();
            
            // HR / Institutional data
            $table->foreignId('unity_execution_id')->nullable()->constrained('unity_executions')->nullOnDelete();
            $table->foreignId('work_department_id')->nullable()->constrained('work_departments')->nullOnDelete();
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->nullOnDelete();
            
            // Medical / Professional data
            $table->string('collegiate_number')->nullable();
            $table->foreignId('specialty_id')->nullable()->constrained('specialties')->nullOnDelete();
            
            // Geographical / Contact data
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->foreignId('gender_id')->nullable()->constrained('genders')->nullOnDelete();
            $table->foreignId('municipality_id')->nullable()->constrained('municipalities')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
