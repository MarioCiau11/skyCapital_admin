<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuloRequest extends FormRequest
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
            $rules = [
                'inputModulo' => ['required', 'max:50', 'string'],
                'inputDescripción' => ['nullable', 'max:250', 'string'],
                'selectTipo' => ['required', 'max:20', 'string'],
                'selectProyecto' => ['required', 'max:100', 'string'],
                'inputMT2' => ['required', 'between:0,999999999.99', 'numeric'],
                'inputValor' => ['required', 'between:0,999999999.99', 'numeric'],
                'inputNivel' => ['required', 'between:0,999999999.99', 'numeric'],
                'inputCajones' => ['nullable', 'between:0,999999999.99', 'numeric'],
                'selectEstatus' => ['string', 'required'],
                'inputImgPrincipal' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'selectBanco' => ['nullable', 'max:15', 'string'],
                'inputCuenta' => ['nullable', 'max:50', 'string'],
                'inputClabe' => ['nullable', 'max:22', 'string'],
            ];
        }else{
            $rules = [
                'inputModulo' => ['nullable', 'max:50', 'string'],
                'inputDescripción' => ['nullable', 'max:250', 'string'],
                'selectTipo' => ['required', 'max:20', 'string'],
                'selectProyecto' => ['required', 'max:100', 'string'],
                'inputMT2' => ['required', 'between:0,999999999.99', 'numeric'],
                'inputValor' => ['required', 'between:0,999999999.99', 'numeric'],
                'inputNivel' => ['required', 'between:0,999999999.99', 'numeric'],
                'inputCajones' => ['nullable', 'between:0,999999999.99', 'numeric'],    
                'selectEstatus' => ['string', 'required'],
                'inputImgPrincipal' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'selectBanco' => ['nullable', 'max:15', 'string'],
                'inputCuenta' => ['nullable', 'max:50', 'string'],
                'inputClabe' => ['nullable', 'max:22', 'string'],
            ];
        }
        return $rules;
    }
}
