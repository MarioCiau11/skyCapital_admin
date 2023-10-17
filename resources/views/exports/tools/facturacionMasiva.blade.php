<table>
    <thead>
        <tr>
            <th>Movimiento</th>
            <th>Fecha Emisión</th>
            <th>Contrato</th>
            <th>Cliente</th>
            <th>Importe</th>
            <th>Saldo</th>
            <th>Proyecto</th>
            <th>Módulo</th>
            <th>Tipo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($movimientos_filtro as $movimiento)
            <tr>
                <td >{{$movimiento->movimiento}}</td>
                <td>{{$movimiento->fechaEmision}}</td>
                <td>{{$movimiento->getOrigen != null ? $movimiento->getOrigen->movimiento: null}} - {{$movimiento->getOrigen != null ? $movimiento->getOrigen->folioMov: null}}</td>
                <td>{{$movimiento->getCliente != null ? $movimiento->getCliente->razonSocial: null}}</td>
                <td >${{number_format($movimiento->total, 2)}} </td>
                <td >${{number_format($movimiento->saldo, 2)}} </td>
                <td >{{$movimiento->getProyecto != null ? $movimiento->getProyecto->nombre: null}} </td>
                <td >{{$movimiento->getModulo != null ? $movimiento->getModulo->clave : null}} - {{$movimiento->getModulo != null ? $movimiento->getModulo->descripcion : null}} </td>
                <td>{{$movimiento->getModulo != null ? $movimiento->getModulo->tipo : null}}</td>
            </tr>
        @endforeach
    </tbody>
</table>