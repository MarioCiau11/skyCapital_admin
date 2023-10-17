<tr>
    <td >{{$saldo->getCliente != null ? $saldo->getCliente->razonSocial : ''}}</td>
    <td >{{$saldo->getCliente != null ? $saldo->getCliente->getCategoria == null ? '' : $saldo->getCliente->getCategoria->nombre : ''}}</td>
    <td >{{$saldo->getCliente != null ? $saldo->getCliente->getGrupo == null ? '' : $saldo->getCliente->getGrupo->nombre : ''}}</td>
    <td >{{$saldo->movimiento}}</td>
    <td >{{$saldo->getMoneda != null ? $saldo->getMoneda->clave : ''}}</td>
    <td >${{$saldo->getMoneda != null ? number_format($saldo->getMoneda->tipoCambio, 2) : ''}}</td>
    <td >{{$saldo->getSucursal != null ? $saldo->getSucursal->nombre: ''}}</td>
    <td >{{$saldo->fechaEmision}}</td>
    <td >{{$saldo->fechaVencimiento}}</td>
    <td >{{$saldo->estatus}}</td>
</tr>