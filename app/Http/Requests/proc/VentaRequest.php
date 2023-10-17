<?php

namespace App\Http\Requests\proc;

use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
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
        //  dd($this->request->all());
       $rules = [
            'selectMovimiento' => ['required'],
            'inputID' => ['nullable','string'],
            'inputFolio' => ['nullable','string','max:100'],
            'inputClave' => ['nullable','string','max:100'],
            'selectMoneda' => ['required'],
            'inputTipoCambio' => ['required','numeric', 'between:0,999999999.99'],
            'selectProyecto' => ['required_if:selectMovimiento,Contrato','nullable'],
            'selectModulo' => ['required_if:selectMovimiento,Contrato','nullable'],
            'inputValor' => ['nullable','string'],
            'inputNivel' => ['nullable','string'],
            'inputMT2' => ['nullable','string'],
            'inputTipo' => ['nullable','string'],
            'inputCajones' => ['nullable','string'],
            'inputObservaciones' => ['nullable','string','max:250'],
            'selectProp' => ['required'], //este es el propietario
            'inputAsignadoPropietario' => ['nullable','numeric'],
            'selectCoprop' => ['nullable'],
            'impAsign' => ['nullable','numeric'],
            'selectCondicion' => ['required'],
            'inputFechaVencimiento' => ['required','date_format:Y-m-d'],
            'radioContrato' => ['required_if:selectMovimiento,Contrato','nullable'],
            'radioEsquema' => ['required_if:selectMovimiento,Contrato','nullable'],
            'renglones' => ['nullable'], 
            'inputSubtotal' => ['nullable','numeric'],
            'inputImpuestos' => ['nullable','numeric'],
            'inputTotal' => ['nullable','numeric'],

            'inputFechaContrato' => ['required','date_format:Y-m-d'],
            'selectPromo' => ['nullable'],
            'selectPeriodicidad' => ['required'],
            'inputFechaIni' => ['required','date_format:Y-m-d'],
            'inputAnual' => ['nullable','numeric'],
            'inputMeses' => ['required_if:radioEsquema,Mensualidad','nullable','int'],
            'inputEnganche' => ['required','numeric'],
            'inputFechaFin' => ['required','date_format:Y-m-d'],
            'inputMonto' => ['nullable'],
            'inputIVA' => ['nullable'],
            'inputTotalTabla' => ['nullable'],
            'selectEtiqueta' => ['nullable'],
            'selectVendedor' => ['nullable'],
            'inputEngancheC' => ['nullable','numeric'],
            'inputImporteEnganche' => ['nullable'],
            'inputComisionable' => ['nullable','numeric'],
            'inputMontoComisionable' => ['nullable'],
            'chekTipoComisión' => ['nullable'],
            'inputAsesor' => ['nullable','string','max:100'],
            'inputPorcentajeAsesor' => ['nullable','numeric'],
            'inputNetoAsesor' => ['nullable','numeric'],
            'importeFacAsesor' => ['nullable','numeric'],
            'inputFormaPagoAsesor' => ['nullable','string','max:100'],
            'inputReferido' => ['nullable','string','max:100'],
            'inputPorcentajeReferido' => ['nullable','numeric'],
            'inputNetoReferido' => ['nullable','numeric'],
            'inputFacReferido' => ['nullable','numeric'],
            'inputFormaPagoReferido' => ['nullable','string','max:100'],
            'inputBroker' => ['nullable','string','max:100'],
            'inputPorcentajeBroker' => ['nullable'],
            'inputNetoBroker' => ['nullable','numeric'],
            'inputFacBroker' => ['nullable','numeric'],
            'inputFormaPagoBroker' => ['nullable','string','max:100'],
            'inputRetencion' => ['nullable'],
            'inputSumaNeto' => ['nullable'],
            'inputSumaImpFac' => ['nullable'],
            'inputTotalNeto' => ['nullable'],
            'inputArticles' => ['nullable'],
            'inputEstatus' => ['nullable'],
            'inputArticlesDelete' => ['nullable'],
            'inputTablePlanVenta' => ['nullable'],
            'inputResultadoCorrida' => ['nullable'],
            'inputCoprops' => ['nullable'],
            'selectCopropArray' => ['nullable'],
            'impAsignArray' => ['nullable'],
            'inputCobro' => ['nullable'],
       ];

         return $rules;
    }

    
}
