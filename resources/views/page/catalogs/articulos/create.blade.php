@extends('layouts.layout')

@section('content')
    <main class="ath-body">
        <section class="bg-white">

            <h3 class="title title-md">Crear Artículo</h3>

            {{ Breadcrumbs::render() }}

            <form action="{{ route('cat.articulos.store') }}" class="form-validate createForm" method="post" id="form-create">
                @csrf
                @include('page.catalogs.articulos.form')
                <input type="submit" class="btn btn-grad submitButton" value="crear Artículo">
            </form>
        </section>
    </main>
    @include('include.mensaje')
@endsection

@section('extra-js')
    <script src="{{ asset('js\catalogos\articulos.js') }}"></script>
@endsection
