<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Reglas exclusivas para cuando solo se cambia la contraseña
        if ($this->has('update_password_only')) {
            return [
                'current_password' => 'required|current_password',
                'password' => 'required|min:8|confirmed',
            ];
        }

        // Reglas para la actualización del perfil
        return [
            'first_name' => 'required|string|max:255',
            'second_name' => 'nullable|string|max:255',
            'third_name' => 'nullable|string|max:255',
            'first_last_name' => 'required|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'married_last_name' => 'nullable|string|max:255',
            'cui' => ['nullable', 'string', 'max:13', Rule::unique('users')->ignore($this->user()->id)],
            'nit' => ['nullable', 'string', 'max:9', Rule::unique('users')->ignore($this->user()->id)],
            'marital_status' => 'nullable|string|in:soltero,casado,divorciado,viudo,union_libre',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date',
            'gender_id' => 'nullable|exists:genders,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'banner_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
        ];
    }
}
