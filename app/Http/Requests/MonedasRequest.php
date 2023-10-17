<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MonedasRequest extends FormRequest
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
                'inputClave' =>['required','max:10','string'],
                'inputName' => ['required','max:50','string' ],
                'inputTipo' => ['required','numeric'],
                'inputDescripcion' => ['string','nullable','max:250'],
                'selectEstatus' => ['bool','required']
            ];
        }
        return [
                'inputClave' =>['required','max:10','string'],
                'inputName' => ['required','max:50','string' ],
                'inputTipo' => ['required','numeric'],
                'inputDescripcion' => ['string','nullable','max:250'],
                'selectEstatus' => ['bool','required']
        ];
    }
    public function attributes()
    {
        return[
            'inputClave' => 'Clave',
            'inputName' =>'Nombre',
            'inputTipo' => 'Tipo de cambio',
            'inputDescripcion' => 'Descripción',
            'selectEstatus' => 'Estatus'
        ];
    }
}
