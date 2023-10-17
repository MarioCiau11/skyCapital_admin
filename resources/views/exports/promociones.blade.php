<table>
    <thead>
    <tr>
        <th>Clave</th>
        <th>Nombre</th>
        <th>Estatus</th>
        <th>Fecha Creación</th>
        <th>Ultima Actualización</th>
        <th>Fecha Baja</th>
    </tr>
    </thead>
    <tbody>
    @foreach($promociones as $promocion)
        <tr>
            <td>{{ $promocion->clave }}</td>
            <td>{{ $promocion->nombre}}</td>
            <td>{{ $promocion->estatus == 1 ? 'Alta' : 'Baja' }}</td>
            <td>{{ $promocion->fechaAlta}}</td>
            <td>{{ $promocion->fechaCambio}}</td>
            <td>{{ $promocion->fechaBaja}}</td>
        </tr>
    @endforeach
    </tbody>
</table>