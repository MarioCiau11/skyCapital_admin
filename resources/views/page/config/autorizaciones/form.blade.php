<div class="row">
    <div class="col-lg-12">
        <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
    </div>
    <div class="col-lg-4 ">
        <div class="field-item">
            <label for="" class="field-label">Clave  <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <input name="inputClave" value="" class="input-bordered"
                    type="text" required>
                @if ($errors->has('inputClave'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputClave') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4 ">
        <div class="fiel-item">
            <label for="" class="field-label">Nombre  <span style="color: red">*</span> </label>
            <div class="fiel-wrap">
                <input name="inputName" value="" class="input-bordered" type="text" required>
                @if ($errors->has('inputName'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputName') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="field-item">
            <label for="" class="field-label">Clave SAT  <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <select name="claveSat" value="" id="selectClavesat" name="" class=""
                    data-select2-theme="bordered" required>
                    <option value=""> Seleccione una opcion</option>
                  
                </select>
                @if ($errors->has('claveSat'))
                    <div class="alert alert-danger">
                        {{ $errors->first('claveSat') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="field-item">
            <label for="" class="field-label">Tipo de cambio  <span style="color: red">*</span> </label>
            <div class="field-wrap">
                <input name="inputTipo" value="" type="text"
                    class="input-bordered" required>
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
                <input name="inputDescripcion" value=""
                    type="text" class="input-bordered input-textarea"></input>
                @if ($errors->has('inputDescripcion'))
                    {{ $errors->first('inputDescripcion') }}
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
                    <option value="1" >Alta</option>
                    <option value="0" >Baja</option>
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
