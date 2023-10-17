@extends('layouts.layout')
@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/catalogos/modulos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/procesos/modal-client.css') }}">
@endsection
@php
    //  dd($saldoGlobalCliente);
@endphp

@section('content')
    <main class="ath-body">
        <section class="bg-white">
            <form action="{{ route('proc.ventas.store') }}" class="form-validate createForm" method="post" id="form-create" enctype="multipart/form-data">
                @method('POST')
                @csrf
                @include('page.proc.ventas.form')
                <div class="col-lg-12 text-center mb-5">
                    @php
                        $usuario = auth()->user();
                        // dd($venta->movimiento);
                        // dd($user->can($venta->movimiento.' E'));
                    @endphp
                    @if ($venta->movimiento == null)
                        {{-- @dd('no hay movimiento aun ') --}}
                        <input type="submit" class="btn btn-grad submitButton" value="Guardar" style="margin-left: 40px" id="btnGuardarVenta">
                        
                    @else
                        {{-- @dd('ya se creo el movimiento') --}}
                        @if ($usuario->can($venta->movimiento.' E'))
                            @if ($venta->movimiento == "Factura")
                                <input type="submit" class="btn btn-grad" value="Guardar" style="margin-left: 40px" onclick="guardarFactura()" id="btnGuardarFactura">
                            @else
                                <input type="submit" class="btn btn-grad" value="Guardar" style="margin-left: 40px" id="btnGuardarVenta">
                            @endif
                        @endif
                    @endif
                    
                </div>
            </form>
        </section>
    </main>

    @include('include.mensaje')

@endsection
@section('modal')
    @include('include.proc.modals.infoClienteModal')
    @include('include.proc.modals.articulosModal')
    {{-- @include('include.proc.modals.clientesModal') --}}
    @include('include.proc.modals.facturaModal')
    @include('include.proc.modals.infoModuloModal')
    @include('include.proc.modals.flujoModal')
    @include('include.proc.modals.cobranzaModal')
    @include('include.proc.modals.anticiposModal')
