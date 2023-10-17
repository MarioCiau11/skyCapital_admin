@extends('layouts.layout')

@section('content')

<?php 
    // dd($roles);
?>
<section class="bg-white">
    <div class="row">
        <div class="col-lg-10 col-md-10 col-xs-10">
            <div class="nk-block-text">
                <h2 class="title">Usuarios</h2>
            </div>
        </div>
        <div class="col-lg-2 col-sm-12 float-left">
            <a href="{{route("config.usuarios.create")}}" class="btn btn-outline btn-sm btn-success float-left">Crear Usuario</a>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-lg-12">
            {{ Breadcrumbs::render() }}
        </div>
    </div>
    {!! Form::open(['route' => 'config.usuarios.filtro', 'method'=>'POST']) !!}
    {{-- <form  action="{{ route('config.usuarios.filtro')}}" method="post"> --}}
        {{-- @csrf --}}
    <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
        <div class="col-lg-4">
            {!! Form::label('nombre', 'Nombre', array('class' => 'field-label')) !!}
            {!! Form::text('inputName',
            session()->has('nombre') ? session()->get('nombre') : null,['class'=>'input-bordered']) !!}
        </div>
        <div class="col-lg-4">
            {!! Form::label('nombre', 'Usuario', array('class' => 'field-label')) !!}
            {!! Form::text('inputUser',
            session()->has('user') ? session()->get('user') : null,['class'=>'input-bordered']) !!}
        </div>
        <div class="col-sm-4 col-lg-2">
            {!! Form::label('Rol', 'Rol', array('class' => 'field-label')) !!}
            {!! Form::select('selectRol', $roles, 
            session()->has('rol') ? session()->get('rol') : null, array('id' => 'selectRol', "class" => '', 'placeholder' => 'Todos')) !!} 
        </div>
        <div class="col-sm-4 col-lg-2">
            {!! Form::label('status', 'Estatus', array('class' => 'field-label')) !!}
            {!! Form::select('selectEstatus', ['Todos' => 'Todos', '1' => 'Alta', '0' => 'Baja'], 
            session()->has('estatus') ? session()->get('estatus') : 'Todos', array('id' => 'selectEstatus', "class" => '')) !!} 
        </div>


        <div class="col-lg-12">
            {{-- <div class="botonesM">
            <a href="{{route('config.usuarios.index')}}" type="reset" class="btn btn-outline btn-sm btn-light mt-3">Restablecer</a>
            {!!Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3 btnBusqueda', 'name' => 'action'])!!}
            {!!Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action'])!!}
            {!! Form::close() !!}
            </div> --}}
            <div class="botonesM">
                <a href="{{ route('config.usuarios.index') }}" type="reset"
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
                        <ul class="menu-mega-list" id="shCol">
                            <li class="menu-item">
                                <div class="ckbox ckbox-primary">
                                    {!!Form::checkbox('Opciones', '0', true, ['id' => 'checkOpciones'])!!}
                                    {!!Form::label('checkOpciones', 'Opciones', array('class' => 'negrita'))!!}
                                </div>
                            </li>
                            <li>
                                <div class="ckbox ckbox-primary">
                                    {!!Form::checkbox('ID', '1', true, ['id' => 'checkClave'])!!}
                                    {!!Form::label('checkClave', 'ID', array('class' => 'negrita'))!!}
                                </div>
                            </li>
                            
                            
                            <li>
                                <div class="ckbox ckbox-primary">
                                    {!!Form::checkbox('nombre', '2', true, ['id' => 'checkNombre'])!!}
                                    {!!Form::label('checkNombre', 'Nombre', array('class' => 'negrita'))!!}
                                </div>
                            </li>
                            <li>
                                <div class="ckbox ckbox-primary">
                                    {!!Form::checkbox('Usuario', '3', true, ['id' => 'checkUsuario'])!!}
                                    {!!Form::label('checkUsuario', 'Usuario', array('class' => 'negrita'))!!}
                                </div>
                            </li>
                            <li>
                                <div class="ckbox ckbox-primary">
                                    {!!Form::checkbox('Correo', '4', false, ['id' => 'checkDescripcion'])!!}
                                    {!!Form::label('checkDescripcion', 'Correo', array('class' => 'negrita'))!!}
                                </div>
                            </li>
                            <li>
                                <div class="ckbox ckbox-primary">
                                    {!!Form::checkbox('rol', '5', true, ['id' => 'checkrol'])!!}
                                    {!!Form::label('checkrol', 'Rol', array('class' => 'negrita'))!!}
                                </div>
                            </li>
                            <li>
                                <div class="ckbox ckbox-primary">
                                    {!!Form::checkbox('estatus', '6', false, ['id' => 'checkEstatus'])!!}
                                    {!!Form::label('checkEstatus', 'Estatus', array('class' => 'negrita'))!!}
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
                    <table  class="table table-striped table-bordered widthAll datatable" id="shTable">
                        <thead>
                            <tr>
                                <th class="table-head">Opciones</th>
                                <th class="table-head">ID</th>
                                <th class="table-head">Nombre</th>
                                <th class="table-head">Usuario</th>
                                <th class="table-head">Correo electrónico</th>
                                <th class="table-head">Rol</th>
                                <th class="table-head">Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(session()->has('user_filtro'))
                                @foreach (session('user_filtro') as $user)
                                    @include('include.config.usersItem')
                                @endforeach
                                @else
                                @foreach ($users as $user)
                                    @include('include.config.usersItem')
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

<script src="{{ asset('js/config/usuarios.js')}}"></script>
@endsection