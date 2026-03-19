<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('second_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('first_last_name');
            $table->string('second_last_name')->nullable();
            $table->string('married_last_name')->nullable();

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true);

            $table->string('profile_photo_path', 2048)->nullable();
            $table->string('banner_photo_path', 2048)->nullable();
            $table->string('cui', 13)->nullable()->unique();
            $table->string('nit')->nullable()->unique();
            $table->enum('marital_status', ['soltero', 'casado', 'divorciado', 'viudo', 'union_libre'])->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->foreignId('unity_execution_id')->nullable()->constrained('unity_executions')->nullOnDelete();
            $table->foreignId('work_department_id')->nullable()->constrained('work_departments')->nullOnDelete();

            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('municipality_id')->nullable()->constrained('municipalities')->nullOnDelete();
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->nullOnDelete();

            $table->string('collegiate_number')->nullable();
            $table->foreignId('specialty_id')->nullable()->constrained('specialties')->nullOnDelete();

            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();

            $table->enum('estado', ['disponible', 'ocupado', 'ausente', 'privado'])->default('disponible');
            $table->string('theme_preference')->nullable()->default('auto');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};