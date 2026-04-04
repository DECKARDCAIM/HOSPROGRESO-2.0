<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRelativeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'relationship_type_id' => 'required|exists:relationship_types,id',
            'first_name' => 'required|string|max:255',
            'second_name' => 'nullable|string|max:255',
            'third_name' => 'nullable|string|max:255',
            'first_last_name' => 'required|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'married_last_name' => 'nullable|string|max:255',
            'cui' => 'required|string|digits:13|unique:patient_relatives,cui',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'El paciente es obligatorio.',
            'relationship_type_id.required' => 'El tipo de relación es obligatorio.',
            'first_name.required' => 'El primer nombre es obligatorio.',
            'first_last_name.required' => 'El primer apellido es obligatorio.',
            'cui.required' => 'El CUI es obligatorio.',
            'cui.digits' => 'El CUI debe tener exactamente 13 dígitos.',
            'cui.unique' => 'Este CUI ya está registrado.',
        ];
    }
}
