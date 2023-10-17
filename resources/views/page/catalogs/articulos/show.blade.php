@extends('layouts.layout')

@section('content')
<section class="bg-white">
    <div class="columns is-marginless is-centered is-mobile">
        <div class="column is-11">

        <div class="columns is-mobile">
            <div class="column">
            <h1 class="title is-3">Artículo: {{$Articulos->nombre}}</h1>
            </div>
        </div>
        <hr class="is-marginless">
        {{ Breadcrumbs::render('cat.articulos.show', $Articulos) }}

        <div class="columns">
            <div class="column">
                <div class="buttons">
                <ul class="btn-grp gutter-20px gutter-vr-20px">
                    <li> <a href="{{route('cat.articulos.edit', Crypt::encrypt($Articulos->idArticulos))}}" class="badge badge-outline badge-info">Editar Artículo</a></li>
                    <li>
                        <form id="delete-form" action="{{ route('cat.articulos.destroy', Crypt::encrypt($Articulos->idArticulos)) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        @if ($Articulos->estatus == 1)
                            <button id="button-delete" type="button" class="badge badge-outline badge-danger delete2">Baja</button>
                        @endif
                        </form>
                    </li>
                    <li>
                        <a href="#" class="badge badge-outline badge-light" data-toggle="modal" data-target="#modal-small">Histórico</a>
                    </li>
                </ul>
                {{-- <a href="{{route('cat.articulos.historico')}}" class="badge badge-outline badge-light">historico</a> --}}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="field-item">
                        {!! Form::label('inputClave', 'Clave', ['class' => 'field-label']) !!}
                        <div class="field-wrap">
                            {!! Form::text('inputClave', $Articulos->clave , ['class' => 'input-bordered','id' => 'inputClave','disabled']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="field-item">
                        {!! Form::label('selectTipo', 'Tipo', ['class' => 'field-label']) !!}
                        <div class="field-wrap">
                            {!! Form::select('selectTipo', ['Servicio' => 'Servicio'], $Articulos->tipo,['id' => 'selectTipo','disabled']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="field-item">
                        {!! Form::label('selectEstatus', 'Estatus', ['class'=> 'field-label']) !!}
                        <div class="field-wrap">
                            {!! Form::select('selectEstatus', [ 1 => 'Alta', 0 => 'Baja' ],
                            $Articulos->estatus, 
                            ['id' => 'selectEstatus','disabled']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="field-item">
                        {!! Form::label('inputDescripcion', 'Descripción', ['class' => 'field-label']) !!}
                        <div class="field-wrap">
                            {!! Form::text('inputDescripcion', $Articulos->descripcion, ['id' => 'inputDescripcion','class' => 'input-bordered','disabled']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="field-item">
                        {!! Form::label('selectUnidad', 'Unidad Venta', ['class' => 'field-label']) !!}
                        <div class="field-wrap">
                            {!! Form::select('selectUnidad', $Unidades ,$Articulos->unidadVenta,['id' => 'selectUnidad','disabled']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="field-item">
                        {!! Form::label('selectCategoria', 'Categoría', ['class'=> 'field-label']) !!}
                        <div class="field-wrap">
                            {!! Form::select('selectCategoria', [ null => 'Seleccione una Categoría']+$categoria,$Articulos->categoria, ['id' => 'selectCategoria','disabled']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="field-item">
                        {!! Form::label('selectGrupo', 'Grupo', ['class'=> 'field-label']) !!}
                        <div class="field-wrap">
                            {!! Form::select('selectGrupo', [null => 'Seleccione un Grupo'] + $grupo, $Articulos->grupo, ['id' => 'selectGrupo','disabled']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="field-item">
                        {!! Form::label('inputIVA', '% IVA', ['class' => 'field-label']) !!}
                        <div class="field-wrap">
                            {!! Form::text('inputIVA',$Articulos->IVA, ['class' => 'input-bordered percentage','id' => 'inputIVA','disabled']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <h3 class="title title-md">Precios</h3>
            <div class="row">
                <div class="col-lg-8">
                    <div class="field-item">
                        {!! Form::label('inputPrecio', 'Lista1 /Precio Lista', ['class' => 'field-label']) !!}
                        <div class="field-wrap">
                            {!! Form::text('inputPrecio',$Articulos->precio, ['class' => 'input-bordered currency','id' => 'inputPrecio','disabled']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('modal')
    <div class="modal fade" id="modal-small">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
                <div class="modal-body p-md-4 p-lg-5">
                    <h3 class="title title-md">Historial de cambios de Precio</h3>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <div class="datatable-container">
                                    <table class="table" >
                                        <thead style="font-size: 15px">
                                            <tr>
                                                <th class="table-des">Artículo</th>
                                                <th class="table-des">Lista de precio</th>
                                                <th class="table-des">Precio anterior</th>
                                                <th class="table-des">Precio nuevo</th>
                                                <th class="table-des">Fecha de cambio</th>
                                                <th class="table-des">Usuario que realizó la Modificación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($histPrecio as $hist)
                                            <tr>
                                                <td style="font-size: 14px">{{$hist->articulo}}</td>
                                                <td style="font-size: 14px">{{number_format($hist->listaPrecio,0)}}</td>
                                                <td style="font-size: 14px">$ {{number_format($hist->precio_anterior,2)}}</td>
                                                <td style="font-size: 14px">$ {{number_format($hist->precio_nuevo,2)}}</td>
                                                <td style="font-size: 14px">{{$hist->fechaCambio}}</td>
                                                <td style="font-size: 14px">{{$hist->user_id}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-js')
    <script src="{{asset('js\catalogos\articulos.js')}}"></script>
@endsection

