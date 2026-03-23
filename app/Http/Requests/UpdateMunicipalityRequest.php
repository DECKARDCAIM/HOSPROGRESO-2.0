<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMunicipalityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:5',
            'country_id' => 'required|exists:countries,id',
            'department_id' => 'required|exists:departments,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'country_id.required' => 'El campo país es obligatorio.',
            'country_id.exists' => 'El país seleccionado no es válido.',
            'department_id.required' => 'El campo departamento es obligatorio.',
            'department_id.exists' => 'El departamento seleccionado no es válido.',
            'name.min' => 'El campo nombre debe tener al menos 5 caracteres.',
        ];
    }
}
