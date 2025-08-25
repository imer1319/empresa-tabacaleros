<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentoRequest extends FormRequest
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
            'productor_id' => 'required|exists:productors,id',
            'nombre' => 'required|string|max:255',
            'tipo_documento_id' => 'required|exists:tipo_documentos,id',
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
            'observaciones' => 'nullable|string',
            'es_requerido' => 'boolean',
            'estado' => 'required|string|in:pendiente,entregado,aprobado,rechazado,vencido',
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
            'productor_id.required' => 'El productor es requerido.',
            'productor_id.exists' => 'El productor seleccionado no existe.',
            'nombre.required' => 'El nombre del documento es requerido.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'tipo_documento_id.required' => 'El tipo de documento es requerido.',
            'tipo_documento_id.exists' => 'El tipo de documento seleccionado no existe.',
            'archivo.required' => 'El archivo es requerido.',
            'archivo.file' => 'Debe subir un archivo válido.',
            'archivo.mimes' => 'El archivo debe ser de tipo: pdf, jpg, jpeg, png, doc, docx.',
            'archivo.max' => 'El archivo no debe pesar más de 10MB.',
            'observaciones.string' => 'Las observaciones deben ser texto.',
            'es_requerido.boolean' => 'El campo es requerido debe ser verdadero o falso.',
            'estado.required' => 'El estado es requerido.',
            'estado.string' => 'El estado debe ser texto.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'fecha_entrega.date' => 'La fecha de entrega debe ser una fecha válida.',
            'fecha_revision.date' => 'La fecha de revisión debe ser una fecha válida.',
            'fecha_vencimiento.date' => 'La fecha de vencimiento debe ser una fecha válida.'
        ];
    }
}