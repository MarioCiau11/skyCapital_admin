<table>
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Identificador</th>
        <th>Descripción</th>
        <th>Estatus</th>
        <th>Fecha Creación</th>
        <th>Ultima Actualización</th>

    </tr>
    </thead>
    <tbody>
    @foreach($roles as $rol)
        <tr>
            <td>{{ $rol->name }}</td>
            <td>{{ $rol->identifier }}</td>
            <td>{{ $rol->descript }}</td>
            <td>{{ $rol->status == 1 ? 'Alta' : 'Baja'}}</td>
            <td>{{ $rol->created_at}}</td>
            <td>{{ $rol->updated_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>