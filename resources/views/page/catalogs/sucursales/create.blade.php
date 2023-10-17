@extends('layouts.layout')

@section('extra-css')
@endsection

@section('content')
    <main class="ath-body">
        <section class="bg-white">

            <h3 class="title title-md">Crear Sucursal</h3>

            {{ Breadcrumbs::render() }}

            <form action="{{ route('cat.sucursales.store') }}" class="form-validate createForm" method="post" id="form-create" enctype="multipart/form-data">
                @method('POST')
                @csrf
                @include('page.catalogs.sucursales.form')
                <input type="submit" class="btn btn-grad submitButton" value="crear sucursal">
            </form>
        </section>
    </main>
    @include('include.mensaje')
@endsection

@section('extra-js')
    <script src="{{ asset('js/catalogos/sucursales.js') }}"></script>
@endsection
