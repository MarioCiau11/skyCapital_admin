<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConceptosModRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if ($this->getMethod() == 'POST') {
            return [
                'inputConcepto' => ['required','string','max:50'],
                'selectEstatus' => ['required','bool'],
                'selectModulo' => ['required','string','max:20'],
                'selectMovimiento' => ['required','string','max:50'],
                'inputClave' => ['nullable','string','max:100']
    
            ];
        }

        return [
            'inputConcepto' => ['required','string','max:50'],
            'selectEstatus' => ['required','bool'],
            'selectModulo' => ['required','string','max:20'],
            'selectMovimiento' => ['required','string','max:50'],
            'inputClave' => ['nullable','string','max:100']

        ];
    }

    public function attributes()
    {
        return [
        'inputConcepto' => 'Nombre del Concepto',
        'selectEstatus' => 'Estatus',
        'selectModulo' => 'Módulo',
        'selectMovimiento' => 'Movimiento',
        'inputClave' => 'Clave de producto o servicio'
        ];
    }
}
