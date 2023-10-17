@extends('layouts.layout')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/catalogos/modulos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/procesos/modal-client.css') }}">
@endsection

@section('content')
{{-- @php
    dd($parametro);
@endphp --}}
<main class="ath-body">
    <section class="bg-white">
        <form action="{{route('proc.tesoreria.store')}}"
        class="form-validate createForm" method="post" id="form-create" enctype="multipart/form-data"
        >
            @method('POST')
            @csrf
            @include('page.proc.tesoreria.form')
            {{-- @if ($cxc->movimiento !== 'Factura' && $cxc->estatus !== 'CONCLUIDO') --}}
                <div class="col-lg-12 text-center mb-5">
                    @php
                        $usuario = Auth::user();
                    @endphp
                    @if ($tesoreria->movimiento != null)
                        @if ($usuario->can($tesoreria->movimiento.' E'))
                            <input type="submit" class="btn btn-grad" value="Guardar" style="margin-left: 40px" id="btnGuardarVenta">
                        @endif
                    @else
                        <input type="submit" class="btn btn-grad submitButton" value="Guardar" style="margin-left: 40px" id="btnGuardarVenta">
                    @endif
                </div>
            {{-- @endif --}}
        </form>
    </section>    
</main>    

@include('include.mensaje')

@endsection
@section('modal')
    @include('include.proc.modals.cuentasModal')
    @include('include.proc.modals.cuentasDestinoModal')
    @include('include.proc.modals.flujoModal')
    @include('include.proc.modals.cuentasSaldoModal')
{{-- @include('include.proc.modals.infoClienteModal')
    @include('include.proc.modals.infoModuloModal')
    @include('include.proc.modals.clientesModal')
    @include('include.proc.modals.proyectosModal')

    @include('include.proc.modals.cobroModal')
    @include('include.proc.modals.anticiposModal')
    @include('include.proc.modals.movimientosCxcModal')
     --}}
@endsection

@section('extra-js')
<script src="{{ asset('js\procesos\flujo.js') }}"></script>
<script>
    const movimiento = "{{ $tesoreria->movimiento }}";

    if(movimiento == 'Transferencia'){
            $('.cuentaDestino').show();
        }else{
            $('.cuentaDestino').hide();
        }
    console.log(movimiento);

    $(document).ready(function(){

        $('#selectMoneda').val('{{ !empty($tesoreria['moneda']) ? $tesoreria['moneda'] : ($parametro ? $parametro->monedaDefault : '1') }}').trigger('change');

        console.log('{{ !empty($tesoreria['moneda']) ? $tesoreria['moneda'] : ($parametro ? $parametro->monedaDefault : '1') }}');

        jsIdMovimiento = {!! json_encode($tesoreria->idTesoreria) !!};
        // console.log(jsIdMovimiento);
        flujoPrincipal(jsIdMovimiento,'Tesoreria');
    });
</script>
<script src="{{ asset('js\procesos\tesoreria\tesoreriaProcesos.js') }}"></script>
@endsection