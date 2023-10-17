
<?php
//dd($condicionesCred,$errors);
?>
<div class="row">
    <div class="col-lg-12">
        <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
    </div>
    <div class="col-md-8">
        <div class="field-item">
            <label for="" class="field-label">Nombre <span style="color: red">*</span></label>
            <div class="field-wrap">
                <input value="{{old('name') ?? $condicionesCred->nombreCondicion}}" name="name" class="input-bordered" type="text" required 
                @if ($condicionesCred->nombreCondicion != null)
                    disabled
                @endif
                >
                @if ($condicionesCred->nombreCondicion != null)
                <input value="{{$condicionesCred->nombreCondicion}}" name="name" class="input-bordered" type="hidden">
                @endif
                @if ($errors->has('name'))
                <div class="alert aler-danger">
                    {{$errors->first('name')}}
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="field-item">
            <label for="" class="field-label">Tipo de Condición <span style="color: red">*</span></label>
            <div class="field-wrap">
                <select id="selectTipo_condicion"  name="selectTipo" class="" data-select2-theme="bordered" required>
                    <option value="">Seleccione una Opción</option>
                    <option value="Crédito"  >Crédito</option>
                    <option value="Contado" >Contado</option>
                </select>
            </div>
            @if($errors->has('selectTipo'))
            <div class="alert alert-danger">
                {{$errors->first('selectTipo')}}
            </div>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="field-item">
            <label for="" id="" class="field-label">Días Vencimiento <span class="spanlab" hidden style=" color : red;">*</span></label>
            <div class="field-wrap">
                <input value="{{old('diasVenci') ?? $condicionesCred->diasVencimiento}}" id="diasVenci" name="diasVenci" type="text" class="input-bordered">
            </div>
            @if($errors->has('diasVenci'))
            <div class="alert alert-danger">
                {{$errors->first('diasVenci')}}
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="field-item">
            <label for="" id="labTipod"  class="field-label">Tipo Días <span class="spanlab" hidden style=" color : red;">*</span></label>
            <div class="field-wrap">
                <select value=""  id="selectTipo_dias" name="selectTipo_dias" class="" data-select2-theme="bordered" >
                    <option value="">Seleccione una Opción</option>
                    <option value="Hábiles" @if(old('selectTipo_dias',$condicionesCred->tipoDias)=='Hábiles') selected @endif>Hábiles</option>
                    <option value="Naturales" @if(old('selectTipo_dias',$condicionesCred->tipoDias)=='Naturales') selected @endif>Naturales</option>
                </select>
            </div>
            @if($errors->has('selectTipo_dias'))
            <div class="alert alert-danger">
                {{$errors->first('selectTipo_dias')}}
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="field-item">
            <label for="" class="field-label">Días Hábiles <span class="spanlab" hidden style="color: red">*</span></label>
            <div class="field-wrap">
                <select  value="" id="selectDias_habil" name="selectDias_habil" class="selectDias_habil" data-select2-theme="bordered" >
                    <option value="">Seleccione una Opción</option>
                    <option value="Lun-Vie" @if(old('selectDias_habil',$condicionesCred->diasHabiles)=='Lun-Vie')selected @endif>Lun-vie</option>
                    <option value="Lun-Sab" @if(old('selectDias_habil',$condicionesCred->diasHabiles)=='Lun-Sab')selected @endif>Lun-Sab</option>
                    <option value="Todos" @if(old('selectDias_habil',$condicionesCred->diasHabiles)=='Todos')selected @endif>Todos</option>
                </select>
                @if($errors->has('selectDias_habil'))
                <div class="alert alert-danger">
                    {{$errors->first('selectDias_habil')}}
                </div>
                @endif
            </div>
        </div>
    </div>
    {{-- <div class="col-md-4">
        <div class="field-item">
            <label for="" class="field-label">Método de pago</label>
            <select value=""id="selectMetodo" name="selectMetodo" class="" data-select2-theme="bordered" >
                <option value="">Selecciona una opción</option>
                @foreach ($c_MetodoPago as $metodo)
                    <option value="{{$metodo->c_MetodoPago}}" @if(old('selectMetodo',$condicionesCred->metodoPago) == $metodo->c_MetodoPago ) selected @endif >{{$metodo->descripcion}}</option>
                @endforeach
            </select>
        </div>
    </div> --}}
    <div class="col-md-2">
        <div class="field-item">
            <div class="field-wrap">
                <label for="" class="field-label">Estatus</label>
                <select value="" id="selectEstatus" name="selectEstatus" class="" data-select2-theme="bordered" required>
                    <option value="">Selecciona una opción</option>
                    <option value="1" @if (old('selectEstatus', $condicionesCred->estatus) == 1 || old('selectEstatus', $condicionesCred->estatus) === null) selected @endif>Alta</option>
                    <option value="0" @if (old('selectEstatus', $condicionesCred->estatus) == 0 && old('selectEstatus', $condicionesCred->estatus) !== null) selected @endif>Baja</option>
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
<script>
$(document).ready(function(){
    console.log('{{$condicionesCred->tipoCondicion}}');
    $("#selectTipo_condicion").val('{{ isset($condicionesCred) ? $condicionesCred->tipoCondicion : '' }}').trigger('change');
});
</script>





