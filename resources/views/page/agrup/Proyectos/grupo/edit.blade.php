@extends('layouts.layout')

@section('content')
<main class="ath-body">
    <section class="bg-white">


            <h3 class="title title-md">Editar Grupo</h3>

            {{-- {{ Breadcrumbs::render('config.conceptos-modulos.edit', $grupo) }} --}}

            <form action="{{ route('agrup.proyectos.grupo.update', Crypt::encrypt($Grupo->idGrupo))}}" class="form-validate" method="post" id="form-edit">
                @method('PATCH')
                    @csrf
                    @include('page.agrup.Proyectos.grupo.form')
                <input class="btn btn-grad" type="submit" value="Editar grupo">
                <a href="{{route('agrup.proyectos.grupo.index')}}" name="accion" class="btn btn-light" type="submit">cancelar</a>
            </form>

    </section>
</main>
@include('include.mensaje')
@endsection

@section('extra-js')
    <script src="{{asset('js\agrup\grupo.js')}}"></script>
@endsection