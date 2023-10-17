@extends('layouts.layout')

@section('content')
<main class="ath-body">
    <section class="bg-white">

        <h3 class="title title-md">Crear Categoría</h3>
        
        {{-- {{ Breadcrumbs::render() }} --}}

        <form action="{{route('agrup.clientes.categoria.store')}}" class="form-validate createForm" method="post" id="form-create">
            @csrf
            @include('page.agrup.Clientes.categoria.form')
            <input type="submit" class="btn btn-grad submitButton" value="crear categoría" >
            <a href="{{route('agrup.clientes.categoria.index')}}" name="accion" class="btn btn-light" type="submit">cancelar</a>

        </form>
    </section>
</main>
@include('include.mensaje')
@endsection

@section('extra-js')
    <script src="{{asset('js\agrup\categorias.js')}}"></script>
@endsection