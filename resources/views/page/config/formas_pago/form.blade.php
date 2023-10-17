<div class="row mt-2">
    <div class="col-lg-12">
        <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
    </div>
    <div class="col-lg-6 ">
        <div class="field-item">
            <label for="inputClave" class="field-label">Clave <span style="color: red">*</span></label>
            <dif class="field-wrap">
                {!! Form::text('inputClave', old('inputClave',$formasPago->clave), ['class' => 'input-bordered','id' => 'inputClave','required']) !!}
                @if ($errors->has('inputClave'))
                    <div class="alert alert-danger">
                        {{$errors->first('inputClave')}}
                    </div>
                @endif
            </dif>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="field-item">
            <label for="inputNombre" class="field-label">Nombre <span style="color: red">*</span></label>
            <dif class="field-wrap">
                {!! Form::text('inputNombre', old('inputNombre',$formasPago->nombre), ['class' => 'input-bordered','id' => 'inputNombre','required']) !!}
                @if ($errors->has('inputNombre'))
                    <div class="alert alert-danger">
                        {{$errors->first('inputNombre')}}
                    </div>
                @endif
            </dif>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12">
        <div class="field-item">
            <label for="inputDescripcion" class="field-label">Descripción </label>
            <dif class="field-wrap">
                {!! Form::text('inputDescripcion', old('inputDescripcion',$formasPago->descripcion), ['class' => 'input-bordered','id' => 'inputDescripcion']) !!}
                @if ($errors->has('inputDescripcion'))
                    <div class="alert alert-danger">
                        {{$errors->first('inputDescripcion')}}
                    </div>
                @endif
            </dif>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-lg-8">
        <div class="field-item">
            <label for="" class="field-label">Moneda  <span style="color: red">*</span> </label>
            <div class="field-wrap">
                {!! Form::select('selectMoneda', $monedas, 
                old('selectMoneda') ?? $formasPago->monedaSat, 
                ['id' => 'selectMoneda','placeholder' => 'Seleccione una moneda','required']) !!}
                @if ($errors->has('selectMoneda'))
                    <div class="alert alert-danger">
                        {{$errors->first('selectMoneda')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="field-item">
            <label for="" class="field-label">Estatus</label>
            <div class="field-wrap">
                {!! Form::select('selectEstatus', [1 => 'Alta', 0 => 'Baja'], old('selectEstatus', $formasPago->estatus ?? 1), [
                'id' => 'selectEstatus','required'
                ]) !!}
                @if ($errors->has('selectEstatus'))
                <div class="alert alert-danger">
                    {{ $errors->first('selectEstatus') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
