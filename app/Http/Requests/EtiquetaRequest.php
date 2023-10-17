<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EtiquetaRequest extends FormRequest
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
                'inputName' => ['required', 'max:250', 'string'],
                'selectEstatus' => ['bool', 'required'],
            ];


        }else{
            $rules = [
                'inputClave' => ['nullable', 'max:50', 'string'],
                'inputName' => ['required', 'max:250', 'string'],
                'selectEstatus' => ['bool', 'required'],
            ];
        }
        return $rules;
    }
}
