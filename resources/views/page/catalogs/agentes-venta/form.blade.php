<div class="row">
    <div class="col-lg-12">
        <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
    </div>
    <div class="col-lg-10">
        <div class="field-item">
            {!! Form::label('', 'Clave', ['class' => 'field-label']).'<span style = "color : red;">*</span>' !!}
            <div class="field-wrap">
                {!! Form::text('inputClave', old('inputClave',$Agentes->clave) ?? $nextId , ['class' => 'input-bordered','required','disabled' => $Agentes->clave != '' ? true : false]) !!}
                @if ($Agentes->clave != null)
                    {!! Form::hidden('inputClave', $Agentes->clave, []) !!}
                @endif
                @if ($errors->has('inputClave'))
                    <div class="alert alert-danger">
                        {{$errors->first('inputClave')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="field-item">
            {!! Form::label('', 'Nombre', ['class' => 'field-label']).'<span style = "color : red;">*</span>' !!}
            <div class="field-wrap">
                {!! Form::text('inputName', old('inputName') ?? $Agentes->nombre, ['class' => 'input-bordered','required']) !!}
                @if ($errors->has('inputName'))
                    <div class="alert alert-danger">
                        {{$errors->first('inputName')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3">
        <div class="field-item">
            {!! Form::label('', 'Tipo', ['class' => 'field-label']).'<span style = "color : red;">*</span>' !!}
            <div class="field-wrap">
                {!! Form::select('selectTipo', ['vendedor' => 'Vendedor'],old('selectTipo') ?? ucfirst($Agentes->tipo),
                ['id' => 'selectTipo','required']) !!}
                @if ($errors->has('selectTipo'))
                    <div class="alert alert-danger">
                        {{$errors->first('selectTipo')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="field-item">
            {!! Form::label('', 'Categoría', ['class'=> 'field-label']) !!}
            <div class="field-wrap">
                {!! Form::select('selectCategoria', $Categorias, old('selectGrupo') ?? $Agentes->categoria, 
                ['id' => 'selectCategoria','placeholder' => 'Seleccione una Categoría']) !!}
                @if ($errors->has('selectCategoria'))
                    <div class="aler alert-danger">
                        {{$errors->first('selectCategoria')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-9">
        <div class="field-item">
            {!! Form::label('', 'Grupo', ['class'=> 'field-label']) !!}
            <div class="field-wrap">
                {!! Form::select('selectGrupo', $Grupos, old('selectGrupo') ?? $Agentes->grupo, 
                ['id' => 'selectGrupo','placeholder' => 'Seleccione un Grupo']) !!}
                @if ($errors->has('selectGrupo'))
                    <div class="aler alert-danger">
                        {{$errors->first('selectGrupo')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="field-item">
            {!! Form::label('', 'Estatus', ['class'=> 'field-label']) !!}
            <div class="field-wrap">
                {{-- {!! Form::select('selectEstatus', ['' => 'seleecione una opción',1 => 'Alta', 0 => 'Baja' ], old('selectEstatus') ?? $Agentes->estatus, ['id' => 'selectEstatus']) !!} --}}
                {!! Form::select('selectEstatus', [ 1 => 'Alta', 0 => 'Baja' ],
                old('selectEstatus', $Agentes->estatus ?? 1), 
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