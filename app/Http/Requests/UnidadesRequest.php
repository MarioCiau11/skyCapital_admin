<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnidadesRequest extends FormRequest
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
            return[
                'inputUnidad' => ['required','string','max:50'],
                'inputDecimal' => ['required','int'],
                // 'selectClave' => ['required','string','Max:50'],
                'selectEstatus' => ['bool','required']
            ];
        }
        return [
            'inputUnidad' => ['required','string','max:50'],
            'inputDecimal' => ['required','int'],
            // 'selectClave' => ['required','string','Max:50'],
            'selectEstatus' => ['bool','required']
        ];
    }
    public function attributes()
    {
        return[
            'inputUnidad' =>'Unidad',
            'inputDecimal' => 'Decimal válida',
            'selectClave' => 'Clave SAT',
            'selectEstatus' => 'Estatus'
        ];
    }
}
