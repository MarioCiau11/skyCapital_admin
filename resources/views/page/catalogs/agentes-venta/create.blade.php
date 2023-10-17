@extends('layouts.layout')

@section('extra-css')


@endsection

@section('content')
<main class="ath-body">
    <section class="bg-white">

        <h3 class="title title-md">Crear Asesor Comercial</h3>
        
        {{ Breadcrumbs::render() }}

        <form action="{{route('cat.agentes-venta.store')}}" class="form-validate createForm" method="post" id="form-create">
            @csrf
            @include('page.catalogs.agentes-venta.form')
            <input type="submit" class="btn btn-grad submitButton" value="crear Asesor" >
        </form>
    </section>
</main>
@include('include.mensaje')
@endsection

@section('extra-js')
<script src="{{ asset('js\catalogos\agentes-venta.js')}}"></script>
@endsection
