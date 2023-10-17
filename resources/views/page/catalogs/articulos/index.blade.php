@extends('layouts.layout')
@section('content')
    <section class="bg-white">
        <div class="row">
            <div class="col-lg-6">
                <div class="nk-block-text">
                    <h2 class="title">Artículos</h2>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 contenedorBotones"> 
                <div class="item">
                    <a class="btn btn-dark btn-sm btn-with-icon btn-alt btn-outline toggle-tigger" href="#">
                        <span>Grupos</span>
                        <em class="icon fas fa-caret-down"></em>
                    </a>
                    <ul class="drop-list drop-list-full drop-list-center toggle-class toggle-drop">
                        <li><a href="{{ route('agrup.articulos.grupo.create') }}" class=""><i class="fa fa-check-circle" aria-hidden="true"></i> Crear</a></li>
                        <li><a href="{{ route('agrup.articulos.grupo.index') }}" class=""><i class="fa fa-eye" aria-hidden="true"></i> Ver</a></li>
                    </ul>
                </div>
    
                    
                <div class="item">
                    <a class="btn btn-dark btn-sm btn-with-icon btn-alt btn-outline toggle-tigger" href="#">
                        <span>Categorías</span>
                        <em class="icon fas fa-caret-down"></em>
                    </a>
                    <ul class="drop-list drop-list-full drop-list-center toggle-class toggle-drop">
                        <li><a href="{{route('agrup.articulos.categoria.create')}}" class=""><i class="fa fa-check-circle" aria-hidden="true"></i> Crear</a></li>
                        <li><a href="{{ route('agrup.articulos.categoria.index') }}" class=""><i class="fa fa-eye" aria-hidden="true"></i>Ver</a></li>
                    </ul>
                </div>
    
                <div class="item">
                        <a href="{{ route('cat.articulos.create') }}" class="btn btn-outline btn-sm btn-success ">
                        Crear
                        Artículo
                    </a>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-lg-12">
                {{ Breadcrumbs::render() }}
            </div>
        </div>

        {!! Form::open(['route' => 'cat.articulos.filtro', 'method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-4">
                {!! Form::label('inputName', 'Nombre', ['class' => 'field-label']) !!}
                {!! Form::text('inputName', session()->has('inputName') ? session()->get('inputName') : null, [
                    'class' => 'input-bordered',
                ]) !!}
            </div>
            <div class="col-lg-3">
                {!! Form::label('selectCategoria', 'Categoría', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectCategoria',
                    ['Todos' => 'Todos'] + $categoria,
                    session()->has('selectCategoria') ? session()->get('selectCategoria') : null,
                    ['id' => 'selectCategoria'],
                ) !!}
            </div>
            <div class="col-lg-3">
                {!! Form::label('selectGrupo', 'Grupo', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectGrupo',
                    ['Todos' => 'Todos'] + $grupo,
                    session()->has('selectGrupo') ? session()->get('selectGrupo') : null,
                    ['id' => 'selectGrupo'],
                ) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label('selectEstatus', 'Estatus', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectEstatus',
                    ['1' => 'Alta', '0' => 'Baja', 'Todos' => 'Todos'],
                    session()->has('selectEstatus') ? session()->get('selectEstatus') : 'Todos',
                    ['id' => 'selectEstatus', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-12 ">
                <div class="botonesM">
                <a href="{{ route('cat.articulos.index') }}" type="reset"
                    class="btn btn-outline btn-sm btn-light  btn-auto-2 float-left mt-3">Restablecer</a>
                {!! Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3', 'name' => 'action']) !!}
                {!! Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action']) !!}
                {!! Form::close() !!}
                </div>

                {{-- columnas --}}
                <ul class="menu menu2 mx-auto btn btn-outline btn-sm btn-light btn-auto float-right mt-2" >
                    <li class="menu-item has-sub">
                        <a class="menu-link nav-link menu-toggle typografy" href="#">Columnas</a>
                        <div class="menu-sub menu-drop menu-mega ">
                            <div class="menu-mega-innr">
                                <ul class="menu-mega-list" id="shCol" style="z-index:1000;">
                                    <li class="menu-item">
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Opciones', '0', true, ['id' => 'checkOpciones']) !!}
                                            {!! Form::label('checkOpciones', 'Opciones', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Clave', '1', true, ['id' => 'checkclave']) !!}
                                            {!! Form::label('checkclave', 'Clave', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Tipo', '2', true, ['id' => 'checkTipo']) !!}
                                            {!! Form::label('checkTipo', 'Tipo ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Descripcion', '3', true, ['id' => 'checkDescripcion']) !!}
                                            {!! Form::label('checkDescripcion', 'Descripción', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Unidad Venta', '4', true, ['id' => 'checkUnidadV']) !!}
                                            {!! Form::label('checkUnidadV', 'Unidad Venta', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Categoria', '5', true, ['id' => 'checkCategoria']) !!}
                                            {!! Form::label('checkCategoria', 'Categoría', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Grupo', '6', false, ['id' => 'checkGrupo']) !!}
                                            {!! Form::label('checkGrupo', 'Grupo', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('IVA', '7', false, ['id' => 'checkIva']) !!}
                                            {!! Form::label('checkIva', '% IVA', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Usuario', '8', false, ['id' => 'checkUsuario']) !!}
                                            {!! Form::label('checkUsuario', 'Usuario', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Lista de precio', '9', false, ['id' => 'checkLista']) !!}
                                            {!! Form::label('checkLista', 'Lista de precio', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Estatus', '10', false, ['id' => 'checkEstatus']) !!}
                                            {!! Form::label('checkEstatus', 'Estatus', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Creado', '11', false, ['id' => 'checkCreado']) !!}
                                            {!! Form::label('checkCreado', 'Creado', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Actualizado', '12', false, ['id' => 'checkFechab']) !!}
                                            {!! Form::label('checkActualizado', 'Actualizado', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Baja', '13', false, ['id' => 'checkActualizado']) !!}
                                            {!! Form::label('checkFechab', 'Baja', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
                {{-- fin columnas --}}
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-12">
                <div class=" table-responsive">
                    <div class="datatable-container">
                        <table class="table table-striped table-bordered widthAll datatable" id="shTable">
                            <thead>
                                <tr>
                                    <th class="table-head">Opciones</th>
                                    <th class="table-head">Clave</th>
                                    <th class="table-head">Tipo</th>
                                    <th class="table-head">Descripción</th>
                                    <th class="table-head">Unidad de Venta</th>
                                    <th class="table-head">Categoría</th>
                                    <th class="table-head">Grupo</th>
                                    <th class="table-head">% IVA</th>
                                    <th class="table-head">Usuario que lo Registró</th>
                                    <th class="table-head">Lista de Precio</th>
                                    <th class="table-head">Estatus</th>
                                    <th class="table-head">Fecha de Alta</th>
                                    <th class="table-head">Última Actualización</th>
                                    <th class="table-head">Fecha de Baja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('articulos_filtro'))
                                    @foreach (session('articulos_filtro') as $articulo)
                                        @include('include.catalogs.articuloItem')
                                    @endforeach
                                @else
                                    @foreach ($Articulos as $articulo)
                                        @include('include.catalogs.articuloItem')
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('include.mensaje')
@endsection

@section('extra-js')
    <script src="{{ asset('js\catalogos\articulos.js') }}"></script>
@endsection
