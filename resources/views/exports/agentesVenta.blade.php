<table>
    <thead>
        <tr>
            <th>Clave</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Categoria</th>
            <th>Grupo</th>
            <th>Estatus</th>
            <th>Usuario que lo Registró</th>
            <th>Fecha de Creación</th>
            <th>Fecha de Actualización</th>
            <th>Fecha de Baja</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Agentes as $agente)
        <tr>
            <td>{{$agente->clave}}</td>
            <td>{{ucwords($agente->nombre)}}</td>
            <td>{{ucfirst($agente->tipo)}}</td>
            <td >{{$agente->categoria != null ? ucfirst($agente->relationCat->nombre) : null}} </td>
            <td >{{$agente->grupo != null ? ucfirst($agente->relationGrupo->nombre) : null}} </td>
            <td>{{$agente->estatus == 1 ? 'Alta' : 'Baja'}}</td>
            <td>{{$agente->getUser->username}}</td>
            <td >{{$agente->fechaAlta}}</td>
            <td >{{$agente->fechaCambio}}</td>
            <td >{{$agente->fechaBaja}}</td>
        </tr>
        @endforeach
    </tbody>
</table>