@endsection
@section('extra-js')
<script src="{{ asset('js\procesos\flujo.js') }}"></script>
<script src="{{ asset('js\procesos\infoCliente.js') }}"></script>
<script>
    $(document).ready(function() {
        let tablaVenta = "{{old('inputTablePlanVenta')}}";
        let clientes = JSON.parse('{!!$clientes!!}'); 
        let articulosBd = JSON.parse('{!!$venta->getDetalle!!}');
        // console.log(articulosBd);
        let tablaArticulosDatos = "{{old('inputArticles')}}";
        let coPropsInput = "{{old('inputCoprops')}}";

        let jsCoprops = {!! json_encode($venta->getCoprops) !!};

        let radioContrato = "{{json_encode($venta->tipoContrato) == 1 || old('radioContrato') == 'Venta' ?'Venta':'Renta'}}";
        let radioEsquema = "{{json_encode($venta->esquemaPago) == 1 || old('radioEsquema') == 'Mensualidad' ?'Mensualidad':'Contado'}}";

        if (radioContrato == "Renta") {
            radioContrato = document.getElementById('radioRenta');
            tipoContrato(radioContrato);
        }
        if (radioEsquema == "Contado") {
            radioEsquema = document.getElementById('radioContado');
            tipoEsquema(radioEsquema);
        }
            
        
        // console.log(jsCoprops);
        
        if (tablaArticulosDatos != '') {

        const tablaArticulosStr = tablaArticulosDatos.replace(/&quot;/g,'"');
        const tablaArticulosJson = JSON.parse(tablaArticulosStr);
        // console.log(tablaArticulosJson);
        let datos = [];
        tablaArticulosJson.forEach(element => {
            // datos.push([element.clave, element.articulo, element.tipo, element.precio, element.unidad, element.IVA]);
            if (element.cantidad != null) {
                datos.push(element);                
            }
        });
        // console.log(datos);
            if (datos.length > 0) {
                $('#controlArticulo').hide();
                for (let i = articulosBd.length ; i < datos.length; i++) {
                    
                    let clave = datos[i]["articulo"];
                    let articulo = datos[i]["descripcion"];
                    let precio = datos[i]["precio"];
                    let unidad = datos[i]["unidad"];
                    let IVA = formatoMoneySin(datos[i]["porcentajeIva"]);
                    let cantidad = datos[i]["cantidad"];
                    let importe = formatoMoney(datos[i]["importe"]);
                    let importeIVA = formatoMoney(datos[i]["iva"]);
                    let total = formatoMoney(datos[i]["total"]);

                    if(clave != ''){
                        $('#itemArticulos').append(`<tr id="fila2-${clave}">
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                        <td contenteditable="true" onchange="buscadorArticulo(this)">${clave}</td>
                        <td>${articulo}</td>
                        <td contenteditable="true" class="currency" oninput="calculoImporte(this)" id="cantidad-${clave}">${cantidad}</td>
                        <td>${unidad}</td>
                        <td  contenteditable="true" class="currency" oninput="calculoImporte(this)" id="precio-${clave}">$ ${precio}</td>
                        <td>${importe}</td>
                        <td>${IVA}</td>
                        <td>${importeIVA}</td>
                        <td>${total}</td>
                        <td>
                        <i onclick="eliminarRenglon(this)" class="fa fa-trash  btn-delete-articulo" aria-hidden="true" style="color: red; font-size: 25px; cursor: pointer;"></i>
                        </td>
                        <td style="display:none;"></td>
                    </tr>`);
                    }
                    

                    // contadorArticulos++;
                
                }
                jQuery("#cantidadArticulos").val(datos.length);
            }
            else{
                $('#controlArticulo').show();
            }
        }

        if (tablaVenta != '' ) {
          const tablaStr = tablaVenta.replace(/&quot;/g,'"');
            const tabla = JSON.parse(tablaStr);
            // console.log(tabla, 'tabla');
            tabla.forEach(element => {
                // console.log(element);
                $('#tdInversion').text(element.inversionInicial);
                $('#tdInversionP').text(element.inversionInicialPorcentaje);
                $('#tdMensualidades').text(element.mensualidades);
                $('#tdMensualidadesP').text(element.mensualidadesPorcentaje);
                $('#tdFiniquito').text(element.finiquito);
                $('#tdFiniquitoP').text(element.finiquitoPorcentaje);
            });

            if ($('#selectMovimiento').val() != "Factura") {
                $('#btnCorrida').click();
            }
            
        }

        if (coPropsInput != '') {
            let coPropDatosStr = coPropsInput.replace(/&quot;/g,'"');
            let coPropsDatosJson = JSON.parse(coPropDatosStr);
            for (let i = 0; i < coPropsDatosJson.length; i++) {
                $('#nCoprop').append(`
                <label class="col-lg-2 col-form-label field-label">
                    <span class="table-add float-right mb-3 mr-2"></span>
                    Co-Propietario ${x}
                </label>
                <div class="col-lg-6">
                    <div class="col-sm-12 input-font input-correction">
                        <select id="selectCoprop-${x}" name="selectCopropArray[]" >
                            <option value="">Seleccione un copropietario</option>
                            ${Object.entries(clientes).map(([key, value]) => `<option ${coPropsDatosJson[i]["id"] === key ? 'selected' : ''} value="${key}">${value}</option>`)}
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-12">
                    <input id="inputCoprop-${x}" type="text" name="impAsignArray[]" class="input-bordered input-font currency" placeholder="Importe asignado ($)" value="${coPropsDatosJson[i]["importe"] == null ? 0.00 : coPropsDatosJson[i]["importe"] }">
                </div>
                `);
                 x++;
                let select = $(`#selectCoprop-${x-1}`);
                select.select2({
                    width: '100%'
                });
                select.on('select', function (e) {
                let valor = {
                    id:$(this).val(),
                    nombre:$(this).find('option:selected').text()
                }
                coPropsDatosJson.push(valor);
                $('#inputCoprops').val(JSON.stringify(coPropsDatosJson));
                });
            }
        }
        else{
            let venta = {!! json_encode($venta)!!};
            console.log(venta);
            let coPropsDatosJson = jsCoprops;
            for (let i = 0; i < coPropsDatosJson.length; i++) {
                $('#nCoprop').append(`
                <label class="col-lg-2 col-form-label field-label">
                    <span class="table-add float-right mb-3 mr-2"></span>
                    Co-Propietario ${x}
                </label>
                <div class="col-lg-6">
                    <div class="col-sm-12 input-font input-correction">
                        <select id="selectCoprop-${x}" name="selectCopropArray[]" >
                            <option value="">Seleccione un copropietario</option>
                            ${Object.entries(clientes).map(([key, value]) => `<option ${coPropsDatosJson[i].coprop === key ? 'selected' : ''} value="${key}">${value}</option>`)}
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-12">
                    <input id="inputCoprop-${x}" type="text" name="impAsignArray[]" class="input-bordered input-font currency" placeholder="Importe asignado ($)" value="${coPropsDatosJson[i]["importe"] == null ? 0.00 : coPropsDatosJson[i]["importe"]}">
                </div>
                `);
                 x++;
                let select = $(`#selectCoprop-${x-1}`);
                let input = $(`#inputCoprop-${x-1}`);
                select.select2({
                    width: '100%'
                });
                select.on('select', function (e) {
                let valor = {
                    id:$(this).val(),
                    nombre:$(this).find('option:selected').text()
                }
                coPropsDatosJson.push(valor);
                $('#inputCoprops').val(JSON.stringify(coPropsDatosJson));
                });
                if (venta.estatus == "CONCLUIDO") {
                    select.prop('disabled', true);
                    input.prop('readOnly', true);
                }
            }
        }
            
            



        $('#selectMoneda').val('{{ !empty($venta['moneda']) ? $venta['moneda'] : ($parametro ? $parametro->monedaDefault : '1') }}').trigger('change');
        $('#selectProyecto').change();
        $('#selectModulo').val('{{ !empty($venta['modulo']) ? $venta['modulo'] : '' }}').trigger('change');
        // $('#selectModulo').change();
        $('#inputMeses').val('{{ !empty($venta['financiamientoMeses']) ? $venta['financiamientoMeses'] : old('inputMeses') }}').trigger('keyup');
        $('#selectVendedor').val('{{ !empty($venta['vendedor']) ? $venta['vendedor'] : '' }}').trigger('change');
        if('{{$venta->getCobro != null}}'){
            $('#selectCuentaDinero').val('{{ $venta->getCobro != null ? $venta->getCobro->cuentaDinero : '' }}').trigger('change');
        }
        

        let estatus = '{{ !empty($venta['estatus']) ? $venta['estatus'] : '' }}';

        if(estatus != 'PENDIENTE'){
        $('#selectCondicion').val('{{ !empty($venta['condicionPago']) ? $venta['condicionPago'] :  old('selectCondicion') }}').trigger('change');
        }
        
    });
    
    jsIdMovimiento = {!! json_encode($venta->idVenta) !!};

    flujoPrincipal(jsIdMovimiento,'Ventas');
    
    </script>
<script src="{{ asset('js\procesos\ventas\ventas_contrato.js') }}"></script>
<script src="{{ asset('js\procesos\ventas\ventas_financiamiento.js') }}"></script>
<script src="{{ asset('js\procesos\ventas\ventas_comisiones.js') }}"></script>
@endsection
