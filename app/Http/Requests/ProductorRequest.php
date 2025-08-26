<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero_productor' => ['required', 'string', 'max:255'],
            'nombre_completo' => ['required', 'string', 'max:255'],
            'cuit_cuil' => ['required', 'string', 'regex:/^[0-9]{11}$/'],
            'telefono' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email'],
            'direccion' => ['required', 'string', 'max:255'],
            'localidad' => ['required', 'string', 'max:255'],
            'departamento' => ['required', 'string', 'max:255'],
            'estado_documentacion' => ['required', 'string', 'in:En proceso,Aprobado,Faltante']
        ];
    }

    public function messages(): array
    {
        return [
            'numero_productor.required' => 'El número de productor es obligatorio',
            'numero_productor.max' => 'El número de productor no puede tener más de 255 caracteres',
            'nombre_completo.required' => 'El nombre completo es obligatorio',
            'nombre_completo.max' => 'El nombre completo no puede tener más de 255 caracteres',
            'cuit_cuil.required' => 'El CUIT/CUIL es obligatorio',
            'cuit_cuil.regex' => 'El CUIT/CUIL debe tener 11 dígitos numéricos',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe tener un formato válido',
            'direccion.required' => 'La dirección es obligatoria',
            'direccion.max' => 'La dirección no puede tener más de 255 caracteres',
            'localidad.required' => 'La localidad es obligatoria',
            'localidad.max' => 'La localidad no puede tener más de 255 caracteres',
            'departamento.required' => 'El departamento es obligatorio',
            'departamento.max' => 'El departamento no puede tener más de 255 caracteres',
            'estado_documentacion.required' => 'El estado de documentación es obligatorio',
            'estado_documentacion.in' => 'El estado de documentación debe ser: En proceso, Aprobado o Faltante'
        ];
    }
}