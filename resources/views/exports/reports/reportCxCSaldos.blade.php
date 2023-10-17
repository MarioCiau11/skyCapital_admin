<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/reportes.css')}}">
    <title>Antigüedad de saldos</title>
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
                    <td align="center"><b>ANTIGÜEDAD DE SALDOS</b></td><td></td>
                </tr>
                <tr>
                </tr><br>
            </table>
            <table class="table-bordered-2 empty">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Movimiento</th>
                        <th>Referencia</th>
                        <th>Fecha de emisión</th>
                        <th>Fecha de vencimiento</th>
                        <th>Días</th>
                        <th>Al corriente</th>
                        <th>De 1 a 15</th>
                        <th>De 16 a 30</th>
                        <th>De 31 a 60</th>
                        <th>De 61 a 90</th>
                        <th>Más de 90 días</th>
                    </tr>
                    
                </thead>
                @php
                    $sorted = $saldos != null ? $saldos->sortBy('getCliente.idCliente') : [];
                    $lastClient = null;
                    $lastCurrency = null;
                    $clientTotals = [];
                    $filtradoSaldos = $sorted;
                    // dd($sorted);
                    
                    function printClientTotals($totals, $money) {
                            return '
                                <tr><td></td>
                                    <td style="border-top: 3px solid #000"><b>Total:</b></td>
                                    <td style="border-top: 3px solid #000"><b>$ '.number_format($totals['totalSuma'], 2).' '.strtolower($money).'</b></td>
                                    <td style="border-top: 3px solid #000" colspan="3"></td>
                                    <td align="center" style="border-top: 3px solid #000"><b>$ '.number_format($totals['totalCorriente'], 2).'</b></td>
                                    <td align="center" style="border-top: 3px solid #000"><b>$ '.number_format($totals['total1-15'], 2).'</b></td>
                                    <td align="center" style="border-top: 3px solid #000"><b>$ '.number_format($totals['total16-30'], 2).'</b></td>
                                    <td align="center" style="border-top: 3px solid #000"><b>$ '.number_format($totals['total31-60'], 2).'</b></td>
                                    <td align="center" style="border-top: 3px solid #000"><b>$ '.number_format($totals['total61-90'], 2).'</b></td>
                                    <td align="center" style="border-top: 3px solid #000"><b>$ '.number_format($totals['total90+'], 2).'</b></td>
                                </tr><tr></tr>';
                        }
                    
                @endphp
                @foreach ($filtradoSaldos as $key => $value)
                    @php
                        $actualClient = $value->getCliente != null ? $value->getCliente->razonSocial : '';
                        $clave = $value->getCliente != null ? $value->getCliente->clave: '';
                        $money = $value->getMoneda != null ? $value->getMoneda->clave : null;
                        $fechaE = Carbon::parse($value->fechaEmision);
                        $fechaV = Carbon::parse($value->fechaVencimiento);
                        $fechaVCopy = Carbon::parse($value->fechaVencimiento);
                        
                        $diaE = $fechaE->format('d');
                        $diaV = $fechaV->format('d');

                        $mesE = $mesesEnEspanol[$fechaE->month - 1];
                        $mesV = $mesesEnEspanol[$fechaV->month - 1];

                        $fechaFinalE = $diaE . '/' . $mesE . '/' . $fechaE->year;
                        $fechaFinalV = $diaV . '/' . $mesV . '/' . $fechaV->year;

                        $saldo = $value->saldo != null ? '$'. ' ' .number_format($value->saldo,2) : '';
                        $totalcorr = Carbon::today() <= $fechaVCopy;
                        $total1 = Carbon::today() > $fechaVCopy && Carbon::today() <= $fechaVCopy->addDays(15);
                        $total16 = Carbon::today() > $fechaVCopy->addDays(15) && Carbon::today() <= $fechaVCopy->addDays(30);
                        $total31 = Carbon::today() > $fechaVCopy->addDays(30) && Carbon::today() <= $fechaVCopy->addDays(60);
                        $total61 = Carbon::today() > $fechaVCopy->addDays(60) && Carbon::today() <= $fechaVCopy->addDays(90);
                        $total90 = Carbon::today() > $fechaVCopy->addDays(90);

                        $fechaActual = Carbon::now()->format('Y-m-d');
                        $currentDate = Carbon::createFromFormat('Y-m-d', $fechaActual);
                        $fechaVencimiento = Carbon::parse($value->fechaVencimiento)->format('Y-m-d');
                        $shippingDate = Carbon::createFromFormat('Y-m-d', $fechaVencimiento);
                        $diasMoratorios = $shippingDate->diffInDays($currentDate);

                        if($fechaVencimiento <= $fechaActual){
                            $dias = $diasMoratorios;
                        }else{
                            $dias = '-' . $diasMoratorios;
                        }
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
                                    'totalCorriente' => 0,
                                    'total1-15' => 0,
                                    'total16-30' => 0,
                                    'total31-60' => 0,
                                    'total61-90' => 0,
                                    'total90+' => 0,
                                    'totalSuma' => 0
                                ];
                            } else {
                                $clientTotals[$actualClient][$money] = [
                                    'totalCorriente' => $clientTotals[$actualClient][$money]['totalCorriente'] + ($totalcorr ? $value->saldo : 0),
                                    'total1-15' => $clientTotals[$actualClient][$money]['total1-15'] + ($total1 ? $value->saldo : 0),
                                    'total16-30' => $clientTotals[$actualClient][$money]['total16-30'] + ($total16 ? $value->saldo : 0),
                                    'total31-60' => $clientTotals[$actualClient][$money]['total31-60'] + ($total31 ? $value->saldo : 0),
                                    'total61-90' => $clientTotals[$actualClient][$money]['total61-90'] + ($total61 ? $value->saldo : 0),
                                    'total90+' => $clientTotals[$actualClient][$money]['total90+'] + ($total90 ? $value->saldo : 0),
                                    'totalSuma' => $clientTotals[$actualClient][$money]['totalSuma'] + $value->saldo
                                ];
                            }
                            // dd($clientTotals[$money]);
                        @endphp
                        <tbody>
                            <tr class="m-top"><td colspan="7" align="left"><b>
                                <pre>{{$clave}}                     {{$actualClient}}</pre></b></td>
                            </tr>
                    @endif
                    @php
                        $clientTotals[$actualClient][$money]['totalCorriente'] += ($totalcorr ? $value->saldo : 0);
                        $clientTotals[$actualClient][$money]['total1-15'] += ($total1 ? $value->saldo : 0);
                        $clientTotals[$actualClient][$money]['total16-30'] += ($total16 ? $value->saldo : 0);
                        $clientTotals[$actualClient][$money]['total31-60'] += ($total31 ? $value->saldo : 0);
                        $clientTotals[$actualClient][$money]['total61-90'] += ($total61 ? $value->saldo : 0);
                        $clientTotals[$actualClient][$money]['total90+'] += ($total90 ? $value->saldo : 0);
                        $clientTotals[$actualClient][$money]['totalSuma'] += $value->saldo;
                        // dd($lastClient, $lastCurrency, $clientTotals);
                    @endphp
                    <tr>
                        <td></td>
                        @if (str_contains($value->movimiento,'Mensualidad'))
                        <td class="border-t2">{{$value->movimiento}}</td>
                        @else
                        <td class="border-t2">{{$value->movimiento}} {{$value->folioMov}}</td>
                        @endif
                        <td class="border-t2">{{$value->referencia}}</td>
                        <td class="border-t2">{{$fechaFinalE}}</td>
                        <td class="border-t2">{{$fechaFinalV}}</td>
                        <td class="border-t2">{{$value->diasMoratorios != null ? $value->diasMoratorios : $dias}}</td>
                        <td class="border-t2" align="center">{{$totalcorr ? $saldo : ''}}</td>
                        <td class="border-t2" align="center">{{$total1 ? $saldo : ''}}</td>
                        <td class="border-t2" align="center">{{$total16 ? $saldo : ''}}</td>
                        <td class="border-t2" align="center">{{$total31 ? $saldo : ''}}</td>
                        <td class="border-t2" align="center">{{$total61 ? $saldo : ''}}</td>
                        <td class="border-t2" align="center">{{$total90 ? $saldo : ''}}</td>
                    </tr>
                    </tbody>
                @endforeach
                {!! printClientTotals($clientTotals[$actualClient][$lastCurrency], $lastCurrency) !!}
            </table>
        </div>
    </body>
</html>

