@extends('layouts.layout')

@section('extra-css')

@endsection
@section('content')
<main class="ath-body">
    <section class="bg-white">

            <h3 class="title title-md">Editar Empresa</h3>

            {{ Breadcrumbs::render('cat.empresas.edit', $Empresa) }}

            <form action="{{ route('cat.empresas.update', Crypt::encrypt($Empresa->idEmpresa))}}" class="form-validate createForm" method="post" id="form-edit" enctype="multipart/form-data">
                @method('PATCH')
                    @csrf
                    @include('page.catalogs.empresas.form')
                <input class="btn btn-grad submitButton" type="submit" value="Editar Empresa">
            </form>
    </section>
</main>
@include('include.mensaje')
@endsection
@section('extra-js')
<script src="{{ asset('js/catalogos/empresas.js') }}"></script>
@endsection
