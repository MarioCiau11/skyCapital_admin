<div class="col-lg-12">
    <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
</div>
<h3 class="text-black">Datos generales</h3>
<div class="row">
    <div class="col-lg-8 ">
        <div class="field-item">
            <label for="" class="field-label">Clave  <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <input name="inputClave" value="{{ old('inputClave', $Promocion->clave) ?? $Promocion->getNextID() }}" class="input-bordered"
                    type="text" required readonly>
                @if ($errors->has('inputClave'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputClave') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="field-item">
            <label for="" class="field-label">Estatus</label>
            <div class="field-wrap">
                <select value="{{ old('selectEstatus') ?? $Promocion->estatus }}" id="selectEstatus" name="selectEstatus" class=""
                    data-select2-theme="bordered">
                    <option value="1" @if(old('selectEstatus',$Promocion->estatus)=='1') selected @endif>Alta</option>
                    <option value="0" @if(old('selectEstatus',$Promocion->estatus)=='0') selected @endif>Baja</option>
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
<div class="row">
    <div class="col-lg-12">
        <div class="fiel-item">
            <label for="" class="field-label">Nombre  <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <input name="inputName" value="{{ old('inputName') ?? $Promocion->nombre }}" class="input-bordered" type="text" required>
                @if ($errors->has('inputName'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputName') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<br>