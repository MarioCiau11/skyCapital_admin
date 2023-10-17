@extends('layouts.layout')
@section('extra-css')
@endsection
@section('content')
<main class="ath-body">
    <section class="bg-white">
        <h3 class="title title-md">Crear Forma de Pago</h3>

        {{ Breadcrumbs::render() }}

        <form action="{{route('config.formas-pago.store')}}" class="form-validate createForm" method="post" id="form-create">
            @csrf
            @include('page.config.formas_pago.form')
            <input class="btn btn-grad submitButton" type="submit" value="Crear Forma de Pago">
        </form>
    </section>
</main>
@include('include.mensaje')
@endsection

@section('extra-js')
<script src="{{ asset('js/config/formas_pago_cobro.js')}}"></script>
@endsection
