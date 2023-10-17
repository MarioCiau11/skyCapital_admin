<div class="row">
    <div class="col-lg-12">
        <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
    </div>
    <div class="col-lg-6 ">
        <div class="field-item">
            <label for="" class="field-label">Clave @if($Monedas->clave == null)  <span style="color: red">*</span> @endif </label>
            <div class="field-wrap">
                @if ($Monedas->clave != null)
                    <input type="hidden" name="inputClave" value="{{$Monedas->clave}}">
                @endif
                <input name="inputClave" value="{{ old('inputClave') ?? $Monedas->clave }}" class="input-bordered"
                @if($Monedas->clave != null) disabled @endif   type="text" required autofocus>
                @if ($errors->has('inputClave'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputClave') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6 ">
        <div class="fiel-item">
            <label for="" class="field-label">Nombre  <span style="color: red">*</span> </label>
            <div class="fiel-wrap">
                <input name="inputName" value="{{ old('inputName') ?? $Monedas->nombre }}" class="input-bordered" type="text" required autofocus>
                @if ($errors->has('inputName'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputName') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- <div class="col-lg-4">
        <div class="field-item">
            <label for="" class="field-label">Clave SAT  <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <select name="claveSat" value="" id="selectClavesat" name="" class=""
                    data-select2-theme="bordered" required>
                    <option value=""> Seleccione una opcion</option>
                    @foreach ($c_monedas as $clave)
                        <option value="{{ $clave->c_Moneda }}" @if (old('claveSat', $Monedas->claveSat) == $clave->c_Moneda) selected @endif>
                            {{ $clave->descripcion }}</option>
                    @endforeach
                </select>
                @if ($errors->has('claveSat'))
                    <div class="alert alert-danger">
                        {{ $errors->first('claveSat') }}
                    </div>
                @endif
            </div>
        </div>
    </div> --}}
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="field-item">
            <label for="" class="field-label">Tipo de Cambio  <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <input name="inputTipo" id="inputTipo" value="{{ old('inputTipo') ?? $Monedas->tipoCambio }}" type="text"
                    class="input-bordered currency" required autofocus>
                @if ($errors->has('inputTipo'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputTipo') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-10">
        <div class="field-item">
            <label for="" class="field-label">Descripción</label>
            <div class="field-wrap">
                <input name="inputDescripcion" value="{{ old('inputDescripcion') ?? $Monedas->descripcion }}"
                    type="text" class="input-bordered input-textarea"></input>
                @if ($errors->has('inputDescripcion'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputDescripcion') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="field-item">
            <label for="" class="field-label">Estatus</label>
            <div class="field-wrap">
                <select value="" id="selectEstatus" name="selectEstatus" class=""
                    data-select2-theme="bordered">
                    <option value="">Selecciona una opción</option>
                    <option value="1" @if (old('selectEstatus', $Monedas->estatus) == 1 || old('selectEstatus', $Monedas->estatus) === null) selected @endif>Alta</option>
                    <option value="0" @if (old('selectEstatus', $Monedas->estatus) == 0 && old('selectEstatus', $Monedas->estatus) !== null) selected @endif>Baja</option>
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
