<table>
    <thead>
        <tr>
            <th>Clave</th>
            <th>Banco</th>
            <th>No. de Cuenta</th>
            <th>Tipo de Cuenta</th>
            <th>Empresa</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($CuentasDinero as $cuentas)
        <tr>
            <td >{{$cuentas->clave}}</td>
            <td >{{$cuentas->idInstitucionf != null ?$cuentas->getInstituciones->nombre : null }}</td>
            <td >{{$cuentas->noCuenta}} </td>
            <td >{{$cuentas->tipoCuenta}} </td>
            <td >{{$cuentas->idEmpresa != null ? $cuentas->getEmpresas->nombreEmpresa : null}} </td>
        </tr>
        @endforeach
    </tbody>
</table>