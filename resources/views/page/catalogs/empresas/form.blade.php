<div class="col-lg-12">
    <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
</div>
<h3 class="text-black">Datos generales</h3>
<div class="row">
    <div class="col-lg-4 ">
        <div class="field-item">
            <label for="" class="field-label">Clave  <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <input name="inputClave" value="{{ old('inputClave') ?? $Empresa->clave }}" class="input-bordered"
                    type="text" required @if($Empresa->clave != '') disabled @endif>
                @if ($errors->has('inputClave'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputClave') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-8 ">
        <div class="fiel-item">
            <label for="" class="field-label">Nombre  <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <input name="inputName" value="{{ old('inputName') ?? $Empresa->nombreEmpresa }}" class="input-bordered" type="text" required>
                @if ($errors->has('inputName'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputName') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="field-item">
            <label for="" class="field-label">Nombre Corto <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <input name="inputNameShort" value="{{ old('inputNameShort') ?? $Empresa->nombreCorto }}" class="input-bordered" type="text" required>
                @if ($errors->has('inputNameShort'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputNameShort') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="field-item">
            <label for="" class="field-label">Estatus</label>
            <div class="field-wrap">
                <select value="{{ old('selectEstatus') ?? $Empresa->estatus }}" id="selectEstatus" name="selectEstatus" class=""
                    data-select2-theme="bordered">
                    <option value="1" @if(old('selectEstatus',$Empresa->estatus)=='1') selected @endif>Alta</option>
                    <option value="0" @if(old('selectEstatus',$Empresa->estatus)=='0') selected @endif>Baja</option>
                </select>
                @if ($errors->has('selectEstatus'))
                    <div class="alert alert-danger">
                        {{ $errors->first('selectEstatus') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="field-item">
            <div class="field-wrap">
                {!! Form::label('status', 'Logo de la Empresa', ['class' => 'field-label']) !!}
                            <div class="contenedor-carga-imagen">
                                <button class="btn-subir-archivo form-control btn-upload-logo-empresa btn-primary"><i
                                        class="fa fa-upload upload-icon" aria-hidden="true"></i>Seleccionar archivo</button>
                                <span class="title-img form-control">Archivo no seleccionado</span>
                            </div>
                            {!! Form::file('logoEmpresa', ['class' => 'logoEmpresa']) !!}
                @if ($errors->has('logoEmpresa'))
                    <div class="alert alert-danger">
                        {{ $errors->first('logoEmpresa') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-2">
        <div class="field-item">
            @if (!empty($logo))
            <figure class="image" style="margin: 1em auto; /*max-height: 128px;*/ max-width: 128px; filter: brightness(1.1); mix-blend-mode: multiply;">
              <img src="{{$logo}}">
            </figure>
          @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="field-item">
            <label for="" class="field-label">Descripción</label>
            <div class="field-wrap">
                <textarea name="inputDescripcion" value=""
                    type="text" class="input-bordered">{{ old('inputDescripcion') ?? $Empresa->descripcion }}</textarea>
                @if ($errors->has('inputDescripcion'))
                    {{ $errors->first('inputDescripcion') }}
                @endif
            </div>
        </div>
    </div>
  
</div>

<h3 class="text-black">Información General</h3>
<div class="row">
    <div class="col-lg-12 ">
        <div class="field-item">
            <label for="" class="field-label">Dirección  <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <input name="inputDireccion" value="{{ old('inputDireccion') ?? $Empresa->direccion }}" class="input-bordered"
                    type="text" required>
                @if ($errors->has('inputDireccion'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputDireccion') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
   
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="field-item">
            <label for="" class="field-label">País <span style="color: red">*</span></label>
            <div class="field-wrap">
                <input type="text" name="inputPais" id="inputPais" value="{{ old('inputPais') ?? $Empresa->pais }}" class="input-bordered" required/>
                @if ($errors->has('inputPais'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputPais') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="field-item">
            <label for="" class="field-label">Estado <span style="color: red">*</span></label>
            <div class="field-wrap">
                <input type="text" name="inputEstado" id="inputEstado" value="{{ old('inputEstado') ?? $Empresa->estado }}"  class="input-bordered" required/>
                @if ($errors->has('inputEstado'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputEstado') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="field-item">
            <label for="" class="field-label">Ciudad <span style="color: red">*</span></label>
            <div class="field-wrap">
                <input type="text" name="inputCiudad" id="inputCiudad" value="{{ old('inputCiudad') ?? $Empresa->ciudad }}" class="input-bordered" required/>
                @if ($errors->has('inputCiudad'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputCiudad') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="field-item">
            <label for="" class="field-label">Código Postal <span style="color: red">*</span></label>
            <div class="field-wrap">
                <input type="text" name="inputCP" id="inputCP" value="{{ old('inputCP') ?? $Empresa->codigoPostal }}" class="input-bordered" required/>
                @if ($errors->has('inputCP'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputCP') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="field-item">
            <label for="" class="field-label">Colonia <span style="color: red">*</span></label>
            <div class="field-wrap">
                <input type="text" name="inputColonia" id="inputColonia" value="{{ old('inputColonia') ?? $Empresa->colonia }}" class="input-bordered" required/>
                @if ($errors->has('inputColonia'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputColonia') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="field-item">
            <label for="" class="field-label">Teléfono 1 </label>
            <div class="field-wrap">
                <input type="text" name="inputTelefono1" id="inputTelefono1" value="{{ old('inputTelefono1') ?? $Empresa->telefono1 }}" class="input-bordered cellphone"/>
                @if ($errors->has('inputTelefono1'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputTelefono1') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="field-item">
            <label for="" class="field-label">Teléfono 2 </label>
            <div class="field-wrap">
                <input type="text" name="inputTelefono2" id="inputTelefono2" value="{{ old('inputTelefono2') ?? $Empresa->telefono2 }}" class="input-bordered cellphone"/>
                @if ($errors->has('inputTelefono2'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputTelefono2') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="field-item">
            <label for="" class="field-label">Correo <span style="color: red">*</span></label>
            <div class="field-wrap">
                <input type="text" name="inputCorreo" id="inputCorreo" value="{{ old('inputCorreo') ?? $Empresa->correoElectronico }}" class="input-bordered" placeholder="@" required/>
                @if ($errors->has('inputCorreo'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputCorreo') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<h3 class="text-black">Datos Fiscales</h3>
<div class="row">
    <div class="col-lg-5">
        <div class="field-item">
            <label for="" class="field-label">RFC <span style="color: red">*</span></label>
            <div class="field-wrap">
                <input type="text" name="inputRFC" id="inputRFC" value="{{ old('inputRFC') ?? $Empresa->RFC }}" class="input-bordered" required/>
                @if ($errors->has('inputRFC'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputRFC') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="field-item">
            <label for="" class="field-label">Regimen Fiscal <span style="color: red">*</span></label>
            <div class="field-wrap">
                <input type="text" name="inputRegimen" id="inputRegimen" value="{{ old('inputRegimen') ?? $Empresa->regimenFiscal }}" class="input-bordered" required/>
                @if ($errors->has('inputRegimen'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputRegimen') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-5">
        <div class="field-item">
            <label for="" class="field-label">Registro Patronal </label>
            <div class="field-wrap">
                <input type="text" name="inputRegistro" id="inputRegistro" value="{{ old('inputRegistro') ?? $Empresa->registroPatronal }}" class="input-bordered"/>
                @if ($errors->has('inputRegistro'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputRegistro') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="field-item">
            <label for="" class="field-label">Representante </label>
            <div class="field-wrap">
                <input type="text" name="inputRepresentante" id="inputRepresentante" value="{{ old('inputRepresentante') ?? $Empresa->representante }}" class="input-bordered"/>
                @if ($errors->has('inputRepresentante'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputRepresentante') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- <div class="row">
    <div class="col-lg-6">
        <div class="field-item">
            <label for="" class="field-label">Ruta Llave (Visible desde servidor SQL) </label>
            <div class="field-wrap">
                {!! Form::file('certificadoKey', ['class' => 'certificados-sat', 'id' => 'key']) !!}
                            <div class="ruta-certificado">
                                {!! Form::text('key', isset($Empresa) ? $Empresa->rutaLlave : null, ['class' => 'form-control', 'id' => 'certificate-key', 'disabled']) !!}
                                <button id="btn-certicate-key" class="btn btn-primary btn-cerificados"><i
                                        class="fa fa-upload upload-icon" aria-hidden="true"></i></button>
                            </div>
                @if ($errors->has('certificadoKey'))
                    <div class="alert alert-danger">
                        {{ $errors->first('certificadoKey') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="field-item">
            <label for="" class="field-label">Ruta Certificado (Visible desde servidor SQL) </label>
            <div class="field-wrap">
                {!! Form::file('certificadoCer', ['class' => 'certificados-sat', 'id' => 'cer']) !!}
                
                            <div class="ruta-certificado">
                                {!! Form::text('cer', isset($Empresa) ? $Empresa->rutaCertificado : null, ['class' => 'form-control', 'id' => 'certificate-cer', 'disabled']) !!}
                                <button id="btn-certicate-cer" class="btn btn-primary btn-cerificados"><i
                                        class="fa fa-upload upload-icon" aria-hidden="true"></i></button>
                            </div>
                @if ($errors->has('certificadoCer'))
                    <div class="alert alert-danger">
                        {{ $errors->first('certificadoCer') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        {!! Form::label('passwordKey', 'Contraseña SAT', ['class' => 'field-label']) !!}
        <input id="passwordKey" type="password" class="form-control" name="passwordKey" @if(!empty($Empresa->passwordSat)) disabled @endif/>
        <input id="passwordKey2" type="hidden" class="form-control" name="passwordKey2" value="{{ isset($Empresa) && !empty($Empresa->passwordSat) ? \Crypt::decrypt($Empresa->passwordSat) : ''}}" />

    </div>

    @if(!empty($Empresa->passwordSat))
    <div class="col-md-6">
        <div class="form-group"
            style="display: flex; flex-direction: column; justify-content: start; align-items: start">
            {!! Form::label('changePassword', 'Cambiar contraseña SAT', ['class' => 'negrita', 'id' => 'labelContraseña']) !!}
            <input type="checkbox" id="changePassword" name="changePassword">
        </div>
    </div>
    @endif


</div> --}}

<h3 class="text-black">Ruta Documentos</h3>

<div class="row">
    <div class="col-lg-12">
        <div class="field-item">
            <label for="inputDocumentos" class="field-label">Ruta Documentos <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <input type="text" name="inputDocumentos" id="inputDocumentos"  value="{{ old('inputDocumentos') ?? $Empresa->rutaDocumentos }}" class="input-bordered" required/>
                @if ($errors->has('inputDocumentos'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputDocumentos') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>