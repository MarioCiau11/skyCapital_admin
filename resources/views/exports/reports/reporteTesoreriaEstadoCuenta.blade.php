<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/reportes.css') }}">
    @if ($reporte == 'CONCENTRADO')
        <title>KARDEX - ESTADO DE CUENTA POR CUENTA DE DINERO - CONCENTRADO</title>
    @else
        <title>KARDEX - ESTADO DE CUENTA POR CUENTA DE DINERO - DESGLOSADO</title>
    @endif
</head>
@php
    setlocale(LC_ALL, 'es_ES@euro', 'es_ES', 'esp');
    $fecha = date('Y-m-d H:i:s');
    $fechaUnix = strtotime($fecha);
    $fechaFormateada = strftime('%e de %B de %Y', $fechaUnix);
@endphp

<body>
    <div class="container">
        <br>
        <table class="cabecera table-cabecera">
            <tr>
                <td class="logo" align="left">
                    <img src="{{ $imgLogo }}" alt="">
                </td>
                <td align="center">
                    <strong style="font-size: 15px">{{ Str::upper(session('company')->nombreEmpresa) }} </strong>
                    <br>
                    {{ session('company')->RFC }}
                </td>
                <td align="left">
                    <strong>Fecha de Emisión: </strong>{{ $fechaFormateada }}
                    <br>
                    Moneda: {{ $moneda }}
                </td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0" style="width: 100%">
            <tr>
                <td></td>
                @if ($reporte == 'CONCENTRADO')
                    <td class="titulo">ESTADO DE CUENTA POR CUENTA DE DINERO - CONCENTRADO</td>
                @else
                    <td class="titulo">ESTADO DE CUENTA POR CUENTA DE DINERO - DESGLOSADO</td>
                @endif
                <td></td>
            </tr>
        </table>
        <br>
        <br>
        <table class="table-borderedT">
            <thead>
                @if ($reporte == 'CONCENTRADO')
                    <tr>
                        <th><strong>CUENTA</strong></th>
                        <th><strong>DESCRIPCIÓN</strong></th>
                        <th><strong>NÚMERO CUENTA</strong></th>
                        <th><strong>SALDO INICIAL</strong></th>
                        <th><strong>CARGOS</strong></th>
                        <th><strong>ABONOS</strong></th>
                        <th><strong>SALDO FINAL</strong></th>
                    </tr>
                @else
                    <tr>
                        <th><strong>Cuenta</strong></th>
                        <th><strong>Movimiento</strong></th>
                        <th><strong>Referencia</strong></th>
                        <th><strong>Beneficiario</strong></th>
                        <th><strong>Cargos</strong></th>
                        <th><strong>Abonos</strong></th>
                        <th><strong>Saldo Final</strong></th>
                    </tr>
                @endif

            </thead>
            <tbody>

                @if ($reporte == 'CONCENTRADO')
                    @if ($moneda == 'Todos')
                        @php
                            $arrayCuentasMoneda = [];
                            foreach ($reporte_filtro as $key => $cuenta) {
                                if (array_key_exists($cuenta->moneda, $arrayCuentasMoneda)) {
                                    array_push($arrayCuentasMoneda[$cuenta->moneda], $cuenta);
                                } else {
                                    $arrayCuentasMoneda[$cuenta->moneda] = [];
                                    array_push($arrayCuentasMoneda[$cuenta->moneda], $cuenta);
                                }
                                // $arrayCuentasMoneda[$cuenta->moenda] = $cuenta->cuenta;
                            }
                            // dd($arrayCuentasMoneda);
                        @endphp
                        @foreach ($arrayCuentasMoneda as $key => $moneda)
                            <tr>
                                <td colspan="7" align="center"><strong>Moneda: {{ $key }}</strong></td>
                            </tr>
                            @foreach ($moneda as $cuentas)
                                <tr>
                                    <td>{{ $cuentas->cuenta }}</td>
                                    <td>{{ $cuentas->descripcion }}</td>
                                    <td>{{ $cuentas->numeroCuenta }}</td>
                                    <td>${{ number_format($cuentas->saldoInicial, 2) }}</td>
                                    <td>${{ number_format($cuentas->cargos, 2) }}</td>
                                    <td>${{ number_format($cuentas->abonos, 2) }}</td>
                                    <td>${{ number_format($cuentas->saldoFinal, 2) }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @else
                        @foreach ($reporte_filtro as $tesoreria)
                            <tr>
                                <td>{{ $tesoreria->cuenta }}</td>
                                <td>{{ $tesoreria->descripcion }}</td>
                                <td>{{ $tesoreria->numeroCuenta }}</td>
                                <td>${{ number_format($tesoreria->saldoInicial, 2) }}</td>
                                <td>${{ number_format($tesoreria->cargos, 2) }}</td>
                                <td>${{ number_format($tesoreria->abonos, 2) }}</td>
                                <td>${{ number_format($tesoreria->saldoFinal, 2) }}</td>
                            </tr>
                        @endforeach
                    @endif
                @else
                    @if ($moneda == 'Todos')
                        @php
                            $saldoFinal = [];
                        @endphp
                        @foreach ($reporte_filtro as $moneda => $fechaArray)
                            {{-- iteración de fechas --}}
                            <tr>
                                <td align="center" colspan="7"> <strong>Moneda: {{$moneda}}</strong></td>
                            </tr>
                            @foreach ($fechaArray as $fecha => $cuentasArray)
                                {{-- iteración de cuentas --}}
                                @foreach ($cuentasArray as $cuenta => $registrosArray)
                                    @php
                                        $totalCargos = 0;
                                        $totalAbonos = 0;
                                        $montoInicial = 0;
                                        $saldosFinales = 0;
                                    @endphp
                                    {{-- iteración de registros --}}
                                    @foreach ($registrosArray as $key => $registro)
                                        {{-- @dd($registro) --}}
                                        @php
                                            if ($registro['cargo'] != null) {
                                                $totalCargos += (float) $registro['cargo'];
                                            }
                                            if ($registro['abono'] != null) {
                                                $totalAbonos += (float) $registro['abono'];
                                            }
                                            if ($key == 0) {
                                                $montoInicial = array_key_exists($registro['cuenta'], $saldoFinal) ? $saldoFinal[$registro['cuenta']] : $montoInicial;
                                                $saldosFinales = $montoInicial + $totalCargos - $totalAbonos;
                                            } else {
                                                $saldosFinales = $montoInicial + $totalCargos - $totalAbonos;
                                            }
                                            
                                            if (!array_key_exists($registro['cuenta'], $saldoFinal)) {
                                                //Creamos la key en el arreglo q contendra los saldos finales
                                                $saldoFinal[$registro['cuenta']] = $saldosFinales;
                                            } else {
                                                //Incrementamos el saldo final de la misma key
                                                $saldoFinal[$registro['cuenta']] = $saldosFinales;
                                            }
                                            // dd($saldoFinal);
                                        @endphp
                                        <tr>
                                            <td>{{ $registro['cuenta'] }}</td>
                                            <td>{{ $registro['movimiento'] }}</td>
                                            <td>{{ $registro['referencia'] }}</td>
                                            <td>{{ $registro['beneficiario'] }}</td>
                                            <td>$ {{ number_format($registro['cargo'], 2) }}</td>
                                            <td>$ {{ number_format($registro['abono'], 2) }}</td>
                                            <td>$ {{ number_format($saldosFinales, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><strong>{{ $fecha }}</strong></td>
                                        <td></td>
                                        <td></td>
                                        <td align="right"> <strong>Totales</strong> </td>
                                        <td><strong>$ {{ number_format($totalCargos, 2) }}</strong></td>
                                        <td><strong>$ {{ number_format($totalAbonos, 2) }}</strong></td>
                                        <td><strong>$ {{ number_format($saldosFinales, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                                {{-- @dd($saldoFinal) --}}
                            @endforeach
                        @endforeach
                    @else
                        @php
                            $saldoFinal = [];
                        @endphp
                        @foreach ($reporte_filtro as $moneda => $fechaArray)
                            {{-- iteración de fechas --}}
                            @foreach ($fechaArray as $fecha => $cuentasArray)
                                {{-- iteración de cuentas --}}
                                @foreach ($cuentasArray as $cuenta => $registrosArray)
                                    @php
                                        $totalCargos = 0;
                                        $totalAbonos = 0;
                                        $montoInicial = 0;
                                        $saldosFinales = 0;
                                    @endphp
                                    {{-- iteración de registros --}}
                                    @foreach ($registrosArray as $key => $registro)
                                        {{-- @dd($registro) --}}
                                        @php
                                            if ($registro['cargo'] != null) {
                                                $totalCargos += (float) $registro['cargo'];
                                            }
                                            if ($registro['abono'] != null) {
                                                $totalAbonos += (float) $registro['abono'];
                                            }
                                            if ($key == 0) {
                                                $montoInicial = array_key_exists($registro['cuenta'], $saldoFinal) ? $saldoFinal[$registro['cuenta']] : $montoInicial;
                                                $saldosFinales = $montoInicial + $totalCargos - $totalAbonos;
                                            } else {
                                                $saldosFinales = $montoInicial + $totalCargos - $totalAbonos;
                                            }
                                            
                                            if (!array_key_exists($registro['cuenta'], $saldoFinal)) {
                                                //Creamos la key en el arreglo q contendra los saldos finales
                                                $saldoFinal[$registro['cuenta']] = $saldosFinales;
                                            } else {
                                                //Incrementamos el saldo final de la misma key
                                                $saldoFinal[$registro['cuenta']] = $saldosFinales;
                                            }
                                            // dd($saldoFinal);
                                        @endphp
                                        <tr>
                                            <td>{{ $registro['cuenta'] }}</td>
                                            <td>{{ $registro['movimiento'] }}</td>
                                            <td>{{ $registro['referencia'] }}</td>
                                            <td>{{ $registro['beneficiario'] }}</td>
                                            <td>$ {{ number_format($registro['cargo'], 2) }}</td>
                                            <td>$ {{ number_format($registro['abono'], 2) }}</td>
                                            <td>$ {{ number_format($saldosFinales, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><strong>{{ $fecha }}</strong></td>
                                        <td></td>
                                        <td></td>
                                        <td align="right"> <strong>Totales</strong> </td>
                                        <td><strong>$ {{ number_format($totalCargos, 2) }}</strong></td>
                                        <td><strong>$ {{ number_format($totalAbonos, 2) }}</strong></td>
                                        <td><strong>$ {{ number_format($saldosFinales, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                                {{-- @dd($saldoFinal) --}}
                            @endforeach
                        @endforeach
                    @endif
                @endif
            </tbody>
        </table>
    </div>
</body>

</html>
