@extends('layouts.layout')
@section('content')
    <section class="bg-white">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-10">
                <div class="nk-block-text">
                    <h2 class="title">Facturación Masiva</h2>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-lg-12">
                {{ Breadcrumbs::render() }}
            </div>
        </div>

        {!! Form::open(['route' => 'tools.facturacion.filtro', 'method' => 'POST']) !!}
        <div class="row row row-cols-1 row-cols-sm-2 row-cols-md-4">
            <div class="col-lg-1">
                <div class="mt-2">
                    <input type="radio" name="radioContrato" class="input-checkbox" value="Venta" required="" aria-required="true" id="radioVenta"
                    @if(session()->has('radioContrato')) @if(session('radioContrato') == 'Venta') checked @endif @endif >

                    <label class="field-label" for="radioVenta">Venta</label>
                </div>
                <div class="mt-2">
                    <input type="radio" name="radioContrato" class="input-checkbox" value="Renta" required="" aria-required="true" id="radioRenta"
                    @if(session()->has('radioContrato')) @if(session('radioContrato') == 'Renta') checked @endif @endif
                    >
                    <label for = "radioRenta" class="field-label">Renta</label>
                </div>
            </div>
            <div class="col-lg-5">
                {!! Form::label('selectCliente', 'Cliente', ['class' => 'field-label']) !!}
                {!! Form::select(
                    'selectCliente',
                    ['Todos' => 'Todos'] + $clientes,
                    session()->has('selectCliente') ? session()->get('selectCliente') : null,
                    ['id' => 'selectCliente'],
                ) !!}
            </div>
        
           
            <div class="row col-lg-6" id="fecha-rango">
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Form::label('inputFechaInicio', 'Fecha inicio', ['class' => 'field-label']) !!}
                        <div class="form-group">
                            {!! Form::date('inputFechaInicio', session()->has('inputFechaInicio') ? session()->get('inputFechaInicio') : date('Y-m-d'), [
                            'class' => 'input-bordered', 'id' => 'inputFechaInicio', 'placeholder' => 'DD/MM/AAAA', 'autocomplete' => 'off', 'required' => 'required'
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Form::label('inputFechaFinal', 'Fecha final', ['class' => 'field-label']) !!}
                        <div class="form-group">
                            {!! Form::date('inputFechaFinal', session()->has('inputFechaFinal') ? session()->get('inputFechaFinal') : date('Y-m-d'), [
                            'class' => 'input-bordered', 'id' => 'inputFechaFinal', 'placeholder' => 'DD/MM/AAAA', 'autocomplete' => 'off', 'required' => 'required'
                            ]) !!}
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-lg-12" style="">
                <div class="row">
                    <div class="col-lg-6 botonesM" style="">

                        <a href="{{ route('tools.facturacion') }}" type="reset" class="btn btn-outline btn-sm btn-light  btn-auto-2 float-left mt-3">Restablecer</a>
                        {!!Form::submit('Búsqueda', ['class' => 'btn btn-outline btn-sm btn-primary mt-3', 'name' => 'action'])!!}
                        {!!Form::submit('Exportar excel', ['class' => 'btn btn-outline btn-sm btn-success mt-3', 'name' => 'action'])!!}
            {!! Form::close() !!}
                    </div>

                    <div class="col-lg-4 mt-2" style="">
                        <a href="#" class="btn btn-outline btn-sm btn-success float-right" onclick="facturar()">Facturar</a>
                    </div>
                </div>
              
        
                    <div class="col-md-4 mt-3" style="">
                        <div class="form-group">
                            {!! Form::checkbox(
                                'marcarTodos',
                                'Marcar todos',
                                [],
                                ['id' => 'marcarTodos'],
                            ) !!}
                            {!! Form::label('marcarTodos', 'Marcar todos', '') !!}
                        </div>
                    </div>
                
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
                                    <th class="table-head">Fecha Emisión.</th>
                                    <th class="table-head">Contrato</th>
                                    <th class="table-head">Cliente</th>
                                    <th class="table-head">Importe</th>
                                    <th class="table-head">Saldo</th>
                                    <th class="table-head">Proyecto</th>
                                    <th class="table-head">Módulo</th>
                                    <th class="table-head">Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (session()->has('movimientos_filtro'))
                                    @foreach (session('movimientos_filtro') as $movimiento)
                                    <tr>
                                        <td>
                                            {{-- agregar checkbox --}}
                                            <input type="checkbox" name="movimiento[]" value="{{$movimiento->idVenta}}" id="movimiento{{$movimiento->idVenta}}">

                                        </td>
                                        <td >{{$movimiento->movimiento}}</td>
                                        <td>{{$movimiento->fechaEmision}}</td>
                                        <td>{{$movimiento->getOrigen != null ? $movimiento->getOrigen->movimiento: null}} - {{$movimiento->getOrigen != null ? $movimiento->getOrigen->folioMov: null}}</td>
                                        <td>{{$movimiento->getCliente != null ? $movimiento->getCliente->razonSocial: null}}</td>
                                        <td >${{number_format($movimiento->total, 2)}} </td>
                                        <td >${{number_format($movimiento->saldo, 2)}} </td>
                                        <td >{{$movimiento->getProyecto != null ? $movimiento->getProyecto->nombre: null}} </td>
                                        <td >{{$movimiento->getModulo != null ? $movimiento->getModulo->clave : null}} - {{$movimiento->getModulo != null ? $movimiento->getModulo->descripcion : null}} </td>
                                        <td>{{$movimiento->getModulo != null ? $movimiento->getModulo->tipo : null}}</td>
                                    </tr>
                                 
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
<script src="{{ asset('js\tools\facturacion.js') }}"></script>
@endsection
