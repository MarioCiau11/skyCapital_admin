<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticulosRequest extends FormRequest
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
                'inputClave' => ['required','string','max:10'],
                'selectTipo' => ['required','string','max:20'],
                'selectEstatus' => ['required','bool'],
                'inputDescripcion' => ['string','nullable','max:255'],
                'selectUnidad' => ['required','string','max:50'],
                'selectCategoria' => ['nullable' , 'string' , 'max:50'],
                'selectGrupo' => ['nullable','string','max:50'],
                'inputIVA' => ['required','numeric'],
                'inputPrecio' => ['required','numeric']
            ];
        }
        return [
            'inputClave' => ['required','string','max:10'],
                'selectTipo' => ['required','string','max:20'],
                'selectEstatus' => ['required','bool'],
                'inputDescripcion' => ['string','nullable','max:255'],
                'selectUnidad' => ['required','string','max:50'],
                'selectCategoria' => ['nullable' , 'string' , 'max:50'],
                'selectGrupo' => ['nullable','string','max:50'],
                'inputIVA' => ['required','numeric'],
                'inputPrecio' => ['required','numeric']
        ];
    }

    public function attributes()
    {
        return [
            'inputClave' => 'Clave del artículo',
            'selectTipo' => 'Tipo de artículo',
            'selectEstatus' => 'Estatus',
            'inputDescripcion' => 'Descripción',
            'selectUnidad' => 'Unidad Venta',
            'selectCategoria' => 'Categoria',
            'selectGrupo' => 'Grupo',
            'inputIVA' => 'IVA',
            'inputPrecio' => 'Lista de Precios'
        ];
    }
}
