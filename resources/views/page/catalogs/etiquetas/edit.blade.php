@extends('layouts.layout')

@section('extra-css')

@endsection
@section('content')
<main class="ath-body">
    <section class="bg-white">

            <h3 class="title title-md">Editar Etiqueta</h3>

            {{ Breadcrumbs::render('cat.etiquetas.edit', $Etiqueta) }}

            <form action="{{ route('cat.etiquetas.update', Crypt::encrypt($Etiqueta->idEtiqueta))}}" class="form-validate createForm" method="post" id="form-edit" enctype="multipart/form-data">
                @method('PATCH')
                    @csrf
                    @include('page.catalogs.etiquetas.form')
                <input class="btn btn-grad submit" type="submit" value="Editar Etiqueta">
            </form>
    </section>
</main>
@include('include.mensaje')
@endsection
@section('extra-js')
<script src="{{ asset('js/catalogos/etiquetas.js') }}"></script>
@endsection
