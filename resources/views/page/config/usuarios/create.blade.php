@extends('layouts.layout')

@section('extra-css')

@endsection

@section('content')
<main class="ath-body">
    <section class="bg-white">

            <h3 class="title title-md">Crear Usuario</h3>
          
            {{ Breadcrumbs::render() }}
         
            <form action="{{route('config.usuarios.store')}}" class="form-validate createForm" method="post" id="form-create">
                    @csrf
                    @include('page.config.usuarios.form')
                <input class="btn btn-grad submitButton" type="submit" value="Crear Usuario">
            </form>

    </section>
</main>
@include('include.mensaje')
@endsection

@section('extra-js')
<script src="{{ asset('js/config/usuarios.js')}}"></script>
@endsection

