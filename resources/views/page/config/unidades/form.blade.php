<div class="row">
    <div class="col-lg-12">
        <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
    </div>
    <div class="col-lg-6 ">
        <div class="field-item">
            <label for="" class="field-label">Unidad  <span style="color: red">*</span></label>
            <div class="field-wrap">
                <input value="{{old('inputUnidad') ?? $Unidades->unidad}}" name="inputUnidad" class="input-bordered" type="text" required @if( $Unidades->unidad != '') readonly @endif>
                @if (($errors->has('inputUnidad')))
                    <div class="alert alert-danger">
                        {{$errors->first('inputUnidad')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="field-item">
            <label for="" class="field-label">Decimal Válida  <span style="color: red">*</span></label>
            <div class="field-wrap">
                <input type="text" value="{{old('inputDecimal') ?? $Unidades->decimalValida}}" name="inputDecimal" class="input-bordered" required>
                @if ($errors->has('inputDecimal'))
                    <div class="alert alert-danger">
                        {{$errors->first('inputDecimal')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- <div class="col-lg-4">
        <div class="field-item">
            <label for="" class="field-label">Clave SAT</label>
            <div class="field-wrap">
                <select value="" id="selectClave" name="selectClave" class="" data-select2-theme="bordered">
                    <option value="">Selecciones una clave</option>
                    @foreach ($c_ClaveUnidad as $item)
                        <option value="{{$item->c_ClaveUnidad}}" @if(old('selectClave',$Unidades->claveSat) == $item->c_ClaveUnidad)selected @endif >{{$item->nombre}} </option>
                    @endforeach
                </select>
                @if ($errors->has('selectClave'))
                    <div class="alert alert-danger">
                        {{$errors->first('selectClave')}}
                    </div>
                @endif
            </div>
        </div>
    </div> --}}
</div>
<div class="row">
    <div class="col-lg-3">
        <div class="field-item">
            <div class="field-wrap">
                <label for="" class="field-label">Estatus</label>
                <select value="" id="selectEstatus" name="selectEstatus" class="" data-select2-theme="bordered" >
                    <option value="">Selecciona una opción</option>
                    <option value="1" @if (old('selectEstatus', $Unidades->estatus) == 1 || old('selectEstatus', $Unidades->estatus) === null) selected @endif>Alta</option>
                    <option value="0" @if (old('selectEstatus', $Unidades->estatus) == 0 && old('selectEstatus', $Unidades->estatus) !== null) selected @endif>Baja</option>
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
