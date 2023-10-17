<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/reportes.css')}}">
    <title>Reporte de comisiones</title>
</head>
@php
    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
    $fechaC = $venta->fechaContrato;
    $fechaC_base = strftime("%d de %B de %Y", strtotime($fechaC));

    $fechaI = $venta->fechaInicioMensualidad;
    $fechaI_base = strftime("%d de %B de %Y", strtotime($fechaI));
@endphp
    <body>
        <div class="container-2">
            <br>
            <table cellpadding="0" cellspacing="0" class="cabecera">
                <tr>
                    <td colspan="5"><h2>Solicitud de pago (comisiones)</h2></td>
                    <td colspan="1" align="center" class="logoC">
                        <img src="{{$imgProyecto}}">
                    </td>
                </tr>
                <tr>
                    <td class="cell-width">Fecha de solicitud: </td>
                    <td align="center" class="border-b" colspan="3">{{(strtoupper($fechaC_base))}}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Empresa: </td>
                    <td align="center" class="border-b" colspan="3">{{strtoupper($venta->getEmpresa->nombreEmpresa)}}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Proyecto: </td>
                    <td align="center" class="border-b" colspan="3">{{strtoupper($venta->getProyecto->nombre)}}</td>
                    <td></td>
                </tr>
            </table>
            <br>
            <table class="table-right">
                <tr>
                    <td class="border-t font-small">Liberado</td>
                </tr>
                <tr>
                    <td>José Enrique Gasque</td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="20" class="cabecera-2">
                <tr>
                    <td class="cell-width" colspan="1">Cliente: </td>
                    <td align="center" class="border-b" colspan="0">{{(strtoupper($venta->getCliente->razonSocial))}}</td>
                </tr>
                <tr>
                    <td class="cell-width" colspan="1">Módulo: </td>
                    <td align="center" class="border-b" colspan="1">{{$venta->getProyecto != null ? strtoupper($venta->getProyecto->clave) : null}} - {{ $venta->getModulo != null ? $venta->getModulo->clave : null}}</td>
                </tr>
                <tr>
                    <td class="cell-width" colspan="1">Contrato asignado: </td>
                    <td align="center" class="border-b" colspan="1"><b>{{strtoupper($venta->claveProyecto)}}</b></td>
                </tr>
                <tr>
                    <td class="cell-width" colspan="1">Asesor/Broker: </td>
                    <td align="center" class="border-b" colspan="1">{{ucfirst($venta->asesor)}}</td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td class="font-small"><b>NOTA*</b> Si es broker nuevo adjuntar información fiscal y bancaria</td>
                </tr>
                <tr>
                    <td class="font-small"><b>NOTA**</b> Acompañar la solicitud con el comprobante de pago del enganche</td>
                </tr>
            </table>
            <br>
            <table>
                <tr>
                    <td class="cell-width">Etiqueta: </td>
                    <td align="center" class="border-b cell-width-large font-small" colspan="4"><b>{{$venta->getEtiqueta != null ? $venta->getEtiqueta->nombre : null}}</b></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="20" class="cabecera-3">
                <tr>
                    <td class="text-2" style="width: 50px;">Venta: </td>
                    <td class="empty label-comission <?php if ($venta->tipoComision == 1) echo 'input-background';?>">
                        @if ($venta->tipoComision == 1)
                            <label>X</label>
                        @endif
                    </td>
                    
                    <td class="text-2">Renta: </td>
                    <td class="empty label-comission <?php if ($venta->tipoComision == 2) echo 'input-background';?>">
                        @if ($venta->tipoComision == 2)
                            <label>X</label>
                        @endif
                    </td>

                    <td class="text-2" style="width: 50px;">Socios: </td>
                    <td class="empty label-comission <?php if ($venta->tipoComision == 3) echo 'input-background';?>">
                        @if ($venta->tipoComision == 3)
                            <label>X</label>
                        @endif
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td class="cell-width">Observaciones: </td>
                    <td class="cell-width-large" colspan="4" rowspan="3">
                        <p style="text-align: justify;" class="align-border <?php if ($venta->observaciones == null) echo 'border-b';?>">
                            <label class="border-b">{{$venta->observaciones}}</label>
                        </p>
                    </td>
                </tr>
            </table>
            <br>
            <table>
                <tr>
                    <td class="cell-width">Fecha de pago: </td>
                    <td align="center" class="border-b cell-width-large font-small" colspan="4">{{strtoupper($fechaI_base)}}</td>
                </tr>
            </table>
            <br>
            <table>
                <tr>
                    <td class="cell-width">Precio de operación: </td>
                    <td align="center" class="border-b cell-width-medium" colspan="4">$ {{number_format($venta->valorOperacionComision, 2)}}</td>
                    <td>+ I.V.A</td>
                </tr>
            </table>
            <br>
            <table>
                <tr>
                    <td class="cell-width">Enganche: </td>
                    <td align="center" class="border-b cell-width-xs" colspan="1">{{$venta->porcentajeEnganche}}%</td>
                    <td align="center" class="border-b cell-width" colspan="4">$ {{number_format($venta->importeEnganche, 2)}}</td>
                </tr>
            </table>
            <br>
            <table>
                <tr>
                    <td class="cell-width">% Comisionable: </td>
                    <td align="center" class="border-b cell-width-xs" colspan="1">{{$venta->porcentajeComisionable}}%</td>

                    <td align="right" class="cell-width-medium">Monto comisionable: </td>
                    <td align="center" class="border-b cell-width" colspan="4">$ {{number_format($venta->montoComisionable, 2)}}</td>
                </tr>
            </table>
            <br>
            <table class="table-bordered-2">
                <tr class="empty">
                    <td rowspan="4" class="font-small">Importe de comisión: </td>
                    <td></td><td></td><td></td>
                    <td>Neto</td>
                    <td>Factura</td>
                    <td>Forma de pago</td>
                </tr>
                <tr class="font-small">
                    <td>Asesor</td>
                    <td>{{$venta->asesor}}</td>
                    <td>{{number_format($venta->porcentajeAsesor, 1)}}%</td>
                    <td><span class="align-monto">$</span>{{number_format($venta->netoAsesor, 2)}}</td>
                    <td><span class="align-monto">$</span>{{number_format($venta->facturaAsesor, 2)}}</td>
                    <td>{{strtoupper($venta->formaPagoAsesor)}}</td>
                </tr>
                <tr class="font-small">
                    <td>Referidor</td>
                    <td>{{$venta->referido}}</td>
                    <td>{{number_format($venta->porcentajeReferido, 1)}}%</td>
                    <td><span class="align-monto">$</span>{{number_format($venta->netoReferido, 2)}}</td>
                    <td><span class="align-monto">$</span>{{number_format($venta->facturaReferido, 2)}}</td>
                    <td>{{strtoupper($venta->formaPagoReferido)}}</td>
                </tr>
                <tr class="font-small">
                    <td>Broker</td>
                    <td>{{$venta->broker}}</td>
                    <td>{{number_format($venta->porcentajeBroker, 1)}}%</td>
                    <td><span class="align-monto">$</span>{{number_format($venta->netoBroker, 2)}}</td>
                    <td><span class="align-monto">$</span>{{number_format($venta->facturaBroker, 2)}}</td>
                    <td>{{strtoupper($venta->formaPagoBroker)}}</td>
                </tr>
                <tr class="font-small">
                    <td></td><td></td><td></td><td></td>
                    <td><b><span class="align-monto">$</span>{{number_format($venta->totalNeto, 2)}}</b></td>
                    <td><b><span class="align-monto">$</span>{{number_format($venta->totalFactura, 2)}}</b></td>
                    <td></td>
                </tr>
                <tr class="font-small">
                    <td></td><td></td><td colspan="2" align="right"><b>ISR</b></td>
                    <td><b><span class="align-monto">$</span>{{number_format($venta->retencion, 2)}}</b></td>
                    <td></td><td></td>
                </tr>
                <tr class="font-small">
                    <td></td><td></td><td colspan="2" align="right"><b>TOTAL A PAGAR</b></td>
                    <td><b><span class="align-monto">$</span>{{number_format($venta->totalPago, 2)}}</b></td>
                    <td></td><td></td>
                </tr>
            </table>
            <br><br><br>
            <table class="table-bordered-2">
                <tr class="empty font-small">
                    <td class="border-t">Solicita</td>
                    <td>&nbsp;</td>
                    <td class="border-t">Revisa</td>
                    <td>&nbsp;</td>
                    <td class="border-t">Autoriza</td>
                </tr>
                <tr class="empty font-small">
                    <td>Lic. Diana Rodríguez</td>
                    <td></td>
                    <td>Ing. Roberto Serrano</td>
                    <td></td>
                    <td>C.P. Álvaro Rosado</td>
                </tr>
            </table>
        </div>
    </body>
</html>

