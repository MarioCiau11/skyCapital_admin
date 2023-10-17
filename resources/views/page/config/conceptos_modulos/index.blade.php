@extends('layouts.layout')


@section('content')
<section class="bg-white">
    <div class="row">
        <div class="col-lg-10 col-md-10 col-xs-10">
            <div class="nk-block-text">
                <h2 class="title">Conceptos de módulos</h2>
            </div>
        </div>
        <div class="col-lg-2 col-sm-12 float-left">
            <a href="{{route("config.conceptos-modulos.create")}}" class="btn btn-outline btn-sm btn-success float-left">Crear concepto</a>
        </div>
    </div>

    <div class="row ">
        <div class="col-lg-12">
            {{ Breadcrumbs::render() }}
        </div>
    </div>
    {!! Form::open(['route' => 'config.conceptos-modulos.filtro','method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-10">
                {!! Form::label('nombre', 'Nombre', array('class' => 'field-label')) !!}
                {!! Form::text('inputName',
                session()->has('nombre') ?session()->get('nombre'): null , ['class' => 'input-bordered']) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label('status', 'Estatus', array('class' => 'field-label')) !!}
                {!! Form::select('selectEstatus', ['1' => 'Alta', '0' => 'Baja',  'Todos' => 'Todos'], session()->has('status') ? session()->get('status') : 'Alta', array('id' => 'selectEstatus', "class" => '')) !!}
            </div>
            <div class="col-lg-12 ">
                <div class="botonesM">
                <a href="{{route('config.conceptos-modulos.index')}}" type="reset" class="btn btn-outline btn-sm btn-light  btn-auto-2 float-left mt-3">Restablecer</a>
                {!!Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3 btnBusqueda', 'name' => 'action'])!!}
                {!!Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action'])!!}
    {!! Form::close() !!}
                </div>

                {{--columnas--}}
                <ul class="menu mx-auto btn btn-outline btn-sm btn-light btn-auto float-right mt-2" >
                    <li class="menu-item has-sub">
                        <a class="menu-link nav-link menu-toggle" href="#">Columnas</a>
                        <div class="menu-sub menu-drop menu-mega ">
                            <div class="menu-mega-innr">
                                <ul class="menu-mega-list" id="shCol">
                                    <li class="menu-item">
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Opciones', '0', true, ['id' => 'checkOpciones   '])!!}
                                            {!!Form::label('checkOpciones  ', 'Opciones  ', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li class="menu-item">
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Concepto', '1', true, ['id' => 'checkConcepto   '])!!}
                                            {!!Form::label('checkConcepto  ', 'Concepto  ', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Modulo', '2', true, ['id' => 'checkModulo'])!!}
                                            {!!Form::label('checkModulo', 'Modulo', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Movimiento', '3', false, ['id' => 'checkMovimiento'])!!}
                                            {!!Form::label('checkMovimiento', 'Movimiento ', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Estatus', '4', false, ['id' => 'checkEstatus'])!!}
                                            {!!Form::label('checkEstatus', 'Estatus ', array('class' => 'negrita'))!!}
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
        <div class="col-lg-12 col-sm-6">
            <div class=" table-responsive">
                <div class="datatable-container">
                    <table class="table table-striped table-bordered widthAll datatable" id="shTable">
                        <thead>
                            <tr>
                                <th class="table-head">Opciones</th>
                                <th class="table-head">Nombre</th>
                                <th class="table-head">Módulo</th>
                                <th class="table-head">Movimiento</th>
                                <th class="table-head">Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (session()->has('concepto_filtro'))
                                @foreach (session('concepto_filtro') as $conceptos)
                                    @include('include.config.conceptosModItem')
                                @endforeach
                            @else
                                 @foreach ($ConceptosMod as $conceptos)
                                @include('include.config.conceptosModItem')
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
<script src="{{asset('js/config/conceptos_modulos.js')}}"></script>
@endsection
