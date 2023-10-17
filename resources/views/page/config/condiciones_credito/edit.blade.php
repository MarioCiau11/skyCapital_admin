@extends('layouts.layout')

@section('extra-css')

@endsection

@section('content')
<main class="ath-body">
    <section class="bg-white">


            <h3 class="title title-md">Editar Condición</h3>

            {{ Breadcrumbs::render('config.condiciones-credito.edit', $condicionesCred) }}

            <form action="{{ route('config.condiciones-credito.update', Crypt::encrypt($condicionesCred->idCondicionesc))}}" class="form-validate createForm" method="post" id="form-edit">
                @method('PATCH')
                    @csrf
                    @include('page.config.condiciones_credito.form')
                <input class="btn btn-grad submitButton" type="submit" value="Editar condición">
                <a href="{{route('config.condiciones-credito.index')}}" name="accion" class="btn btn-light" type="submit">cancelar</a>
            </form>

    </section>
</main>
@include('include.mensaje')
@endsection


@section('extra-js')
<script src="{{ asset('js/config/condiciones_credito.js')}}"></script>

@endsection

