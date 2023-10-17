@extends('layouts.layout')

@section('extra-css')

@endsection

@section('content')
<main class="ath-body">
    <section class="bg-white">

            
            <h3 class="title title-md">Editar Usuario</h3>
          
            {{ Breadcrumbs::render('config.usuarios.edit', $user) }}
         
            <form action="{{ route('config.usuarios.update', Crypt::encrypt($user->user_id))}}" class="form-validate createForm" method="post" id="form-edit">
                @method('PATCH')
                    @csrf
                    @include('page.config.usuarios.form')
                <input class="btn btn-grad submitButton" type="submit" value="Editar Usuario">
                <a href="{{route('config.usuarios.index')}}" name="accion" class="btn btn-light" type="submit">cancelar</a>
            </form>

    </section>
</main>
@endsection

@section('extra-js')
<script src="{{ asset('js/config/usuarios.js')}}"></script>
@endsection