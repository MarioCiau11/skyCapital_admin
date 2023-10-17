<tr>
    <td >{{$articulo->movimiento}}</td>
    <td >{{$articulo->claveProyecto}}</td>
    <td >{{$articulo->getArticulo != null ? $articulo->getArticulo->descripcion : mb_strtoupper($articulo->descripcionArticulo)}}</td>
    <td >{{$articulo->unidadVenta != null ? $articulo->unidadVenta : 'N/A'}}</td>
    <td >{{$articulo->getUsuario != null ? $articulo->getUsuario->user_name : ''}}</td>
    <td >{{$articulo->getMoneda != null ? $articulo->getMoneda->nombre : ''}}</td>
    <td >${{number_format($articulo->getMoneda != null ? $articulo->getMoneda->tipoCambio : null, 2)}}</td>
    <td >{{$articulo->fechaEmision}}</td>
    <td >{{$articulo->getProyecto != null ? $articulo->getProyecto->nombre : ''}}</td>
    <td >{{$articulo->getSucursal != null ? $articulo->getSucursal->nombre: ''}}</td>
    <td >{{$articulo->estatus}}</td> 
</tr>