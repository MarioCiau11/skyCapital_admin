@extends('layouts.layout')


@section('content')
    <section>
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-10">
                <div class="nk-block-text">
                    <h2 class="title">Autorizaciones</h2>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 float-left">
                <a href="{{ route('config.autorizaciones.create') }}" class="btn btn-outline btn-sm btn-success float-left">Crear
                    autorización</a>
            </div>
        </div>

        <div class="row ">
            <div class="col-lg-12">
                {{ Breadcrumbs::render() }}
            </div>
        </div>
        {!! Form::open(['route' => 'config.monedas.filtro', 'method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-12">
                {!! Form::label('nombre', 'Nombre', ['class' => 'field-label']) !!}
                {!! Form::text('inputName', session()->has('nombre') ? session()->get('nombre') : null, [
                    'class' => 'input-bordered',
                ]) !!}
            </div>
            <div class="col-lg-12 ">
                <div class="botonesM">
                <a href="{{ route('config.monedas.index') }}" type="reset"
                    class="btn btn-outline btn-sm btn-light  btn-auto-2 float-left mt-3">Restablecer</a>
                {!! Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3', 'name' => 'action']) !!}
                {!! Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action']) !!}
                {!! Form::close() !!}
                </div>

                {{-- columnas --}}
                <ul class="menu mx-auto btn btn-outline btn-sm btn-light btn-auto float-right mt-2">
                    <li class="menu-item has-sub">
                        <a class="menu-link nav-link menu-toggle" href="#">Columnas</a>
                        <div class="menu-sub menu-drop menu-mega ">
                            <div class="menu-mega-innr">
                                <ul class="menu-mega-list" id="shCol">
                                    <li class="menu-item">
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Opciones', '0', true, ['id' => 'checkOpciones']) !!}
                                            {!! Form::label('checkOpciones', 'Opciones', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Modulo', '1', true, ['id' => 'checkclave']) !!}
                                            {!! Form::label('checkclave', ' Modulo', ['class' => 'negrita']) !!}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ckbox ckbox-primary">
                                            {!! Form::checkbox('Movimiento', '2', true, ['id' => 'checkSAT']) !!}
                                            {!! Form::label('checkSAT', 'Movimiento ', ['class' => 'negrita']) !!}
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
                                    <th class="table-head">Modulo</th>
                                    <th class="table-head">Movimiento</th> 
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @if (session()->has('monedas_filtro'))
                                
                                    @foreach (session('monedas_filtro') as $moneda)
                                        @include('include.config.monedasItem')
                                    @endforeach
                                @else
                                    @foreach ($Monedas as $moneda)
                                        @include('include.config.monedasItem')
                                    @endforeach
                                @endif --}}
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
    <script src="{{ asset('js/config/monedas.js') }}"></script>
@endsection
