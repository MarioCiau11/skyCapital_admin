@extends('layouts.layout')

@section('content')

    <sectio class="bg-white">
        <div class="row">
        <div class="col-lg-8">
            <div class="nk-block-text">
                <h2 class="title">Grupos de proyectos</h2>
            </div>
        </div>
        <div class="col-lg-4 float-left">
            <a href="{{route("agrup.proyectos.grupo.create")}}" class="btn btn-outline btn-sm btn-success float-left">Crear grupo</a>
            <a href="{{route("cat.proyectos.index")}}" class="btn btn-outline btn-sm btn-light ml-2">Regresar</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">

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
                            @foreach ($Grupo as $grupos)
                                @include('include.agrup.proyectos.grupoItem')
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