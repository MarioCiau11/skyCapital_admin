<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{ route('proc.cxc.create',['cxc' => Crypt::encrypt($cxc ->idCXC)]) }}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
        </div>
    </td>
    <td >{{$cxc->movimiento}}</td>
    <td >{{$cxc->folioMov}}</td>
    <td >{{$cxc->getMoneda != null ? $cxc->getMoneda->clave : null}} </td>
    <td >${{number_format($cxc->tipoCambio, 2)}} </td>
    <td >{{$cxc->cuentaDinero}}</td>
    <td >{{$cxc->anticipo}} </td>
    <td >{{$cxc->getCliente != null ? $cxc->getCliente->razonSocial : null}} </td>
    <td >{{$cxc->getCondition != null ? $cxc->getCondition->nombreCondicion : null}} </td>
    <td >{{$cxc->fechaEmision}} </td>
    <td >{{$cxc->fechaVencimiento}} </td>
    <td style="white-space: nowrap">$ {{number_format($cxc->importe, 2)}} </td>
    <td style="white-space: nowrap">$ {{number_format($cxc->impuestos, 2)}} </td>
    <td style="white-space: nowrap">$ {{number_format($cxc->total, 2)}} </td>
    <td style="white-space: nowrap">$ {{number_format($cxc->saldo, 2)}} </td>
    <td >{{$cxc->diasMoratorios}} </td>
    <td >{{$cxc->getProyecto != null ? $cxc->getProyecto->nombre : null}} </td>
    <td style="white-space: nowrap">{{$cxc->getModulo != null ? $cxc->getModulo->clave : null}} - {{$cxc->getModulo != null ? $cxc->getModulo->descripcion : null}}</td>
    <td >{{$cxc->estatus}}</td>
    <td >{{$cxc->fechaAlta}}</td>
    <td >{{$cxc->fechaCambio}}</td>

</tr>