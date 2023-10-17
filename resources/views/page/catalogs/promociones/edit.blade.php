@extends('layouts.layout')

@section('extra-css')

@endsection
@section('content')
<main class="ath-body">
    <section class="bg-white">

            <h3 class="title title-md">Editar Promoción</h3>

            {{ Breadcrumbs::render('cat.promociones.edit', $Promocion) }}

            <form action="{{ route('cat.promociones.update', Crypt::encrypt($Promocion->idPromocion))}}" class="form-validate createForm" method="post" id="form-edit" enctype="multipart/form-data">
                @method('PATCH')
                    @csrf
                    @include('page.catalogs.promociones.form')
                <input class="btn btn-grad submitButton" type="submit" value="Editar Promoción">
            </form>
    </section>
</main>
@include('include.mensaje')
@endsection
@section('extra-js')
<script src="{{ asset('js/catalogos/promociones.js') }}"></script>
@endsection
