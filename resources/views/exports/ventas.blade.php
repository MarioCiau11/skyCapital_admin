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
            <th>Estatus</th>
            <th>Fecha de Alta</th>
            <th>Última Actualización</th>
            <th>Fecha de Baja</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Ventas as $venta)
        <tr>
            <td >{{$venta->movimiento}} </td>
            <td >{{$venta->folioMov}}</td>
            <td >{{$venta->getMoneda != null ? $venta->getMoneda->nombre : ''}}</td>
            <td >{{date("d/m/Y H:i:s", strtotime($venta->fechaEmision))}}</td>
            <td >{{$venta->getCondition != null ? $venta->getCondition->nombreCondicion : ''}} </td>
            <td >{{$venta->getProyecto != null ? $venta->getProyecto->nombre : ''}} </td>
            <td >{{$venta->getModulo != null ? $venta->getModulo->clave : null}} - {{$venta->getModulo != null ? $venta->getModulo->descripcion : null}} </td>
            <td >{{$venta->getCliente->razonSocial}} </td>
            <td >${{number_format($venta->total, 2)}} </td>
            <td >{{$venta->estatus}}</td>
            <td >{{date("d/m/Y H:i:s", strtotime($venta->fechaAlta))}}</td>
            <td >{{$venta->fechaCambio}}</td>
            <td >{{$venta->fechaBaja}}</td>
        </tr>
        @endforeach
    </tbody>
</table>