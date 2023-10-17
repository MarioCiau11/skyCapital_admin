@php
    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
    $fechaI = strftime("%d de %B de %Y", strtotime($fechaInicio));
    $fechaF = strftime("%d de %B de %Y", strtotime($fechaFinal));

    use Carbon\Carbon;
    setlocale(LC_TIME, 'es_ES');
    $mesesEnEspanol = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
@endphp
<table>
    <thead>
        <tr></tr>
        <tr>
            <th></th><th></th><th></th><th></th>
            <th>Nombre de la empresa: &nbsp; <br> {{session('company')->nombreEmpresa}} </th>
            <th>Fecha emisión del reporte: {{date('d/m/Y')}}</th>
        </tr><tr></tr>
        <tr>
            <th></th><th></th><th></th><th></th>
            <th>RFC de la empresa: &nbsp; {{session('company')->RFC}} </th>
        </tr>
        <tr></tr>
        <tr>
            <th></th><th></th><th></th><th></th>
            <th align="center" style="font-size: 13px;"><b>ANTIGUEDAD DE SALDOS</b></th>
        </tr>
        <tr></tr>
    </thead>
</table>
<table>
    <thead>
        <tr>
            <th align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>Cliente</i></b></th>
            <th width="40" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>Movimiento</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>Referencia</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>Fecha de emisión</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>Fecha de vencimiento</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>Días moratorios</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>Al corriente</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>De 1 a 15</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>De 16 a 30</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>De 31 a 60</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>De 61 a 90</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>Más de 90 días</i></b></th>
        </tr>
    </thead>
    @php
        $sorted = $saldos != null ? $saldos->sortBy('getCliente.idCliente') : [];
        $lastClient = null;
        $lastCurrency = null;
        $clientTotals = [];
        $filtradoSaldos = $sorted;

        if (empty($sorted)) {
                return '';
            }
        else {
            function printClientTotals($totals, $money) {
                
                return '
                    <tr><td></td>
                        <td style="border-top: 3px solid #000"><b>Total:</b></td>
                        <td style="border-top: 3px solid #000"><b>$ '.number_format($totals['totalSuma'], 2).' '.ucfirst(strtolower($money)).'</b></td>
                        <td style="border-top: 3px solid #000" colspan="3"></td>
                        <td align="center" style="border-top: 3px solid #000"><b>$ '.number_format($totals['totalCorriente'], 2).'</b></td>
                        <td align="center" style="border-top: 3px solid #000"><b>$ '.number_format($totals['total1-15'], 2).'</b></td>
                        <td align="center" style="border-top: 3px solid #000"><b>$ '.number_format($totals['total16-30'], 2).'</b></td>
                        <td align="center" style="border-top: 3px solid #000"><b>$ '.number_format($totals['total31-60'], 2).'</b></td>
                        <td align="center" style="border-top: 3px solid #000"><b>$ '.number_format($totals['total61-90'], 2).'</b></td>
                        <td align="center" style="border-top: 3px solid #000"><b>$ '.number_format($totals['total90+'], 2).'</b></td>
                    </tr><tr></tr>';
            }
        }
    @endphp
    @foreach ($filtradoSaldos as $key => $value)
        @php
            $actualClient = $value->getCliente != null ? $value->getCliente->razonSocial : '';
            $clave = $value->getCliente != null ? $value->getCliente->clave: '';
            $money = $value->getMoneda != null ? $value->getMoneda->clave : null;	
        @endphp
        @if ($lastClient !== $actualClient || $lastCurrency !== $money)
            @if (!is_null($lastClient) && !is_null($lastCurrency))
                {!! printClientTotals($clientTotals[$lastCurrency], $lastCurrency) !!}
            @endif
            @php
                $lastClient = $actualClient;
                $lastCurrency = $money;
                if (!isset($clientTotals[$money])) {
                    $clientTotals[$money] = [
                        'totalCorriente' => 0,
                        'total1-15' => 0,
                        'total16-30' => 0,
                        'total31-60' => 0,
                        'total61-90' => 0,
                        'total90+' => 0,
                        'totalSuma' => 0
                    ];
                } else {
                    $clientTotals[$money] = [
                        'totalCorriente' => $clientTotals[$money]['totalCorriente'],
                        'total1-15' => $clientTotals[$money]['total1-15'],
                        'total16-30' => $clientTotals[$money]['total16-30'],
                        'total31-60' => $clientTotals[$money]['total31-60'],
                        'total61-90' => $clientTotals[$money]['total61-90'],
                        'total90+' => $clientTotals[$money]['total90+'],
                        'totalSuma' => $clientTotals[$money]['totalSuma']
                    ];
                }
            @endphp
            <tbody>
                <tr></tr>
                <tr>
                    <td align="center">
                        <b><pre>{{$clave}}</pre></b>
                    </td>
                    <td align="left">
                        <b><pre>{{$actualClient}}</pre></b>
                    </td>
                </tr>
        @endif
        @php
            $fechaE = Carbon::parse($value->fechaEmision);
            $fechaV = Carbon::parse($value->fechaVencimiento);
            
            $diaE = $fechaE->format('d');
            $diaV = $fechaV->format('d');

            $mesE = $mesesEnEspanol[$fechaE->month - 1];
            $mesV = $mesesEnEspanol[$fechaV->month - 1];

            $fechaFinalE = $diaE . '/' . $mesE . '/' . $fechaE->year;
            $fechaFinalV = $diaV . '/' . $mesV . '/' . $fechaV->year;

            $saldo = $value->saldo != null ? '$'. ' ' .number_format($value->saldo,2) : '';
            $totalcorr = Carbon::today() <= $fechaV;
            $total1 = Carbon::today() > $fechaV && Carbon::today() <= $fechaV->addDays(15);
            $total16 = Carbon::today() > $fechaV->addDays(15) && Carbon::today() <= $fechaV->addDays(30);
            $total31 = Carbon::today() > $fechaV->addDays(30) && Carbon::today() <= $fechaV->addDays(60);
            $total61 = Carbon::today() > $fechaV->addDays(60) && Carbon::today() <= $fechaV->addDays(90);
            $total90 = Carbon::today() > $fechaV->addDays(90);

            $clientTotals[$money]['totalCorriente'] += ($totalcorr) ? $value->saldo : 0;
            $clientTotals[$money]['total1-15'] += ($total1) ? $value->saldo : 0;
            $clientTotals[$money]['total16-30'] += ($total16) ? $value->saldo : 0;
            $clientTotals[$money]['total31-60'] += ($total31) ? $value->saldo : 0;
            $clientTotals[$money]['total61-90'] += ($total61) ? $value->saldo : 0;
            $clientTotals[$money]['total90+'] += ($total90) ? $value->saldo : 0;
            $clientTotals[$money]['totalSuma'] =  $clientTotals[$money]['totalCorriente'] 
                                                + $clientTotals[$money]['total1-15'] 
                                                + $clientTotals[$money]['total16-30'] 
                                                + $clientTotals[$money]['total31-60'] 
                                                + $clientTotals[$money]['total61-90'] 
                                                + $clientTotals[$money]['total90+'];

        @endphp
        <tbody>
            <tr>
                <td ></td>
                @if (str_contains($value->movimiento,'Mensualidad'))
                <td style="border-top: 3px solid #000">{{$value->movimiento}}</td>
                @else
                <td style="border-top: 3px solid #000">{{$value->movimiento}} {{$value->folioMov}}</td>
                @endif
                {{-- <td style="border-top: 3px solid #000">{{$value->movimiento}} {{$value->folioMov}}</td> --}}
                <td style="border-top: 3px solid #000">{{$value->observaciones}}</td>
                <td align="center" style="border-top: 3px solid #000">{{$fechaFinalE}}</td>
                <td align="center" style="border-top: 3px solid #000">{{$fechaFinalV}}</td>
                <td align="center" style="border-top: 3px solid #000">{{$value->diasMoratorios}}</td>
                <td align="center" style="border-top: 3px solid #000">@if ($totalcorr) {{$saldo}} @endif</td>
                <td align="center" style="border-top: 3px solid #000">@if ($total1) {{$saldo}} @endif</td>
                <td align="center" style="border-top: 3px solid #000">@if ($total16) {{$saldo}} @endif</td>
                <td align="center" style="border-top: 3px solid #000">@if ($total31) {{$saldo}} @endif</td>
                <td align="center" style="border-top: 3px solid #000">@if ($total61) {{$saldo}} @endif</td>
                <td align="center" style="border-top: 3px solid #000">@if ($total90) {{$saldo}} @endif</td>
            </tr>
        </tbody>
    @endforeach
    @if (!is_null($lastClient) && !is_null($lastCurrency))
        {!! printClientTotals($clientTotals[$lastCurrency], $lastCurrency) !!}
    @endif
</table>