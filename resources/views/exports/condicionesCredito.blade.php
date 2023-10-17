<table>
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Tipo de Condición</th>
        <th>Días para Vencimiento</th>
        <th>Tipo de Días</th>
        <th>Días Hábiles</th>
        {{-- <th>Método de pago SAT</th> --}}
        <th>Estatus</th>
        <th>Usuario que lo Registró</th>
        <th>Fecha Creación</th>
        <th>Última Actualización</th>
        <th>Fecha de Baja</th>
    </tr>
    </thead>
    <tbody>
    @foreach($condicionesCredito as $condicion)
        <tr>
            <td >{{$condicion->nombreCondicion}}</td>
            <td >{{$condicion->tipoCondicion}}</td>
            <td >{{$condicion->diasVencimiento}} </td>
            <td >{{$condicion->tipoDias}} </td>
            <td >{{$condicion->diasHabiles}} </td>
            {{-- <td >{{$condicion->metodoPago == null 
                ? $condicion->metodoPago : $condicion->getMetodoPago->descripcion}} </td> --}}
            <td >{{$condicion->estatus == 1 ? 'Alta' : 'Baja'}}</td>
            <td>{{ $condicion->user_id == null ? $condicion->user_id : $condicion->getUser->user_name }}</td>
            <td >{{$condicion->fecha_Alta}}</td>
            <td >{{$condicion->fecha_Cambio}}</td>
            <td >{{$condicion->fecha_Baja}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
