@extends('layouts.layout')
@section('content')
    <section class="bg-white">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-10">
                <div class="nk-block-text">
                    <h2 class="title">Estado de cuenta</h2>
                </div>
            </div>
        </div>
        @php
            // dd($monedas);   
        @endphp
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
                <select name="selectCliente2" id="selectCliente2" style="display: none"><option selected value="">Todos</option></select>
                <select name="selectPlazo" id="selectPlazo" style="display: none"><option selected value="">Todos</option></select>
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
                    session()->has('selectMoneda') ? session()->get('selectMoneda') : 'PESOS',
                    ['id' => 'selectMoneda', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label('selectEstatus', 'Estatus', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectEstatus',
                    ['Todos' => 'Todos', 'SIN AFECTAR' => 'SIN AFECTAR', 'POR CONFIRMAR' => 'POR CONFIRMAR', 'PENDIENTE' => 'PENDIENTE', 'CONCLUIDO' => 'CONCLUIDO', 'CANCELADO' => 'CANCELADO'],
                    session()->has('selectEstatus') ? session()->get('selectEstatus') : 'CONCLUIDO',
                    ['id' => 'selectEstatus', 'class' => ''],
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
                <div class="botonesM contenedorBtn_Reports">
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
                                            {!! Form::checkbox('Folio', '4', true, ['id' => 'checkFolio']) !!}
                                            {!! Form::label('checkFolio', 'Folio', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Moneda', '5', true, ['id' => 'checkMoneda']) !!}
                                            {!! Form::label('checkMoneda', 'Moneda', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('tipoCambio', '6', false, ['id' => 'tipoCambio']) !!}
                                            {!! Form::label('tipoCambio', 'Tipo Cambio', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('fechaEmi', '7', true, ['id' => 'checkfechaEmi']) !!}
                                            {!! Form::label('checkfechaEmi', 'Fecha Expedición', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Cargos', '8', true, ['id' => 'checkCargos']) !!}
                                            {!! Form::label('checkCargos', 'Cargos ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Abonos', '9', true, ['id' => 'checkAbonos']) !!}
                                            {!! Form::label('checkAbonos', 'Abonos ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Saldo', '10', true, ['id' => 'checkSaldo']) !!}
                                            {!! Form::label('checkSaldo', 'Saldo ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Aplica', '11', false, ['id' => 'checkAplica']) !!}
                                            {!! Form::label('checkAplica', 'Aplica', ['class' => 'negrita']) !!}
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
                                    <th class="table-head">Folio</th>
                                    <th class="table-head">Moneda</th>
                                    <th class="table-head">Tipo Cambio</th>
                                    <th class="table-head">Fecha Expedición</th>
                                    <th class="table-head">Cargos</th>
                                    <th class="table-head">Abonos</th>
                                    <th class="table-head">Saldo</th>
                                    <th class="table-head">Aplica</th>
                                    <th class="table-head">Sucursal</th>
                                    <th class="table-head">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('reportes_filtro'))
                                    @foreach (session('reportes_filtro') as $estado)
                                    {{-- @dd(session('reportes_filtro')); --}}
                                        @include('include.reports.report2CxCItem')
                                    @endforeach
                                @else
                                    @foreach ($estados as $key => $estado)
                                        @include('include.reports.report2CxCItem')
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
