<table>
    <thead>
    <tr>
        <th>Clave</th>
        <th>Nombre</th>
        <th>Estatus</th>
        <th>Ciudad</th>
        <th>Estado</th>
        <th>País</th>
        <th>Fecha Creación</th>
        <th>Ultima Actualización</th>
        <th>Fecha Baja</th>
    </tr>
    </thead>
    <tbody>
    @foreach($instituciones as $institucion)
        <tr>
            <td>{{ $institucion->clave }}</td>
            <td>{{ $institucion->nombre}}</td>
            <td>{{ $institucion->estatus == 1 ? 'Alta' : 'Baja' }}</td>
            <td>{{ $institucion->ciudad}}</td>
            <td>{{ $institucion->estado}}</td>
            <td>{{ $institucion->pais}}</td>
            <td>{{ $institucion->fechaAlta}}</td>
            <td>{{ $institucion->fechaCambio}}</td>
            <td>{{ $institucion->fechaBaja}}</td>
        </tr>
    @endforeach
    </tbody>
</table>