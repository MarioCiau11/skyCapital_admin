<table>
    <thead>
    <tr>
        <th>Unidad</th>
        <th>Decimal Válida</th>
        <th>Estatus</th>
        <th>Usuario que lo Registró</th>
        <th>Fecha creación</th>
        <th>Última actualización</th>
        <th>Fecha de Baja</th>
    </tr>
    </thead>
    <tbody>
    @foreach($Unidades as $unidad)
        <tr>
            <td >{{$unidad->unidad}}</td>
            <td >{{$unidad->decimalValida}}</td>
            <td >{{$unidad->estatus == 1 ? 'Alta' : 'Baja'}}</td>
            <td >{{$unidad->userRelation->username}}</td>
            <td >{{$unidad->fechaAlta}}</td>
            <td >{{$unidad->fechaCambio}}</td>
            <td >{{$unidad->fecha_Baja}}</td>
        </tr>
    @endforeach
    </tbody>
</table>