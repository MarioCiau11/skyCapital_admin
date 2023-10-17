@extends('layouts.layout')

@section('extra-css')

@endsection

@section('content')
<main class="ath-body">
    <section class="bg-white">

            <h3 class="title title-md">Editar Cuenta de Dinero</h3>

            {{ Breadcrumbs::render('cat.cuentas-dinero.edit', $CuentasDinero) }}

            <form action="{{ route('cat.cuentas-dinero.update', Crypt::encrypt($CuentasDinero->idCuentasDinero))}}" class="form-validate createForm" method="post" id="form-edit">
                @method('PATCH')
                    @csrf
                    @include('page.catalogs.cuentas-dinero.form')
                <input class="btn btn-grad submitButton" type="submit" value="Editar Cuenta">
            </form>

    </section>
</main>
@include('include.mensaje')
@endsection
@section('extra-js')
    <script src="{{asset('js\catalogos\cuentas-dinero.js')}}"></script>
@endsection
