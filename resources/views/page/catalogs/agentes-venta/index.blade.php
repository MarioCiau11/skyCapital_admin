@extends('layouts.layout')


@section('content')
<section class="bg-white">
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="nk-block-text">
                <h2 class="title">Asesor Comercial</h2>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 contenedorBotones"> 
            <div class="item">
                <a class="btn btn-dark btn-sm btn-with-icon btn-alt btn-outline toggle-tigger" href="#">
                    <span>Grupos</span>
                    <em class="icon fas fa-caret-down"></em>
                </a>
                <ul class="drop-list drop-list-full drop-list-center toggle-class toggle-drop">
                    <li><a href="{{ route('agrup.grupo.create') }}" class=""><i class="fa fa-check-circle" aria-hidden="true"></i> Crear</a></li>
                    <li><a href="{{ route('agrup.grupo.index') }}" class=""><i class="fa fa-eye" aria-hidden="true"></i> Ver</a></li>
                </ul>
            </div>

                
            <div class="item">
                <a class="btn btn-dark btn-sm btn-with-icon btn-alt btn-outline toggle-tigger" href="#">
                    <span>Categorías</span>
                    <em class="icon fas fa-caret-down"></em>
                </a>
                <ul class="drop-list drop-list-full drop-list-center toggle-class toggle-drop">
                    <li><a href="{{route('agrup.categoria.create')}}" class=""><i class="fa fa-check-circle" aria-hidden="true"></i> Crear</a></li>
                    <li><a href="{{ route('agrup.categoria.index') }}" class=""><i class="fa fa-eye" aria-hidden="true"></i>Ver</a></li>
                </ul>
            </div>

            <div class="item">
                    <a href="{{ route('cat.agentes-venta.create') }}" class="btn btn-outline btn-sm btn-success ">
                    Crear
                    Asesor Comercial
                </a>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-lg-12">
            {{ Breadcrumbs::render() }}
        </div>
    </div>
    {!! Form::open(['route' => 'cat.agentes-venta.filtro','method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-3">
                {!! Form::label('inputClave', 'Clave', array('class' => 'field-label')) !!}
                {!! Form::text('inputClave',
                session()->has('inputClave') ? session()->get('inputClave') : null, ['class' => 'input-bordered']) !!}
            </div>
            <div class="col-lg-7">
                {!! Form::label('inputName', 'Nombre', array('class' => 'field-label')) !!}
                {!! Form::text('inputName',
                session()->has('inputName') ?session()->get('inputName'): null , ['class' => 'input-bordered']) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label('selectEstatus', 'Estatus', array('class' => 'field-label')) !!}
                {!! Form::select('selectEstatus', ['1' => 'Alta', '0' => 'Baja',  'Todos' => 'Todos'], session()->has('selectEstatus') ? session()->get('selectEstatus') : 'Todos', array('id' => 'selectEstatus', "class" => '')) !!}
            </div>
            <div class="col-lg-12 ">
                <div class="botonesM">
                <a href="{{route('cat.agentes-venta.index')}}" type="reset" class="btn btn-outline btn-sm btn-light  btn-auto-2 float-left mt-3">Restablecer</a>
                {!!Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3', 'name' => 'action'])!!}
                {!!Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action'])!!}
    {!! Form::close() !!}
                </div>

                {{--columnas--}}
                <ul class="menu menu2 mx-auto btn btn-outline btn-sm btn-light btn-auto float-right mt-2" >
                    <li class="menu-item has-sub">
                        <a class="menu-link nav-link menu-toggle typografy" href="#">Columnas</a>
                        <div class="menu-sub menu-drop menu-mega ">
                            <div class="menu-mega-innr">
                                <ul class="menu-mega-list" id="shCol" style="z-index:1000;">
                                    <li class="menu-item">
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Opciones', '0', true, ['id' => 'checkOpciones'])!!}
                                            {!!Form::label('checkOpciones', 'Opciones', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Clave', '1', true, ['id' => 'checkClave'])!!}
                                            {!!Form::label('checkClave', 'Clave', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Nombre', '2', true, ['id' => 'checkNombre'])!!}
                                            {!!Form::label('checkNombre', 'Nombre ', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Tipo', '3', true, ['id' => 'checkTipo'])!!}
                                            {!!Form::label('checkTipo', 'Tipo ', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Categoría', '4', true, ['id' => 'checkCategoria'])!!}
                                            {!!Form::label('checkCategoria', 'Categoría', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!!Form::checkbox('Grupo', '5', true, ['id' => 'checkGrupo'])!!}
                                            {!!Form::label('checkGrupo', 'Grupo', array('class' => 'negrita'))!!}
                                        </div>
                                    </li>
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
                                <th class="table-head">Clave</th>
                                <th class="table-head">Nombre</th>
                                <th class="table-head">Tipo</th>
                                <th class="table-head">Categoría</th>
                                <th class="table-head">Grupo</th>
                                <th class="table-head">Estatus</th>
                                <th class="table-head">Usuario que lo Registró</th>
                                <th class="table-head">Fecha Creación</th>
                                <th class="table-head">Última Actualización</th>
                                <th class="table-head">Fecha de Baja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (session()->has('agente_filtro'))
                                @foreach (session('agente_filtro') as $agente)
                                    @include('include.catalogs.agentesItem')
                                @endforeach
                            @else
                                @foreach ($Agentes as $agente)
                                    @include('include.catalogs.agentesItem')
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
<script src="{{ asset('js\catalogos\agentes-venta.js')}}"></script>
@endsection
