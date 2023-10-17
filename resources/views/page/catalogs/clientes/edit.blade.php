@extends('layouts.layout')
@section('content')
<main class="ath-body">
    <section class="bg-white">

        <h3 class="title title-md">Editar Cliente</h3>

        {{ Breadcrumbs::render('cat.clientes.edit',$Clientes) }}

        <form action="{{ route('cat.clientes.update', Crypt::encrypt($Clientes->idCliente)) }}" class="form-validate createForm" method="post" id="form-create" enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            @include('page.catalogs.clientes.form')
            <input class="btn btn-grad submitButton" type="submit" value="Editar Cliente">
            <a href="{{route('cat.clientes.index')}}" class="btn btn-light">Cancelar</a>
        </form>
    </section>
</main>

@include('include.mensaje')
@endsection

@section('extra-js')
    <script src="{{asset('js\catalogos\clientes.js')}}"></script>
    <script>
        $(document).ready(function($){
    
        const objCliente = <?php echo json_encode($Clientes);?>;
        const checkM = $('#checkPersonaM');
        const checkF = $('#checkPersonaF');
        console.log(objCliente);

        
        // console.log(checkF,checkM);
        if (objCliente['tipoPersona'] == 1) {
            checkF.click();
            // checkF.prop('checked',true).trigger('change');
        }
        else{
            checkM.click();
            // checkM.prop('checked',true).trigger('change');
        }
    })
    </script>
@endsection