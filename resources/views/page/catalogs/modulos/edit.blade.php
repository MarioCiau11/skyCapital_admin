@extends('layouts.layout')

@section('extra-css')

@endsection
@section('content')
<main class="ath-body">
    <section class="bg-white">

            <h3 class="title title-md">Editar Modulo</h3>

            {{ Breadcrumbs::render('cat.modulos.edit', $Modulo) }}

            <form action="{{ route('cat.modulos.update', Crypt::encrypt($Modulo->idModulo))}}" class="form-validate createForm" method="post" id="form-edit" enctype="multipart/form-data">
                @method('PATCH')
                    @csrf
                    @include('page.catalogs.modulos.form')
                <input class="btn btn-grad submitButton" type="submit" value="Editar Módulo">
            </form>
    </section>
</main>
@include('include.mensaje')
@endsection
@section('extra-js')
<script src="{{ asset('js/catalogos/modulos.js') }}"></script>
@endsection
