<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentoRequest extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'tipo_documento_id' => 'required|exists:tipo_documentos,id',
            'estado' => 'required|in:pendiente,entregado,aprobado,rechazado,vencido',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'observaciones' => 'nullable|string',
            'es_requerido' => 'boolean',
            'fecha_entrega' => 'nullable|date',
            'fecha_revision' => 'nullable|date',
            'fecha_vencimiento' => 'nullable|date'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del documento es requerido.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'tipo_documento_id.required' => 'El tipo de documento es requerido.',
            'tipo_documento_id.exists' => 'El tipo de documento seleccionado no existe.',
            'archivo.file' => 'Debe subir un archivo válido.',
            'archivo.mimes' => 'El archivo debe ser de tipo: pdf, jpg, jpeg, png, doc, docx.',
            'archivo.max' => 'El archivo no debe pesar más de 10MB.',
            'observaciones.string' => 'Las observaciones deben ser texto.',
            'es_requerido.boolean' => 'El campo es requerido debe ser verdadero o falso.',
            'estado.required' => 'El estado es requerido.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'fecha_entrega.date' => 'La fecha de entrega debe ser una fecha válida.',
            'fecha_revision.date' => 'La fecha de revisión debe ser una fecha válida.',
            'fecha_vencimiento.date' => 'La fecha de vencimiento debe ser una fecha válida.'
        ];
    }
}