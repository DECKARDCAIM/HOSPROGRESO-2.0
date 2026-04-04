<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCivilStatusRequest extends FormRequest
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
                Rule::unique('civil_statuses')->ignore($this->route('civil_status') ?? $this->route('civilStatus')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'name.min' => 'El campo nombre debe tener al menos 3 caracteres.',
            'name.unique' => 'Este estado civil ya se encuentra registrado.',
        ];
    }
}
