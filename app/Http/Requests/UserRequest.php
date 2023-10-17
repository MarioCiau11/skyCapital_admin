<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            return [
              'name' => ['required', 'string', 'max:100'],
              'username' => ['required', 'string', 'max:10', 'unique:users'],
              'password' => ['required', 'string', 'min:4', 'max:20'],
              'inputContraseña2' => ['required', 'string', 'min:4', 'same:password', 'max:20'],
              'email' => ['string', 'email', 'max:255', 'nullable'],
              'selectEstatus' => ['required','int'],
              'selectRol' => ['string'],
              'selectEmpresa' => ['int'],
              'empresas.*' => ['int'],
            ];
          }
          return [
            'name' => ['required', 'string', 'max:100'],
            'username' => [
              'required',
              'string',
              'max:10'
            ],
            'password' => ['string', 'min:4', 'nullable', 'max:20'],
            'inputContraseña2' => ['string', 'min:4', 'nullable', 'same:password', 'max:20'],
            'email' => ['string', 'email', 'max:100', 'nullable'],
            'selectEstatus' => ['required','int'],
            'selectRol' => ['string'],
            'selectEmpresa' => ['int'],
            'empresas.*' => ['int'],
          ];
    }

   /**
    * Get custom attributes for validator errors.
    *
    * @return array
    */
    public function attributes()
    {
      return [
        'email' => 'correo electrónico',
        'username' => 'usuario',
        'password' => 'contraseña',
        'name' => 'nombre',
        'inputContraseña2' => 'confirmar contraseña',
        'selectEstatus' => 'estatus',
        'selectRol' => 'rol',
        'selectEmpresa' => 'empresa',
      ];
    }
    
}
