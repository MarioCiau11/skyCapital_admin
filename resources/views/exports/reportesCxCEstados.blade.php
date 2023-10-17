@php
    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
    $fechaI = strftime("%d de %B de %Y", strtotime($fechaInicio));
    $fechaF = strftime("%d de %B de %Y", strtotime($fechaFinal));

    use Carbon\Carbon;
    setlocale(LC_TIME, 'es_ES');
    $mesesEnEspanol = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
@endphp
<table border="1">
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
            <th align="center" style="font-size: 13px;">ESTADO DE CUENTA</th>
        </tr>
        <tr>
            <td></td><td></td><td></td><td></td>
            @foreach ($estados as $key => $articulo)
                @php
                    if ($cliente != "Todos")
                        $cliente = $articulo->getCliente->razonSocial;
                    else
                        $cliente = "Todos";    
                @endphp
            @endforeach
            <td align="center"><b>{{strtoupper($cliente)}}</b></td><td></td>
        </tr>
        <tr></tr>
    </thead>
</table>
<table>
    <thead>
        <tr><th></th>
            <th align="center" style="border-bottom: 5px solid #000;"><b><i>Cliente</i></b></th>
            <th width="50" align="center" style="border-bottom: 5px solid #000;"><b><i>Movimiento</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000;"><b><i>Fecha emisión</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000;"><b><i>Cargos</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000;"><b><i>Abonos</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000;"><b><i>Saldo</i></b></th>
        </tr>
    </thead>
    @php
        $sorted = $estados != null ? $estados->sortBy('getCliente.idCliente') : [];
        $lastClient = null;
        $lastCurrency = null;
        $clientTotals = [];
        $filtradoArt = $sorted;

        if (empty($sorted)) {
                return '';
            }
        else {
            function printClientTotals($totals, $money) {
                return '<br>
                        <tr>
                            <td></td>
                            <td class="border-t3" colspan="3"></td>
                            <td class="border-t3"><b>$ '.number_format($totals['totalCargos'], 2).'</b></td>
                            <td class="border-t3"><b>$ '.number_format($totals['totalAbonos'], 2).'</b></td>
                            <td class="border-t3"><b>$ '.number_format($totals['totalSaldo'], 2). ' ' .ucfirst(strtolower($money)).'</b></td>
                            <td></td>
                        </tr>';
            }
        }
    @endphp
    @foreach ($filtradoArt as $key => $value)
        @php
            $actualClient = $value->getCliente != null ? $value->getCliente->razonSocial : '';
            $clave = $value->getCliente != null ? $value->getCliente->clave : '';
            $money = $value->moneda != null ? $value->moneda : '';
        @endphp
        @if ($lastClient !== $actualClient || $lastCurrency !== $money)
            @if (!is_null($lastClient) && !is_null($lastCurrency))
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
                <tr><td></td>
                    <td align="center">
                        <b><pre>{{$clave}}</pre></b>
                    </td>
                    <td align="left">
                        <b><pre>{{$actualClient}}</pre></b>
                    </td>
                </tr>
                <tr></tr>
        @endif
        @php
            $fechaCarbon = Carbon::parse($value->fechaEmision);
            
            $diaFormat = $fechaCarbon->format('d');
            $mesFormat = $mesesEnEspanol[$fechaCarbon->month - 1];

            $fechaFinal = $diaFormat . '/' . $mesFormat . '/' . $fechaCarbon->year;
            $clientTotals[$actualClient][$money]['totalCargos'] += $value->cargo;
            $clientTotals[$actualClient][$money]['totalAbonos'] += $value->abono;
            $clientTotals[$actualClient][$money]['totalSaldo'] += ($value->cargo - $value->abono);

            // dd($totalCargos, $totalAbonos, $totalSaldo);
        @endphp
        <tbody>
            <tr>
                <td></td><td></td>
                <td style="border-top: 3px solid #000">{{$value->movimiento}} {{strpos($value->movimiento, 'Mensualidad') !== false ? '' : $value->folio}}</td>
                <td style="border-top: 3px solid #000">{{$fechaFinal}}</td>
                <td style="border-top: 3px solid #000">{{$value->cargo != null ? '$'. ' ' .number_format($value->cargo,2) : ''}}</td>
                <td style="border-top: 3px solid #000">{{$value->abono != null ? '$'. ' ' .number_format($value->abono,2) : ''}}</td>
                <td style="border-top: 3px solid #000">$ {{number_format($value->cargo-$value->abono,2)}}</td>
            </tr>
            @if ($value->aplica != null)
                <tr>
                    <td></td><td></td>
                    <td style="border-top: 3px solid #000"><b>{{$value->aplica}} {{$value->idAplica}}</b></td><td style="border-top: 3px solid #000"></td>
                    <td style="border-top: 3px solid #000"><b>$ {{number_format($value->cargo,2)}}</b></td>
                    <td style="border-top: 3px solid #000"><b>$ {{number_format($value->abono,2)}}</b></td>
                    <td style="border-top: 3px solid #000"><b>$ {{number_format($value->cargo-$value->abono,2)}}</b></td>
                </tr>
                <tr>
                    <td></td><td></td>
                    <td><b>{{$value->movimiento}} {{strpos($value->movimiento, 'Mensualidad') !== false ? '' : $value->folio}}</b></td>
                </tr>
            @endif
        </tbody>
    @endforeach
    @if (!is_null($lastClient) && !is_null($lastCurrency))
        {!! printClientTotals($clientTotals[$actualClient][$lastCurrency], $lastCurrency) !!}
    @endif
</table>