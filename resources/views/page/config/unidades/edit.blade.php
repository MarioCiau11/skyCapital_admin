@extends('layouts.layout')


@section('extra-css')
@endsection

@section('content')
    <main class="ath-body">
        <section class="bg-white">
            <h3 class="title title-md">Editar Unidad</h3>

            {{ Breadcrumbs::render('config.unidades.edit', $Unidades) }}

            <form action="{{ route('config.unidades.update', Crypt::encrypt($Unidades->idUnidades)) }}" class="createForm form-validate"
                method="post" id="form-create">
                @method('PATCH')
                @csrf
                @include('page.config.unidades.form')
                <input type="submit" class="btn btn-grad submitButton" value="Editar Unidad">
                <a href="{{route('config.unidades.index')}}" name="accion" class="btn btn-light" type="submit">cancelar</a>
            </form>
        </section>
    </main>
    @include('include.mensaje')
@endsection

@section('extra-js')
    <script src="{{ asset('js/config/unidades.js') }}"></script>
@endsection
