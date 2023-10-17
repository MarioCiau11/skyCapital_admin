@extends('layouts.layout')
@section('content')
<main class="ath-body">
    <section class="bg-white">

        <h3 class="title title-md">Crear Cuenta de Dinero</h3>

        {{ Breadcrumbs::render() }}

        <form action="{{ route('cat.cuentas-dinero.store') }}" class="form-validate createForm" method="post" id="form-create">
            @csrf
            @include('page.catalogs.cuentas-dinero.form')
            <input class="btn btn-grad submitButton" type="submit" value="Crear Cuenta">
        </form>
    </section>
</main>
@include('include.mensaje')
@endsection

@section('extra-js')
    <script src="{{asset('js\catalogos\cuentas-dinero.js')}}"></script>
@endsection