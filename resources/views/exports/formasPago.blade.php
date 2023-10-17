<table>
    <thead>
    <tr>
        <th>Clave</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Moneda</th>
        <th>Estatus</th>
        <th>Usuario que lo Registró</th>
        <th>Fecha Creación</th>
        <th>Última Actualización</th>
        <th>Fecha de Baja</th>
    </tr>
    </thead>
    <tbody>
    @foreach($formasPago as $formas)
        <tr>
            <td >{{$formas->clave}}</td>
            <td >{{$formas->nombre}}</td>
            <td >{{$formas->descripcion}} </td>
            <td >{{$formas->monedaSat}} </td>
            <td >{{$formas->estatus == 1 ? 'Alta' : 'Baja'}}</td>
            <td >{{$formas->userRelation->username}} </td>
            <td >{{$formas->fecha_Alta}}</td>
            <td >{{$formas->fecha_Cambio}}</td>
            <td >{{$formas->fecha_Baja}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
