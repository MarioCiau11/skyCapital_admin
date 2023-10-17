@extends('layouts.layout')

@section('content')
<main class="ath-body">
    <section class="bg-white">


            <h3 class="title title-md">Editar Categoría</h3>

            {{-- {{ Breadcrumbs::render('config.conceptos-modulos.edit', $Categoria) }} --}}

            <form action="{{ route('agrup.categoria.update', Crypt::encrypt($Categoria->idCategoria))}}" class="form-validate" method="post" id="form-edit">
                @method('PATCH')
                    @csrf
                    @include('page.agrup.Agentes.categoria.form')
                <input class="btn btn-grad" type="submit" value="Editar Categoría">
                <a href="{{route('agrup.categoria.index')}}" name="accion" class="btn btn-light" type="submit">cancelar</a>
            </form>

    </section>
</main>
@include('include.mensaje')
@endsection

@section('extra-js')
    <script src="{{asset('js\agrup\categorias.js')}}"></script>
@endsection
