<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('work_departments')->ignore($this->route('work_department')),
            ],
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del departamento es obligatorio.',
            'name.min' => 'El nombre del departamento debe tener al menos 3 caracteres.',
            'name.unique' => 'Este departamento ya está registrado.',
        ];
    }
}
