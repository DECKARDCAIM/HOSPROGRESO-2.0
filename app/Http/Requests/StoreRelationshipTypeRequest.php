<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRelationshipTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255|unique:relationship_types,name',
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
