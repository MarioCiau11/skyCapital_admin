@extends('layouts.layout')
@section('content')
<main class="ath-body">
    <section class="bg-white">

        <h3 class="title title-md">Crear Cliente</h3>

        {{ Breadcrumbs::render() }}

        <form action="{{ route('cat.clientes.store') }}" class="form-validate createForm" method="post" id="form-create" enctype="multipart/form-data">
            @csrf
            @include('page.catalogs.clientes.form')
            <input class="btn btn-grad submitButton" type="submit" value="Crear Cliente">
        </form>
    </section>
</main>
@include('include.mensaje')
@endsection

@section('extra-js')
    <script src="{{asset('js\catalogos\clientes.js')}}"></script>
@endsection