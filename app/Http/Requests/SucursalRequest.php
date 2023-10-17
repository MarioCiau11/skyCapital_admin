<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SucursalRequest extends FormRequest
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
            'inputDireccion' => ['required', 'string', 'max:100'],
            'inputPais' => ['required', 'string', 'max:100'],
            'inputEstado' => ['required', 'string', 'max:30'],
            'inputCiudad' => ['required', 'string', 'max:30'],
            'inputCP' => ['required', 'string', 'max:15'],
            'inputColonia' => ['required', 'string', 'max:100'],
            'selectEstatus' => ['bool', 'required'],
            'empresa' => ['required', 'exists:CAT_EMPRESAS,idEmpresa'],
        ];

        if ($this->getMethod() == 'POST') {
            $rules['inputClave'] = ['required', 'max:10', 'string', 'unique:CAT_SUCURSALES,clave'];
            $rules['inputName'] = ['required', 'max:100', 'string'];

        } else {
            $rules['inputClave'] = ['nullable', 'max:10', 'string'];
            $rules['inputName'] = ['required', 'max:100', 'string'];
        }

        return $rules;
    }
}
