<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/reportes.css')}}">
    <title>{{$tesoreria->movimiento.'-'.$tesoreria->folioMov}}</title>
</head>
@php
    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
    $fechaE = $tesoreria->fechaEmision;
    $fechaE_base = strftime("%d %B %Y", strtotime($fechaE));
@endphp
<body>
    <div class="container-4">
        <br>
        <table class="cabecera">
            <tr>
                <td class="logo">
                    <img src="{{$imgLogo}}" alt="">
                    <td>Nombre de la empresa: {{strtoupper($tesoreria->getEmpresa != null ? $tesoreria->getEmpresa->nombreEmpresa : '')}} <br>
                        <br>RFC de la empresa: {{$tesoreria->getEmpresa->RFC}}</td>
                    <td>Fecha de impresión: {{(date('d/m/Y'))}}
                    <br><br><br></td>
                </td>
            </tr>
        </table>
        <table class="table-bordered-3 empty">
            <tr>
                <td width="200" class="font-xg"><b>{{$tesoreria->movimiento}} <i>{{$tesoreria->folioMov}}</i></b></td>
                <td width="110"><i>{{ucfirst(strtolower($tesoreria->estatus))}}</i></td>
                <td width="180"></td>
                <td width="110"><i>{{ucfirst(strtolower($tesoreria->getMoneda->nombre))}}</i></td>
                <td><i>{{ucwords($fechaE_base)}}</i></td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0" class="cabecera">
            <tr>
                <td align="right" class="cell-width">Cuenta: </td>
                <td align="left" class="cell-width-large input-padding"><b>{{strtoupper($cuentaOrigen->clave.' - '.$cuentaOrigen->noCuenta)}}</b></td>
                @if ($tesoreria->movimiento == 'Transferencia' )
                    <td align="right">Cuenta Destino: </td>
                    <td align="right" class="input-padding" colspan="1"><b>{{strtoupper($cuentaDestino)}}</b></td>
                    <td width="30"></td>
                @endif
            </tr>
            <tr>
                <td align="right">Referencia: </td>
                <td class="cell-width-large input-padding" align="left">{{$tesoreria->referencia}}</td>
                {{-- <td align="center" class="border-b" colspan="3">{{strtoupper($venta->getEmpresa->nombreEmpresa)}}</td> --}}
                <td align="right">Importe: </td>
                <td align="right" class="input-padding" colspan="1"><b>${{number_format($tesoreria->importeTotal, 2)}}</b></td>
                <td width="30"></td>
            </tr>
            <tr>
                <td align="right">Forma de Pago: </td>
                <td class="cell-width-large input-padding" align="left">{{$tesoreria->getFormaPago->clave}}</td>
                <td width="30"></td>
            </tr>
            <tr>
                <td align="right">Observaciones: </td>
                <td align="left" class="cell-width-large input-padding">{{ucfirst($tesoreria->observaciones)}}</td>
            </tr>
             <tr>
                <td align="left" colspan="2"><em>({{strtolower($importeLetra)}})</em></td>
                {{-- <td align="left" class="cell-width-large input-padding" rowspan="3"><em>({{$importeLetra}})</em></td> --}}
            </tr>
            <tr><td></td><td></td><td></td><td></td></tr>
            <tr><td></td><td></td><td></td><td></td></tr>
        </table>
        @if ($tesoreria->movimiento == 'Ingreso' || $tesoreria->movimiento == 'Depósito')
            <br>
            <br>
            <table cellpadding="0" cellspacing="0" class="" >
                <thead>
                    <tr class="border-b2">
                        <th width="200">Importe</th>
                        <th  width="200">Forma Pago</th>
                        <th  width="200">Referencia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td align="center">${{number_format($tesoreria->importeTotal, 2)}}</td>
                        <td align="center">{{$tesoreria->getFormaPago->clave}}</td>
                        <td align="center">{{$tesoreria->referencia}}</td>
                    </tr>
                </tbody>
            </table>
        @endif
        
    </div>
</body>
</html>