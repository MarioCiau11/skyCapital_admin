<table>
    <thead>
        <tr>
            <th>Movimiento</th>
            <th>Folio</th>
            <th>Moneda</th>
            <th>Fecha de emisión</th>
            <th>Condición de pago</th>
            <th>Proyecto</th>
            <th>Módulo</th>
            <th>Cliente</th>
            <th>Importe total</th>
            <th>Días moratorios</th>
            <th>Estatus</th>
            <th>Última Actualización</th>
            <th>Fecha de Baja</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Cxc as $cxc)
        <tr>
            <td >{{$cxc->movimiento}} </td>
            <td >{{$cxc->folioMov}}</td>
            <td >{{$cxc->getMoneda != null ? $cxc->getMoneda->nombre : ''}}</td>
            <td >{{date("d/m/Y H:i:s", strtotime($cxc->fechaEmision))}}</td>
            <td >{{$cxc->getCondition != null ? $cxc->getCondition->nombreCondicion : ''}} </td>
            <td >{{$cxc->getProyecto != null ? $cxc->getProyecto->nombre : ''}} </td>
            <td >{{$cxc->getModulo != null ? $cxc->getModulo->clave : null}} - {{$cxc->getModulo != null ? $cxc->getModulo->descripcion : null}} </td>
            <td >{{$cxc->getCliente->razonSocial}} </td>
            <td >${{number_format($cxc->total, 2)}} </td>
            <td >{{$cxc->diasMoratorios}}</td>
            <td >{{$cxc->estatus}}</td>
            <td >{{$cxc->fechaCambio}}</td>
            <td >{{$cxc->fechaBaja}}</td>
        </tr>
        @endforeach
    </tbody>
</table>