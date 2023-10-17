<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormasRequest extends FormRequest
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
        if($this->getMethod() == 'POST'){
            return [

                'inputClave' => ['required','string','max:10'],
                'inputNombre' => ['required','string','max:50'],
                'inputDescripcion' => ['string','max:50','nullable'],
                // 'formaPago' => ['nullable','string','max:10','nullable'],
                'selectMoneda' => ['required'],
                'selectEstatus' => ['required','bool']
            ];
        }

        return [

                'inputClave' => ['required','string','max:10'],
                'inputNombre' => ['required','string','max:50'],
                'inputDescripcion' => ['string','max:50','nullable'],
                // 'formaPago' => ['nullable','string','max:10','nullable'],
                'selectMoneda' => ['required'],
                'selectEstatus' => ['required','bool']
        ];
    }

    public function attributes(){
        return[
            'name' => 'Nombre de la Forma de pago',
            'clave' => 'Clave',
            'descripcion' => 'Descripción',
            'formaPago' => 'Forma de pago SAT',
            'moneda' => 'Moneda SAT',
            'selectEstatus' => 'Estatus'
        ];
    }
}
