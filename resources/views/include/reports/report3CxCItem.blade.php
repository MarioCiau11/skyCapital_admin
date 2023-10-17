<tr>
    <td >{{$ingreso->cliente}}</td>
    <td >{{$ingreso->categoria}}</td>
    <td >{{$ingreso->grupo}}</td>
    <td >{{$ingreso->movimiento}}</td>
    <td >$ {{number_format($ingreso->ingreso,2)}}</td>
    <td >{{$ingreso->Banco}}</td>
    <td >{{$ingreso->folioExterno}}</td>
    <td >{{$ingreso->moneda}}</td>
    <td >$ {{number_format($ingreso->tipoCambio, 2)}}</td>
    <td >{{$ingreso->tipo}}</td>
    <td >{{$ingreso->fecha}}</td>
    <td >{{$ingreso->proyecto}}</td>
    <td >{{$ingreso->modulo}}</td>
    <td >{{$ingreso->estatus}}</td>
</tr>