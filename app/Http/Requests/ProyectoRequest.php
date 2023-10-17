<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProyectoRequest extends FormRequest
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
                'inputClave' => ['required', 'max:50', 'string'],
                'inputNombre' => ['required', 'max:100', 'string'],
                'inputDescripcion' => ['nullable', 'max:250', 'string'],
                'inputPresupuesto' => ['nullable', 'between:0,999999999.99', 'numeric'],
                'inputCajones' => ['nullable', 'between:0,999999999.99', 'numeric'],
                'inputCajonesRestantes' => ['nullable', 'between:0,999999999.99', 'numeric'],
                'inputInicio' => ['nullable', 'date'],
                'inputFin' => ['nullable', 'date'],
                'inputAño' => ['required', 'max:4', 'string'],
                'inputNiveles' => ['nullable', 'string', 'max:10'],
                'inputMT2Totales' => ['nullable', 'string', 'max:20'],
                'inputDireccion' => ['nullable', 'string', 'max:100'],
                'inputColonia' => ['nullable', 'string', 'max:50'],
                'inputCiudad' => ['nullable', 'string', 'max:50'],
                'inputEstado' => ['nullable', 'string', 'max:50'],
                'inputPais' => ['nullable', 'string', 'max:50'],
                'inputCP' => ['nullable', 'string', 'max:10'],
                'selectEstatus' => ['bool', 'required'],
                'selectCategoria' => ['nullable', 'string'],
                'selectGrupo' => ['nullable', 'string'],
                'inputImgPrincipal' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'inputTituloLink1' => ['nullable', 'string', 'max:50'],
                'inputLink1' => ['nullable', 'string', 'max:50'],
                'inputTituloLink2' => ['nullable', 'string', 'max:50'],
                'inputLink2' => ['nullable', 'string', 'max:50'],
                'inputTituloLink3' => ['nullable', 'string', 'max:50'],
                'inputLink3' => ['nullable', 'string', 'max:50'],
            ];
        }else{
            $rules = [
                'inputClave' => ['nullable', 'max:50', 'string'],
                'inputNombre' => ['required', 'max:100', 'string'],
                'inputDescripcion' => ['nullable', 'max:250', 'string'],
                'inputPresupuesto' => ['nullable', 'between:0,999999999.99', 'numeric'],
                'inputCajones' => ['nullable', 'between:0,999999999.99', 'numeric'],    
                'inputCajonesRestantes' => ['nullable', 'between:0,999999999.99', 'numeric'],
                'inputInicio' => ['nullable', 'date'],
                'inputFin' => ['nullable', 'date'],
                'inputAño' => ['nullable', 'max:4', 'string'],
                'inputNiveles' => ['nullable', 'string', 'max:10'],
                'inputMT2Totales' => ['nullable', 'string', 'max:20'],
                'inputDireccion' => ['nullable', 'string', 'max:100'],
                'inputColonia' => ['nullable', 'string', 'max:50'],
                'inputCiudad' => ['nullable', 'string', 'max:50'],
                'inputEstado' => ['nullable', 'string', 'max:50'],
                'inputPais' => ['nullable', 'string', 'max:50'],
                'inputCP' => ['nullable', 'string', 'max:10'],
                'selectEstatus' => ['bool', 'required'],
                'selectCategoria' => ['nullable', 'string'],
                'selectGrupo' => ['nullable', 'string'],
                'inputImgPrincipal' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'inputTituloLink1' => ['nullable', 'string', 'max:50'],
                'inputLink1' => ['nullable', 'string', 'max:50'],
                'inputTituloLink2' => ['nullable', 'string', 'max:50'],
                'inputLink2' => ['nullable', 'string', 'max:50'],
                'inputTituloLink3' => ['nullable', 'string', 'max:50'],
                'inputLink3' => ['nullable', 'string', 'max:50'],
            ];
        }
        return $rules;
    }
}
