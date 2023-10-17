@extends('layouts.layout')
@section('content')
    <section class="bg-white">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-10">
                <div class="nk-block-text">
                    <h2 class="title">Auxiliares por Cuenta de Dinero Nivel - concentrado</h2>
                </div>
            </div>
        </div>
        {!! Form::open(['route' => 'report.tesoreria.filtro', 'method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-4">
                {!! Form::label('selectCuentaD', 'Cuenta', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectCuentaD',
                    ['Todos' => 'Todos'] + $cuentasdinero,
                    session()->has('selectCuentaD') ? session()->get('selectCuentaD') : 'Todos',
                    ['id' => 'selectCuentaD', 'class' => ''],
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
                    session()->has('selectMoneda') ? session()->get('selectMoneda') : $monedaDefault->idMoneda,
                    ['id' => 'selectMoneda', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label('selectMovimiento', 'Movimiento', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectMovimiento',
                    ['Todos' => 'Todos', 'Egreso' => 'Egreso', 'Ingreso' => 'Ingreso', 'Solicitud Depósito' => 'Solicitud Depósito', 'Depósito' => 'Depósito', 'Transferencia' => 'Transferencia'],
                    session()->has('selectMovimiento') ? session()->get('selectMovimiento') : 'Todos',
                    ['id' => 'selectMovimiento', 'class' => ''],
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
                <input type="hidden" name="inputReporte" value="CONCENTRADO">
            </div>
            <div class="col-lg-12 ">
                <div class="contenedorBtn_Reports">
                    <a href="" type="reset" class="btn btn-outline btn-sm btn-light  btn-auto-2 float-left mt-3">Restablecer</a>
                    {!!Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3', 'name' => 'action'])!!}
                    {!!Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action'])!!}
                    {!!Form::submit('Exportar PDF', ['class' => 'btn btn-outline btn-sm btn-danger mt-3', 'name' => 'action'])!!}
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
                                            {!! Form::checkbox('Cuenta', '0', true, ['id' => 'checkCuenta']) !!}
                                            {!! Form::label('checkCuenta', 'Cuenta', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Movimiento', '1', true, ['id' => 'checkMovimiento']) !!}
                                            {!! Form::label('checkMovimiento', 'Movimiento ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Fecha', '2', true, ['id' => 'checkFecha']) !!}
                                            {!! Form::label('checkFecha', 'Fecha', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Moneda', '3', true, ['id' => 'checkMoneda']) !!}
                                            {!! Form::label('checkMoneda', 'Moneda', ['class' => 'negrita']) !!}
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
                                    <th class="table-head">Cuenta</th>
                                    <th class="table-head">Movimiento</th>
                                    <th class="table-head">Fecha</th>
                                    <th class="table-head">Moneda</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('reporte_filtro'))
                                    @foreach (session('reporte_filtro') as $tesoreria)
                                        @include('include.reports.reportTesoreriaItem')
                                    @endforeach
                                @else
                                    @foreach ($reporte as $tesoreria)
                                        @include('include.reports.reportTesoreriaItem')
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
