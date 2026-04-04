<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRelationshipTypeRequest extends FormRequest
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
                Rule::unique('relationship_types')->ignore($this->route('relationship_type')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del tipo de relación es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.unique' => 'Este tipo de relación ya está registrado.',
        ];
    }
}
