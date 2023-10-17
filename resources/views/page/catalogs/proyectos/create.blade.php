@extends('layouts.layout')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/catalogos/modulos.css') }}">
@endsection

@section('content')
    <main class="ath-body">
        <section class="bg-white">

            <h3 class="title title-md">Crear Proyecto</h3>

            {{ Breadcrumbs::render() }}

            <form action="{{ route('cat.proyectos.store') }}" class="form-validate createForm" method="post" id="form-create" enctype="multipart/form-data">
                @method('POST')
                @csrf
                @include('page.catalogs.proyectos.form')
       
                <div style="text-align: center;">
                    <input type="submit" class="btn btn-grad submitButton" value="crear Proyecto">
                </div>
                
    
            </form>
        </section>
    </main>
    @include('include.mensaje')
@endsection
  </script>
@section('extra-js')
    <script src="{{ asset('js/catalogos/proyectos.js') }}"></script>
@endsection
