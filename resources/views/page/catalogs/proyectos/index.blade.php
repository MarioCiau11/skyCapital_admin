@extends('layouts.layout')


@section('content')
    <section class="bg-white">
        <div class="row">
            <div class="col-lg-6 col-md-10 col-xs-10">
                <div class="nk-block-text">
                    <h2 class="title">Proyectos</h2>
                </div>
            </div>
            <div class="col-lg-6 contenedorBotones"> 
                <div class="item" >
                    <a class="btn btn-dark btn-sm btn-with-icon btn-alt btn-outline toggle-tigger" href="#">
                        <span>Grupos</span>
                        <em class="icon fas fa-caret-down"></em>
                    </a>
                    <ul class="drop-list drop-list-full drop-list-center toggle-class toggle-drop">
                        <li><a href="{{ route('agrup.proyectos.grupo.create') }}" class=""><i class="fa fa-check-circle" aria-hidden="true"></i> Crear</a></li>
                        <li><a href="{{ route('agrup.proyectos.grupo.index') }}" class=""><i class="fa fa-eye" aria-hidden="true"></i> Ver</a></li>
                    </ul>

                </div>
                      
                <div class="item" >
                    <a class="btn btn-dark btn-sm btn-with-icon btn-alt btn-outline toggle-tigger" href="#">
                        <span>Categorías</span>
                        <em class="icon fas fa-caret-down"></em>
                    </a>
                    <ul class="drop-list drop-list-full drop-list-center toggle-class toggle-drop">
                        <li><a href="{{ route('agrup.proyectos.categoria.create') }}" class=""><i class="fa fa-check-circle" aria-hidden="true"></i> Crear</a></li>
                        <li><a href="{{ route('agrup.proyectos.categoria.index') }}" class=""><i class="fa fa-eye" aria-hidden="true"></i>Ver</a></li>
                    </ul>
                </div>
                <div class="item">
                        <a href="{{ route('cat.proyectos.create') }}" class="btn btn-outline btn-sm btn-success ">Crear
                            Proyecto</a>
                </div>    
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-lg-12">
                {{ Breadcrumbs::render() }}
            </div>
        </div>
        {!! Form::open(['route' => 'cat.proyectos.filtro', 'method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-5">
                {!! Form::label('clave', 'Clave', ['class' => 'field-label']) !!}
                {!! Form::text('inputClave', session()->has('clave') ? session()->get('clave') : null, [
                    'class' => 'input-bordered',
                    'id' => 'clave'
                ]) !!}
            </div>
            <div class="col-lg-5">
                {!! Form::label('nombre', 'Nombre', ['class' => 'field-label']) !!}
                {!! Form::text('inputName', session()->has('nombre') ? session()->get('nombre') : null, [
                    'class' => 'input-bordered',
                    'id' => 'nombre'
                ]) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label('selectEstatus', 'Estatus', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectEstatus',
                    ['1' => 'Alta', '0' => 'Baja', 'Todos' => 'Todos'],
                    // session()->has('status') ? session()->get('status') : 'Todos',
                    session()->get('status2','Todos'),
                    ['id' => 'selectEstatus', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-12">
                <div class="botonesM">
                    <a href="{{ route('cat.proyectos.index') }}" type="reset"
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
                                            {!! Form::checkbox('Clave', '1', true, ['id' => 'checkClave']) !!}
                                            {!! Form::label('checkClave', 'Clave', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Nombre', '2', true, ['id' => 'checkNombre']) !!}
                                            {!! Form::label('checkNombre', 'Nombre', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Presupuesto', '3', true, ['id' => 'checkDescripcion']) !!}
                                            {!! Form::label('checkDescripcion', 'Descripción ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Presupuesto', '4', true, ['id' => 'checkPresupuesto']) !!}
                                            {!! Form::label('checkPresupuesto', 'Presupuesto ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Año', '5', true, ['id' => 'checkAño']) !!}
                                            {!! Form::label('checkAño', 'Año Inicio Proyecto ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('MT2', '6', false, ['id' => 'checkMT2']) !!}
                                            {!! Form::label('checkMT2', 'MT2 ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Niveles', '7', false, ['id' => 'checkNiveles']) !!}
                                            {!! Form::label('checkNiveles', 'Niveles Totales', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Categoría', '8', false, ['id' => 'checkCategoría']) !!}
                                            {!! Form::label('checkCategoría', 'Categoría ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Grupo', '9', false, ['id' => 'checkGrupo']) !!}
                                            {!! Form::label('checkGrupo', 'Grupo', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Estatus', '10', true, ['id' => 'checkEstatus']) !!}
                                            {!! Form::label('checkEstatus', 'Estatus', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>

                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Creado', '11', false, ['id' => 'checkFechaa']) !!}
                                            {!! Form::label('checkFechaa', 'Creado', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Actualizado', '12', false, ['id' => 'checkFechac']) !!}
                                            {!! Form::label('checkFechac', 'Actualizado', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Baja', '13', false, ['id' => 'checkFechab']) !!}
                                            {!! Form::label('checkFechab', 'Baja', ['class' => 'negrita']) !!}
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
                                    <th class="table-head">Descripción</th>
                                    <th class="table-head">Presupuesto</th>
                                    <th class="table-head">Año Inicio Proyecto</th>
                                    <th class="table-head">MT2</th>
                                    <th class="table-head">Niveles Totales</th>
                                    <th class="table-head">Categoría</th>
                                    <th class="table-head">Grupo</th>
                                    <th class="table-head">Estatus</th>
                                    <th class="table-head">Fecha Creación</th>
                                    <th class="table-head">Última Actualización</th>
                                    <th class="table-head">Fecha de Baja</th>   
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('proyectos_filtro'))
                                
                                    @foreach (session('proyectos_filtro') as $proyecto)
                                        @include('include.catalogs.proyectosItem')
                                    @endforeach
                                @else
                                    @foreach ($Proyectos as $proyecto)
                                        @include('include.catalogs.proyectosItem')
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
    <script src="{{ asset('js/catalogos/proyectos.js') }}"></script>
@endsection
