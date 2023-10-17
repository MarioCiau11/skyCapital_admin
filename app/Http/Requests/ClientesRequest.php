<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientesRequest extends FormRequest
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
        if ($this->getMethod() == 'POST' ) {
           $rules = [
                "checkPersona" => ['required'],
                "inputRazon" => ['required','string','max:250'],
                "inputRFC" => ['required','string','max:15'],
                "inputClave" => ['Required','int'],
                "inputCURP" => ['nullable','Required_if:checkPersona,personaFisica','string','max:30'],
                "inputNombre" => ['nullable','required_if:checkPersona,personaMoral','string','max:100'],
                "inputApellidoP" => ['nullable','required_if:checkPersona,personaMoral','string','max:100'],
                "inputApellidoM" => ['nullable','required_if:checkPersona,personaMoral','string','max:100'],
                "inputTelefonoC" => ['nullable','digits:10'],
                "inputCorreo" => ['nullable','string','email','max:50'],
                "inputDireccion" => ['required','string','max:100'],
                "inputVialidades" => ['required','string','max:100'],
                "inputNoInterior" => ['required','string','max:50'],
                "inputNoExterior" => ['required','string','max:50'],
                "inputColonia" => ['required','string','max:50'],
                "inputLocalidad" => ['required','string','max:50'],
                "inputEstado" => ['required','string','max:50'],
                "inputPais" => ['required','string','max:50'],
                "inputCodigoP" => ['required','int'],
                "inputTelOf1" => ['nullable','int'],
                "inputTelOf2" => ['nullable','int'],
                "inputRepTelCel" => ['nullable','digits:10'],
                "inputContacto1" => ['nullable','string','max:50'],
                "inputCorreoElct1" => ['required','string','max:50', Rule::unique('CAT_CLIENTES', 'correoElectronico1')->ignore($this->inputClave, 'clave')],
                "inputContacto2" => ['nullable','string','max:50'],
                "inputCorreoElct2" => ['nullable','string','max:50'],
                "inputcontraseñaPortal" => ['nullable','string','max:50'],
                "textareaObservaciones" => ['nullable','string','max:250'],
                "inputOcupacion" => ['nullable','string','max:100'],
                'selectCategoria' => ['nullable' , 'string' , 'max:50'],
                'selectGrupo' => ['nullable','string','max:50'],
                'selectEstatus' => ['required','bool'],
                "selectCondicion" => ['nullable' , 'string' , 'max:50'],
                "inputRegimen" => ['nullable','string','max:100'],
                "inputDocumento" => ['array'],
                // "inputDocumento.*" =>[,'nullable','string','max:100']
            ];
        }
        else{
            $rules = [
                "checkPersona" => ['required'],
                "inputRazon" => ['required','string','max:250'],
                "inputRFC" => ['required','string','max:15'],
                "inputClave" => ['nullable','int'],
                "inputCURP" => ['nullable','Required_if:checkPersona,personaFisica','string','max:30'],
                "inputNombre" => ['nullable','required_if:checkPersona,personaMoral','string','max:100'],
                "inputApellidoP" => ['nullable','required_if:checkPersona,personaMoral','string','max:100'],
                "inputApellidoM" => ['nullable','required_if:checkPersona,personaMoral','string','max:100'],
                "inputTelefonoC" => ['nullable','digits:10'],
                "inputCorreo" => ['nullable','string','email','max:50'],
                "inputDireccion" => ['required','string','max:100'],
                "inputVialidades" => ['required','string','max:100'],
                "inputNoInterior" => ['required','string','max:50'],
                "inputNoExterior" => ['required','string','max:50'],
                "inputColonia" => ['required','string','max:50'],
                "inputLocalidad" => ['required','string','max:50'],
                "inputEstado" => ['required','string','max:50'],
                "inputPais" => ['required','string','max:50'],
                "inputCodigoP" => ['required','int'],
                "inputTelOf1" => ['nullable','int'],
                "inputTelOf2" => ['nullable','int'],
                "inputRepTelCel" => ['nullable','digits:10'],
                "inputContacto1" => ['nullable','string','max:50'],
                "inputCorreoElct1" => ['nullable','string','max:50', Rule::unique('CAT_CLIENTES', 'correoElectronico1')->ignore($this->inputClave, 'clave')],
                "inputContacto2" => ['nullable','string','max:50'],
                "inputCorreoElct2" => ['nullable','string','max:50'],
                "inputcontraseñaPortal" => ['nullable','string','max:50'],
                "textareaObservaciones" => ['nullable','string','max:250'],
                "inputOcupacion" => ['nullable','string','max:100'],
                'selectCategoria' => ['nullable' , 'string' , 'max:50'],
                'selectGrupo' => ['nullable','string','max:50'],
                'selectEstatus' => ['required','bool'],
                "selectCondicion" => ['nullable' , 'string' , 'max:50'],
                "inputRegimen" => ['nullable','string','max:100'],
                "inputDocumento" => ['array'],
                // "inputDocumento.*" =>[,'nullable','string','max:100']
            ];
        }
        
        return $rules;
    }
    public function attributes()
    {
        return [
            "checkPersona" => 'Tipo de Persona',
                "inputRazon" => 'Nombre/Razón Social',
                "inputClave" => 'Clave',
                "inputRFC" => 'RFC',
                "inputCURP" => 'CRUP',
                "inputNombre" => 'Nombre del representante legal',
                "inputApellidoP" => 'Apellido Paterno',
                "inputApellidoM" => 'Apellido Materno',
                "inputTelefonoC" => 'Teléfono celular del representante legal',
                "inputCorreo" => 'Correo electrónico del representante legal',
                "inputDireccion" => 'Dirección',
                "inputVialidades" => 'Entre Vialidades',
                "inputNoInterior" => 'Número Interior',
                "inputNoExterior" => 'Número Exterior',
                "inputColonia" => 'Colonia/Fraccionamiento',
                "inputLocalidad" => 'Localidad/Municipio',
                "inputEstado" => 'Estado',
                "inputPais" => 'País',
                "inputCodigoP" => 'Código Postal',
                "inputTelOf1" => 'Teléfono de Oficina 1',
                "inputTelOf2" => 'Teléfono de Oficina 2',
                "inputRepTelCel" => 'Teléfono Celular',
                "inputContacto1" => 'Contacto 1',
                "inputCorreoElct1" => 'Correo Electrónico 1',
                "inputContacto2" => 'Contacto 2',
                "inputCorreoElct2" => 'Correo Electrónico 2',
                "inputcontraseñaPortal" => 'Contraseña del Portal',
                "textareaObservaciones" => 'Observaciones',
                "inputOcupacion" => 'Ocuapción',
                'selectCategoria' => 'Categoria',
                'selectGrupo' => 'Grupo',
                'selectEstatus' => 'Estatus',
                'selectCondicion' => 'Condición de Pago',
                "inputRegimen" => 'Régimen Fiscal',
                "inputDocumento" => 'Documento',
                "inputRutaDocumento" => 'Ruta del documento',
        ];
    }
}
