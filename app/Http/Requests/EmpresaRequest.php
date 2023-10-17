<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule as ValidationRule;

class EmpresaRequest extends FormRequest
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
            'inputDescripcion' => ['string','nullable','max:250'],
            'selectEstatus' => ['bool','required'],
            'logoEmpresa' => ['nullable', 'file'],
            'inputDireccion' => ['required','string','max:100'],
            'inputPais' => ['required','string', 'max:100'],
            'inputEstado' => ['required','string', 'max:30'],
            'inputCiudad' => ['required','string', 'max:30'],
            'inputCP' => ['required','string', 'max:15'],
            'inputColonia' => ['required','string', 'max:100'],
            'inputTelefono1' => ['nullable','string','max:50'],
            'inputTelefono2' => ['nullable','string','max:50'],
            'inputCorreo' => ['required','string','max:50'],
            'inputRFC' => ['required','string','max:13'],
            'inputRegimen' => ['required','string', 'max:100'],
            'inputRegistro' => ['nullable','string','max:20'],
            'inputRepresentante' => ['nullable','string','max:250'],
            'certificadoKey' => ['nullable','file'],
            'certificadoCer' => ['nullable','file'],
            'passwordKey' => ['nullable','string','max:20'],

          ];
        if($this->getMethod() == 'POST'){

            $rules['inputClave'] =['required','max:10','string', 'unique:CAT_EMPRESAS,clave'];
            $rules['inputName'] = ['required','max:100','string' ];
            $rules['inputNameShort'] = ['nullable','string','max:100'];
            $rules['inputDocumentos'] = ['required', 'unique:CAT_EMPRESAS,rutaDocumentos'];
    
        }else{
            $rules['inputClave'] =['nullable','max:10','string'];
            $rules['inputName'] = ['required','max:100','string' ];
            $rules['inputNameShort'] = ['required','string','max:100'];
            $rules['inputDocumentos'] = ['nullable'];
        }

        return $rules;
    
    }

    public function attributes()
    {
        return[
            'inputClave' => 'Clave',
            'inputName' =>'Nombre',
            'inputNameShort' => 'Nombre corto',
            'inputDescripcion' => 'Descripción',
            'selectEstatus' => 'Estatus',
            'logoEmpresa' => 'Logo',
            'inputDireccion' => 'Dirección',
            'inputPais' => 'País',
            'inputEstado' => 'Estado',
            'inputCiudad' => 'Ciudad',
            'inputCP' => 'Código postal',
            'inputColonia' => 'Colonia',
            'inputTelefono1' => 'Teléfono 1',
            'inputTelefono2' => 'Teléfono 2',
            'inputCorreo' => 'Correo',
            'inputRFC' => 'RFC',
            'inputRegimen' => 'Régimen',
            'inputRegistro' => 'Registro',
            'inputRepresentante' => 'Representante',
            'certificadoKey' => 'Certificado key',
            'certificadoCer' => 'Certificado cer',
        ];
    }
}
