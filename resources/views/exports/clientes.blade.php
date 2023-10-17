<table>
    <thead>
        <tr>
            <th>Clave</th>
            <th>Nombre/Razón social</th>
            <th>RFC</th>
            <th>CURP</th>
            <th>Observaciones/Comentarios</th>
            <th>Ocupación</th>
            <th>Grupo</th>
            <th>Categoria</th>
            <th>Condición de Pago</th>
            <th>Usuario que lo Registró</th>
            <th>Estatus</th>
            <th>Fecha de Alta</th>
            <th>Última Actualización</th>
            <th>Fecha de Baja</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Clientes as $cliente)
        <tr>
            <td >{{$cliente->clave}}</td>
            <td >{{$cliente->razonSocial}}</td>
            <td >{{$cliente->RFC}} </td>
            <td >{{$cliente->CURP}} </td>
            <td >{{$cliente->observaciones}}</td>
            <td >{{$cliente->ocupacion}}</td>
            <td >{{$cliente->getGrupo != null ? $cliente->getGrupo->nombre : ''}} </td>
            <td >{{$cliente->getCategoria != null ? $cliente->getCategoria->nombre : ''}} </td>
            <td >{{$cliente->getCondiciones != null ? $cliente->getCondiciones->nombreCondicion : ''}}</td>
            <td >{{$cliente->getUser!= null ? $cliente->getUser->username : ''}}</td>
            <td >{{$cliente->estatus == 1 ? 'Alta' : 'Baja'}}</td>
            <td >{{$cliente->fechaAlta}}</td>
            <td >{{$cliente->fechaCambio}}</td>
            <td >{{$cliente->fechaBaja}}</td>
        </tr>
        @endforeach
    </tbody>
</table>