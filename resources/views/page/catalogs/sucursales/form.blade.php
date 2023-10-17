<div class="col-lg-12">
    <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
</div>
<h3 class="text-black">Datos generales</h3>
<div class="row">
    <div class="col-lg-4 ">
        <div class="field-item">
            <label for="" class="field-label">Clave  <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <input name="inputClave" value="{{ old('inputClave') ?? $Sucursal->clave }}" class="input-bordered"
                    type="text" required @if($Sucursal->clave != '') disabled @endif>
                @if ($errors->has('inputClave'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputClave') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="fiel-item">
            <label for="" class="field-label">Nombre  <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <input name="inputName" value="{{ old('inputName') ?? $Sucursal->nombre }}" class="input-bordered" type="text" required>
                @if ($errors->has('inputName'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputName') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-2">
        <div class="field-item">
            <label for="" class="field-label">Estatus</label>
            <div class="field-wrap">
                <select value="{{ old('selectEstatus') ?? $Sucursal->estatus }}" id="selectEstatus" name="selectEstatus" class=""
                    data-select2-theme="bordered">
                    <option value="1" @if(old('selectEstatus',$Sucursal->estatus)=='1') selected @endif>Alta</option>
                    <option value="0" @if(old('selectEstatus',$Sucursal->estatus)=='0') selected @endif>Baja</option>
                </select>
                @if ($errors->has('selectEstatus'))
                    <div class="alert alert-danger">
                        {{ $errors->first('selectEstatus') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<h3 class="text-black">Información</h3>


<div class="row">
    <div class="col-lg-12 ">
        <div class="field-item">
            <label for="" class="field-label">Dirección  <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <input name="inputDireccion" value="{{ old('inputDireccion') ?? $Sucursal->direccion }}" class="input-bordered"
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
                <input type="text" name="inputPais" id="inputPais" value="{{ old('inputDireccion') ?? $Sucursal->pais }}" class="input-bordered" required/>
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
                <input type="text" name="inputEstado" id="inputEstado" value="{{ old('inputDireccion') ?? $Sucursal->estado }}"  class="input-bordered" required/>
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
                <input type="text" name="inputCiudad" id="inputCiudad" value="{{ old('inputDireccion') ?? $Sucursal->ciudad }}" class="input-bordered" required/>
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
                <input type="text" name="inputCP" id="inputCP" value="{{ old('inputDireccion') ?? $Sucursal->codigoPostal }}" class="input-bordered" required/>
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
                <input type="text" name="inputColonia" id="inputColonia" value="{{ old('inputDireccion') ?? $Sucursal->colonia }}" class="input-bordered" required/>
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
    <div class="col-lg-12 ">
        <div class="field-item">
            <label for="" class="field-label">Empresa  <span style="color: red">*</span> </label>
            {!! Form::select('empresa', $Empresas, $Sucursal->idEmpresa, [
                'id' => 'select-empresa',
                'class' => '',
                'placeholder' => 'Seleccione uno...',
            ]) !!}
            <div class="field-wrap">
              
                @if ($errors->has('empresa'))
                    <div class="alert alert-danger">
                        {{ $errors->first('empresa') }}
                    </div>
                @endif
            </div>
        </div>
    </div>   
</div>
