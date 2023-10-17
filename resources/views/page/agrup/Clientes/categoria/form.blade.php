<div class="row">
    <div class="col-lg-10">
        <div class="field-item">
            {{-- {!! Form::label('', 'Nombre', ['class'=>'field-label']) !!} --}}
            {!! Html::decode(Form::label('', 'Nombre <span style="color: red">*</span>', ['class'=>'field-label'])) !!}
            <div class="field-wrap">
                {!! Form::text('inputName', old('inputName') ?? $Categoria->nombre, ['class' => 'input-bordered','required']) !!}
                @if ($errors->has('inputName'))
                    {{$errors->first('inputName')}}
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="field-item">
            {!! Form::label('', 'Estatus', ['class'=> 'field-label']) !!}
            <div class="field-wrap">
                {!! Form::select('selectEstatus', [ 1 => 'Alta', 0 => 'Baja' ],
                old('selectEstatus', $Categoria->estatus ?? 1), 
                ['id' => 'selectEstatus']) !!}
                @if ($errors->has('selectEstatus'))
                    <div class="aler alert-danger">
                        {{$errors->first('selectEstatus')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>