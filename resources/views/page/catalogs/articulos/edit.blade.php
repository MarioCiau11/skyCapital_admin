@extends('layouts.layout')

@section('content')
<main class="ath-body">
    <section class="bg-white">

            <h3 class="title title-md">Editar Artículo</h3>

            {{ Breadcrumbs::render('cat.articulos.edit', $Articulos) }}

            <form action="{{ route('cat.articulos.update', Crypt::encrypt($Articulos->idArticulos))}}" class="form-validate createForm" method="post" id="form-edit">
                @method('PATCH')
                    @csrf
                    @include('page.catalogs.articulos.form')
                <input class="btn btn-grad submitButton" type="submit" value="Editar artículo">
                <a href="{{route('cat.articulos.index')}}" class="btn btn-light">Cancelar</a>
            </form>

    </section>
</main>
@include('include.mensaje')
@endsection
@section('extra-js')
    <script src="{{asset('js\catalogos\articulos.js')}}"></script>
@endsection
