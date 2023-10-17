@extends('layouts.layout')
@section('content')
    <section class="bg-white">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-10">
                <div class="nk-block-text">
                    <h2 class="title">Ingresos por proyecto</h2>
                </div>
            </div>
        </div>
        {!! Form::open(['route' => 'report.cxc.filtro', 'method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-4">
                {!! Form::label('selectCliente', 'Cliente', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectCliente',
                    ['Todos' => 'Todos'] + $clientes,
                    session()->has('selectCliente') ? session()->get('selectCliente') : 'Todos',
                    ['id' => 'selectCliente', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-4">
                {!! Form::label('selectCategoria', 'Categoría', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectCategoria',
                    ['Todos' => 'Todos'] + $categorias,
                    session()->has('selectCategoria') ? session()->get('selectCategoria') : 'Todos',
                    ['id' => 'selectCategoria', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-4">
                {!! Form::label('selectGrupo', 'Grupo', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectGrupo',
                    ['Todos' => 'Todos'] + $grupos,
                    session()->has('selectGrupo') ? session()->get('selectGrupo') : 'Todos',
                    ['id' => 'selectGrupo', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-3">
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
                    session()->has('selectMoneda') ? session()->get('selectMoneda') : $parametroGen->clave,
                    ['id' => 'selectMoneda', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label('selectTipoContrato', 'Tipo', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectTipoContrato',
                    ['Todos' => 'Todos', 'Venta' => 'Venta', 'Renta' => 'Renta', 'Anticipo' => 'Anticipo'],
                    session()->has('selectTipoContrato') ? session()->get('selectTipoContrato') : 'Todos',
                    ['id' => 'selectTipoContrato', 'class' => ''],
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
                    {!!Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3', 'name' => 'action3'])!!}
                    {!!Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action3'])!!}
                    {!!Form::submit('Exportar PDF', ['class' => 'btn btn-outline btn-sm btn-danger mt-3', 'name' => 'action3'])!!}
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
                                            {!! Form::label('checkCategoría', 'Categoría', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Grupo', '2', false, ['id' => 'checkGrupo']) !!}
                                            {!! Form::label('checkGrupo', 'Grupo', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Movimiento', '3', true, ['id' => 'checkMovimiento']) !!}
                                            {!! Form::label('checkMovimiento', 'Movimiento', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Ingreso', '4', true, ['id' => 'checkIngreso']) !!}
                                            {!! Form::label('checkIngreso', 'Ingreso', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Banco', '5', true, ['id' => 'checkBanco']) !!}
                                            {!! Form::label('checkBanco', 'Banco', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Folio externo', '6', false, ['id' => 'checkFolioExt']) !!}
                                            {!! Form::label('checkFolioExt', 'Folio externo ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Moneda', '7', true, ['id' => 'checkMoneda']) !!}
                                            {!! Form::label('checkMoneda', 'Moneda', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('TipoCambio', '8', false, ['id' => 'checkTipoCambio']) !!}
                                            {!! Form::label('checkTipoCambio', 'Tipo Cambio', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Tipo', '9', true, ['id' => 'checkTipo']) !!}
                                            {!! Form::label('checkTipo', 'Tipo', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Fecha', '10', true, ['id' => 'checkFecha']) !!}
                                            {!! Form::label('checkFecha', 'Fecha', ['class' => 'negrita']) !!}
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
                                            {!! Form::checkbox('Módulo', '12', true, ['id' => 'checkMódulo']) !!}
                                            {!! Form::label('checkMódulo', 'Módulo', ['class' => 'negrita']) !!}
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
                                    <th class="table-head">Ingreso</th>
                                    <th class="table-head">Banco</th>
                                    <th class="table-head">Folio externo</th>
                                    <th class="table-head">Moneda</th>
                                    <th class="table-head">Tipo de cambio</th>
                                    <th class="table-head">Tipo</th>
                                    <th class="table-head">Fecha</th>
                                    <th class="table-head">Proyecto</th>
                                    <th class="table-head">Módulo</th>
                                    <th class="table-head">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('reportes_filtro'))
                                    @foreach (session('reportes_filtro') as $ingreso)
                                        @include('include.reports.report3CxCItem')
                                    @endforeach
                                @else
                                    @foreach ($ingresos as $key => $ingreso)
                                        @include('include.reports.report3CxCItem')
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
