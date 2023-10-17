@extends('layouts.layout')
@section('content')
    <section class="bg-white">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-10">
                <div class="nk-block-text">
                    <h2 class="title">Antigüedad de saldos</h2>
                </div>
            </div>
        </div>
        {!! Form::open(['route' => 'report.cxc.filtro', 'method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-3">
                {!! Form::label('selectCliente', 'Del cliente', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectCliente',
                    ['Todos' => 'Todos'] + $clientes,
                    session()->has('selectCliente') ? session()->get('selectCliente') : 'Todos',
                    ['id' => 'selectCliente', 'class' => ''],
                ) !!}
                <select name="selectEstatus" style="display: none"><option selected value="">Todos</option></select>
                <select name="selectFecha" style="display: none"><option selected value="">Todos</option></select>
                <input type="text" name="inputFechaInicio" style="display: none">
                <input type="text" name="inputFechaFinal" style="display: none">
            </div>
            <div class="col-lg-3">
                {!! Form::label('selectCliente2', 'Al cliente', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectCliente2',
                    ['Todos' => 'Todos'] + $clientes,
                    session()->has('selectCliente2') ? session()->get('selectCliente2') : 'Todos',
                    ['id' => 'selectCliente2', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-3">
                {!! Form::label('selectPlazo', 'Plazo', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectPlazo',
                    ['Todos' => 'Todos', 'A partir del corriente' => 'A partir del corriente', 
                    'A partir del 1 al 15' => 'A partir del 1 al 15', 
                    'A partir del 16 al 30' => 'A partir del 16 al 30', 
                    'A partir del 31 al 60' => 'A partir del 31 al 60',
                    'A partir del 61 al 90' => 'A partir del 61 al 90', 
                    'Más de 90 días' => 'Más de 90 días'],
                    session()->has('selectPlazo') ? session()->get('selectPlazo') : 'Todos',
                    ['id' => 'selectPlazo', 'class' => ''],
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
            <div class="col-lg-12 ">
                <div class="contenedorBtn_Reports">
                    <a href="" type="reset" class="btn btn-outline btn-sm btn-light  btn-auto-2 float-left mt-3">Restablecer</a>
                    {!!Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3', 'name' => 'action1'])!!}
                    {!!Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action1'])!!}
                    {!!Form::submit('Exportar PDF', ['class' => 'btn btn-outline btn-sm btn-danger mt-3', 'name' => 'action1'])!!}
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
                                            {!! Form::checkbox('Moneda', '4', true, ['id' => 'checkMoneda']) !!}
                                            {!! Form::label('checkMoneda', 'Moneda', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('tipoCambio', '5', false, ['id' => 'tipoCambio']) !!}
                                            {!! Form::label('tipoCambio', 'Tipo Cambio', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Sucursal', '6', true, ['id' => 'checkSucursal']) !!}
                                            {!! Form::label('checkSucursal', 'Sucursal', ['class' => 'negrita']) !!}
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
                                            {!! Form::checkbox('fechaVenci', '8', true, ['id' => 'checkFechaVenci']) !!}
                                            {!! Form::label('checkFechaVenci', 'Fecha vencimiento', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Estatus', '9', true, ['id' => 'checkEstatus']) !!}
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
                                    <th class="table-head">Moneda</th>
                                    <th class="table-head">Tipo de cambio</th>
                                    <th class="table-head">Sucursal</th>
                                    <th class="table-head">Fecha de expedición</th>
                                    <th class="table-head">Fecha de vencimiento</th>
                                    <th class="table-head">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('reportes_filtro'))
                                    @foreach (session('reportes_filtro') as $saldo)
                                        @include('include.reports.reportCxCItem')
                                    @endforeach
                                @else
                                    @foreach ($saldos as $saldo)
                                        @include('include.reports.reportCxCItem')
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
