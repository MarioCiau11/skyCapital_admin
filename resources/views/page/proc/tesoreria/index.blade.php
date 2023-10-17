@extends('layouts.layout')
@section('content')
    <section class="bg-white">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-10">
                <div class="nk-block-text">
                    <h2 class="title">Tesorería</h2>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 float-left">
                <a href="{{ route('proc.tesoreria.create') }}" class="btn btn-outline btn-sm btn-success float-left">Crear
                    Tesorería</a>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-12">
                {{ Breadcrumbs::render() }}
            </div>
        </div>
        {!! Form::open(['route' => 'proc.tesoreria.filtro', 'method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-3">
                {!! Form::label('inputFolio', 'Folio', ['class' => 'field-label']) !!}
                {!! Form::text('inputFolio', session()->has('inputFolio') ? session()->get('inputFolio') : null, [
                    'class' => 'input-bordered',
                ]) !!}
            </div>
            <div class="col-lg-3">
                {!! Form::label('selectMovimiento', 'Movimiento', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectMovimiento',
                    ['Todos' => 'Todos', 'Egreso' => 'Egreso', 'Ingreso' => 'Ingreso', 'Solicitud Depósito' => 'Solicitud Depósito', 'Depósito' => 'Depósito', 'Transferencia' => 'Transferencia'],
                    session()->has('selectMovimiento') ? session()->get('selectMovimiento') : 'Todos',
                    ['id' => 'selectMovimiento', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-3">
                {!! Form::label('selectCuentaD', 'Cuenta de dinero', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectCuentaD',
                    ['Todos' => 'Todos'] + $cuentasdinero,
                    session()->has('selectCuentaD') ? session()->get('selectCuentaD') : 'Todos',
                    ['id' => 'selectCuentaD', 'class' => ''],
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
            <div class="col-lg-2">
                {!! Form::label('selectEstatus', 'Estatus', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectEstatus',
                    ['Todos' => 'Todos', 'SIN AFECTAR' => 'SIN AFECTAR', 'PENDIENTE' => 'PENDIENTE', 'CONCLUIDO' => 'CONCLUIDO', 'CANCELADO' => 'CANCELADO'],
                    session()->has('selectEstatus') ? session()->get('selectEstatus') : 'CONCLUIDO',
                    ['id' => 'selectEstatus', 'class' => ''],
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
            <div class="col-lg-2">
                {!! Form::label('selectUsuario', 'Usuario', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectUsuario',
                    ['Todos' => 'Todos'] + $usuarios,
                    session()->has('selectUsuario') ? session()->get('selectUsuario') : Auth::user()->user_id,
                    ['id' => 'selectUsuario', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label('selectSucursal', 'Sucursal', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectSucursal',
                    ['Todos' => 'Todos'] + $sucursales,
                    session()->has('selectSucursal') ? session()->get('selectSucursal') : session('sucursal')->idSucursal,
                    ['id' => 'selectSucursal', 'class' => ''],
                ) !!}
            </div>
            
            <div class="row col-lg-12">
                <div class="row col-lg-8" style="display: none;" id="fecha-rango">
                    <div class="col-lg-4">
                        <div class="form-group">
                            {!! Form::label('inputFechaInicio', 'Fecha inicio', ['class' => 'field-label']) !!}
                            <div class="form-group">
                                <input id="inputFechaInicio" type="date" name="inputFechaInicio" value="{{ session()->has('inputFechaInicio') ? session()->get('inputFechaInicio') : null }}" class="input-bordered">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            {!! Form::label('inputFechaFinal', 'Fecha final', ['class' => 'field-label']) !!}
                            <div class="form-group">
                                <input id="inputFechaFinal" type="date" name="inputFechaFinal" value="{{session()->has('inputFechaFinal') ? session()->get('inputFechaFinal') : null}}" class="input-bordered">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="botonesM">
                    <a href="{{ route('proc.tesoreria.index') }}" type="reset" class="btn btn-outline btn-sm btn-light  btn-auto-2 float-left mt-3">Restablecer</a>
                    {!!Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3', 'name' => 'action'])!!}
                    {!!Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action'])!!}
        {!! Form::close() !!}
                    </div>
                

                {{-- columnas --}}
                <ul class="menu menu2 mx-auto btn btn-outline btn-sm btn-light btn-auto float-right mt-2" >
                    <li class="menu-item has-sub">
                        <a class="menu-link nav-link menu-toggle typografy" href="#">Columnas</a>
                        <div class="menu-sub menu-drop menu-mega ">
                            <div class="menu-mega-innr">
                                <ul class="menu-mega-list" id="shCol2" style="z-index:1000;">
                                    <li class="menu-item">
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Opciones', '0', true, ['id' => 'checkOpciones']) !!}
                                            {!! Form::label('checkOpciones', 'Opciones', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Movimiento', '1', true, ['id' => 'checkMovimiento']) !!}
                                            {!! Form::label('checkMovimiento', 'Movimiento', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Folio', '2', true, ['id' => 'checkFolio']) !!}
                                            {!! Form::label('checkFolio', 'Folio', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Moneda', '3', true, ['id' => 'checkMoneda']) !!}
                                            {!! Form::label('checkMoneda', 'Moneda', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('TipoCambio', '4', false, ['id' => 'checkTipo']) !!}
                                            {!! Form::label('checkTipo', 'Tipo Cambio', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Beneficiario', '5', true, ['id' => 'checkBeneficiario']) !!}
                                            {!! Form::label('checkBeneficiario', 'Beneficiario', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('CuentaDinero', '6', true, ['id' => 'checkCuenta']) !!}
                                            {!! Form::label('checkCuenta', 'Cuenta de dinero', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('FormaPago', '7', false, ['id' => 'checkFormaPago']) !!}
                                            {!! Form::label('checkFormaPago', 'FormaPago', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Origen', '8', false, ['id' => 'checkOrigen']) !!}
                                            {!! Form::label('checkOrigen', 'Origen', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('OrigenTipo', '9', false, ['id' => 'checkfechaVenci']) !!}
                                            {!! Form::label('checkfechaVenci', 'Tipo origen', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('FechaEmi', '10', true, ['id' => 'checkFechaEmi']) !!}
                                            {!! Form::label('checkFechaEmi', 'Fecha de emision', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Importe', '11', false, ['id' => 'checkImporte']) !!}
                                            {!! Form::label('checkImporte', 'Importe', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Impuestos', '12', false, ['id' => 'checkImpuestos']) !!}
                                            {!! Form::label('checkImpuestos', 'Impuestos', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('ImporteTotal', '13', true, ['id' => 'checkImporteT']) !!}
                                            {!! Form::label('checkImporteT', 'Importe Total', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('SaldoCuenta', '14', true, ['id' => 'checkSaldoCuenta']) !!}
                                            {!! Form::label('checkSaldoCuenta', 'Saldo cuenta', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Estatus', '15', true, ['id' => 'checkEstatus']) !!}
                                            {!! Form::label('checkEstatus', 'Estatus', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Creado', '16', false, ['id' => 'checkCreado']) !!}
                                            {!! Form::label('checkCreado', 'Creado', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Actualizado', '17', false, ['id' => 'checkActualizado']) !!}
                                            {!! Form::label('checkActualizado', 'Actualizado', ['class' => 'negrita']) !!}
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
                        <table class="table table-striped table-bordered widthAll datatable" id="shTable2">
                            <thead>
                                <tr>
                                    <th class="table-head" @readonly(true)></th>
                                    <th class="table-head">Movimiento</th>
                                    <th class="table-head">Folio</th>
                                    <th class="table-head">Moneda</th>
                                    <th class="table-head">Tipo de cambio</th>
                                    <th class="table-head">Beneficiario</th>
                                    <th class="table-head">Cuenta de dinero</th>
                                    <th class="table-head">Forma de pago</th>
                                    <th class="table-head">Origen</th>
                                    <th class="table-head">Tipo de origen</th>
                                    <th class="table-head">Fecha de emisión</th>
                                    <th class="table-head">Importe</th>
                                    <th class="table-head">Impuestos</th>
                                    <th class="table-head">Importe total</th>
                                    <th class="table-head">Saldo cuenta</th>
                                    <th class="table-head">Estatus</th>
                                    <th class="table-head">Fecha de alta</th>
                                    <th class="table-head">Última actualización</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('tesoreria_filtro'))
                                    @foreach (session('tesoreria_filtro') as $tesoreria)
                                        @include('include.proc.tesoreriaItem')
                                    @endforeach
                                @else
                                    @foreach ($tesoreria as $tesoreria)
                                        @include('include.proc.tesoreriaItem')
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
    <script src="{{ asset('js\procesos\tesoreria\tesoreria.js') }}"></script>
@endsection
