<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CuentasDineroRequest extends FormRequest
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
            $rules = [
                "inputClave" => ['required','string','max:10'],
                "selectBanco" => ['required'],
                "inputNoCuenta" => ['required','string','max:50'],
                "inputCuentaClave" => ['required','string','max:50'],
                "inputReferencia" => ['required','string','max:50'],
                "inputConvenio" => ['required','string','max:50'],
                "selectTipo" => ['required'],
                "selectMoneda" => ['required'],
                "selectEmpresa" => ['required'],
                "selectEstatus" => ['required'],
            ];
        }
        $rules = [
            "inputClave" => ['nullable','string','max:10'],
            "selectBanco" => ['required'],
            "inputNoCuenta" => ['required','string','max:50'],
            "inputCuentaClave" => ['required','string','max:50'],
            "inputReferencia" => ['required','string','max:50'],
            "inputConvenio" => ['required','string','max:50'],
            "selectTipo" => ['required'],
            "selectMoneda" => ['required'],
            "selectEmpresa" => ['required'],
            "selectEstatus" => ['required'],
        ];
        return $rules;
    }
    public function attributes(){
        return [
            "inputClave" => 'Clave',
            "selectBanco" => 'Banco',
            "inputNoCuenta" => 'Número de Cuenta',
            "inputCuentaClave" => 'Cuenta Clave',
            "inputReferencia" => 'Referencia del Banco',
            "inputConvenio" => 'Convenio del Banco',
            "selectTipo" => 'Tipo de Cuenta',
            "selectMoneda" => 'Moneda',
            "selectEmpresa" => 'Empresa',
            "selectEstatus" => 'Estatus',
        ];
    }
}
