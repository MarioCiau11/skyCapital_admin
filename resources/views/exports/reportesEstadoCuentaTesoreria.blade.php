<table style="border-collapse: collapse">
    <thead>
        <tr></tr>
        <tr></tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th colspan="2">Fecha emisión del reporte: {{ date('d/m/Y') }}</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th> <b>{{ session('company')->nombreEmpresa }}</b></th>
            <th></th>
            <th></th>
            <th> Moneda: {{ $moneda }} </th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th><b>{{ session('company')->RFC }}</b></th>
        </tr>
        <tr>
            <th></th>
            @if ($reporte == 'CONCENTRADO')
                <th colspan="5">KARDEX - ESTADO DE CUENTA POR CUENTA DE DINERO - CONCENTRADO</th>
            @else
                <th colspan="5">KARDEX - ESTADO DE CUENTA POR CUENTA DE DINERO - DESGLOSADO</th>
            @endif
        </tr>
        <tr></tr>
        @if ($reporte == 'CONCENTRADO')
            <tr>
                <th><strong>Cuenta</strong></th>
                <th><strong>Descripción</strong></th>
                <th><strong>Número de Cuenta</strong></th>
                <th><strong>Saldo Inicial</strong></th>
                <th><strong>Cargos</strong></th>
                <th><strong>Abonos</strong></th>
                <th><strong>Saldo Final</strong></th>
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
    <tbody style="border: 1px solid black">
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
                        <td align="center" colspan="7"> <strong>Moneda: {{ $moneda }}</strong></td>
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
