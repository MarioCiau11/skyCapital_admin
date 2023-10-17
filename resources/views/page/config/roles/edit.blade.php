@extends('layouts.layout')

@section('extra-css')

@endsection

@section('content')
<main class="ath-body">
    <section class="bg-white">

            
            <h3 class="title title-md">Editar Rol</h3>
          
            {{ Breadcrumbs::render('config.roles.edit', $rol) }}
         
            <form action="{{ route('config.roles.update', Crypt::encrypt($rol->id))}}" class="form-validate createForm" method="post" id="form-edit">
                @method('PATCH')
                    @csrf
                    @include('page.config.roles.form')
                    <div class="text-center">
                        <input class="btn btn-grad submitButton" type="submit" value="Editar Rol">
                        <a href="{{route('config.roles.index')}}" name="accion" class="btn btn-light" type="submit">cancelar</a>
                    </div>
                
            </form>

    </section>
</main>
@endsection

@section('extra-js')
<script src="{{ asset('js/config/roles.js')}}"></script>
@endsection