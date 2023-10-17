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
        <form action="{{route('proc.cxc.store')}}"
        class="form-validate createForm" method="post" id="form-create" enctype="multipart/form-data"
        >
            @method('POST')
            @csrf
            @include('page.proc.cxc.form')
            @if ($cxc->movimiento !== 'Factura' && $cxc->estatus !== 'CONCLUIDO')
                <div class="col-lg-12 text-center mb-5">
                    @php
                        $usuario = Auth::user();
                    @endphp
                    @if ($cxc->movimiento != null)
                        @if ($usuario->can($cxc->movimiento.' E'))
                            <input type="submit" class="btn btn-grad" value="Guardar" style="margin-left: 40px" id="btnGuardarVenta">
                        @endif
                    @else
                        <input type="submit" class="btn btn-grad submitButton" value="Guardar" style="margin-left: 40px" id="btnGuardarVenta">
                    @endif
                   

                </div>
            @endif
        </form>
    </section>    
</main>   

@include('include.mensaje')

@endsection
@section('modal')
    @include('include.proc.modals.infoClienteModal')
    @include('include.proc.modals.infoModuloModal')
    @include('include.proc.modals.clientesModal')
    @include('include.proc.modals.cuentasModal')
    @include('include.proc.modals.proyectosModal')
    @include('include.proc.modals.modulosModal')
    @include('include.proc.modals.cobroModal')
    @include('include.proc.modals.anticiposModal')
    @include('include.proc.modals.movimientosCxcModal')
    @include('include.proc.modals.flujoModal')
@endsection

@section('extra-js')
<script src="{{ asset('js\procesos\flujo.js') }}"></script>
<script src="{{ asset('js\procesos\infoCliente.js') }}"></script>
<script src="{{ asset('js\procesos\cxc\cxcProceso.js') }}"></script>
    <script>
        jQuery(document).ready(function(){
            $('#selectMoneda').val('{{ !empty($cxc['moneda']) ? $cxc['moneda'] : ($parametro ? $parametro->monedaDefault : '1') }}').trigger('change');
            tablaMovimientos = "{{old('inputMovimientos')}}"; 
            let movimientosBD = JSON.parse('{!! $cxc->getDetalle !!}');

            console.log(movimientosBD);

            if (tablaMovimientos !== '') {
                const movimientoStr = tablaMovimientos.replace(/&quot;/g,'"');
                const movimientoJson = JSON.parse(movimientoStr);
                
                // console.log(movimientosAgregados);
                let datos = [];
                movimientoJson.forEach(element => {
                    if (element.aplicaConsecutivo != '') {
                        datos.push(element); 
                    }
                });
                console.log(datos);
                if (datos.length > 0) {
                    
                    $('#controlMovimientos').hide();
                    for (let i = movimientosBD.length; i < datos.length; i++) {
                        movimientosAgregados.add(parseInt(datos[i].aplicaConsecutivo));
                        $("#tbodyMovimientos").append(
                            `<tr id="fila-${datos[i].aplicaConsecutivo}">
                            <td>
                                <select name="selectTableAplicacion" id="selectTableAplicacion">
                                <option value="Factura">Factura</option>
                                </select>
                            </td>
                            <td style="display: flex">
                                <div contenteditable="false" style="width: 80%" id="tdAplicaConsecutivo">${datos[i].aplicaConsecutivo}</div>
                                <div>
                                <a href="" class="btn btn-sm btn-auto btn-info modalAplica" data-toggle="modal" data-target="#modalMov">
                                    <i class="fas fa-search fa-lg"></i>
                                </a>
                                </div>
                            </td>
                            <td contenteditable="true" class="currency" id="tdImporte" oninput="calculoDiferencia(this)">${datos[i].importe}</td>
                            <td id="tdDiferencia" class="currency">$ 0.00</td>
                            <td id="tdPorcentaje" class="percentage">0 %</td>
                            <td>
                                <i onclick="eliminarRenglon(this)" class="fa fa-trash btn-delete-articulo" aria-hidden="true" style="color: red; font-size: 25px; cursor: pointer;"></i>
                            </td>
                            <td style="display: none" id="tdSaldo">${datos[i].importe}</td>
                            <td style="display: none" id="tdId">${datos[i].id}</td>
                            <td style="display: none" id="tdReferencia">${datos[i].referencia}</td>
                            </tr>`
                        );
                        contadorMovimientos ++;
                    }
                }else{
                    $('#controlMovimientos').show();
                }
            } 
            jsIdMovimiento = {!! json_encode($cxc->idCXC) !!};
            flujoPrincipal(jsIdMovimiento,'CxC');
            // console.log(tablaMovimientos);
        });
    </script>
@endsection