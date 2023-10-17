{{-- <?php
    dd($cxc, $clientes, $usuarios, $monedas);
?> --}}

@extends('layouts.layout')
@section('content')
    <section class="bg-white">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-10">
                <div class="nk-block-text">
                    <h2 class="title">Cuentas por cobrar</h2>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 float-left">
                <a href="{{ route('proc.cxc.create') }}" class="btn btn-outline btn-sm btn-success float-left">Crear
                    CXC</a>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-12">
                {{ Breadcrumbs::render() }}
            </div>
        </div>
        {!! Form::open(['route' => 'proc.cxc.filtro', 'method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-4">
                {!! Form::label('inputFolio', 'Folio', ['class' => 'field-label']) !!}
                {!! Form::text('inputFolio', session()->has('inputFolio') ? session()->get('inputFolio') : null, [
                    'class' => 'input-bordered',
                ]) !!}
            </div>
            <div class="col-lg-3">
                {!! Form::label('selectCliente', 'Clave o nombre del cliente', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectCliente',
                    $clientes,
                    session()->has('selectCliente') ? session()->get('selectCliente') : null,
                    ['id' => 'selectCliente', 'placeholder' => 'Seleccione un cliente'],
                ) !!}
                <input type="text" id="inputClientes" name="inputClientes" value="" readonly hidden />
            </div>
            <div class="col-lg-3">
                {!! Form::label('selectMovimiento', 'Movimiento', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectMovimiento',
                    ['Todos' => 'Todos', 'Anticipo' => 'Anticipo', 'Aplicacion' => 'Aplicacion', 'Cobro' => 'Cobro', 'Factura' => 'Factura'],
                    session()->has('selectMovimiento') ? session()->get('selectMovimiento') : 'Todos',
                    ['id' => 'selectMovimiento', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label('selectEstatus', 'Estatus', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectEstatus',
                    ['Todos' => 'Todos', 'SIN AFECTAR' => 'SIN AFECTAR', 'PENDIENTE' => 'PENDIENTE', 'CONCLUIDO' => 'CONCLUIDO', 'CANCELADO' => 'CANCELADO'],
                    session()->has('selectEstatus') ? session()->get('selectEstatus') : 'PENDIENTE',
                    ['id' => 'selectEstatus', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-2">
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
                {!! Form::label('selectFechaV', 'Fecha de vencimiento', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectFechaV',
                    ['Todos' => 'Todos', 'Hoy' => 'Hoy', 'Ayer' => 'Ayer', 'Semana' => 'Semana', 'Mes' => 'Mes',
                    'Año móvil' => 'Año móvil', 'Año pasado' => 'Año pasado', 'Rango de fechas' => 'Rango de fechas'],
                    session()->has('selectFechaV') ? session()->get('selectFechaV') : 'Mes',
                    ['id' => 'selectFechaV', 'class' => '', 'onchange' => 'showDate()'],
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
            <div class="col-lg-2">
                {!! Form::label('selectMoneda', 'Moneda', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectMoneda',
                    ['Todos' => 'Todos'] + $monedas,
                    session()->has('selectMoneda') ? session()->get('selectMoneda') : '1',
                    ['id' => 'selectMoneda', 'class' => ''],
                ) !!}
            </div>
            <div class="row col-lg-12">
                <div class="row col-lg-3" style="display: none;" id="fecha-rango">
                    <div class="col-lg-12">
                        <div class="form-group">
                            {!! Form::label('inputFechaInicio', 'Fecha inicio', ['class' => 'field-label']) !!}
                            <div class="form-group">
                                {!! Form::date('inputFechaInicio', session()->has('inputFechaInicio') ? session()->get('inputFechaInicio') : null, [
                                'class' => 'input-bordered', 'id' => 'inputFechaInicio', 'placeholder' => 'DD/MM/AAAA', 'autocomplete' => 'off'
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
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
                <div class="row col-lg-3" style="display: none;" id="fecha-rango-v">
                    <div class="col-lg-12">
                        <div class="form-group">
                            {!! Form::label('inputFechaInicioV', 'Fecha inicio vencimiento', ['class' => 'field-label']) !!}
                            <div class="form-group">
                                {!! Form::date('inputFechaInicioV', session()->has('inputFechaInicioV') ? session()->get('inputFechaInicioV') : null, [
                                'class' => 'input-bordered', 'id' => 'inputFechaInicioV', 'placeholder' => 'DD/MM/AAAA', 'autocomplete' => 'off'
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            {!! Form::label('inputFechaFinalV', 'Fecha final vencimiento', ['class' => 'field-label']) !!}
                            <div class="form-group">
                                {!! Form::date('inputFechaFinalV', session()->has('inputFechaFinalV') ? session()->get('inputFechaFinalV') : null, [
                                'class' => 'input-bordered', 'id' => 'inputFechaFinalV', 'placeholder' => 'DD/MM/AAAA', 'autocomplete' => 'off'
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="botonesM">
                    <a href="{{ route('proc.cxc.index') }}" type="reset" class="btn btn-outline btn-sm btn-light  btn-auto-2 float-left mt-3">Restablecer</a>
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
                                            {!! Form::label('checkFolio', 'Folio ', ['class' => 'negrita']) !!}
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
                                            {!! Form::checkbox('CuentaDinero', '5', true, ['id' => 'checkCuenta']) !!}
                                            {!! Form::label('checkCuenta', 'Cuenta de dinero', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Anticipo', '6', false, ['id' => 'checkAnticipo']) !!}
                                            {!! Form::label('checkAnticipo', 'Anticipo', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Cliente', '7', true, ['id' => 'checkCliente']) !!}
                                            {!! Form::label('checkCliente', 'Cliente', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Condicion', '8', false, ['id' => 'checkCondicion']) !!}
                                            {!! Form::label('checkCondicion', 'Condición Pago', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('fechaEmi', '9', true, ['id' => 'checkfechaEmi']) !!}
                                            {!! Form::label('checkfechaEmi', 'Fecha de emision', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('fechaVenci', '10', true, ['id' => 'checkfechaVenci']) !!}
                                            {!! Form::label('checkfechaVenci', 'Fecha Vencimiento', ['class' => 'negrita']) !!}
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
                                            {!! Form::checkbox('Saldo', '14', true, ['id' => 'checkSaldo']) !!}
                                            {!! Form::label('checkSaldo', 'Saldo', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('DiasMora', '15', true, ['id' => 'checkDiasMora']) !!}
                                            {!! Form::label('checkDiasMora', 'Días moratorios', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Proyecto', '16', true, ['id' => 'checkProyecto']) !!}
                                            {!! Form::label('checkProyecto', 'Proyecto', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Modulo', '17', true, ['id' => 'checkModulo']) !!}
                                            {!! Form::label('checkModulo', 'Módulo', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Estatus', '18', true, ['id' => 'checkEstatus']) !!}
                                            {!! Form::label('checkEstatus', 'Estatus', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Creado', '19', false, ['id' => 'checkCreado']) !!}
                                            {!! Form::label('checkCreado', 'Creado', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Actualizado', '20', false, ['id' => 'checkActualizado']) !!}
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
                                    <th class="table-head">Folio mov</th>
                                    <th class="table-head">Moneda</th>
                                    <th class="table-head">Tipo de cambio</th>
                                    <th class="table-head">Cuenta de dinero</th>
                                    <th class="table-head">Anticipo</th>
                                    <th class="table-head">Cliente</th>
                                    <th class="table-head">Condición de pago</th>
                                    <th class="table-head">Fecha de emisión</th>
                                    <th class="table-head">Fecha de vencimiento</th>
                                    <th class="table-head">Importe</th>
                                    <th class="table-head">Impuestos</th>
                                    <th class="table-head">Importe total</th>
                                    <th class="table-head">Saldo</th>
                                    <th class="table-head">Días morat.</th>
                                    <th class="table-head">Proyecto</th>
                                    <th class="table-head">Módulo</th>
                                    <th class="table-head">Estatus</th>
                                    <th class="table-head">Fecha de alta</th>
                                    <th class="table-head">Última actualización</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('cxc_filtro'))
                                    @foreach (session('cxc_filtro') as $cxc)
                                        @include('include.proc.cxcItem')
                                    @endforeach
                                @else
                                    @foreach ($cxc as $cxc)
                                        @include('include.proc.cxcItem')
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
    <script src="{{ asset('js\procesos\cxc\cxc.js') }}"></script>
@endsection
