@extends('layouts.layout')


@section('extra-css')
@endsection

@section('content')
    <main class="ath-body">
        <section class="bg-white">
            <h3 class="title title-md">Crear Unidad</h3>

            {{ Breadcrumbs::render() }}

            <form action="{{ route('config.unidades.store') }}" class="form-validate createForm" method="post" id="form-create">
                @csrf
                @include('page.config.unidades.form')
                <input type="submit" class="btn btn-grad submitButton" value="crear Unidad">
            </form>
        </section>
    </main>
    @include('include.mensaje')
@endsection

@section('extra-js')
    <script src="{{ asset('js/config/unidades.js') }}"></script>
@endsection
