@extends('layouts.layout')

@section('extra-css')

@endsection
@section('content')
<main class="ath-body">
    <section class="bg-white">

            <h3 class="title title-md">Editar Moneda</h3>

            {{ Breadcrumbs::render('config.monedas.edit', $Monedas) }}

            <form action="{{ route('config.monedas.update', Crypt::encrypt($Monedas->idMoneda))}}" class="form-validate createForm" method="post" id="form-edit">
                @method('PATCH')
                    @csrf
                    @include('page.config.monedas.form')
                <input class="btn btn-grad submitButton" type="submit" value="Editar Moneda">
                <a href="{{route('config.monedas.index')}}" name="accion" class="btn btn-light" type="submit">cancelar</a>
            </form>
    </section>
</main>
@include('include.mensaje')
@endsection
@section('extra-js')
    <script src="{{ asset('js/config/monedas.js') }}"></script>
@endsection
