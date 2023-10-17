<table>
    <thead>
    <tr>
        <th>Clave</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Tipo de cambio</th>
        <th>Estatus</th>
        <th>Usuario que lo Registró</th>
        <th>Fecha Creación</th>
        <th>Última Actualización</th>
        <th>Fecha de Baja</th>
    </tr>
    </thead>
    <tbody>
    @foreach($Monedas as $moneda)
        <tr>
            <td >{{$moneda->clave}}</td>
            <td >{{$moneda->nombre}}</td>
            <td >{{$moneda->descripcion}} </td>
            <td >{{$moneda->tipoCambio}} </td>
            <td >{{$moneda->estatus == 1 ? 'Alta' : 'Baja'}}</td>
            <td >{{$moneda->userRelation->username}} </td>
            <td >{{$moneda->fechaAlta}}</td>
            <td >{{$moneda->fechaCambio}}</td>
            <td >{{$moneda->fechaBaja}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
