@extends('layouts.layout')


@section('content')
<section class="bg-white">
    <div class="row">
        <div class="col-lg-10 col-md-10 col-xs-10">
            <div class="nk-block-text">
                <h2 class="title">Condiciones de crédito</h2>
            </div>
        </div>
        <div class="col-lg-2 col-sm-12 float-left">
            <a href="{{route("config.condiciones-credito.create")}}" class="btn btn-outline btn-sm btn-success float-left">Crear condición</a>
        </div>
    </div>

    <div class="row  mt-2">
        <div class="col-lg-12">
            {{ Breadcrumbs::render() }}
        </div>
    </div>
    {!! Form::open(['route' => 'config.condiciones-credito.filtro','method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-10">
                {!! Form::label('nombre', 'Nombre', array('class' => 'field-label')) !!}
                {!! Form::text('inputName',
                session()->has('nombre') ?session()->get('nombre'): null , ['class' => 'input-bordered']) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label('status', 'Estatus', array('class' => 'field-label')) !!}
                {!! Form::select('selectEstatus', ['1' => 'Alta', '0' => 'Baja',  'Todos' => 'Todos'],
                 session()->get('estatus', 'Todos'),
                array('id' => 'selectEstatus', "class" => '')) !!}
            </div>
            <div class="col-lg-12 ">
                <div class="botonesM">
                <a href="{{route('config.condiciones-credito.index')}}" type="reset" class="btn btn-outline btn-sm btn-light  btn-auto-2 float-left mt-3">Restablecer</a>
                {!!Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3 btnBusqueda', 'name' => 'action'])!!}
                {!!Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action'])!!}
    {!! Form::close() !!}
                </div>

                {{--columnas--}}
                <ul class="menu menu2 mx-auto btn btn-outline btn-sm btn-light btn-auto float-right mt-2" >
                    <li class="menu-item has-sub">
                        <a class="menu-toggle typografy" href="#">Columnas</a>
                        <div class="menu-sub menu-drop menu-mega ">
                            <div class="menu-mega-innr">
                                <ul class="menu-mega-list" id="shCol">
                                    <li class="menu-item">
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Opciones', '0', true, ['id' => 'checkOpciones'])!!}
                                            {!!Form::label('checkOpciones', 'Opciones', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Nombre', '1', true, ['id' => 'checkNombre'])!!}
                                            {!!Form::label('checkNombre', 'Nombre', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Tipo condición', '2', true, ['id' => 'checkTipo'])!!}
                                            {!!Form::label('checkTipo', 'Tipo condición ', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Días vencimiento', '3', true, ['id' => 'checkDias'])!!}
                                            {!!Form::label('checkDias', 'Días vencimiento', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Tipo días', '4', true, ['id' => 'checkTipod'])!!}
                                            {!!Form::label('checkTipod', 'Tipo días ', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Días hábiles', '5', false, ['id' => 'checkDiash'])!!}
                                            {!!Form::label('checkDiash', 'Días hábiles', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    {{-- <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Método pago', '6', true, ['id' => 'checkMetodo'])!!}
                                            {!!Form::label('checkMetodo', 'Método pago', array('class' => 'negrita'))!!}
                                        </div>
                                    </li> --}}
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Estatus', '6', true, ['id' => 'checkEstatus'])!!}
                                            {!!Form::label('checkEstatus', 'Estatus', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Usuario', '7', false, ['id' => 'checkUsuario'])!!}
                                            {!!Form::label('checkUsuario', 'Usuario', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Creado', '8', false, ['id' => 'checkfechaa'])!!}
                                            {!!Form::label('checkfechaa', 'Creado', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Actualizado', '9', false, ['id' => 'checkfechac'])!!}
                                            {!!Form::label('checkfechac', 'Actualizado', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Baja', '10', false, ['id' => 'checkfechab'])!!}
                                            {!!Form::label('checkfechab', 'Baja', array('class' => 'negrita'))!!}
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
                                <th class="table-head">Nombre</th>
                                <th class="table-head">Tipo de condición</th>
                                <th class="table-head">Días para vencimiento</th>
                                <th class="table-head">Tipo de días</th>
                                <th class="table-head">Días hábiles</th>
                                {{-- <th class="table-head">Método de pago SAT</th> --}}
                                <th class="table-head">Estatus</th>
                                <th class="table-head">Usuario</th>
                                <th class="table-head">Fecha Creación</th>
                                <th class="table-head">Última Actualización</th>
                                <th class="table-head">Fecha de Baja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (session()->has('condicion_filtro'))
                                @foreach (session('condicion_filtro') as $condicion)
                                    @include('include.config.condicionesItem')
                                @endforeach
                            @else
                                @foreach ($condiciones as $condicion)
                                    @include('include.config.condicionesItem')
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
<script src="{{ asset('js/config/condiciones_credito.js')}}"></script>
@endsection
