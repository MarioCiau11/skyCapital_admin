@extends('layouts.layout')

@section('extra-css')

@endsection
@section('content')
<main class="ath-body">
    <section class="bg-white">

            <h3 class="title title-md">Editar Institución</h3>

            {{ Breadcrumbs::render('cat.instituciones.edit', $Institucion) }}

            <form action="{{ route('cat.instituciones.update', Crypt::encrypt($Institucion->idInstitucionf))}}" class="form-validate createForm" method="post" id="form-edit" enctype="multipart/form-data">
                @method('PATCH')
                    @csrf
                    @include('page.catalogs.instituciones.form')
                <input class="btn btn-grad submit" type="submit" value="Editar Institución">
            </form>
    </section>
</main>
@include('include.mensaje')
@endsection
@section('extra-js')
<script src="{{ asset('js/catalogos/instituciones.js') }}"></script>
@endsection
