<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReleaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'type' => 'required|in:update,release',
            'published_at' => 'nullable|date',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:51200', // 50MB max por archivo
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título del comunicado es obligatorio.',
            'content.required' => 'Debes redactar el contenido del comunicado.',
            'background_image.image' => 'La imagen de fondo debe ser un archivo válido (JPEG, PNG, WEBP).',
            'background_image.max' => 'La imagen de fondo no debe superar los 5MB.',
            'documents.*.mimes' => 'Solo se permiten archivos PDF, Word o Imágenes.',
            'documents.*.max' => 'Los archivos adjuntos no deben superar los 50MB.',
        ];
    }
}
