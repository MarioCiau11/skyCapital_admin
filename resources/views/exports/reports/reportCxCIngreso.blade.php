<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/reportes.css')}}">
    <title>Ingresos por proyecto</title>
</head>
@php
    use Carbon\Carbon;
    setlocale(LC_TIME, 'es_ES');
    $mesesEnEspanol = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];    
    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
    $fechaI = strftime("%d de %B de %Y", strtotime($fechaInicio));
    $fechaF = strftime("%d de %B de %Y", strtotime($fechaFinal));
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
                    <td>Fecha de impresión: {{(date('d/m/Y'))}} <br>
                        @php
                            if ($fechaInicio != '' && $fechaFinal != '') {
                                echo "Periodo consultado del $fechaI al $fechaF <br>";
                            }
                            else {
                                echo "Periodo consultado: $fecha <br>";
                            }
                        @endphp
                        Moneda: {{$moneda}}
                    </td>
                </tr>
                <tr>
                    <td></td><td></td>
                    <td align="center"><b>INGRESOS POR PROYECTO</b></td><td></td>
                </tr>
                <tr>
                </tr><br>
            </table>
            <table class="table-bordered-2 empty bg-top">
                <thead>
                    <tr>
                        <th class="bg-cells-blue">Fecha</th>
                        <th width="100" class="bg-cells-blue">Folio sistema</th>
                        <th width="100" class="bg-cells-blue">Folio externo</th>
                        <th width="300" class="bg-cells-blue">Cliente</th>
                        <th width="100" class="bg-cells-blue">Ingreso</th>
                        <th width="300" class="bg-cells-blue">Proyecto</th>
                        <th class="bg-cells-blue">Banco</th>
                        <th class="bg-cells-blue">Módulo</th>
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
                    // dd($sorted, $ingresos);
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
                            <tr style="color: red;"><td colspan="7" align="left"><b><pre>@if ($actualType === 'Anticipo')ENGANCHE @elseif ($actualType === 'Venta')MENSUALIDAD @elseif ($actualType === 'Renta')RENTA @endif</pre>
                            </b></td><td></td></tr>
                    @endif
                    @php
                        $typeTotals[$actualType][$money]['totalIngreso'] += $value->ingreso;
                        $currencyTotals[$money] += $value->ingreso;
                        // dd($lastType, $lastCurrency, $typeTotals);
                    @endphp
                    <tr>
                        <td >{{date('d/m/Y', strtotime($value->fecha))}}</td>
                        <td >{{$value->folio}}</td>
                        <td align="left">{{$value->folioExterno}}</td>
                        <td align="left">{{$value->cliente}}</td>
                        <td align="right"><span style="float: left; margin-right: 5px;">$</span>{{ number_format($value->ingreso, 2) }}</td>
                        <td >{{$value->proyecto}}</td>
                        <td >{{$value->Banco}}</td>
                        <td >{{$value->modulo}}</td>
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
                <br><br><br><br><br><br>
                <tbody>
                    <tr><td colspan="3"></td>
                        <td class="border-t">Entrega:<br>
                            Lilia Monforte</td><td></td>
                        <td colspan="1" class="border-t">Recibe:<br>
                            C.P. Alvaro Rosado</td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>

