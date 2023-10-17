@extends('layouts.layout')

@section('extra-css')


@endsection

@section('content')
<main class="ath-body">
    <section class="bg-white">

        <h3 class="title title-md">Crear Grupo</h3>
        
        {{-- {{ Breadcrumbs::render() }} --}}

        <form action="{{route('agrup.proyectos.grupo.store')}}" class="form-validate createForm" method="post" id="form-create">
            @csrf
            @include('page.agrup.Agentes.grupo.form')
            <input type="submit" class="btn btn-grad submitButton" value="crear grupo" >
            <a href="{{route('agrup.proyectos.grupo.index')}}" name="accion" class="btn btn-light" type="submit">cancelar</a>

        </form>
    </section>
</main>
@include('include.mensaje')
@endsection

@section('extra-js')
    <script src="{{asset('js\agrup\grupo.js')}}"></script>
@endsection
