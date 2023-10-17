<table>
    <thead>
        <tr>
            <th>Movimiento</th>
            <th>Folio</th>
            <th>Moneda</th>
            <th>Beneficiario</th>
            <th>Forma de pago</th>
            <th>Cuenta de dinero</th>
            <th>Fecha de emisión</th>
            <th>Importe total</th>
            <th>Saldo de cuenta</th>
            <th>Estatus</th>
            <th>Última Actualización</th>
            <th>Fecha de Baja</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Tesoreria as $tesoreria)
        <tr>
            <td >{{$tesoreria->movimiento}} </td>
            <td >{{$tesoreria->folioMov}}</td>
            <td >{{$tesoreria->getMoneda != null ? $tesoreria->getMoneda->nombre : ''}}</td>
            <td >{{$tesoreria->beneficiario}} </td>
            <td >{{$tesoreria->formaPago}} </td>
            <td >{{$tesoreria->cuentaDinero}}</td>
            <td >{{date("d/m/Y H:i:s", strtotime($tesoreria->fechaEmision))}}</td>
            <td >${{number_format($tesoreria->importeTotal, 2)}} </td>
            <td >${{number_format($tesoreria->saldoCuenta, 2)}} </td>
            <td >{{$tesoreria->estatus}}</td>
            <td >{{$tesoreria->fechaCambio}}</td>
            <td >{{$tesoreria->fechaBaja}}</td>
        </tr>
        @endforeach
    </tbody>
</table>