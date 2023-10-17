@extends('layouts.layout')

@section('content')

    <section class="bg-white">
        <div class="row">
        <div class="col-lg-8">
            <div class="nk-block-text">
                <h2 class="title">Categorías de Agentes</h2>
            </div>
        </div>
        <div class="col-lg-4 float-left mb2">
            <a href="{{route("agrup.categoria.create")}}" class="btn btn-outline btn-sm btn-success float-left">Crear categoría</a>
            <a href="{{route("cat.agentes-venta.index")}}" class="btn btn-outline btn-sm btn-light ml-2">Regresar</a>
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
                                <th class="table-head">Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Categoria as $categorias)
                                @include('include.agrup.agentes.categoriaItem')
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </section>
    @include('include.mensaje')
@endsection