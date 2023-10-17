@extends('layouts.layout')

@section('extra-css')

@endsection
@section('content')

    <section class="bg-white">

            <h3 class="title title-md">Editar Proyecto</h3>

            {{ Breadcrumbs::render('cat.proyectos.edit', $Proyecto) }}

            <form action="{{ route('cat.proyectos.update', Crypt::encrypt($Proyecto->idProyecto))}}" class="form-validate createForm" method="post" id="form-edit" enctype="multipart/form-data">
                @method('PATCH')
                    @csrf
                    @include('page.catalogs.proyectos.form')
                <input class="btn btn-grad submitButton" type="submit" value="Editar Proyecto">
            </form>
    </section>

@include('include.mensaje')
@endsection
@section('extra-js')
<script src="{{ asset('js/catalogos/proyectos.js') }}"></script>
@endsection
