@extends('layouts.layout')

@section('extra-css')

@endsection

@section('content')
<main class="ath-body">
    <section class="bg-white">

            <h3 class="title title-md">Crear Rol</h3>
          
            {{ Breadcrumbs::render() }}
         
            <form action="{{route('config.roles.store')}}" class="form-validate createForm" method="post" id="form-create">
                    @csrf
                    @include('page.config.roles.form')
                    <div class="text-center">
                        <input class="btn btn-grad submitButton" type="submit" value="Crear Rol">
                    </div>
            </form>

    </section>
</main>
@include('include.mensaje')
@endsection

@section('extra-js')
<script src="{{ asset('js/config/roles.js')}}"></script>
@endsection

