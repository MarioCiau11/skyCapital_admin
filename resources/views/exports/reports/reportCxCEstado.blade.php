<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/reportes.css')}}">
    <title>Estado de cuenta</title>
</head>
@php
    use Carbon\Carbon;
    setlocale(LC_TIME, 'es_ES');
    $mesesEnEspanol = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
@endphp
    <body>
        <div class="container-5">
            <table  class="cabecera" style="font-size: 16px !important;">
                <tr>
                    <td class="logoI">
                        {{-- <img src="<?php echo $base64ImageE ?>"> --}}
                        <img src="{{$imgLogo}}">
                    </td>
                    <td style="width: 200px"></td>
                    <td align="left">Nombre de la empresa: <b>{{strtoupper(session('company')->nombreEmpresa)}}</b> <br><br>
                    RFC de la empresa: <b>{{strtoupper(session('company')->RFC)}}</b></td>
                    <td>Fecha de impresión: {{(date('d/m/Y'))}} <br><br>&nbsp;
                    </td>
                </tr>
                <tr>
                    <td></td><td></td>
                    <td align="center"><b>ESTADO DE CUENTA</b></td><td></td>
                </tr>
                <tr>
                    <td></td><td></td>
                    @foreach ($estados as $key => $articulo)
                        @php
                            if ($cliente != "Todos")
                                $cliente = $articulo->getCliente->razonSocial;
                            else
                                $cliente = "Todos";    
                        @endphp
                    @endforeach
                    <td align="center"><b>{{strtoupper($cliente)}}</b></td><td></td>
                </tr><br>
            </table>
            <table class="table-bordered-5 empty">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Movimiento</th>
                        <th>Fecha</th>
                        <th>Cargos</th>
                        <th>Abonos</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                @php
                    $sorted = $estados != null ? $estados->sortBy('getCliente.idCliente') : [];
                    $lastClient = null;
                    $lastCurrency = null;
                    $clientTotals = [];
                    $filtradoEst = $sorted;

                    function printClientTotals($totals, $money) {
                        return '<br>
                            <tr>
                                <td class="border-t3" colspan="3"></td>
                                <td class="border-t3"><b>$ '.number_format($totals['totalCargos'], 2).'</b></td>
                                <td class="border-t3"><b>$ '.number_format($totals['totalAbonos'], 2).'</b></td>
                                <td class="border-t3"><b>$ '.number_format($totals['totalSaldo'], 2). ' ' .ucfirst(strtolower($money)).'</b></td>
                                <td></td>
                            </tr>';
                    }
                    // dd($filtradoEst, $sorted)
                @endphp
                @foreach ($filtradoEst as $key => $value)
                    @php
                        $actualClient = $value->getCliente != null ? $value->getCliente->razonSocial : '';
                        $clave = $value->getCliente != null ? $value->getCliente->clave : '';
                        $proyecto = $value->getProyecto != null ? $value->getProyecto->nombre : '';
                        $modulo = $value->getModulo != null ? $value->getModulo->descripcion : '';
                        $money = $value->moneda != null ? $value->moneda : '';
                    @endphp
                    @if ($lastClient !== $actualClient || $lastCurrency !== $money)
                        @if (!is_null($lastClient))
                            {!! printClientTotals($clientTotals[$lastClient][$lastCurrency], $lastCurrency) !!}
                        @endif
                        @php
                            $lastClient = $actualClient;
                            $lastCurrency = $money;
                            if (!isset($clientTotals[$actualClient][$money])) {
                                $clientTotals[$actualClient][$money] = [
                                                'totalCargos' => 0,
                                                'totalAbonos' => 0,
                                                'totalSaldo' => 0,
                                ];
                            } else {
                                $clientTotals[$actualClient][$money] = [
                                    'totalCargos' => $clientTotals[$actualClient][$money]['totalCargos'],
                                    'totalAbonos' => $clientTotals[$actualClient][$money]['totalAbonos'],
                                    'totalSaldo' => $clientTotals[$actualClient][$money]['totalSaldo']
                                ];
                            }
                        @endphp
                        <tbody>
                        <tr class="m-top">
                            <td align="left"><b><pre>{{$proyecto}}</pre></b></td>
                            <td colspan="6" align="left"><b><pre>{{$modulo}} - {{$modulo}}</pre></b></td>
                        </tr>
                        <tr>
                            <td align="center"><b><pre>{{$clave}}</pre></b></td>
                            <td colspan="6" align="left"><b><pre>{{$actualClient}}</pre></b></td>
                        </tr>
                    @endif
                    @php
                        $fechaCarbon = Carbon::parse($value->fechaEmision);
                        $diaFormat = $fechaCarbon->format('d');
                        $mesFormat = $fechaCarbon->format('m');
                        $fechaFinal = $diaFormat . '/' . $mesFormat . '/' . $fechaCarbon->year;
                        $clientTotals[$actualClient][$money]['totalCargos'] += $value->cargo != null ? $value->cargo : $value->total;
                        $clientTotals[$actualClient][$money]['totalAbonos'] += $value->abono != null ? $value->abono : 0;
                        $clientTotals[$actualClient][$money]['totalSaldo'] += $value->cargo != null ? ($value->cargo - $value->abono) : ($value->total - $value->abono);
                    @endphp
                    <tr>
                        <td></td>
                        <td class="border-t2">{{$value->movimiento}} {{strpos($value->movimiento, 'Mensualidad') !== false ? '' : $value->folio}}</td>
                        <td class="border-t2">{{($fechaFinal)}}</td>
                        <td class="border-t2">{{$value->cargo != null ? '$'. ' ' .number_format($value->cargo,2) : '$'. ' ' .number_format($value->total,2)}}</td>
                        <td class="border-t2">{{$value->abono != null ? '$'. ' ' .number_format($value->abono,2) : ''}}</td>
                        <td class="border-t2">$ {{number_format($value->cargo != null ? ($value->cargo - $value->abono) : ($value->total - $value->abono),2)}}</td>
                    </tr>
                    @if ($value->aplica != null)
                        <tr>
                            <td></td>
                            <td class="border-t2">{{$value->aplica}} {{$value->idAplica}}</></td><td class="border-t2"></td>
                            <td class="border-t2">$ @if (strpos($value->aplica, 'Aplicación') !== false) {{number_format($value->cargo,2)}} @else 0 @endif</td>
                            <td class="border-t2">$ @if (strpos($value->aplica, 'Aplicación') !== false) {{number_format($value->abono,2)}} @else {{number_format($value->cargo - $value->abono,2)}} @endif</td>
                            <td class="border-t2">@if (strpos($value->aplica, 'Aplicación') !== false) {{number_format($value->cargo - $value->abono,2)}} @else - $ {{number_format($value->cargo - $value->abono,2)}} @endif</td>
                        </tr>
                        <tr>
                            <td></td><td></td>
                            <td><b>{{$value->movimiento}} {{strpos($value->movimiento, 'Mensualidad') !== false ? '' : $value->folio}}</b></td>
                        </tr>
                    @endif
                    </tbody>
                @endforeach
                {!! printClientTotals($clientTotals[$actualClient][$lastCurrency], $lastCurrency) !!}
            </table>
            
        </div>
    </body>
</html>

