<?php

namespace App\Http\Requests\proc;

use Illuminate\Foundation\Http\FormRequest;

class CxCRequest extends FormRequest
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
    // dd($this->request->all());
        $rules = [
            "selectMovimiento" => ["required"],
            "inputID" => ["nullable"],
            "inputFolioMov" => ["nullable"],
            "selectMoneda" => ["required"],
            "inputTipoCambio" => ['required','numeric', 'between:0,999999999.99'],
            "inputFechaEmision" => ["required","date_format:Y-m-d"],
            "inputCliente" => ["required"],
            "inputNombreCliente" =>["required"],
            "selectFormaPago" => ["required"],
            "inputCuenta" => ["required"],
            "inputCuentaMoneda" => ["nullable"],
            "inputImporte" =>  ['required','numeric', 'between:1,999999999.99'],
            "inputImpuesto" => ["nullable"],
            "inputImporteTotal" => ["required"],
            "inputObservaciones" => ["nullable","string","max:250"],
            "inputReferencia" => ["nullable","string","max:100"],
            "inputSaldo" => ["nullable"],
            "inputProyecto" => ['nullable', 'required_if:selectMovimiento,Anticipo'],
            "inputNombreProyecto" => ['nullable', 'required_if:selectMovimiento,Anticipo'],
            "inputModulo" => ['nullable', 'required_if:selectMovimiento,Anticipo'],
            "inputNombreModulo" => ['nullable', 'required_if:selectMovimiento,Anticipo'],
            // "checkDepositoAnticipado" => [""],
            "inputAnticipo" => ["nullable", 'required_if:selectMovimiento,Aplicación'],
            "inputIdAnticipo" => ["nullable"],
            "inputMonedaAnticipo" => ["nullable"],
            "inputAnticipoImporte" => ["nullable"],
            // "selectTableAplicacion" =>[""],
            // "inputEstatus" => ["required"],
            "inputSaldoCliente" => ["nullable"],
            "inputMovimientos" => ["nullable"],
            "inputMovimientosDelete" => ["nullable"],
            "inputImporteCalc" => ["nullable"],
        ];
    
        
        return $rules;
    }
}
