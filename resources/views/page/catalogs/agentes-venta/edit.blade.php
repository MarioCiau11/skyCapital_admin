@extends('layouts.layout')

@section('extra-css')

@endsection

@section('content')
<main class="ath-body">
    <section class="bg-white">

            <h3 class="title title-md">Editar Asesor Comercial</h3>

            {{ Breadcrumbs::render('cat.agentes-venta.edit', $Agentes) }}

            <form action="{{ route('cat.agentes-venta.update', Crypt::encrypt($Agentes->idAgentes))}}" class="form-validate createForm" method="post" id="form-edit">
                @method('PATCH')
                    @csrf
                    @include('page.catalogs.agentes-venta.form')
                <input class="btn btn-grad submitButton" type="submit" value="Editar Asesor">
            </form>

    </section>
</main>
@include('include.mensaje')
@endsection
@section('extra-js')
<script src="{{ asset('js\catalogos\agentes-venta.js')}}"></script>
@endsection
