<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMunicipalityRequest extends FormRequest
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
                Rule::unique('municipalities')
                    ->where(fn ($query) => $query->where('department_id', $this->department_id))
                    ->ignore($this->route('municipality')),
            ],
            'country_id' => 'required|exists:countries,id',
            'department_id' => 'required|exists:departments,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'name.min' => 'El campo nombre debe tener al menos 4 caracteres.',
            'name.unique' => 'Este municipio ya se encuentra registrado en el departamento seleccionado.',
            'country_id.required' => 'El campo país es obligatorio.',
            'country_id.exists' => 'El país seleccionado no es válido.',
            'department_id.required' => 'El campo departamento es obligatorio.',
            'department_id.exists' => 'El departamento seleccionado no es válido.',
        ];
    }
}
