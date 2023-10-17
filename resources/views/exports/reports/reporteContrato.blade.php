<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/reportes.css')}}">
    <title>Reporte de venta</title>
</head>

    <body>
        <div class="container">
            <table  class="cabecera">
                <tr>
                    <td class="logo">
                        <img src="{{$imgLogo}}">
                    </td>
                    <td>Nombre de la empresa: {{strtoupper($venta->getEmpresa->nombreEmpresa)}} <br>
                        <br>RFC de la empresa: {{$venta->getEmpresa->RFC}}</td>
                    <td>Fecha de impresión: {{(date('d/m/Y'))}}
                    <br><br><br></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" class="cabecera">
                @if ($venta->movimiento == 'Factura')
                    <tr>
                        <td class="text">Folio factura:</td>
                        <td>
                            <input class="no-border-b" type="text" value="{{$venta->folioMov}}">
                        </td>
                        <td class="">Importe factura:</td>
                        <td>
                            <input class="no-border-b" type="text" placeholder="$ " value="$ {{number_format($venta->total, 2)}}">
                        </td>
                    </tr>
                @elseif ($venta->movimiento == 'Contrato')
                    <tr>
                        <td class="text">Folio del contrato </td>
                        <td>
                            <input type="text" class="inputs-small" value="{{$venta->folioMov}}">
                        </td>
                    </tr>
                @endif
                <tr>
                    <td class="text">Nombre o razón social del cliente</td>
                    <td colspan="3">
                        <input type="text" class="inputs-large" value="{{ucwords($venta->getCliente->razonSocial)}}">
                    </td>
                </tr>
                <tr>
                    <td class="text">Proyecto</td>
                    <td colspan="3">
                        <input type="text" class="inputs-large" value="{{ucwords($venta->getProyecto->nombre)}}">
                    </td>
                </tr>
                <tr>
                    <td class="text">Módulo</td>
                    <td colspan="3">
                        <input type="text" class="inputs-large" value="{{ucfirst($venta->getModulo->descripcion)}}">
                    </td>
                </tr>
                <tr>
                    <td class="text">Monto operación</td>
                    <td colspan="3">
                        <input type="text" class="inputs-large inputs-border" value="$ {{number_format($venta->valorOperacionComision, 2)}}">
                    </td>
                </tr>
                <tr>&nbsp;</tr>
            </table>
            <br>
            <table cellpadding="0" cellspacing="0" class="cabecera ">
                <tr>
                    <td class="text">Enganche</td>
                    <td>
                        <input type="text" class="no-border-b" value="{{$ventaEnganche != null ? $ventaEnganche->totalCorrida : null}}">
                    </td>
                </tr>
                <tr>
                    <td class="text">Mensualidades</td>
                    <td>
                        <input type="text" class="no-border-b" value="{{$ventaMensualidad != null ? $ventaMensualidad->total: null}}">
                    </td>
                    <td class=""># Mensualidades</td>
                    <td>
                        <input type="text" value="{{$venta->financiamientoMeses}}">
                    </td>
                </tr>
                <tr>
                    <td class="text">Finiquito</td>
                    <td>
                        <input type="text" class="inputs-border" value="{{$ventaFiniquito != null ? $ventaFiniquito->totalCorrida: null}}">
                    </td>
                </tr>
            </table>
            <br>
            <table cellpadding="0" cellspacing="0" class="cabecera ">
                <tr>
                    <td class="text">Vendedor</td>
                    <td>
                        <input type="text" class="inputs-large inputs-border" value="{{ucwords($venta->asesor)}}">
                    </td>
                </tr>
            </table>
            <br>
            <table class="table-bordered">
                <tr>
                    <td class="text no-border">Detalle del movimiento</td>
                </tr>
                <tr>
                    <td>Artículo</td>
                    <td>Cantidad</td>
                    <td>Unidad</td>
                    <td>Total</td>
                </tr>
                @if($venta->getDetalle->count() > 0)
                    @foreach($venta->getDetalle as $key => $detalle)
                    <tr id="controlArticulo-{{$key}}">
                        <td>{{$detalle->descripcionArticulo}}</td>
                        <td>{{$detalle->cantidad}}</td>
                        <td>{{$detalle->unidadVenta}}</td>
                        <td>$ {{number_format($detalle->importeTotal, 2)}}</td>
                    </tr>
                    @endforeach
                @else
                    <tr class="empty">
                        <td colspan="4">No hay artículos</td>
                    </tr>
                @endif
            </table>
        </div>
    </body>
</html>

