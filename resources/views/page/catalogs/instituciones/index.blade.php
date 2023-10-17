@extends('layouts.layout')


@section('content')
    <section class="bg-white">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-10">
                <div class="nk-block-text">
                    <h2 class="title">Instituciones Financieras</h2>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 float-left">
                <a href="{{ route('cat.instituciones.create') }}" class="btn btn-outline btn-sm btn-success float-left">Crear
                    Institución</a>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-lg-12">
                {{ Breadcrumbs::render() }}
            </div>
        </div>
        {!! Form::open(['route' => 'cat.instituciones.filtro', 'method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-5">
                {!! Form::label('clave', 'Clave', ['class' => 'field-label']) !!}
                {!! Form::text('inputClave', session()->has('clave') ? session()->get('clave') : null, [
                    'class' => 'input-bordered',
                    'id' => 'clave'
                ]) !!}
            </div>
            <div class="col-lg-5">
                {!! Form::label('nombre', 'Nombre', ['class' => 'field-label']) !!}
                {!! Form::text('inputName', session()->has('nombre') ? session()->get('nombre') : null, [
                    'class' => 'input-bordered',
                    'id' => 'nombre'
                ]) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label('selectEstatus', 'Estatus', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectEstatus',
                    ['1' => 'Alta', '0' => 'Baja', 'Todos' => 'Todos'],
                    session()->has('estatus') ? session()->get('estatus') : 'Todos',
                    ['id' => 'selectEstatus', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-12">
                <div class="botonesM">
                <a href="{{ route('cat.instituciones.index') }}" type="reset"
                    class="btn btn-outline btn-sm btn-light btn-auto-2 float-left mt-3">Restablecer</a>
                {!! Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3 btnBusqueda', 'name' => 'action']) !!}
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
                                            {!! Form::checkbox('Nombre', '2', true, ['id' => 'checkNombre']) !!}
                                            {!! Form::label('checkNombre', 'Nombre ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Estatus', '3', true, ['id' => 'checkEstatus']) !!}
                                            {!! Form::label('checkEstatus', 'Estatus', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>

                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Creado', '4', false, ['id' => 'checkFechaa']) !!}
                                            {!! Form::label('checkFechaa', 'Creado', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Actualizado', '5', false, ['id' => 'checkFechac']) !!}
                                            {!! Form::label('checkFechac', 'Actualizado', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Baja', '6', false, ['id' => 'checkFechab']) !!}
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
            <div class="col-lg-12 ">
                <div class=" table-responsive">
                    <div class="datatable-container">
                        <table class="table table-striped table-bordered widthAll datatable" id="shTable">
                            <thead>
                                <tr>
                                    <th class="table-head">Opciones</th>
                                    <th class="table-head">Clave</th>
                                    <th class="table-head">Nombre</th>
                                    <th class="table-head">Estatus</th>
                                    <th class="table-head">Fecha Creación</th>
                                    <th class="table-head">Última Actualización</th>
                                    <th class="table-head">Fecha de Baja</th>   
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('instituciones_filtro'))
                                
                                    @foreach (session('instituciones_filtro') as $institucion)
                                        @include('include.catalogs.institucionesItem')
                                    @endforeach
                                @else
                                    @foreach ($instituciones as $institucion)
                                        @include('include.catalogs.institucionesItem')
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
    <script src="{{ asset('js/catalogos/sucursales.js') }}"></script>
@endsection
