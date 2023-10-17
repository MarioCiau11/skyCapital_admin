<?php

namespace App\Http\Requests\proc;

use Illuminate\Foundation\Http\FormRequest;

class TesoreriaRequest extends FormRequest
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
        $rules = [
            "selectMovimiento" => ["required"],
            "inputID" => ["nullable"],
            "inputFolioMov" => ["nullable"],
            "selectMoneda" => ["required"],
            "inputTipoCambio" => ["required"],
            "inputFechaEmision" =>["required"],
            "selectFormaPago" => ["required"],
            "inputIdCuenta" => ["nullable"],
            "inputCuenta" => ["required"],
            "inputCuentaDestino" => ["nullable"],
            "inputNombreCuenta" => ["nullable"],
            "inputMonedaCuenta" => ["nullable"],
            "inputNombreCuentaDestino" => ["nullable"],
            "inputMonedaCuentaDestino" => ["nullable"],
            "inputSaldoCuenta" => ["nullable"],
            "inputImporte" => ["required"],
            "inputObservaciones" => ["nullable"],
            "inputReferencia" => ["nullable"],
            "tablaMovimeintosData" => ["nullable"],
            "inputEstatus" => ["required"],
        ];
        return $rules;
    }
}
