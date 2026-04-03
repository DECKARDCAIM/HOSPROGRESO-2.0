<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends FormRequest
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
                'min:4',
                Rule::unique('departments')->where(fn ($query) => $query->where('country_id', $this->country_id)),
            ],
            'country_id' => 'required|exists:countries,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'name.min' => 'El campo nombre debe tener al menos 4 caracteres.',
            'name.unique' => 'Este departamento ya se encuentra registrado en el país seleccionado.',
            'country_id.required' => 'El campo país es obligatorio.',
            'country_id.exists' => 'El país seleccionado no es válido.',
        ];
    }
}
