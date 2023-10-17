<div class="row">
    <div class="col-lg-12">
        <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
    </div>
    <div class="col-lg-4">
        <div class="field-item">
            {!! Form::label('inputClave', 'Clave', ['class' => 'field-label']) . '<span style = "color : red;">*</span>' !!}
            <div class="field-wrap">
                {!! Form::text('inputClave', old('inputClave', $Articulos->clave) ?? $nextId, [
                    'class' => 'input-bordered',
                    'id' => 'inputClave',
                    'disabled' => $Articulos->clave != '' ? true : false,
                ]) !!}
                @if ($Articulos->clave != null)
                    {!! Form::hidden('inputClave', $Articulos->clave, []) !!}
                @endif
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
            {!! Form::label('selectTipo', 'Tipo', ['class' => 'field-label']) . '<span style = "color : red;">*</span>' !!}
            <div class="field-wrap">
                {!! Form::select('selectTipo', ['Servicio' => 'Servicio'], old('selectTipo') ?? $Articulos->tipo, [
                    'id' => 'selectTipo',
                    'required',
                ]) !!}
                @if ($errors->has('selectTipo'))
                    <div class="alert alert-danger">
                        {{ $errors->first('selectTipo') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="field-item">
            {!! Form::label('selectEstatus', 'Estatus', ['class' => 'field-label']) !!}
            <div class="field-wrap">
                {!! Form::select('selectEstatus', [1 => 'Alta', 0 => 'Baja'], old('selectEstatus', $Articulos->estatus ?? 1), [
                    'id' => 'selectEstatus',
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
<div class="row">
    <div class="col-8">
        <div class="field-item">
            {!! Form::label('inputDescripcion', 'Descripción', ['class' => 'field-label']) !!}
            <div class="field-wrap">
                {!! Form::text('inputDescripcion', old('inputDescripcion') ?? $Articulos->descripcion, [
                    'id' => 'inputDescripcion',
                    'class' => 'input-bordered',
                ]) !!}
                @if ($errors->has('inputDescripcion'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputDescripcion') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="field-item">
            {!! Form::label('selectUnidad', 'Unidad Venta', ['class' => 'field-label']) .
                '<span style = "color : red;">*</span>' !!}
            <div class="field-wrap">
                {!! Form::select('selectUnidad', $Unidades, old('selectUnidad') ?? $Articulos->unidadVenta, [
                    'id' => 'selectUnidad',
                    'required',
                ]) !!}
                @if ($errors->has('selectUnidad'))
                    <div class="alert alert-danger">
                        {{ $errors->first('selectUnidad') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="field-item">
            {!! Form::label('selectCategoria', 'Categoría', ['class' => 'field-label']) !!}
            <div class="field-wrap">
                {!! Form::select(
                    'selectCategoria',
                    [null => 'Seleccione una Categoría'] + $categoria,
                    old('selectCategoria') ?? $Articulos->categoria,
                    ['id' => 'selectCategoria'],
                ) !!}
                @if ($errors->has('selectCategoria'))
                    <div class="aler alert-danger">
                        {{ $errors->first('selectCategoria') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="field-item">
            {!! Form::label('selectGrupo', 'Grupo', ['class' => 'field-label']) !!}
            <div class="field-wrap">
                {!! Form::select(
                    'selectGrupo',
                    [null => 'Seleccione un Grupo'] + $grupo,
                    old('selectGrupo') ?? $Articulos->grupo,
                    ['id' => 'selectGrupo'],
                ) !!}
                @if ($errors->has('selectGrupo'))
                    <div class="aler alert-danger">
                        {{ $errors->first('selectGrupo') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="field-item">
            {!! Form::label('inputIVA', '% IVA', ['class' => 'field-label']) . '<span style = "color : red;">*</span>' !!}
            <div class="field-wrap">
                {!! Form::text('inputIVA', old('inputIVA', '16') ?? $Articulos->IVA, [
                    'class' => 'form-control input-bordered percentage',
                    'id' => 'inputIVA',
                    'required',
                    'placeholder' => '0 %'
                ]) !!}
                @if ($errors->has('inputIVA'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputIVA') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<h3 class="title title-md">Precios</h3>
<div class="row">
    <div class="col-lg-8">
        <div class="field-item">
            {!! Form::label('inputPrecio', 'Lista1 /Precio Lista', ['class' => 'field-label']) .
                '<span style = "color : red;">*</span>' !!}
            <div class="field-wrap">
                {!! Form::text('inputPrecio', old('inputPrecio') ?? $Articulos->precio, [
                    'class' => 'input-bordered currency',
                    'id' => 'inputPrecio',
                    'required',
                ]) !!}
                @if ($errors->has('inputPrecio'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputPrecio') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
