<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/reportes.css')}}">
    <title>Reporte de financiamiento</title>
</head>
@php

    use Carbon\Carbon;
    $mesesEnEspanol = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    $fechaCarbon = Carbon::parse($venta->fechaFinContrato);
                        
    $diaFormat = $fechaCarbon->format('d');
    $mesFormat = $mesesEnEspanol[$fechaCarbon->month - 1];

    $fechaF = $diaFormat . '/' . $mesFormat . '/' . $fechaCarbon->year;
    
@endphp
    <body>
        <div class="container-3">
            <table cellpadding="0" cellspacing="0" class="cabecera">
                <tr>
                    <td></td><td></td><td></td><td></td>
                    <td align="right" class="bg-date">
                        {{date('d/m/Y')}}
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="right" colspan="2" class="logoF">
                        <img src="{{$imgLogo}}">
                    </td>
                    <td colspan="2" class="empty"><h2>FINANCIAMIENTO <br>{{strtoupper($venta->getEmpresa->nombreCorto)}}</h1></td>
                </tr>
                <tr>
                    <td rowspan="7" align="center" class="logoP">
                        <img src="{{$imgProyecto}}">
                    </td>
                    <td class="bg-date" colspan="4" align="center"><h3>DATOS GENERALES</h3></td>
                </tr>
                <tr>
                    <td colspan="4" align="center">
                        <h4>CLIENTE: <span class="border-b">{{$venta->getCliente->razonSocial}}</span></h4>
                    </td>
                </tr>
                <tr><td colspan="4"></td></tr>
                <tr class="empty">
                    <td class="border" colspan="1">UNIDAD:</td>
                    <td class="bg-cells bg-cells-white border" colspan="1">
                        {{$venta->getModulo != null ? $venta->getModulo->clave : ''}}-{{$venta->getModulo != null ? $venta->getModulo->descripcion : ''}}
                    </td>
                    <td class="border" colspan="1">FECHA CONTRATO:</td>
                    <td class="border" colspan="1">{{date("d/m/Y", strtotime($venta->fechaContrato))}}</td>
                </tr>
                <tr class="empty">
                    <td class="border" colspan="1">TOTAL M2</td>
                    <td class="bg-cells border" colspan="1">{{$venta->getModulo->mt2}}</td>
                    <td class="border" colspan="1">FINANC (MESES)</td>
                    <td class="bg-cells bg-cells-white border" colspan="1">{{$venta->financiamientoMeses}}</td>
                </tr>
                <tr class="empty">
                    <td class="border" colspan="1">MTTO ANUAL</td>
                    <td class="border" colspan="1">$ {{number_format($venta->mantenimientoAnual, 2)}}</td>
                    <td class="border" colspan="1">ENGANCHE</td>
                    <td class="bg-cells border" colspan="1">{{$venta->enganche}}%</td>
                </tr>
                <tr class="empty">
                    <td class="border" colspan="1">PRECIO INMUEBLE</td>
                    <td class="bg-cells border" colspan="1">{{$venta->getPlan != null ? $venta->getPlan->precioInmueble : null}}</td>
                    <td class="border" colspan="1">FINIQUITO</td>
                    <td class="border" colspan="1">{{strtoupper($fechaF)}}</td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" class="cabecera">
                <tr class="empty">
                    <td class="bg-date"><h3>PLAN DE VENTA</h3></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" class="cabecera">
                <tr>
                    <td class="tables-center-2" align="center">PRECIO</td>
                    <td class="cell-width border" align="right">{{$venta->getPlan != null ? $venta->getPlan->precioInmueble : null}}</td>
                    <td class="align-monto-2" colspan="4"></td>
                </tr>
                <tr>
                    <td align="center">INVERSION INICIAL</td>
                    <td class="cell-width border" align="right">{{$venta->getPlan != null ? $venta->getPlan->inversionInicial: null}}</td>
                    <td class="align-monto-2" colspan="4">{{$venta->getPlan != null ? $venta->getPlan->porcentajeInversion : null}}</td>
                </tr>
                <tr>
                    <td align="center">MENSUALIDADES</td>
                    <td class="cell-width border" align="right">{{$venta->getPlan != null ? $venta->getPlan->mensualidades : null}}</td>
                    <td class="align-monto-2" colspan="4">{{$venta->getPlan != null ? $venta->getPlan->porcentajeMensualidades : null}}</td>
                </tr>
                <tr>
                    <td align="center">FINIQUITO</td>
                    <td class="cell-width border" align="right">{{$venta->getPlan != null ? $venta->getPlan->finiquito : null}}</td>
                    <td class="align-monto-2" colspan="4">{{$venta->getPlan != null ? $venta->getPlan->porcentajeFiniquito : null}}</td>
                </tr>
            </table>
            {{-- Plan de venta --}}
            <table class="table-bordered-2">
                <tr align="center">
                    <td align="right" class="cell-width" rowspan="3"><b>INVERSION <br> INICIAL</b></td>
                    <td width="19%" class="bg-cells border">FECHA DE PAGO</td>
                    <td class="bg-cells border">MONTO</td>
                    <td class="bg-cells border">IVA</td>
                    <td class="bg-cells border">TOTAL</td>
                </tr>
                <tr align="center">
                    <td class="border">{{date("d/m/Y", strtotime($ventaEnganche != null ? $ventaEnganche->fechaPagoCorrida : null))}}</td>
                    <td class="border">{{$ventaEnganche != null ? $ventaEnganche->montoCorrida : null}}</td>
                    <td class="border">{{$ventaEnganche != null ? $ventaEnganche->ivaCorrida : null}}</td>
                    <td class="border">{{$ventaEnganche != null ? $ventaEnganche->totalCorrida : null}}</td>
                </tr>
            </table>
            <table class="table-bordered-2">
                <tr align="center">
                    <td class="cell-width"></td>
                    <td width="19%" class="bg-cells border">FECHA DE PAGO</td>
                    <td class="bg-cells border">MONTO</td>
                    <td class="bg-cells border">IVA</td>
                    <td class="bg-cells border">TOTAL</td>
                </tr>
                @php
                    $mens = $venta->getCorrida;
                    // dd($mensualidades[0], $mensualidades[count($mensualidades)]);
                    unset($mens[0]);
                    unset($mens[count($mens)]);
                    
                @endphp
                    @foreach ($mens as $key => $item)
                        <tr align="center">
                            <td align="right"><b>{{$key}}</b></td>
                            <td class="border">{{date("d/m/Y", strtotime($item != null ? $item->fechaPagoCorrida : null))}}</td>
                            <td class="border">{{$item != null ? $item->montoCorrida: null}}</td>
                            <td class="border">{{$item != null ? $item->ivaCorrida : null}}</td>
                            <td class="border">{{$item != null ? $item->totalCorrida : null}}</td>
                        </tr>
                    @endforeach
                    <tr align="center">
                        <td align="right" class="cell-width"><b>TOTAL MSI</b></td>
                        <td class="bg-cells-gold border"></td>
                        <td class="bg-cells-gold border"><b>${{number_format($venta->subTotalMonto, 2)}}</b></td>
                        <td class="bg-cells-gold border"><b>${{number_format($venta->subTotalIva, 2)}}</b></td>
                        <td class="bg-cells-gold border"><b>${{number_format($venta->totalCorrida, 2)}}</b></td>
                    </tr>
            </table>
            <table class="table-bordered-2" style="margin-top: 5px">
                <tr align="center">
                    <td align="right" class="cell-width"><b>FINIQUITO</b></td>
                    <td width="19%" class="bg-cells-gold border"><b>{{date("d/m/Y", strtotime($ventaFiniquito != null ? $venta->fechaFinContrato : null))}}</b></td>
                    <td  class="bg-cells-gold border"><b>{{$ventaFiniquito != null ? $ventaFiniquito->montoCorrida : null}}</b></td>
                    <td  class="bg-cells-gold border"><b>{{$ventaFiniquito != null ? $ventaFiniquito->ivaCorrida : null}}</b></td>
                    <td  class="bg-cells-gold border"><b>{{$ventaFiniquito != null ? $ventaFiniquito->totalCorrida : null}}</b></td>
                </tr>
                <tr>
                    <td class="cell-width"></td>
                    <td class="font-small" colspan="4">*Precios y condiciones sujetos a cambios.</td>
                </tr>
            </table>
        </div>
    </body>
</html>

