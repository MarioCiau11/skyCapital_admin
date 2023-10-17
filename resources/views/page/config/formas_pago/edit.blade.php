
@extends('layouts.layout')

@section('extra-css')

@endsection
@section('content')
<main class="ath-body">
    <section class="bg-white">

            <h3 class="title title-md">Editar Forma de Pago</h3>

            {{ Breadcrumbs::render('config.formas-pago.edit', $formasPago) }}

            <form action="{{ route('config.formas-pago.update', Crypt::encrypt($formasPago->idFormaspc))}}" class="form-validate createForm" method="post" id="form-edit">
                @method('PATCH')
                    @csrf
                    @include('page.config.formas_pago.form')
                <input class="btn btn-grad submitButton" type="submit" value="Editar Forma de Pago">
                <a href="{{route('config.formas-pago.index')}}" name="accion" class="btn btn-light" type="submit">cancelar</a>
            </form>

    </section>
</main>
@include('include.mensaje')
@endsection


@section('extra-js')
<script src="{{ asset('js/config/formas_pago_cobro.js')}}"></script>

@endsection
