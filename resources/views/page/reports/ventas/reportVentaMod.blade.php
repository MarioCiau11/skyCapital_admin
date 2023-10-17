@extends('layouts.layout')
@section('content')
    <section class="bg-white">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-10">
                <div class="nk-block-text">
                    <h2 class="title">Ventas de módulos</h2>
                </div>
            </div>
        </div>
        @php
            // dd($ventasd);   
        @endphp
        {!! Form::open(['route' => 'report.ventas.filtro', 'method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-5">
                {!! Form::label('selectProyecto', 'Proyecto', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectProyecto',
                    ['Todos' => 'Todos'] + $proyectos,
                    session()->has('selectProyecto') ? session()->get('selectProyecto') : 'Todos',
                    ['id' => 'selectProyecto', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-4">
                {!! Form::label('selectFecha', 'Fecha', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectFecha',
                    ['Todos' => 'Todos', 'Hoy' => 'Hoy', 'Ayer' => 'Ayer', 'Semana' => 'Semana', 'Mes' => 'Mes',
                    'Año móvil' => 'Año móvil', 'Año pasado' => 'Año pasado', 'Rango de fechas' => 'Rango de fechas'],
                    session()->has('selectFecha') ? session()->get('selectFecha') : 'Mes',
                    ['id' => 'selectFecha', 'class' => '', 'onchange' => 'showDate()'],
                ) !!}
            </div>
            <div class="col-lg-3">
                {!! Form::label('selectMoneda', 'Moneda', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectMoneda',
                    ['Todos' => 'Todos'] + $monedas,
                    session()->has('selectMoneda') ? session()->get('selectMoneda') : '1',
                    ['id' => 'selectMoneda', 'class' => ''],
                ) !!}
            </div>
            <div class="row col-lg-8" style="display: none;" id="fecha-rango">
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Form::label('inputFechaInicio', 'Fecha inicio', ['class' => 'field-label']) !!}
                        <div class="form-group">
                            {!! Form::date('inputFechaInicio', session()->has('inputFechaInicio') ? session()->get('inputFechaInicio') : null, [
                            'class' => 'input-bordered', 'id' => 'inputFechaInicio', 'placeholder' => 'DD/MM/AAAA', 'autocomplete' => 'off'
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Form::label('inputFechaFinal', 'Fecha final', ['class' => 'field-label']) !!}
                        <div class="form-group">
                            {!! Form::date('inputFechaFinal', session()->has('inputFechaFinal') ? session()->get('inputFechaFinal') : null, [
                            'class' => 'input-bordered', 'id' => 'inputFechaFinal', 'placeholder' => 'DD/MM/AAAA', 'autocomplete' => 'off'
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 ">
                <div class="contenedorBtn_Reports">
                    <a href="" type="reset" class="btn btn-outline btn-sm btn-light  btn-auto-2 float-left mt-3">Restablecer</a>
                    {!!Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3', 'name' => 'action2'])!!}
                    {!!Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action2'])!!}
                    {!!Form::submit('Exportar PDF', ['class' => 'btn btn-outline btn-sm btn-danger mt-3', 'name' => 'action2'])!!}
        {!! Form::close() !!}
                    </div>
                

                {{-- columnas --}}
                <ul class="menu menu2 mx-auto btn btn-outline btn-sm btn-light btn-auto float-right mt-2" >
                    <li class="menu-item has-sub">
                        <a class="menu-link nav-link menu-toggle typografy" href="#">Columnas</a>
                        <div class="menu-sub menu-drop menu-mega ">
                            <div class="menu-mega-innr">
                                <ul class="menu-mega-list" id="shCol" style="z-index:1000;">
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Cliente', '0', true, ['id' => 'checkCliente']) !!}
                                            {!! Form::label('checkCliente', 'Cliente', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Categoría', '1', false, ['id' => 'checkCategoría']) !!}
                                            {!! Form::label('checkCategoría', 'Categoría ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Grupo', '2', false, ['id' => 'checkGrupo']) !!}
                                            {!! Form::label('checkGrupo', 'Grupo', ['class' => 'negrita']) !!}
                                        </div>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Movimiento', '3', true, ['id' => 'checkMovimiento']) !!}
                                            {!! Form::label('checkMovimiento', 'Movimiento', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Clave', '4', true, ['id' => 'checkClave']) !!}
                                            {!! Form::label('checkClave', 'Clave', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Artículo', '5', true, ['id' => 'checkArtículo']) !!}
                                            {!! Form::label('checkArtículo', 'Artículo ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Unidad', '6', true, ['id' => 'checkUnidad']) !!}
                                            {!! Form::label('checkUnidad', 'Unidad', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Usuario', '7', false, ['id' => 'checkUsuario']) !!}
                                            {!! Form::label('checkUsuario', 'Usuario ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Moneda', '8', true, ['id' => 'checkMoneda']) !!}
                                            {!! Form::label('checkMoneda', 'Moneda', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('tipoCambio', '9', false, ['id' => 'tipoCambio']) !!}
                                            {!! Form::label('tipoCambio', 'Tipo Cambio', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('fechaEmi', '10', true, ['id' => 'checkfechaEmi']) !!}
                                            {!! Form::label('checkfechaEmi', 'Fecha Emisión', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Proyecto', '11', true, ['id' => 'checkProyecto']) !!}
                                            {!! Form::label('checkProyecto', 'Proyecto', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Sucursal', '12', false, ['id' => 'checkSucursal']) !!}
                                            {!! Form::label('checkSucursal', 'Sucursal', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Estatus', '13', true, ['id' => 'checkEstatus']) !!}
                                            {!! Form::label('checkEstatus', 'Estatus', ['class' => 'negrita']) !!}
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
                                    <th class="table-head">Cliente</th>
                                    <th class="table-head">Categoría</th>
                                    <th class="table-head">Grupo</th>
                                    <th class="table-head">Movimiento</th>
                                    <th class="table-head">Clave</th>
                                    <th class="table-head">Artículo</th>
                                    <th class="table-head">Unidad</th>
                                    <th class="table-head">Usuario</th>
                                    <th class="table-head">Moneda</th>
                                    <th class="table-head">Tipo de cambio</th>
                                    <th class="table-head">Fecha de emisión</th>
                                    <th class="table-head">Proyecto</th>
                                    <th class="table-head">Sucursal</th>
                                    <th class="table-head">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('reportes_filtro'))
                                    @foreach (session('reportes_filtro') as $articulo)
                                        @include('include.reports.report2VentaItem')
                                    @endforeach
                                @else
                                    @foreach ($articulos as $articulo)
                                        @include('include.reports.report2VentaItem')
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
    <script src="{{ asset('js\reportes\reports.js') }}"></script>
@endsection
