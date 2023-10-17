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
            <th></th><th></th><th></th>
            <th>Nombre de la empresa: &nbsp; <br> {{session('company')->nombreEmpresa}} </th>
            <th>Fecha emisión del reporte: {{date('d/m/Y')}}</th>
        </tr><tr></tr>
        <tr>
            <th></th><th></th><th></th>
            <th>RFC de la empresa: &nbsp; {{session('company')->RFC}} </th>
        </tr>
        <tr></tr>
        <tr>
            <th></th><th></th><th></th>
            <th align="center" style="font-size: 13px;"><b>INGRESOS POR PROYECTO</b></th>
        </tr>
        <tr></tr>
    </thead>
</table>
<table>
    <thead>
        <tr>
            <th width="28" align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>FECHA</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>FOLIO SISTEMA</i></b></th>
            <th width="28" align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>FOLIO EXTERNO</i></b></th>
            <th width="45" align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>CLIENTE</i></b></th>
            <th width="20" align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>INGRESO</i></b></th>
            <th align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>PROYECTO</i></b></th>
            <th width="20" align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>BANCO</i></b></th>
            <th width="25" align="center" style="border-bottom: 5px solid #000; border-top: 5px solid #000;"><b><i>MÓDULO</i></b></th>
        </tr>
    </thead>
    @php
        $sorted = $ingresos != null ? $ingresos->sortBy(function ($item) {
            if ($item->tipo === 'Anticipo') {
                return 0;
            } elseif ($item->tipo === 'Venta') {
                return 1;
            } elseif ($item->tipo === 'Renta') {
                return 2;
            }
        })->values() : null;
        $lastType = null;
        $lastCurrency = null;
        $typeTotals = [];
        $currencyTotals = [];
        $filtradoIngresos = $sorted;

        if (empty($sorted)) {
                return '';
            }
        else {
            function printTypeTotals($totals, $money, $actualType) {
                $typeLabel = '';
                if ($actualType === 'Anticipo') {
                    $typeLabel = 'TOTAL DE ENGANCHE';
                } elseif ($actualType === 'Venta') {
                    $typeLabel = 'TOTAL INGRESOS VENTAS';
                } elseif ($actualType === 'Renta') {
                    $typeLabel = 'TOTAL INGRESOS RENTAS';
                }
                return '
                    <tr><td colspan="3"></td>
                        <td align="right" style="text-transform: uppercase;"><b>'.$typeLabel.' '.$money.':</b></td>
                        <td class="border-t2" align="right"><b><span style="float: left; margin-right: 5px;">$</span>'.number_format($totals['totalIngreso'], 2).'</b></td>
                        <td colspan="3"></td>
                    </tr>';
            }
        }
    @endphp
    @foreach ($filtradoIngresos as $key => $value)
        @php
            $actualType = $value->tipo;
            $money = $value->moneda;
                        
            if (!isset($currencyTotals[$money])) {
                $currencyTotals[$money] = 0;
            }
        @endphp
        @if ($lastType !== $actualType || $lastCurrency !== $money)
            @if (!is_null($lastType))
                {!! printTypeTotals($typeTotals[$lastType][$lastCurrency], $lastCurrency, $lastType) !!}
            @endif
            @php
                $lastType = $actualType;
                $lastCurrency = $money;

                if (!isset($typeTotals[$actualType][$money])) {
                    $typeTotals[$actualType][$money] = [
                        'totalIngreso' => 0,
                    ];
                } else {
                    $typeTotals[$actualType][$money] = [
                        'totalIngreso' => $typeTotals[$actualType][$money]['totalIngreso'] + $value->ingreso,
                    ];
                }
                // dd($typeTotals[$money]);
            @endphp
            <tbody>
                <tr></tr>
                <tr>
                    <td align="left">
                        <b><pre>@if ($actualType === 'Anticipo')ENGANCHE @elseif ($actualType === 'Venta')MENSUALIDAD @elseif ($actualType === 'Renta')RENTA @endif</pre></b>
                    </td>
                </tr>
        @endif
        @php
            $typeTotals[$actualType][$money]['totalIngreso'] += $value->ingreso;
            $currencyTotals[$money] += $value->ingreso;
            // dd($lastType, $lastCurrency, $typeTotals);
        @endphp
        <tbody>
            <tr>
                <td align="center">{{date('d/m/Y', strtotime($value->fecha))}}</td>
                <td align="center">{{$value->folio}}</td>
                <td align="left">{{$value->folioExterno}}</td>
                <td >{{$value->cliente}}</td>
                <td align="right"><span style="float: left; margin-right: 5px;">$</span>{{ number_format($value->ingreso, 2) }}</td>
                <td align="center">{{$value->proyecto}}</td>
                <td align="center">{{$value->Banco}}</td>
                <td align="center">{{$value->modulo}}</td>
            </tr>
        </tbody>
    @endforeach
    @if (!is_null($lastType))
        {!! printTypeTotals($typeTotals[$lastType][$lastCurrency], $lastCurrency, $lastType) !!}
    @endif
    <tr><td colspan="8"></td></tr>
    @foreach ($currencyTotals as $currency => $total)
        <tr><td colspan="3"></td>
            <td align="center" class="border-b border-t"><b>TOTAL INGRESOS EN {{ $currency }}</b></td>
            <td align="right"><b><span style="float: left; margin-right: 5px;">$</span>{{ number_format($total, 2) }}</b></td>
            <td colspan="3"></td>
        </tr>
    @endforeach
    <tr><td colspan="8"></td></tr><tr><td colspan="8"></td></tr><tr><td colspan="8"></td></tr><tr><td colspan="8"></td></tr><tr><td colspan="8"></td></tr><tr><td colspan="8"></td></tr>
    <tbody>
        <tr><td colspan="2"></td>
            <td align="center" style="border-top: 5px solid #000;">Entrega:<br>
                Lilia Monforte</td><td></td>
            <td align="center" colspan="2" style="border-top: 5px solid #000;">Recibe:<br>
                C.P. Alvaro Rosado</td>
            <td colspan="2"></td>
        </tr>
    </tbody>
</table>