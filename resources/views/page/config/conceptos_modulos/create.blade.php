
@extends('layouts.layout')

@section('extra-css')


@endsection

@section('content')
<main class="ath-body">
    <section class="bg-white">

        <h3 class="title title-md">Crear Concepto</h3>
        
        {{ Breadcrumbs::render() }}

        <form action="{{route('config.conceptos-modulos.store')}}" class="form-validate createForm" method="post" id="form-create">
            @csrf
            @include('page.config.conceptos_modulos.form')
            <input type="submit" class="btn btn-sm btn-success btn-auto submitButton" value="crear Condición" >
        </form>
    </section>
</main>
@include('include.mensaje')
@endsection

@section('extra-js')
<script src="{{asset('js/config/conceptos_modulos.js')}}"></script>
@endsection
