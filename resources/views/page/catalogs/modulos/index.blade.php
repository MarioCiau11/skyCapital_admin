@extends('layouts.layout')


@section('content')
    <section class="bg-white" id="contenedorPadre">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-10">
                <div class="nk-block-text">
                    <h2 class="title">Módulos</h2>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 float-left">
                <a href="{{ route('cat.modulos.create') }}" class="btn btn-outline btn-sm btn-success float-left">Crear
                    Módulo</a>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-lg-12">
                {{ Breadcrumbs::render() }}
            </div>
        </div>
        {!! Form::open(['route' => 'cat.modulos.filtro', 'method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-5">
                {!! Form::label('clave', 'Módulo', ['class' => 'field-label']) !!}
                {!! Form::text('inputClave', session()->has('clave') ? session()->get('clave') : null, [
                    'class' => 'input-bordered',
                    'id' => 'clave'
                ]) !!}
            </div>
            <div class="col-lg-5">
                {!! Form::label('nombre', 'Descripción', ['class' => 'field-label']) !!}
                {!! Form::text('inputName', session()->has('nombre') ? session()->get('nombre') : null, [
                    'class' => 'input-bordered',
                    'id' => 'nombre'
                ]) !!}
            </div>
            <div class="col-lg-2">
                {!! Form::label('selectEstatus', 'Estatus', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectEstatus',
                    ['Disponible' => 'Disponible', 'No disponible' => 'No disponible', 'Apartado' => 'Apartado', 'Vendido' => 'Vendido', 'Baja' => 'Baja', 'Todos' => 'Todos'],
                    session()->get('status','Todos'),
                    ['id' => 'selectEstatus', 'class' => ''],
                ) !!}
            </div>
            <div class="col-lg-12 ">
                <div class="botonesM">
                <a href="{{ route('cat.modulos.index') }}" type="reset"
                    class="btn btn-outline btn-sm btn-light btn-auto-2 float-left mt-3">Restablecer</a>
                {!! Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3 btnBusqueda', 'name' => 'action']) !!}
                {!! Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action']) !!}
                {!! Form::close() !!}
                </div>

                {{-- columnas --}}
                <ul class="menu menu2 mx-auto btn btn-outline btn-sm btn-light btn-auto float-right mt-2" id="principal">
                    <li class="menu-item has-sub">
                        <a class="menu-link nav-link menu-toggle typografy" href="#">Columnas</a>
                        <div class="menu-sub menu-drop menu-mega " id="shCol3">
                            <div class="menu-mega-innr" id="shCol2">
                                <ul class="menu-mega-list" id="shCol" style="overflow:auto;">
                                    <li class="menu-item">
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Opciones', '0', true, ['id' => 'checkOpciones']) !!}
                                            {!! Form::label('checkOpciones', 'Opciones', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Modulo', '1', true, ['id' => 'checkModulo']) !!}
                                            {!! Form::label('checkModulo', 'Módulo', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Descripcion', '2', true, ['id' => 'checkDescripcion']) !!}
                                            {!! Form::label('checkDescripcion', 'Descripción ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Tipo', '3', true, ['id' => 'checkTipo']) !!}
                                            {!! Form::label('checkTipo', 'Tipo ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Proyecto', '4', true, ['id' => 'checkProyecto']) !!}
                                            {!! Form::label('checkProyecto', 'Proyecto ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('MT2', '5', true, ['id' => 'checkMT2']) !!}
                                            {!! Form::label('checkMT2', 'MT2 ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Valor', '6', true, ['id' => 'checkValor']) !!}
                                            {!! Form::label('checkValor', 'Valor ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Nivel', '7', true, ['id' => 'checkNivel']) !!}
                                            {!! Form::label('checkNivel', 'Nivel ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Cajones', '8', false, ['id' => 'checkCajones']) !!}
                                            {!! Form::label('checkCajones', 'Cajones ', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Banco', '9', false, ['id' => 'checkBanco']) !!}
                                            {!! Form::label('checkBanco', 'Banco', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Cuenta', '10', false, ['id' => 'checkCuenta']) !!}
                                            {!! Form::label('checkCuenta', 'Cuenta', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Clabe', '11', false, ['id' => 'checkClabe']) !!}
                                            {!! Form::label('checkClabe', 'Clabe', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Estatus', '12', true, ['id' => 'checkEstatus']) !!}
                                            {!! Form::label('checkEstatus', 'Estatus', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>

                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Creado', '13', false, ['id' => 'checkFechaa']) !!}
                                            {!! Form::label('checkFechaa', 'Creado', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Actualizado', '14', false, ['id' => 'checkFechac']) !!}
                                            {!! Form::label('checkFechac', 'Actualizado', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Baja', '15', false, ['id' => 'checkFechab']) !!}
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
                                    <th class="table-head">Módulo</th>
                                    <th class="table-head">Descripción</th>
                                    <th class="table-head">Tipo</th>
                                    <th class="table-head">Proyecto</th>
                                    <th class="table-head">MT2</th>
                                    <th class="table-head th-200">Valor Operación</th>
                                    <th class="table-head">Nivel/Piso</th>
                                    <th class="table-head th-200"># Cajones Estacionamiento</th>
                                    <th class="table-head">Banco</th>
                                    <th class="table-head">Cuenta</th>
                                    <th class="table-head">Clabe</th>
                                    <th class="table-head">Estatus</th>
                                    <th class="table-head">Fecha Creación</th>
                                    <th class="table-head">Última Actualización</th>
                                    <th class="table-head">Fecha de Baja</th>   
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('modulos_filtro'))
                                
                                    @foreach (session('modulos_filtro') as $modulo)
                                        @include('include.catalogs.modulosItem')
                                    @endforeach
                                @else
                                    @foreach ($Modulos as $modulo)
                                        @include('include.catalogs.modulosItem')
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
    <script src="{{ asset('js/catalogos/modulos.js') }}"></script>
@endsection
