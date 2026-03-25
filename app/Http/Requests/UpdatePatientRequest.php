<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $patientId = $this->route('patient')?->id ?? $this->route('patient');

        return [
            // Datos personales
                        'first_name'             => ['required', 'string', 'max:100'],
            'second_name'            => ['nullable', 'string', 'max:100'],
            'third_name'             => ['nullable', 'string', 'max:100'],
                        'first_last_name'        => ['required', 'string', 'max:100'],
            'second_last_name'       => ['nullable', 'string', 'max:100'],
            'married_last_name'      => ['nullable', 'string', 'max:100'],
            'email'                  => ['nullable', 'email', 'max:255', "unique:patients,email,{$patientId}"],
            'phone'                  => ['nullable', 'string', 'max:8'],
            'dpi'                    => ['nullable', 'string', 'max:20', "unique:patients,dpi,{$patientId}"],
                        'birth_date'             => ['required', 'date'],

            // Catálogos
                        'gender_id'              => ['required', 'integer', 'exists:genders,id'],
            'civil_status_id'        => ['nullable', 'integer', 'exists:civil_statuses,id'],
            'ethnicity_id'           => ['nullable', 'integer', 'exists:ethnicities,id'],
            'linguistic_community_id'=> ['nullable', 'integer', 'exists:linguistic_communities,id'],

            // Otros
            'education'              => ['nullable', 'string', 'max:255'],
            'occupation'             => ['nullable', 'string', 'max:255'],

            // Dirección
            'country_id'             => ['nullable', 'integer', 'exists:countries,id'],
            'department_id'          => ['nullable', 'integer', 'exists:departments,id'],
            'municipality_id'        => ['nullable', 'integer', 'exists:municipalities,id'],
            'place'                  => ['nullable', 'string', 'max:500'],

            // Datos de la madre
            'mother_first_name'      => ['nullable', 'string', 'max:100'],
            'mother_second_name'     => ['nullable', 'string', 'max:100'],
            'mother_third_name'      => ['nullable', 'string', 'max:100'],
            'mother_first_last_name' => ['nullable', 'string', 'max:100'],
            'mother_second_last_name'=> ['nullable', 'string', 'max:100'],
            'mother_married_last_name'=> ['nullable', 'string', 'max:100'],
            'mother_dpi'             => ['nullable', 'string', 'max:20'],

            // Familiares dinámicos (array)
            'relatives'                        => ['nullable', 'array'],
                        'relatives.*.relationship_type_id' => ['required_with:relatives', 'integer', 'exists:relationship_types,id'],
            'relatives.*.first_name'           => ['required_with:relatives', 'string', 'max:100'],
            'relatives.*.second_name'          => ['nullable', 'string', 'max:100'],
            'relatives.*.first_last_name'      => ['required_with:relatives', 'string', 'max:100'],
            'relatives.*.second_last_name'     => ['nullable', 'string', 'max:100'],
            'relatives.*.married_last_name'    => ['nullable', 'string', 'max:100'],
            'relatives.*.dpi'                  => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * Custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'first_name'             => 'primer nombre',
            'first_last_name'        => 'primer apellido',
            'email'                  => 'correo electrónico',
            'dpi'                    => 'DPI',
            'birth_date'             => 'fecha de nacimiento',
            'gender_id'              => 'género',
            'civil_status_id'        => 'estado civil',
            'ethnicity_id'           => 'etnia',
            'linguistic_community_id'=> 'comunidad lingüística',
            'country_id'             => 'país',
            'department_id'          => 'departamento',
            'municipality_id'        => 'municipio',
            'relatives.*.relationship'    => 'relación del familiar',
            'relatives.*.first_name'      => 'primer nombre del familiar',
            'relatives.*.first_last_name' => 'primer apellido del familiar',
            'relatives.*.married_last_name' => 'apellido de casada del familiar',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'El correo electrónico ya está registrado en el sistema.',
            'dpi.unique'   => 'El DPI ya está registrado en el sistema.',
            'relatives.*.relationship.in' => 'La relación del familiar no es válida.',
        ];
    }
}
