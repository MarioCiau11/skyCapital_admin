@extends('layouts.layout')

@section('content')
<section class="bg-white">
    <div class="row">
        <div class="col-lg-10 col-md-10 col-xs-10">
            <div class="nk-block-text">
                <h2 class="title">Cuentas de Dinero</h2>
            </div>
        </div>
        <div class="col-lg-2 col-sm-12 float-left">
            <a href="{{ route('cat.cuentas-dinero.create') }}" class="btn btn-outline btn-sm btn-success float-left">Crear
                Cuenta de Dinero</a>
        </div>
    </div>

    <div class="row  mt-2">
        <div class="col-lg-12">
            {{ Breadcrumbs::render() }}
        </div>
    </div>
    {!! Form::open(['route' => 'cat.cuentas-dinero.filtro', 'method' => 'POST']) !!}
    <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
        <div class="col-lg-5">
            {!! Form::label('inputClave', 'Clave', ['class' => 'field-label']) !!}
            {!! Form::text('inputClave', session()->has('inputClave') ? session()->get('inputClave') : null, [
                'class' => 'input-bordered',
            ]) !!}
        </div>
        <div class="col-lg-5">
            {!! Form::label('inputCuenta', 'Cuenta', ['class' => 'field-label']) !!}
            {!! Form::text('inputCuenta', session()->has('inputCuenta') ? session()->get('inputCuenta') : null, [
                'class' => 'input-bordered',
            ]) !!}
        </div>
        <div class="col-lg-2">
            {!! Form::label('status', 'Estatus', ['class' => 'field-label']) !!}
            {!! Form::select(
                'selectEstatus',
                ['1' => 'Alta', '0' => 'Baja', 'Todos' => 'Todos'],
                session()->get('selectEstatus', 'Todos'),
                ['id' => 'selectEstatus', 'class' => ''],
            ) !!}
        </div>
        <div class="col-lg-12 ">
            <div class="botonesM">
            <a href="{{ route('cat.cuentas-dinero.index') }}" type="reset"
                class="btn btn-outline btn-sm btn-light  btn-auto-2 float-left mt-3">Restablecer</a>
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
                                        {!! Form::checkbox('Banco', '2', true, ['id' => 'checkBanco']) !!}
                                        {!! Form::label('checkBanco', 'Banco ', ['class' => 'negrita']) !!}
                                    </div>
                                </li>
                                <li>
                                    <div class="ckbox ckbox-primary">
                                        {!! Form::checkbox('No. de Cuenta', '3', true, ['id' => 'checknoCuenta']) !!}
                                        {!! Form::label('checknoCuenta', 'No. de Cuenta', ['class' => 'negrita']) !!}
                                    </div>
                                </li>
                                <li>
                                    <div class="ckbox ckbox-primary">
                                        {!! Form::checkbox('Tipo de cuenta', '4', true, ['id' => 'checkTipo']) !!}
                                        {!! Form::label('checkTipo', 'Tipo de cuenta', ['class' => 'negrita']) !!}
                                    </div>
                                </li>
                                <li>
                                    <div class="ckbox ckbox-primary">
                                        {!! Form::checkbox('Empresa', '5', true, ['id' => 'checkEmpresa']) !!}
                                        {!! Form::label('checkEmpresa', 'Empresa', ['class' => 'negrita']) !!}
                                    </div>
                                </li>
                                <li>
                                    <div class="ckbox ckbox-primary">
                                        {!! Form::checkbox('Estatus', '6', true, ['id' => 'checkEstatus']) !!}
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
                                <th class="table-head">Opciones</th>
                                <th class="table-head">Clave</th>
                                <th class="table-head">Banco</th>
                                <th class="table-head">No. de Cuenta</th>
                                <th class="table-head">Tipo de Cuenta</th>
                                <th class="table-head">Empresa</th>
                                <th class="table-head">Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (session()->has('cuentas_filtro'))
                            
                                @foreach (session('cuentas_filtro') as $cuentas)
                                    @include('include.catalogs.cuentasDineroItem')
                                @endforeach
                            @else
                                @foreach ($CuentasDinero as $cuentas)
                                    @include('include.catalogs.cuentasDineroItem')
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
    <script src="{{asset('js\catalogos\cuentas-dinero.js')}}"></script>
@endsection