@extends('layouts.layout')

@section('extra-css')

@endsection
@section('content')
<main class="ath-body">
    <section class="bg-white">

            <h3 class="title title-md">Editar Sucursal</h3>

            {{ Breadcrumbs::render('cat.sucursales.edit', $Sucursal) }}

            <form action="{{ route('cat.sucursales.update', Crypt::encrypt($Sucursal->idSucursal))}}" class="form-validate createForm" method="post" id="form-edit" enctype="multipart/form-data">
                @method('PATCH')
                    @csrf
                    @include('page.catalogs.sucursales.form')
                <input class="btn btn-grad submitButton" type="submit" value="Editar Sucursal">
            </form>
    </section>
</main>
@include('include.mensaje')
@endsection
@section('extra-js')
<script src="{{ asset('js/catalogos/sucursales.js') }}"></script>
@endsection
