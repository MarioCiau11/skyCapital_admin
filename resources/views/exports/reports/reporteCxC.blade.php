<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/reportes.css')}}">
    <title>Reporte {{$cxc->movimiento}}-{{$cxc->folioMov}}</title>
</head>
@php
    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
    $fechaE = $cxc->fechaEmision;
    $fechaE_base = strftime("%d %B %Y", strtotime($fechaE));
    // dd();
@endphp
    <body>
        <div class="container-4">
            <br>
            <table  class="cabecera">
                <tr>
                    <td class="logo">
                        <img src="{{ $imgLogo }}">
                    </td>
                    <td>Nombre de la empresa: {{strtoupper($cxc->getEmpresa != null ? $cxc->getEmpresa->nombreEmpresa : '')}} <br>
                        <br>RFC de la empresa: {{$cxc->getEmpresa->RFC}}</td>
                    <td>Fecha de impresión: {{(date('d/m/Y'))}}
                    <br><br><br></td>
                </tr>
            </table>
            <table class="table-bordered-3 empty">
                <tr>
                    <td width="200" class="font-xg"><b>{{$cxc->movimiento}} <i>{{$cxc->folioMov}}</i></b></td>
                    <td width="110"><i>{{ucfirst(strtolower($cxc->estatus))}}</i></td>
                    <td width="180"></td>
                    <td width="110"><i>{{ucfirst(strtolower($cxc->getMoneda->nombre))}}</i></td>
                    <td><i>{{ucwords($fechaE_base)}}</i></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" class="cabecera">
                <tr>
                    <td align="right" class="cell-width">Cliente: </td>
                    <td align="left" class="cell-width-large input-padding"><b>{{strtoupper($cxc->getCliente->razonSocial)}} ({{$cxc->getCliente->clave}})</b></td>
                    @if ($cxc->movimiento == 'Aplicación' || $cxc->movimiento == 'Cobro')
                        <td align="right">Origen: </td>
                        <td align="right" class="input-padding" colspan="1"><b>{{$cxc->origen}} {{$cxc->origenId}}</b></td>
                        <td width="30"></td>
                    @else
                        <td></td>
                        <td></td>
                        <td width="30"></td>
                    @endif
                </tr>
                <tr>
                    <td align="right">Referencia: </td>
                    <td class="cell-width-large input-padding" align="left">{{$cxc->referencia}}</td>
                    {{-- <td align="center" class="border-b" colspan="3">{{strtoupper($venta->getEmpresa->nombreEmpresa)}}</td> --}}
                    <td align="right">Importe: </td>
                    <td align="right" class="input-padding" colspan="1"><b>${{number_format($cxc->importe, 2)}}</b></td>
                    <td width="30"></td>
                </tr>
                <tr>
                    <td align="right">Observaciones: </td>
                    <td align="left" class="cell-width-large input-padding" rowspan="3">{{ucfirst($cxc->observaciones)}}</td>
                    @if ($cxc->movimiento == 'Anticipo')
                        <td align="right">Impuestos: </td>
                        <td align="right" class="input-padding" colspan="1"><b>${{number_format($cxc->impuestos, 2)}}</b></td>
                        <td width="30"></td>
                    @else
                        <td></td>
                        <td></td>
                        <td width="30"></td>
                    @endif
                </tr>
                <tr><td></td><td></td><td></td><td></td></tr>
                <tr><td></td><td></td><td></td><td></td></tr>
            </table>
            @if ($cxc->movimiento == 'Anticipo')
                <table cellpadding="0" cellspacing="0" class="cabecera">
                    <tr>
                        <td class="cell-width"></td>
                        <td align="left" class="cell-width-large"><b></b></td>
                        <td align="right" width="87">Importe total: </td>
                        <td align="right" class="input-padding" colspan="1"><b>${{number_format($cxc->total, 2)}}</b></td>
                        <td width="30"></td>                       
                    </tr>
                </table>
            @endif
            <br><br>
            @if ($cxc->movimiento != 'Anticipo')
                <table cellpadding="0" cellspacing="0" class="cabecera cobroDetalle">
                    <tr>
                        <td class="cell-width-medium"><b><i>Aplicación:</i></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @php
                        $contador = 0;
                    @endphp
                    @if(count($cxc->getDetalle) > 0)
                        @foreach($cxc->getDetalle as $key => $detalle)
                            <tr>
                                <td>{{$detalle->aplica}} CFDI {{$detalle->aplicaConsecutivo}}</td>
                                <td class="cell-width-large" align="left">${{number_format($detalle->importe, 2)}}</td>
                                {{-- <td align="center" class="border-b" colspan="3">{{strtoupper($venta->getEmpresa->nombreEmpresa)}}</td> --}}
                                <td></td>
                                <td></td>
                            </tr>
                            @php
                                $contador++;
                            @endphp
                        @endforeach
                    @endif
                </table>
            @else
                <table cellpadding="0" cellspacing="0" class="cabecera">
                    <tr>
                        @if($cxc->movimiento != 'Anticipo')
                        <td align="right" class="cell-width">Condición de pago:</td>
                        <td class="cell-width-large input-padding" align="left">{{strtoupper($cxc->getCondition != null ? $cxc->getCondition->tipoCondicion : '')}}</td>
                        <td></td>
                        <td></td>
                        @else
                        <td align="right" class="cell-width">Forma de pago:</td>
                        <td class="cell-width-large input-padding" align="left">{{strtoupper($cxc->getFormaPago != null ? $cxc->getFormaPago->clave : '')}}</td>
                        <td></td>
                        <td></td>
                        @endif
                        
                    </tr>  
                </table>
            @endif
        </div>
    </body>
</html>

