@extends('layouts.layout')
@section('content')
    <section class="bg-white">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-10">
                <div class="nk-block-text">
                    <h2 class="title">Ventas</h2>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 float-left">
                <a href="{{ route('proc.ventas.create') }}" class="btn btn-outline btn-sm btn-success float-left">Crear
                    Venta</a>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-lg-12">
                {{ Breadcrumbs::render() }}
            </div>
        </div>

        {!! Form::open(['route' => 'proc.ventas.filtro', 'method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-4">
                {!! Form::label('inputFolio', 'Folio', ['class' => 'field-label']) !!}
                {!! Form::text('inputFolio', session()->has('inputFolio') ? session()->get('inputFolio') : null, [
                    'class' => 'input-bordered',
                ]) !!}
            </div>
            <div class="col-lg-4">
                {!! Form::label('selectCliente', 'Cliente', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectCliente',
                    $clientes,
                    session()->has('selectCliente') ? session()->get('selectCliente') : null,
                    ['id' => 'selectCliente', 'placeholder' => 'Seleccione un cliente'],
                ) !!}
                <input type="text" id="inputClientes" name="inputClientes" value="{{$clientes}}" readonly hidden />
            </div>
            <div class="col-lg-2">
                {!! Form::label('selectMovimiento', 'Movimiento', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectMovimiento',
                    ['Todos' => 'Todos', 'Contrato' => 'Contrato', 'Factura' => 'Factura', 'Inversión Inicial' => 'Inversión Inicial', 'Mensualidad' => 'Mensualidades', 'Finiquito' => 'Finiquito'],
                    session()->has('selectMovimiento') ? session()->get('selectMovimiento') : 'Todos',
                    ['id' => 'selectMovimiento', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label('selectEstatus', 'Estatus', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectEstatus',
                    ['Todos' => 'Todos', 'SIN AFECTAR' => 'SIN AFECTAR', 'POR CONFIRMAR' => 'POR CONFIRMAR', 'PENDIENTE' => 'PENDIENTE', 'CONCLUIDO' => 'CONCLUIDO', 'CANCELADO' => 'CANCELADO'],
                    session()->has('selectEstatus') ? session()->get('selectEstatus') : 'PENDIENTE',
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
            <div class="col-lg-3">
                {!! Form::label('selectUsuario', 'Usuario', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectUsuario',
                    ['Todos' => 'Todos'] + $usuarios,
                    session()->has('selectUsuario') ? session()->get('selectUsuario') : Auth::user()->user_id,
                    ['id' => 'selectUsuario', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-3">
                {!! Form::label('selectSucursal', 'Sucursal', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectSucursal',
                    ['Todos' => 'Todos'] + $sucursales,
                    session()->has('selectSucursal') ? session()->get('selectSucursal') : session('sucursal')->idSucursal,
                    ['id' => 'selectSucursal', 'class' => ''],
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
            <div class="col-lg-12 ">
                {{-- <div class="botonesM">
                    <a href="{{ route('proc.ventas.index') }}" type="reset"
                    class="btn btn-outline btn-sm btn-light  btn-auto float-left mt-3">Restablecer</a>
                    {!! Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3', 'name' => 'action']) !!}
                    {!! Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action']) !!}
                    {!! Form::close() !!}
                </div> --}}
                <div class="botonesM">
                    <a href="{{ route('proc.ventas.index') }}" type="reset" class="btn btn-outline btn-sm btn-light  btn-auto-2 float-left mt-3">Restablecer</a>
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
                                            {!! Form::checkbox('Folio2', '3', true, ['id' => 'checkFolio2']) !!}
                                            {!! Form::label('checkFolio2', 'Folio2', ['class' => 'negrita']) !!}
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
                                            {!! Form::checkbox('TipoCambio', '5', false, ['id' => 'checkTipoCambio']) !!}
                                            {!! Form::label('checkTipoCambio', 'Tipo Cambio', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('FechaEmi', '6', true, ['id' => 'checkFechaEmi']) !!}
                                            {!! Form::label('checkFechaEmi', 'Fecha Emisión', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Morosidad', '7', true, ['id' => 'checkMorosidad']) !!}
                                            {!! Form::label('checkMorosidad', '% Morosidad', ['class' => 'negrita']) !!}
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
                                            {!! Form::checkbox('fechaVenci', '9', false, ['id' => 'checkfechaVenci']) !!}
                                            {!! Form::label('checkfechaVenci', 'Fecha Vencimiento', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Proyecto', '10', true, ['id' => 'checkProyecto']) !!}
                                            {!! Form::label('checkProyecto', 'Proyecto', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Modulo', '11', true, ['id' => 'checkModulo']) !!}
                                            {!! Form::label('checkModulo', 'Módulo', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Propietario', '12', true, ['id' => 'checkPropietario']) !!}
                                            {!! Form::label('checkPropietario', 'Propietario P.', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Subtotal', '13', false, ['id' => 'checkSubtotal']) !!}
                                            {!! Form::label('checkSubtotal', 'Subtotal', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Impuestos', '14', false, ['id' => 'checkImpuestos']) !!}
                                            {!! Form::label('checkImpuestos', 'Impuestos', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Importe', '15', true, ['id' => 'checkImporte']) !!}
                                            {!! Form::label('checkImporte', 'Importe Total', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Pagado', '16', true, ['id' => 'checkPagado']) !!}
                                            {!! Form::label('checkPagado', 'PayPal', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Estatus', '17', true, ['id' => 'checkEstatus']) !!}
                                            {!! Form::label('checkEstatus', 'Estatus', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Creado', '18', false, ['id' => 'checkCreado']) !!}
                                            {!! Form::label('checkCreado', 'Creado', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Actualizado', '19', false, ['id' => 'checkActualizado']) !!}
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
                                    <th class="table-head">Folio mov.</th>
                                    <th class="table-head">Folio proyecto</th>
                                    <th class="table-head">Moneda</th>
                                    <th class="table-head">Tipo de cambio</th>
                                    <th class="table-head">Fecha de emisión</th>
                                    <th class="table-head">% Morosidad</th>
                                    <th class="table-head">Condición de pago</th>
                                    <th class="table-head">Fecha de vencimiento</th>
                                    <th class="table-head">Proyecto</th>
                                    <th class="table-head">Módulo</th>
                                    <th class="table-head">Propietario principal</th>
                                    <th class="table-head">Subtotal</th>
                                    <th class="table-head">Impuestos</th>
                                    <th class="table-head">Importe total</th>
                                    <th class="table-head">PayPal</th>
                                    <th class="table-head">Estatus</th>
                                    <th class="table-head">Fecha de alta</th>
                                    <th class="table-head">Última actualización</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('ventas_filtro'))
                                    @foreach (session('ventas_filtro') as $venta)
                                        @include('include.proc.ventaItem')
                                    @endforeach
                                @else
                                    @foreach ($ventas as $venta)
                                        @include('include.proc.ventaItem')
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
    <script src="{{ asset('js\procesos\ventas\ventas.js') }}"></script>
@endsection
