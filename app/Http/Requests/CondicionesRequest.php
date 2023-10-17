<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CondicionesRequest extends FormRequest
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
        if($this->getMethod() =='POST'){
            return [
                'name' => ['required','string','max:50'],
                'selectTipo' => ['required','string','max:20'],
                'diasVenci' => ['required_if:selectTipo,credito','int','nullable'],
                'selectTipo_dias' => ['required_if:selectTipo,credito','string','nullable','max:10'],
                'selectDias_habil' => ['required_if:selectTipo,credito','string','nullable','max:10'],
                'selectMetodo' => ['string','nullable','max:100'],
                'selectEstatus' => ['required','bool']
            ];
        }

        return [
            'name' => ['required','string','max:50'],
            'selectTipo' => ['required','string','max:20'],
            'diasVenci' => ['required_if:selectTipo,credito','int','nullable'],
            'selectTipo_dias' => ['required_if:selectTipo,credito','string','nullable','max:10'],
            'selectDias_habil' => ['required_if:selectTipo,credito','string','nullable','max:10'],
            'selectMetodo' => ['string','nullable','max:100'],
            'selectEstatus' => ['required','bool']
        ];
    }
/**
 *
 * @return array
 */
public function attributes(){
    return[
        'name' => 'Nombre de la condición',
        'selectTipo' => 'Tipo de condición',
        'diasVenci' => 'Dias de vencimiento',
        'selectTipo_dias' => 'Tipo de días',
        'selectDias_habil' => 'Días hábiles',
        'selectMetodo' => 'Método de pago',
        'selectEstatus' => 'Estatus'
    ];
}

}
