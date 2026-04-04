<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $patientId = $this->route('patient')?->id ?? $this->route('patient');

        return [
            'first_name' => 'required|string|max:100',
            'second_name' => 'nullable|string|max:100',
            'third_name' => 'nullable|string|max:100',
            'first_last_name' => 'required|string|max:100',
            'second_last_name' => 'nullable|string|max:100',
            'married_last_name' => 'nullable|string|max:100',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('patients')->ignore($patientId)],
            'phone' => 'nullable|string|max:8',
            'cui' => ['nullable', 'string', 'max:13', Rule::unique('patients')->ignore($patientId)],
            'birth_date' => 'required|date',
            'gender_id' => 'required|integer|exists:genders,id',
            'civil_status_id' => 'nullable|integer|exists:civil_statuses,id',
            'ethnicity_id' => 'nullable|integer|exists:ethnicities,id',
            'linguistic_community_id' => 'nullable|integer|exists:linguistic_communities,id',
            'education' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'municipality_id' => 'nullable|integer|exists:municipalities,id',
            'place' => 'nullable|string|max:500',
            'relatives' => 'nullable|array',
            'relatives.*.relationship_type_id' => 'required_with:relatives|integer|exists:relationship_types,id',
            'relatives.*.first_name' => 'required_with:relatives|string|max:100',
            'relatives.*.second_name' => 'nullable|string|max:100',
            'relatives.*.first_last_name' => 'required_with:relatives|string|max:100',
            'relatives.*.second_last_name' => 'nullable|string|max:100',
            'relatives.*.married_last_name' => 'nullable|string|max:100',
            'relatives.*.cui' => 'nullable|string|max:13',
            'allergies' => 'nullable|array',
            'allergies.*' => 'exists:allergies,id',
            'disabilities' => 'nullable|array',
            'disabilities.*' => 'exists:disabilities,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => 'primer nombre',
            'first_last_name' => 'primer apellido',
            'email' => 'correo electrónico',
            'cui' => 'CUI',
            'birth_date' => 'fecha de nacimiento',
            'gender_id' => 'género',
            'civil_status_id' => 'estado civil',
            'ethnicity_id' => 'etnia',
            'linguistic_community_id' => 'comunidad lingüística',
            'municipality_id' => 'municipio',
            'relatives.*.relationship_type_id' => 'relación del familiar',
            'relatives.*.first_name' => 'primer nombre del familiar',
            'relatives.*.first_last_name' => 'primer apellido del familiar',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'El correo electrónico ya está registrado en el sistema.',
            'cui.unique' => 'El CUI ya está registrado en el sistema.',
        ];
    }
}
