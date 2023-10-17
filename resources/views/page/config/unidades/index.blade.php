@extends('layouts.layout')

@section('content')
    <section class="bg-white">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-10">
                <div class="nk-block-text">
                    <h2 class="title">Unidades</h2>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 float-left">
                <a href="{{ route('config.unidades.create') }}" class="btn btn-outline btn-sm btn-success float-left">
                    Crear Unidad</a>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-lg-12">
                {{ Breadcrumbs::render() }}
            </div>
        </div>
        {!! Form::open(['route' => 'config.unidades.filtro', 'method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-10">
                {!! Form::label('unidad', 'Unidad', ['class' => 'field-label']) !!}
                {!! Form::text('inputUnidad', session()->has('unidad') ? session()->get('unidad') : null, [
                    'class' => 'input-bordered',
                ]) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label('status', 'Estatus', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectEstatus',
                    ['1' => 'Alta', '0' => 'Baja', 'Todos' => 'Todos'],
                    session()->has('status') ? session()->get('status') : 'Todos',
                    ['id' => 'selectEstatus', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-12 ">
                <div class="botonesM">
                <a href="{{ route('config.unidades.index') }}" type="reset"
                    class="btn btn-outline btn-sm btn-light  btn-auto-2 float-left mt-3">Restablecer</a>
                {!! Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3 btnBusqueda', 'name' => 'action']) !!}
                {!! Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action']) !!}
                {!! Form::close() !!}
                </div>

                {{-- columnas --}}
                <ul class="menu menu2 mx-auto btn btn-outline btn-sm btn-light btn-auto float-right mt-2">
                    <li class="menu-item has-sub">
                        <a class="menu-link nav-link menu-toggle typografy" href="#">Columnas</a>
                        <div class="menu-sub menu-drop menu-mega ">
                            <div class="menu-mega-innr">
                                <ul class="menu-mega-list" id="shCol">
                                    <li class="menu-item">
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Opciones', '0', true, ['id' => 'checkOpciones']) !!}
                                            {!! Form::label('checkOpciones', 'Opciones', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li class="menu-item">
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Unidad', '1', true, ['id' => 'checkUnidad']) !!}
                                            {!! Form::label('checkUnidad', 'Unidad', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Decimal válida', '2', true, ['id' => 'checkDecimalV']) !!}
                                            {!! Form::label('checkDecimalV', 'Decimal válida', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    {{-- <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Clave SAT', '3', true, ['id' => 'checkClavsesat']) !!}
                                            {!! Form::label('checkClavsesat', 'Calve SAT', ['class' => 'negrita']) !!}
                                        </div>
                                    </li> --}}
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Estatus', '3', false, ['id' => 'checkEstatus']) !!}
                                            {!! Form::label('checkEstatus', 'Estatus', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Usuario', '4', false, ['id' => 'checkUser']) !!}
                                            {!! Form::label('checkUser', 'Usuario', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Creado', '5', false, ['id' => 'checkFechaa']) !!}
                                            {!! Form::label('checkFechaa', 'Creado', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Actualizado', '6', false, ['id' => 'checkFechac']) !!}
                                            {!! Form::label('checkFechac', 'Actualizado', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Baja', '7', false, ['id' => 'checkFechab']) !!}
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
                                    <th class="table-head">Unidad</th>
                                    <th class="table-head">Decimal Válida</th>
                                    <th class="table-head">Estatus</th>
                                    <th class="table-head">Usuario que lo Registró</th>
                                    <th class="table-head">Fecha Creación</th>
                                    <th class="table-head">Última Actualización</th>
                                    <th class="table-head">Fecha de Baja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('unidades_filtro'))
                                    @foreach (session('unidades_filtro') as $unidades)
                                        @include('include.config.UnidadesItem')
                                    @endforeach
                                @else
                                    @foreach ($Unidades as $unidades)
                                        @include('include.config.UnidadesItem')
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
    <script src="{{ asset('js/config/unidades.js') }}"></script>
@endsection
