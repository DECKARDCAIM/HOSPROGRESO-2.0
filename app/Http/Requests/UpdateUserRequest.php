<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');

        $user = User::with('staff')->find($userId);
        $staffId = $user?->staff?->id ?? 0;

        return [
            'first_name' => 'required|string|max:255',
            'second_name' => 'nullable|string|max:255',
            'third_name' => 'nullable|string|max:255',
            'first_last_name' => 'required|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'married_last_name' => 'nullable|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => 'nullable|string|min:8',
            'role_id' => 'nullable|exists:roles,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',

            'cui' => ['nullable', 'string', 'max:13', Rule::unique('staff')->ignore($staffId)],
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
