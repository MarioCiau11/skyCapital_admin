<tr>
    <td >{{$estado->getCliente != null ? $estado->getCliente->razonSocial : ''}}</td>
    <td >{{$estado->getCliente != null ? $estado->getCliente->getCategoria == null ? '' : $estado->getCliente->getCategoria->nombre : ''}}</td>
    <td >{{$estado->getCliente != null ? $estado->getCliente->getGrupo == null ? '' : $estado->getCliente->getGrupo->nombre : ''}}</td>
    <td >{{$estado->movimiento}}</td>
    <td >{{$estado->folio != null ? $estado->folio : $estado->folioMov}}</td>
    <td >{{$estado->getMoneda != null ? $estado->getMoneda->clave : $estado->moneda}}</td>
    <td class="currency">{{$estado->tipoCambio}}</td>
    <td >{{$estado->fechaEmision}}</td>
    <td class="currency">{{$estado->cargo != null ? $estado->cargo : $estado->total}}</td>
    <td class="currency">{{$estado->abono}}</td>
    <td class="currency">{{$estado->cargo != null ? $estado->cargo - $estado->abono : $estado->total - $estado->abono}}</td>
    <td >{{$estado->aplica}}</td>
    <td >{{$estado->getSucursal != null ? $estado->getSucursal->nombre: ''}}</td>
    <td >{{$estado->estatus}}</td>
</tr>