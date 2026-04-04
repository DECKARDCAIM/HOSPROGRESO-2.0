<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'second_name' => 'nullable|string|max:255',
            'third_name' => 'nullable|string|max:255',
            'first_last_name' => 'required|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'married_last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'nullable|exists:roles,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',

            'cui' => 'nullable|string|max:13|unique:staff,cui',
            'nit' => 'nullable|string|max:9',
            'civil_status_id' => 'nullable|exists:civil_statuses,id',
            'phone' => 'nullable|string|max:15',
            'birth_date' => 'nullable|date',
            'gender_id' => 'nullable|exists:genders,id',
            'specialty_id' => 'nullable|exists:specialties,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'municipality_id' => 'nullable|exists:municipalities,id',
            'address' => 'nullable|string|max:500',
            'unity_execution_id' => 'nullable|exists:unity_executions,id',
            'work_department_id' => 'nullable|exists:work_departments,id',
            'collegiate_number' => 'nullable|string|max:50',
        ];
    }
}
