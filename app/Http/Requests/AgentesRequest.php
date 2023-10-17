<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgentesRequest extends FormRequest
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
            return[
                'inputClave' =>['required','string','max:10'],
                'inputName' => ['required','string','max:100'],
                'selectTipo' => ['required','string','max:20'],
                'selectCategoria' => ['nullable','string','max:100'],
                'selectGrupo' => ['nullable','string','max:100'],
                'selectEstatus' => ['required','bool']
            ];
        }
        return [
            'inputClave' =>['nullable','string','max:10'],
            'inputName' => ['required','string','max:100'],
            'selectTipo' => ['required','string','max:20'],
            'selectCategoria' => ['nullable','string','max:100'],
            'selectGrupo' => ['nullable','string','max:100'],
            'selectEstatus' => ['required','bool']
        ];
    }
    public function attributes()
    {
        return [
            'inputClave' => 'Clave del agente',
            'inputName' => 'Nombre del Agente',
            'selectTipo' => 'Tipo',
            'selectCategoria' => 'Categoria',
            'selectGrupo' => 'Grupo',
            'selectEstatus' => 'Estatus' 
        ];
    }
}
