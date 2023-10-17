<div class="row">
    <div class="col-lg-12">
        <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
    </div>
    <div class="col-lg-3">
        <div class="field-item">
            <label for="inputClave" class="field-label">Clave <span style="color: red">*</span></label>
            <dif class="field-wrap">
                {!! Form::text('inputClave', old('inputClave',$CuentasDinero->clave), ['class' => 'input-bordered','id' => 'inputClave','required']) !!}
                @if ($errors->has('inputClave'))
                    <div class="alert alert-danger">
                        {{$errors->first('inputClave')}}
                    </div>
                @endif
            </dif>
        </div>
        
    </div>
</div>
<div class="row">
    <div class="col-lg-10">
        <div class="field-item">
            <label for="selectBanco" class="field-label">Banco <span style="color: red">*</span></label>
            <div class="field-wrap">
                {!! Form::select('selectBanco', $banco, 
                old('selectBanco') ?? $CuentasDinero->idInstitucionf, 
                ['required','placeholder' => 'Seleccione un Banco','id'=>'selectBanco']) !!}
                @if ($errors->has('selectBanco'))
                    <div class="alert alert-danger">
                        {{$errors->first('selectBanco')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="field-item">
            <label for="inputNoCuenta" class="field-label">Número de Cuenta <span style="color: red">*</span></label>
            <div class="field-wrap">
                {!! Form::text('inputNoCuenta', old('inputNoCuenta',$CuentasDinero->noCuenta), ['class' => 'input-bordered','id' => 'inputNoCuenta','required']) !!}
                @if ($errors->has('inputNoCuenta'))
                    <div class="alert alert-danger">
                        {{$errors->first('inputNoCuenta')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="field-item">
            <label for="inputCuentaClave" class="field-label">Cuenta Clave <span style="color: red">*</span></label>
            <div class="field-wrap">
                {!! Form::text('inputCuentaClave', old('inputCuentaClave',$CuentasDinero->cuentaClave), ['class' => 'input-bordered','id'=>'inputCuentaClave','required']) !!}
                @if ($errors->has('inputCuentaClave'))
                    <di class="alert alert-danger">
                        {{$errors->first('inputCuentaClave')}}
                    </di>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="field-item">
            <label for="inputReferencia" class="field-label">Referencia del Banco <span style="color: red">*</span></label>
            <div class="fiel-wrap">
                {!! Form::text('inputReferencia', old('inputReferencia',$CuentasDinero->referenciaBanco), ['class' => 'input-bordered','id' => 'inputReferencia','required']) !!}
                @if ($errors->has('inputReferencia'))
                    {{$errors->first('inputReferencia')}}
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">  
        <div class="fiel-item">
            <label for="inputConvenio" class="field-label">Convenio del Banco <span style="color: red">*</span></label>
            <div class="field-wrap">
                {!! Form::text('inputConvenio', old('inputConvenio',$CuentasDinero->convenioBanco), ['class' => 'input-bordered', 'id' => 'inputConvenio','required']) !!}
                @if ($errors->has('inputConvenio'))
                    <div class="alert alert-danger">
                        {{$erros->first('inputConvenio')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="fiel-item">
            <label for="selectTipo" class="field-label">Tipo de Cuenta <span style="color: red">*</span></label>
            <div class="field-wrap">
                {!! Form::select('selectTipo', ['Caja' => 'Caja', 'Banco' => 'Banco'],
                old('selectTipo') ?? $CuentasDinero->tipoCuenta , 
                ['id' => 'selectTipo' ,'placeholder' => 'Seleccione un Tipo de cuenta','required']) !!}
                @if ($errors->has('selectTipo'))
                    <div class="alert alert-danger">
                        {{$errors->first('selectTipo')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="fiel-item">
            <label for="selectMoneda" class="field-label">Moneda <span style="color: red">*</span></label>
            <div class="field-wrap">
                {!! Form::select('selectMoneda', $monedas, 
                old('selectMoneda') ?? $CuentasDinero->idMoneda, 
                ['id' => 'selectMoneda','placeholder' => 'Seleccione una Moneda','required']) !!}
                @if ($errors->has('selectMoneda'))
                    <div class="alert alert-danger">
                        {{$errors->first('selectMoneda')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="field-item">
            <label for="selectEmpresa" class="field-label">Empresa <span style="color: red">*</span></label>
            <div class="field-wrap">
                {!! Form::select('selectEmpresa', $empresas,
                old('selectEmpresa') ?? $CuentasDinero->idEmpresa, 
                ['id' =>'selectEmpresa','placeholder' => 'Seleccione una Empresa','required']) !!}
                @if ($errors->has('selectEmpresa'))
                    <div class="alert aler-danger">
                        {{$errors->first('selectEmpresa')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="field-item">
            <label for="selectEstatus" class="field-label">Estatus <span style="color: red">*</span></label>
            <div class="field-wrap">
                {!! Form::select('selectEstatus', [1 => 'Alta', 0 => 'Baja'], old('selectEstatus', $CuentasDinero->estatus ?? 1), [
                'id' => 'selectEstatus','required'
                ]) !!}
            @if ($errors->has('selectEstatus'))
                <div class="aler alert-danger">
                    {{ $errors->first('selectEstatus') }}
                </div>
            @endif
            </div>
        </div>
    </div>
</div>