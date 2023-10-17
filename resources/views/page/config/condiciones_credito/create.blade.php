@extends('layouts.layout')

@section('extra-css')


@endsection

@section('content')
<main class="ath-body">
    <section class="bg-white">

        <h3 class="title title-md">Crear Condición de Crédito</h3>
        
        {{ Breadcrumbs::render() }}

        <form action="{{route('config.condiciones-credito.store')}}" class="form-validate createForm" method="post" id="form-create">
            @csrf
            @include('page.config.condiciones_credito.form')
            <input type="submit" class="btn btn-grad submitButton" value="Crear Condición" >
        </form>
    </section>
</main>
@include('include.mensaje')
@endsection

@section('extra-js')
<script src="{{ asset('js/config/condiciones_credito.js')}}"></script>
@endsection
