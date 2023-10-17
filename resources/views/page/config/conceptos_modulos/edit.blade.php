

@extends('layouts.layout')

@section('extra-css')

@endsection

@section('content')
<main class="ath-body">
    <section class="bg-white">


            <h3 class="title title-md">Editar Concepto</h3>

            {{ Breadcrumbs::render('config.conceptos-modulos.edit', $ConceptosMod) }}

            <form action="{{ route('config.conceptos-modulos.update', Crypt::encrypt($ConceptosMod->idConceptosm))}}" class="form-validate" method="post" id="form-edit">
                @method('PATCH')
                    @csrf
                    @include('page.config.conceptos_modulos.form')
                <input class="btn btn-grad" type="submit" value="Editar condición">
            </form>

    </section>
</main>
@include('include.mensaje')
@endsection

@section('extra-js')
<script src="{{asset('js/config/conceptos_modulos.js')}}"></script>
@endsection
