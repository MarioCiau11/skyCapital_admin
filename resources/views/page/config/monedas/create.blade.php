@extends('layouts.layout')

@section('extra-css')
@endsection

@section('content')
    <main class="ath-body">
        <section class="bg-white">

            <h3 class="title title-md">Crear Moneda</h3>

            {{ Breadcrumbs::render() }}

            <form action="{{ route('config.monedas.store') }}" class="form-validate createForm" method="post" id="form-create">
                @csrf
                @include('page.config.monedas.form')
                <input class="btn btn-grad submitButton" type="submit" value="Crear Moneda" id="btn-create">
            </form>
        </section>
    </main>
    @include('include.mensaje')
@endsection

@section('extra-js')
    <script src="{{ asset('js/config/monedas.js') }}"></script>
@endsection
