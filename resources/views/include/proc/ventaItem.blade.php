<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('proc.ventas.create',['venta' =>Crypt::encrypt($venta->idVenta)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
        </div>
    </td>
    <td >{{$venta->movimiento}}</td>
    <td >{{$venta->folioMov}}</td>
    <td >{{$venta->claveProyecto}}</td>
    <td >{{$venta->getMoneda != null ? $venta->getMoneda->clave : null}} </td>
    <td >${{number_format($venta->tipoCambio, 2)}} </td>
    <td >{{$venta->fechaEmision}} </td>
    <td >{{$venta->porcentajeMorosidad }} %</td>
    <td >{{$venta->getCondition != null ? $venta->getCondition->nombreCondicion : null}} </td>
    <td >{{$venta->fechaVencimiento}} </td>
    <td >{{$venta->getProyecto != null ? $venta->getProyecto->nombre: null}} </td>
    <td >{{$venta->getModulo != null ? $venta->getModulo->clave : null}} - {{$venta->getModulo != null ? $venta->getModulo->descripcion : null}} </td>
    <td >{{$venta->getCliente != null ? $venta->getCliente->razonSocial : null}} </td>
    <td >${{number_format($venta->subTotal, 2)}} </td>
    <td >${{number_format($venta->impuestos, 2)}} </td>
    <td >${{number_format($venta->total, 2)}} </td>
    <td >{{$venta->paypal == '1' ? 'Si' : 'No'}}</td>
    <td >{{$venta->estatus}}</td>
    <td >{{$venta->fechaAlta}}</td>
    <td >{{$venta->fechaCambio}}</td>

</tr>