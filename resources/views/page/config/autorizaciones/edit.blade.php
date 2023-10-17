@extends('layouts.layout')

@section('extra-css')

@endsection
@section('content')
<main class="ath-body">
    <section class="bg-white">

            <h3 class="title title-md">Editar Moneda</h3>

            {{ Breadcrumbs::render('config.monedas.edit', $Monedas) }}

            <form action="{{ route('config.monedas.update', Crypt::encrypt($Monedas->idMoneda))}}" class="form-validate" method="post" id="form-edit">
                @method('PATCH')
                    @csrf
                    @include('page.config.monedas.form')
                <input class="btn btn-grad" type="submit" value="Editar forma de pago">
            </form>
    </section>
</main>
@include('include.mensaje')
@endsection
@section('extra-js')
    <script src="{{ asset('js/config/monedas.js') }}"></script>
@endsection
